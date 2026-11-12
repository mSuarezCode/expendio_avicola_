<?php

namespace App\Controllers;

use App\Models\LoteModel;
use App\Models\ProveedorModel;
use App\Models\ProcesoModel;
use App\Models\ProductoModel;
use App\Models\PedidoModel;
use App\Models\CatalogoProductoModel;
use App\Models\SucursalModel;

class DashboardController extends BaseController
{
    public function index()
    {
        // 1. Verificar si el usuario ya perdió la sesión
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Tu sesión ha caducado. Por favor ingresa de nuevo.');
        }

        $usuario = auth()->user();

        if ($usuario) {
            // Verificamos si la propiedad active es diferente de 1 (o true)
            if ((int) $usuario->active !== 1) {
                
                // Destruimos la sesión actual por seguridad
                auth()->logout();
                return redirect()->to('/login')->with('error', 'Tu cuenta ha sido desactivada por el administrador.');
            }
        }

        // 2. Si no tiene sucursal activa en sesión, la determinamos
        if (!session()->has('sucursal_activa_id')) {
            // Si el usuario tiene una sucursal asignada en su perfil, la usamos
            if ($usuario && !empty($usuario->sucursal_id)) {
                $sucursalId = $usuario->sucursal_id;
            } else {
                // Si es superadmin o no tiene ninguna asignada, por defecto usamos la 1 (Centro)
                $sucursalId = 1;
            }

            // Buscamos el nombre de la sucursal para la sesión
            $sucursalModel = new \App\Models\SucursalModel();
            $suc = $sucursalModel->find($sucursalId);
            $nombreSuc = $suc ? $suc['nombre_municipio'] : 'Centro';

            session()->set('sucursal_activa_id', $sucursalId);
            session()->set('nombre_sucursal_activa', $nombreSuc);
        }

        $sucursalActivaId = session()->get('sucursal_activa_id');

        $hoy = date('Y-m-d');
        $loteModel = new LoteModel();
        $proveedorModel = new ProveedorModel();
        $procesoModel = new ProcesoModel();
        $productoModel = new ProductoModel();
        $pedidoModel = new PedidoModel();
        $sucursalModel = new SucursalModel();
        
        $db = \Config\Database::connect(); // <-- Aseguramos la conexión

        // Recolectamos los datos de todas las tablas
        $data['sucursales']      = $sucursalModel->getActivas();
        $data['sucursalActiva']  = session()->get('nombre_sucursal_activa') ?? 'Expendio Centro';
        $data['lotes']       = $loteModel->where('sucursal_id', $sucursalActivaId)->orderBy('id', 'DESC')->findAll();
        $data['proveedores'] = $proveedorModel->getActivos();
        
        $data['procesos'] = $db->table('procesamiento_mermas')
            ->select('procesamiento_mermas.*, CONCAT("Lote #", lotes.id) as lote_codigo, proveedores.nombre_empresa')
            ->join('lotes', 'lotes.id = procesamiento_mermas.lote_id')
            ->join('proveedores', 'proveedores.id = lotes.proveedor_id', 'left')
            ->where('lotes.sucursal_id', $sucursalActivaId)
            ->get()
            ->getResultArray();

        // >>> AGREGAMOS ESTA LÍNEA PARA TRAER LOS DETALLES DE LOS CORTES <<<
        $data['procesamientoDetalles'] = $db->table('procesamiento_detalles')
            ->select('procesamiento_detalles.*, catalogo_productos.nombre as producto_nombre, procesamiento_mermas.lote_id, lotes.sucursal_id')
            ->join('catalogo_productos', 'catalogo_productos.id = procesamiento_detalles.producto_id', 'left')
            ->join('procesamiento_mermas', 'procesamiento_mermas.id = procesamiento_detalles.proceso_id')
            ->join('lotes', 'lotes.id = procesamiento_mermas.lote_id')
            ->where('lotes.sucursal_id', $sucursalActivaId)
            ->get()
            ->getResultArray();

        $data['productos']   = $productoModel->orderBy('id', 'ASC')->findAll();
        // Traer solo los pedidos activos (oculta los archivados/cerrados de días anteriores)
        $data['pedidos']     = $pedidoModel->where('sucursal_id', $sucursalActivaId)
                                        ->where("DATE(created_at)", $hoy)
                                        ->where("estado !=", "archivado")
                                        ->orderBy('id', 'DESC')
                                        ->findAll();
        $data['lotesDisponibles'] = $loteModel->orderBy('id', 'DESC')->findAll();
        // Obtener el nombre de la empresa configurado (por defecto EXPENDIO AVÍCOLA)
        $setting = $db->table('settings')->where('class', 'App')->where('key', 'company_name')->get()->getRowArray();
        $data['nombreEmpresa'] = $setting['value'] ?? 'EXPENDIO AVÍCOLA';

        $data['sucursalActivaId'] = $sucursalActivaId;

        return view('dashboard/index', $data);
    }

    // Método para actualizar el precio diario de un producto
    public function actualizarPrecio()
    {
        $productoModel = new \App\Models\ProductoModel();

        // Leemos los datos de la petición (acepta JSON o POST estándar)
        $id = $this->request->getPost('producto_id');
        $nuevoPrecio = $this->request->getPost('nuevo_precio');

        if ($id !== null && $nuevoPrecio !== null) {
            $data = [
                'precio_venta_actual' => $nuevoPrecio
            ];

            if ($productoModel->update($id, $data)) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Precio actualizado con éxito.'
                ]);
            } else {
                // Devuelve los errores del modelo si la actualización falla
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => implode(', ', $productoModel->errors()) ?: 'Error al actualizar en BD.'
                ]);
            }
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Datos incompletos recibidos por el servidor.'
        ]);
    }

    public function guardarPedido()
    {
        $session = \Config\Services::session();
        $sucursalActivaId = $session->get('sucursal_activa_id');
        $db = \Config\Database::connect();
        $pedidoModel = new \App\Models\PedidoModel();
        $clienteModel = new \App\Models\ClienteModel();

        $clienteNombre = $this->request->getPost('cliente');
        $telefono = $this->request->getPost('telefono');
        $tipoEntrega = $this->request->getPost('tipo_entrega');
        $tipoPago = $this->request->getPost('tipo_pago'); // Captura correcta
        $colonia = $this->request->getPost('colonia');
        $direccion = $this->request->getPost('direccion');
        $referencia = $this->request->getPost('referencia');
        $total = $this->request->getPost('total');
        $itemsJson = $this->request->getPost('items');

        // 1. GESTIÓN DE CLIENTES: Buscar si ya existe por teléfono o registrarlo
        $clienteExistente = $clienteModel->where('telefono', $telefono)->first();

        if ($clienteExistente) {
            $clienteId = $clienteExistente['id'];
            $clienteModel->update($clienteId, [
                'nombre'       => $clienteNombre,
                'tipo_entrega' => $tipoEntrega,
                'colonia'      => $colonia,
                'direccion'    => $direccion,
                'referencia'   => $referencia
            ]);
        } else {
            $clienteId = $clienteModel->insert([
                'telefono'     => $telefono,
                'nombre'       => $clienteNombre,
                'tipo_entrega' => $tipoEntrega,
                'colonia'      => $colonia,
                'direccion'    => $direccion,
                'referencia'   => $referencia
            ]);
        }

        // 2. GUARDAR EL PEDIDO VINCULADO AL CLIENTE
        $db->transStart();


        $pedidoModel->insert([
            'sucursal_id'  => $sucursalActivaId, // <--- ¡Añade esta línea para vincularlo a la sucursal activa!
            'cliente_id'   => $clienteId,
            'cliente'      => $clienteNombre,
            'telefono'     => $telefono,
            'tipo_entrega' => $tipoEntrega,
            'colonia'      => $colonia,
            'direccion'    => $direccion,
            'referencia'   => $referencia,
            'total'        => $total,
            'estado'       => 'pendiente',
            'repartidor'   => $tipoEntrega === 'tienda' ? 'N/A' : 'Sin asignar',
            'tipo_pago'    => $tipoPago, // <--- ¡AQUÍ ESTABA EL DETALLE! Agregamos esta línea
        ]);
        $pedidoId = $pedidoModel->getInsertID();

        $items = json_decode($itemsJson, true);
        if (!empty($items)) {
            $detalleModel = new \App\Models\PedidoDetalleModel();
            foreach ($items as $item) {
                $detalleModel->insert([
                    'pedido_id'       => $pedidoId,
                    'nombre'          => $item['nombre'],
                    'cantidad'        => $item['cant'],
                    'precio_unitario' => $item['precio'],
                    'subtotal'        => $item['subtotal']
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al guardar el pedido.']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'folio'  => $pedidoId,
            'message' => '¡Pedido registrado con éxito!',
            'tipoPago' => $tipoPago,
            'sucursal_id' => $sucursalActivaId
        ]);
    }

    public function actualizarEstadoPedido()
    {
        $pedidoModel = new PedidoModel();
        
        $id = $this->request->getPost('id');
        $estado = $this->request->getPost('estado');
        $repartidor = $this->request->getPost('repartidor'); // Opcional si se asigna en ruta

        $updateData = ['estado' => $estado];
        if ($repartidor) {
            $updateData['repartidor'] = $repartidor;
        }

        $pedidoModel->update($id, $updateData);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Estado actualizado.'
        ]);
    }

    public function getProductosJson()
    {
        //if (!$this->request->isAJAX()) {
        //    return $this->response->setJSON([]);
        //}

        $db = \Config\Database::connect();
        $session = session();
        $sucursalActivaId = $session->get('sucursal_activa_id') ?? 1;

        // 1. Obtenemos los productos del catálogo
        $productos = $db->table('catalogo_productos')->where('estado', 1)->get()->getResultArray();

        // 2. Obtenemos el total vendido hoy por cada producto uniendo detalle_pedidos y pedidos con sucursal
        $ventasHoy = $db->table('detalle_pedidos')
            ->select('detalle_pedidos.nombre, SUM(detalle_pedidos.subtotal) as total_vendido')
            ->join('pedidos', 'pedidos.id = detalle_pedidos.pedido_id')
            ->where('DATE(pedidos.created_at)', date('Y-m-d'))
            ->where('pedidos.sucursal_id', $sucursalActivaId)
            ->groupBy('detalle_pedidos.nombre')
            ->get()
            ->getResultArray();

        $mapaVentas = [];
        foreach ($ventasHoy as $v) {
            $mapaVentas[$v['nombre']] = floatval($v['total_vendido']);
        }

        // 3. Obtenemos el total procesado de cortes filtrado por la sucursal activa
        $procesados = $db->table('procesamiento_detalles')
            ->select('procesamiento_detalles.producto_id, SUM(procesamiento_detalles.cantidad_kg) as total_procesado')
            ->join('procesamiento_mermas', 'procesamiento_mermas.id = procesamiento_detalles.proceso_id')
            ->join('lotes', 'lotes.id = procesamiento_mermas.lote_id')
            ->where('lotes.sucursal_id', $sucursalActivaId)
            ->groupBy('procesamiento_detalles.producto_id')
            ->get()->getResultArray();

        $mapaProcesados = [];
        foreach ($procesados as $p) {
            $mapaProcesados[$p['producto_id']] = floatval($p['total_procesado']);
        }

        // 4. CÁLCULO DE AVES ENTERAS DISPONIBLES DESDE LOS LOTES Y DESPIECE
        $lotesData = $db->table('lotes')
            ->where('sucursal_id', $sucursalActivaId)
            ->get()
            ->getResultArray();

        $loteIds = array_column($lotesData, 'id');
        $procesosMermas = !empty($loteIds) 
            ? $db->table('procesamiento_mermas')->whereIn('lote_id', $loteIds)->get()->getResultArray() 
            : [];
        
        $mapaPiezasDestinadasPorLote = [];
        foreach ($procesosMermas as $pm) {
            $mapaPiezasDestinadasPorLote[$pm['lote_id']] = intval($pm['piezas_destinadas']);
        }

        $stockAvesTipo = ['pollo' => 0, 'gallina' => 0];
        foreach ($lotesData as $l) {
            $loteId = $l['id'];
            $tipo = strtolower($l['tipo_ave']);
            $piezasLote = intval($l['piezas']);
            $piezasDestinadas = $mapaPiezasDestinadasPorLote[$loteId] ?? 0;
            
            // Piezas restantes del lote que quedan como enteros
            $piezasRestantesLote = max(0, $piezasLote - $piezasDestinadas);
            
            if (isset($stockAvesTipo[$tipo])) {
                $stockAvesTipo[$tipo] += $piezasRestantesLote;
            }
        }

        // 5. Obtenemos los ajustes/mermas acumulados por producto desde 'historial_ajustes_almacen'
        $ajustes = $db->table('historial_ajustes_almacen')
            ->select('producto_id, tipo, SUM(cantidad) as total_ajuste')
            ->groupBy('producto_id, tipo')
            ->get()->getResultArray();

        $mapaAjustes = [];
        $mermasHoyTotal = 0;
        $hoy = date('Y-m-d');

        foreach ($ajustes as $a) {
            $pId = $a['producto_id'];
            if (!isset($mapaAjustes[$pId])) {
                $mapaAjustes[$pId] = ['merma' => 0, 'entrada' => 0];
            }
            $mapaAjustes[$pId][$a['tipo']] += floatval($a['total_ajuste']);
        }

        // Mermas específicas de hoy para la tarjeta superior
        $mermasHoyQuery = $db->table('historial_ajustes_almacen')
            ->select('SUM(cantidad) as mermas_hoy')
            ->where('tipo', 'merma')
            ->where('DATE(created_at)', $hoy)
            ->get()->getRow();
        
        $mermasHoyTotal = $mermasHoyQuery ? floatval($mermasHoyQuery->mermas_hoy) : 0.00;

        // 6. Armamos el resultado final combinando todo
        $resultado = [];
        foreach ($productos as $prod) {
            $id = $prod['id'];
            $nombre = $prod['nombre'];
            $tipoUnidad = $prod['tipo_unidad'];
            $estado = $prod['estado'];

            $vendido = $mapaVentas[$nombre] ?? 0;
            $mermaAcumulada = $mapaAjustes[$id]['merma'] ?? 0;
            $entradaAcumulada = $mapaAjustes[$id]['entrada'] ?? 0;

            // Si el producto se mide en piezas (Pollo Entero, Gallina, etc.)
            if ($tipoUnidad === 'piezas') {
                $tipoAveKey = '';
                if (stripos($nombre, 'pollo') !== false) {
                    $tipoAveKey = 'pollo';
                } elseif (stripos($nombre, 'gallina') !== false) {
                    $tipoAveKey = 'gallina';
                }

                $stockBase = $tipoAveKey ? ($stockAvesTipo[$tipoAveKey] ?? 0) : ($mapaProcesados[$id] ?? 0);
                $stockDisponible = ($stockBase + $entradaAcumulada) - ($vendido + $mermaAcumulada);
            } else {
                // Para cortes medidos en kilos
                $inicial = $mapaProcesados[$id] ?? 0;
                $stockDisponible = ($inicial + $entradaAcumulada) - ($vendido + $mermaAcumulada);
            }

            $resultado[] = [
                'id'                   => $id,
                'nombre'               => $nombre,
                'tipo_unidad'          => $tipoUnidad,
                'precio_venta_actual'  => $prod['precio_venta_actual'],
                'stock_disponible_num' => max(0, $stockDisponible),
                'estado'               => $estado,
                'vendido_hoy'          => $vendido
            ];
        }

        return $this->response->setJSON([
            'productos'  => $resultado,
            'mermas_hoy' => $mermasHoyTotal
        ]);
    }

    public function getPedidosPendientesJson()
    {
        $pedidoModel = new \App\Models\PedidoModel();
        $detalleModel = new \App\Models\DetallePedidoModel();
        $hoy = date('Y-m-d');
        
        // Obtenemos los pendientes y de tipo domicilio estrictamente de HOY
        $pendientes = $pedidoModel->where('estado', 'pendiente')
                                ->where('tipo_entrega', 'domicilio')
                                ->where("DATE(created_at)", $hoy)
                                ->findAll();

        foreach ($pendientes as &$p) {
            $p['productos'] = $detalleModel->where('pedido_id', $p['id'])->findAll();
        }

        return $this->response->setJSON($pendientes);
    }

    public function getDetallePedidoJson($id)
    {
        $detalleModel = new \App\Models\DetallePedidoModel();
        $items = $detalleModel->where('pedido_id', $id)->findAll();

        return $this->response->setJSON($items);
    }
    public function getResumenMetricasJson()
    {
        $db = \Config\Database::connect();
        $hoy = date('Y-m-d');
        $session = session();
        $sucursalActivaId = $session->get('sucursal_activa_id') ?? 1;

        // 1. Pendientes / Producción de HOY (excluyendo archivados)
        $pendientes = $db->table('pedidos')
            ->where('estado', 'pendiente') // <--- Singular
            ->where('sucursal_id', $sucursalActivaId)
            ->where("DATE(created_at)", $hoy)
            ->countAllResults();

        // 2. En Camino de HOY
        $enCamino = $db->table('pedidos')
            ->where('estado', 'en_camino')
            ->where('sucursal_id', $sucursalActivaId)
            ->where("DATE(created_at)", $hoy)
            ->countAllResults();

        // 3. Entregados de HOY
        $entregados = $db->table('pedidos')
            ->where('estado', 'entregado')
            ->where('sucursal_id', $sucursalActivaId)
            ->where("DATE(created_at)", $hoy)
            ->countAllResults();

        // 4. Ventas en EFECTIVO de HOY (Excluyendo cancelados y archivados)
        $ventasEfectivo = $db->table('pedidos')
            ->where('tipo_pago', 'efectivo')
            ->where('sucursal_id', $sucursalActivaId)
            ->where("DATE(created_at)", $hoy)
            ->selectSum('total')
            ->get()
            ->getRowArray()['total'] ?? 0;

        // 5. Ventas por TRANSFERENCIA de HOY (Excluyendo cancelados y archivados)
        $ventasTransferencia = $db->table('pedidos')
            ->where('tipo_pago', 'transferencia')
            ->where('sucursal_id', $sucursalActivaId)
            ->where("DATE(created_at)", $hoy)
            ->selectSum('total')
            ->get()
            ->getRowArray()['total'] ?? 0;

        // 6. Venta Total de HOY (Suma de efectivo + transferencia activos)
        $ventaTotal = (float)$ventasEfectivo + (float)$ventasTransferencia;

        return $this->response->setJSON([
            'pendientes'          => $pendientes,
            'en_camino'           => $enCamino,
            'entregados'          => $entregados,
            'venta_total'         => number_format($ventaTotal, 2, '.', ''),
            'venta_efectivo'      => number_format((float)$ventasEfectivo, 2, '.', ''),
            'venta_transferencia' => number_format((float)$ventasTransferencia, 2, '.', '')
        ]);
    }

    // Guardar un producto nuevo
    public function guardarProductoCatalogo()
    {
        $model = new \App\Models\CatalogoProductoModel();

        $data = [
            'nombre'              => $this->request->getPost('nombre'),
            'tipo_unidad'         => $this->request->getPost('tipo_unidad'),
            'precio_venta_actual' => $this->request->getPost('precio_venta_actual'),
            'estado'              => 1
        ];

        if ($model->insert($data)) {
            // Obtenemos el ID del registro que acabamos de insertar en MySQL
            $nuevoId = $model->getInsertID();

            return $this->response->setJSON([
                'status' => 'success', 
                'id' => $nuevoId,
                'message' => 'Producto registrado correctamente.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'No se pudo guardar el producto.'
        ]);
    }

    // Actualizar precio o datos del producto
    public function actualizarProductoCatalogo()
    {
        $model = new CatalogoProductoModel();
        $id = $this->request->getPost('id');

        // Validar que el ID esté presente
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'No se recibió el ID del producto.'
            ]);
        }

        $data = [];
        if ($this->request->getPost('precio_venta_actual') !== null) {
            $data['precio_venta_actual'] = (float) $this->request->getPost('precio_venta_actual');
        }
        if ($this->request->getPost('nombre') !== null) {
            $data['nombre'] = $this->request->getPost('nombre');
        }
        if ($this->request->getPost('tipo_unidad') !== null) {
            $data['tipo_unidad'] = $this->request->getPost('tipo_unidad');
        }

        if (empty($data)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'No hay datos para actualizar.'
            ]);
        }

        // Intentamos actualizar
        $actualizado = $model->update($id, $data);

        if ($actualizado) {
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Actualizado correctamente.'
            ]);
        } else {
            // Capturamos el error exacto que da la base de datos o el modelo
            $errors = $model->errors();
            $dbError = $model->db->error();
            
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Falla en BD',
                'model_errors' => $errors,
                'db_error' => $dbError,
                'id_enviado' => $id,
                'datos_enviados' => $data
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Error al actualizar en la base de datos.'
        ]);
    }

    // Eliminación lógica (cambiar estado a 0)
    public function eliminarProductoCatalogo($id)
    {
        $model = new \App\Models\CatalogoProductoModel();

        if ($model->update($id, ['estado' => 0])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Producto eliminado correctamente.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo eliminar el producto.']);
    }

    // Guardar Repartidor
    public function guardarRepartidor()
    {
        $model = new \App\Models\RepartidorModel();

        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'telefono' => $this->request->getPost('telefono'),
            'vehiculo' => $this->request->getPost('vehiculo'),
            'estado'   => 1
        ];

        if ($model->insert($data)) {
            $nuevoId = $model->getInsertID();
            return $this->response->setJSON([
                'status' => 'success', 
                'id' => $nuevoId,
                'message' => 'Repartidor registrado correctamente.'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo registrar el repartidor.']);
    }

    // Eliminación lógica de Repartidor
    public function eliminarRepartidor($id)
    {
        $model = new \App\Models\RepartidorModel();

        if ($model->update($id, ['estado' => 0])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Repartidor dado de baja.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo eliminar.']);
    }

    public function getRepartidoresJson()
    {
        $repartidorModel = new \App\Models\RepartidorModel();
        $repartidores = $repartidorModel->where('estado', 1)->findAll();

        return $this->response->setJSON($repartidores);
    }

    public function getReportesJson()
    {
        $db = \Config\Database::connect(); // <-- Declaramos e inicializamos la conexión
        $session = session();
        $sucursalActivaId = $session->get('sucursal_activa_id') ?? 1;

        // Reportes de mermas (procesamiento_mermas relacionadas con lotes de la sucursal)
        $mermas = $db->table('procesamiento_mermas')
            ->select('procesamiento_mermas.*')
            ->join('lotes', 'lotes.id = procesamiento_mermas.lote_id')
            ->where('lotes.sucursal_id', $sucursalActivaId)
            ->get()
            ->getResultArray();

        // Fluctuación de lotes filtrada por sucursal
        $lotesFluctuacion = $db->table('lotes')
            ->where('sucursal_id', $sucursalActivaId)
            ->get()
            ->getResultArray();

        // Zonas/colonias agrupadas por pedidos de la sucursal activa
        $zonas = $db->table('pedidos')
            ->select('colonia as zona_entrega, COUNT(*) as total_pedidos, SUM(total) as volumen_venta')
            ->where('sucursal_id', $sucursalActivaId)
            ->groupBy('colonia')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'mermas' => $mermas,
            'lotes_fluctuacion' => $lotesFluctuacion,
            'zonas' => $zonas
        ]);
    }

    public function getFluctuacionReporteJson()
    {
        $db = \Config\Database::connect();
        
        // Capturar fechas del filtro de la vista (si existen)
        $fInicio = $this->request->getGet('inicio') ?? date('Y-m-01');
        $fFin = $this->request->getGet('fin') ?? date('Y-m-d');

        // Consultamos los lotes en el rango de fecha junto con el producto del catálogo que coincide
        $builder = $db->table('lotes l');
        $builder->select('l.id, l.tipo_ave, l.costo_unitario, l.fecha_recepcion, cp.nombre as producto_catalogo, cp.precio_venta_actual');
        $builder->join('catalogo_productos cp', 'LOWER(cp.nombre) LIKE CONCAT("%", l.tipo_ave, "%")', 'left');
        $builder->where('l.fecha_recepcion >=', $fInicio);
        $builder->where('l.fecha_recepcion <=', $fFin);
        $builder->orderBy('l.id', 'DESC');
        
        $lotes = $builder->get()->getResultArray();

        $resultado = [];
        foreach ($lotes as $l) {
            $costoCompra = (float) $l['costo_unitario'];
            // Si hay un precio en catálogo lo usamos, de lo contrario estimamos
            $precioVenta = (float) ($l['precio_venta_actual'] ?? 0); 
            
            $margenDinero = $precioVenta - $costoCompra;
            $margenPorc = ($costoCompra > 0) ? ($margenDinero / $costoCompra) * 100 : 0;

            $resultado[] = [
                'lote_id' => $l['id'],
                'tipo_ave' => ucfirst($l['tipo_ave']),
                'costo_compra' => number_format($costoCompra, 2),
                'precio_venta' => number_format($precioVenta, 2),
                'margen_dinero' => number_format($margenDinero, 2),
                'margen_porc' => number_format($margenPorc, 1),
                'tendencia' => 'Estable' // Puedes lógica adicional si registras historial de precios
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $resultado
        ]);
    }

    public function guardarProcesoDespiece()
    {
        try {
            $db = \Config\Database::connect();
            $db->transStart();

            $loteId = (int) $this->request->getPost('procLoteOrigen');
            $piezasDestinadas = (int) $this->request->getPost('piezas_destinadas');
            $cortesObtenidos = json_decode($this->request->getPost('cortes_json'), true);

            if (!$loteId || empty($cortesObtenidos)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Faltan datos obligatorios.']);
            }

            $lote = $db->table('lotes')->where('id', $loteId)->get()->getRowArray();
            if (!$lote) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'El lote seleccionado no existe.']);
            }

            $pesoInicial = (float) $lote['peso'];
            $piezasIniciales = (int) $lote['piezas'];

            $pesoPromedioPorPieza = $piezasIniciales > 0 ? ($pesoInicial / $piezasIniciales) : 0;
            $kilosAsignadosACortes = $pesoPromedioPorPieza * $piezasDestinadas;

            $pesoUtilCortes = 0;
            foreach ($cortesObtenidos as $corte) {
                $pesoUtilCortes += (float) ($corte['cantidad_kg'] ?? 0);
            }

            $mermaKg = max(0, $kilosAsignadosACortes - $pesoUtilCortes);
            $mermaPorc = $kilosAsignadosACortes > 0 ? ($mermaKg / $kilosAsignadosACortes) * 100 : 0;

            // 1. Registrar la cabecera del despiece
            $db->table('procesamiento_mermas')->insert([
                'lote_id'           => $loteId,
                'piezas_destinadas' => $piezasDestinadas,
                'peso_inicial'      => $kilosAsignadosACortes,
                'peso_util'         => $pesoUtilCortes,
                'merma_kg'          => $mermaKg,
                'merma_porc'        => $mermaPorc,
                'fecha_proceso'     => date('Y-m-d')
            ]);
            $procesoId = $db->insertID();

            // 2. Registrar los detalles vinculando con el producto_id de catalogo_productos
            foreach ($cortesObtenidos as $corte) {
                $productoId = isset($corte['producto_id']) ? (int) $corte['producto_id'] : null;
                $cantidadKg = (float) ($corte['cantidad_kg'] ?? 0);

                if ($cantidadKg <= 0) continue;

                // Si por algún detalle no llegó producto_id pero sí el nombre, buscar o registrar
                if (!$productoId && !empty($corte['producto_nombre'])) {
                    $prodExistente = $db->table('catalogo_productos')->where('nombre', trim($corte['producto_nombre']))->get()->getRowArray();
                    if ($prodExistente) {
                        $productoId = $prodExistente['id'];
                    } else {
                        $db->table('catalogo_productos')->insert([
                            'nombre'              => trim($corte['producto_nombre']),
                            'tipo_unidad'         => 'kilos',
                            'precio_venta_actual' => 0.00,
                            'estado'              => 1
                        ]);
                        $productoId = $db->insertID();
                    }
                }

                if ($productoId) {
                    $db->table('procesamiento_detalles')->insert([
                        'proceso_id'  => $procesoId,
                        'producto_id' => $productoId,
                        'cantidad_kg' => $cantidadKg
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error en la transacción de base de datos.']);
            }

            return $this->response->setJSON([
                'status'        => 'success',
                'message'       => '¡Despiece de lote registrado con éxito!',
                'datos_proceso' => [
                    'lote_id'           => $loteId,
                    'tipo_ave'          => $lote['tipo_ave'],
                    'piezas_originales' => $piezasIniciales,
                    'peso_original'     => $pesoInicial,
                    'piezas_destinadas' => $piezasDestinadas,
                    'piezas_restantes'  => $piezasIniciales - $piezasDestinadas,
                    'kilos_descontados' => $kilosAsignadosACortes,
                    'kilos_restantes'   => $pesoInicial - $kilosAsignadosACortes,
                    'cortes'            => $cortesObtenidos
                ]
            ]);

        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Excepción: ' . $e->getMessage()]);
        }
    }

    // Registrar Proveedor por AJAX
    public function guardarProveedor() 
    {
        $hoy = date('Y-m-d');
        $model = new \App\Models\ProveedorModel();

        $data = [
            'nombre_empresa' => $this->request->getPost('provEmpresa'),
            'contacto'       => $this->request->getPost('provContacto'),
            'telefono'       => $this->request->getPost('provTelefono'),
            'created_at'       => $hoy,
        ];

        if ($model->insert($data)) {
            $nuevoId = $model->getInsertID();
            $proveedorCreado = $model->find($nuevoId);

            return $this->response->setJSON([
                'status'    => 'success',
                'id'        => $nuevoId,
                'proveedor' => $proveedorCreado,
                'message'   => '¡Proveedor registrado correctamente!'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'No se pudo registrar el proveedor.'
        ]);
    }
    
    // Eliminar Proveedor por AJAX
    public function eliminarProveedor($id)
    {
        $model = new \App\Models\ProveedorModel();

        if ($model->update($id, ['estado' => 0])) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Proveedor dado de baja correctamente.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'No se pudo dar de baja al proveedor.'
        ]);
    }

    // Buscar cliente por teléfono para autocompletar pedido
    public function buscarClienteJson()
    {
        $telefono = $this->request->getGet('telefono');
        if (!$telefono) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Teléfono no proporcionado.']);
        }

        $clienteModel = new \App\Models\ClienteModel();
        // Buscamos directamente en la tabla dedicada de clientes
        $cliente = $clienteModel->where('telefono', $telefono)->first();

        if ($cliente) {
            return $this->response->setJSON([
                'status' => 'success',
                'cliente' => [
                    'cliente'      => $cliente['nombre'],
                    'telefono'     => $cliente['telefono'],
                    'tipo_entrega' => $cliente['tipo_entrega'],
                    'colonia'      => $cliente['colonia'],
                    'direccion'    => $cliente['direccion'],
                    'referencia'   => $cliente['referencia']
                ]
            ]);
        }

        return $this->response->setJSON([
            'status' => 'not_found',
            'message' => 'Cliente nuevo'
        ]);
    }


    public function ajustarStock()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Acceso no permitido.']);
        }

        $productoId = $this->request->getPost('producto_id');
        $tipo       = $this->request->getPost('tipo'); // 'merma' o 'entrada'
        $cantidad   = floatval($this->request->getPost('cantidad'));
        $motivo     = trim($this->request->getPost('motivo'));

        if (!$productoId || $cantidad <= 0 || empty($motivo)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Faltan datos obligatorios o la cantidad es inválida.']);
        }

        $db = \Config\Database::connect();
        
        try {
            // Insertamos el registro en el historial de ajustes y mermas
            $db->table('historial_ajustes_almacen')->insert([
                'producto_id' => $productoId,
                'tipo'        => $tipo,
                'cantidad'    => $cantidad,
                'motivo'      => $motivo,
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Ajuste de inventario registrado e historiado correctamente.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al guardar en la base de datos: ' . $e->getMessage()
            ]);
        }
    }

    // Obtener listado de clientes en JSON para la tabla
    public function getClientesJson()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([]);
        }

        $clienteModel = new \App\Models\ClienteModel();
        $clientes = $clienteModel->orderBy('id', 'DESC')->findAll();

        return $this->response->setJSON($clientes);
    }

    // Registrar o actualizar cliente desde el directorio
    public function guardarClienteDirectorio()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Acceso no permitido.']);
        }

        $id           = $this->request->getPost('id');
        $telefono     = trim($this->request->getPost('telefono'));
        $nombre       = trim($this->request->getPost('nombre'));
        $tipoEntrega  = $this->request->getPost('tipo_entrega');
        $colonia      = trim($this->request->getPost('colonia'));
        $direccion    = trim($this->request->getPost('direccion'));
        $referencia   = trim($this->request->getPost('referencia'));

        if (empty($telefono) || empty($nombre)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El teléfono y el nombre son obligatorios.']);
        }

        $clienteModel = new \App\Models\ClienteModel();

        try {
            // Si viene ID o el teléfono ya existe en BD
            $existente = $id ? $clienteModel->find($id) : $clienteModel->where('telefono', $telefono)->first();

            if ($existente) {
                $clienteModel->update($existente['id'], [
                    'telefono'     => $telefono,
                    'nombre'       => $nombre,
                    'tipo_entrega' => $tipoEntrega,
                    'colonia'      => $colonia,
                    'direccion'    => $direccion,
                    'referencia'   => $referencia
                ]);
                $clienteId = $existente['id'];
                $msg = '¡Cliente actualizado con éxito!';
            } else {
                $clienteId = $clienteModel->insert([
                    'telefono'     => $telefono,
                    'nombre'       => $nombre,
                    'tipo_entrega' => $tipoEntrega,
                    'colonia'      => $colonia,
                    'direccion'    => $direccion,
                    'referencia'   => $referencia
                ]);
                $msg = '¡Cliente registrado con éxito!';
            }

            $clienteGuardado = $clienteModel->find($clienteId);

            return $this->response->setJSON([
                'status'  => 'success',
                'cliente' => $clienteGuardado,
                'message' => $msg
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'tipo'  => 'error bd',
                'message' => 'Error en base de datos: ' . $e->getMessage()
            ]);
        }
    }

    public function verificarTransferencia()
    {
        $pedidoModel = new \App\Models\PedidoModel();
        $id = $this->request->getPost('id');

        if ($id) {
            $pedidoModel->update($id, ['pago_verificado' => 1]);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Transferencia verificada correctamente.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'ID no proporcionado.'
        ]);
    }

    public function actualizarPerfil()
    {
        $usuario = auth()->user();
        if (!$usuario) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado.']);
        }

        $nuevoUsername = trim($this->request->getPost('username'));
        if (empty($nuevoUsername)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El nombre de usuario no puede estar vacío.']);
        }

        $usersModel = new \CodeIgniter\Shield\Models\UserModel();
        
        // Actualizamos el username del usuario actual
        $usuario->username = $nuevoUsername;
        
        if ($usersModel->save($usuario)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '¡Nombre de usuario actualizado con éxito!'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'No se pudo actualizar. Es posible que el nombre de usuario ya esté en uso.'
        ]);
    }
    // Obtener la configuración actual de la empresa (puedes llamarlo al cargar el index)
    public function getConfiguracionJson()
    {
        if (!auth()->user()->inGroup('superadmin')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado.']);
        }

        $db = \Config\Database::connect();
        $setting = $db->table('settings')->where('class', 'App')->where('key', 'company_name')->get()->getRowArray();
        
        $nombreEmpresa = $setting['value'] ?? 'EXPENDIO AVÍCOLA';

        return $this->response->setJSON([
            'status' => 'success',
            'company_name' => $nombreEmpresa
        ]);
    }

    // Guardar la nueva configuración de la empresa (Exclusivo Superadmin)
    public function guardarConfiguracion()
    {
        if (!auth()->user()->inGroup('superadmin')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Acceso denegado. Solo el Superadministrador puede modificar la configuración.']);
        }

        $nuevoNombre = trim($this->request->getPost('company_name'));
        if (empty($nuevoNombre)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El nombre de la empresa no puede estar vacío.']);
        }

        $db = \Config\Database::connect();
        
        // Verificar si ya existe la llave en la tabla settings de Shield/CI4
        $existente = $db->table('settings')->where('class', 'App')->where('key', 'company_name')->get()->getRowArray();

        if ($existente) {
            $db->table('settings')->where('class', 'App')->where('key', 'company_name')->update(['value' => $nuevoNombre]);
        } else {
            $db->table('settings')->insert([
                'class' => 'App',
                'key'   => 'company_name',
                'value' => $nuevoNombre,
                'type'  => 'string'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => '¡Configuración actualizada con éxito!',
            'company_name' => $nuevoNombre
        ]);
    }
    // Cambiar de sucursal activa (Exclusivo Superadmin)
    public function cambiarSucursal()
    {
        if (!auth()->user()->inGroup('superadmin')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado.']);
        }

        $sucursalId = $this->request->getPost('sucursal_id');
        $sucursalModel = new SucursalModel();
        $sucursal = $sucursalModel->find($sucursalId);

        if (!$sucursal) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sucursal no encontrada.']);
        }

        // Guardar ID y Nombre en la Sesión
        session()->set('sucursal_activa_id', $sucursal['id']);
        session()->set('nombre_sucursal_activa', $sucursal['nombre_municipio']);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Sucursal cambiada con éxito.'
        ]);
    }

    // Obtener todas las sucursales activas en formato JSON
    public function getSucursalesJson()
    {
        $sucursalModel = new \App\Models\SucursalModel();
        $sucursales = $sucursalModel->findAll();

        return $this->response->setJSON($sucursales);
    }

    // Guardar o Actualizar Sucursal vía AJAX
    public function guardarSucursal()
    {
        $id = $this->request->getPost('id');
        $nombre = trim($this->request->getPost('nombre_municipio'));
        $direccion = trim($this->request->getPost('direccion'));
        $telefono = trim($this->request->getPost('telefono'));

        if (empty($nombre)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El nombre/municipio es obligatorio.']);
        }

        $sucursalModel = new \App\Models\SucursalModel();
        $data = [
            'nombre_municipio' => $nombre,
            'direccion'        => $direccion,
            'telefono'         => $telefono,
            'estado'           => 1
        ];

        try {
            if ($id) {
                $sucursalModel->update($id, $data);
                $msg = '¡Sucursal actualizada con éxito!';
            } else {
                $sucursalModel->insert($data);
                $msg = '¡Sucursal registrada con éxito!';
            }

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => $msg
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error en base de datos: ' . $e->getMessage()
            ]);
        }
    }

    // Dar de baja o eliminar sucursal
    public function eliminarSucursal($id)
    {
        $sucursalModel = new \App\Models\SucursalModel();

        if ($sucursalModel->update($id, ['estado' => 0])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Sucursal desactivada con éxito.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo eliminar la sucursal.']);
    }

    public function verificarNip()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $nip = $this->request->getPost('nip');

        if (empty($nip)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NIP no proporcionado.']);
        }

        $usersModel = new \App\Models\UserModel();
        
        // Buscamos un usuario activo que tenga ese NIP y pertenezca al grupo 'gerente' o 'superadmin'
        // (Puedes ajustar la consulta según cómo manejes los grupos en Shield)
        $usuario = $usersModel->where('nip', $nip)
                            ->where('active', 1)
                            ->first();

        if ($usuario && ($usuario->inGroup('gerente', 'superadmin'))) {
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Autorizado',
                'usuario' => $usuario->username
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'NIP incorrecto o sin privilegios gerenciales.'
        ]);
    }
}