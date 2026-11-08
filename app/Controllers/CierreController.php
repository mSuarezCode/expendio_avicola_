<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CierreController extends BaseController
{
    public function index()
    {
        //
    }

    // 1. Guardar gasto rápido en caja chica (AJAX)
    public function guardarGasto() {
        $tipo = $this->request->getPost('tipo_gasto') ?? 'varios';
        $monto = (float) ($this->request->getPost('monto') ?? 0);
        $descripcion = trim($this->request->getPost('descripcion') ?? '');

        if ($monto <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El monto debe ser mayor a 0']);
        }

        $db = \Config\Database::connect();
        
        $db->query("CREATE TABLE IF NOT EXISTS gastos_caja (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tipo_gasto VARCHAR(50) NOT NULL,
            monto DECIMAL(10,2) NOT NULL,
            descripcion TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $db->table('gastos_caja')->insert([
            'tipo_gasto'  => $tipo,
            'monto'       => $monto,
            'descripcion' => $descripcion,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Gasto registrado correctamente']);
    }

    // Método interno autónomo para calcular el stock actual
    private function calcularStockActualArray() {
        $hoy = date('Y-m-d');
        $db = \Config\Database::connect();

        $productos = $db->table('catalogo_productos')->where('estado', 1)->get()->getResultArray();

        $ventasHoy = $db->table('detalle_pedidos')
            ->select('nombre, SUM(cantidad) as total_vendido')
            ->join('pedidos', 'pedidos.id = detalle_pedidos.pedido_id')
            ->where('DATE(pedidos.created_at)', $hoy)
            ->groupBy('nombre')
            ->get()->getResultArray();

        $mapaVentas = [];
        foreach ($ventasHoy as $v) {
            $mapaVentas[$v['nombre']] = floatval($v['total_vendido']);
        }

        $procesados = $db->table('procesamiento_detalles')
            ->select('producto_id, SUM(cantidad_kg) as total_procesado')
            ->groupBy('producto_id')
            ->get()->getResultArray();

        $mapaProcesados = [];
        foreach ($procesados as $p) {
            $mapaProcesados[$p['producto_id']] = floatval($p['total_procesado']);
        }

        $lotesData = $db->table('lotes')->get()->getResultArray();
        $procesosMermas = $db->table('procesamiento_mermas')->get()->getResultArray();
        
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
            $piezasRestantesLote = max(0, $piezasLote - $piezasDestinadas);
            
            if (isset($stockAvesTipo[$tipo])) {
                $stockAvesTipo[$tipo] += $piezasRestantesLote;
            }
        }

        $ajustes = $db->table('historial_ajustes_almacen')
            ->select('producto_id, tipo, SUM(cantidad) as total_ajuste')
            ->groupBy('producto_id, tipo')
            ->get()->getResultArray();

        $mapaAjustes = [];
        foreach ($ajustes as $a) {
            $pId = $a['producto_id'];
            if (!isset($mapaAjustes[$pId])) {
                $mapaAjustes[$pId] = ['merma' => 0, 'entrada' => 0];
            }
            $mapaAjustes[$pId][$a['tipo']] += floatval($a['total_ajuste']);
        }

        $resultado = [];
        foreach ($productos as $prod) {
            $id = $prod['id'];
            $nombre = $prod['nombre'];
            $tipoUnidad = $prod['tipo_unidad'];

            $vendido = $mapaVentas[$nombre] ?? 0;
            $mermaAcumulada = $mapaAjustes[$id]['merma'] ?? 0;
            $entradaAcumulada = $mapaAjustes[$id]['entrada'] ?? 0;

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
                $inicial = $mapaProcesados[$id] ?? 0;
                $stockDisponible = ($inicial + $entradaAcumulada) - ($vendido + $mermaAcumulada);
            }

            $resultado[] = [
                'id'                   => $id,
                'nombre'               => $nombre,
                'tipo_unidad'          => $tipoUnidad,
                'precio_venta_actual'  => $prod['precio_venta_actual'],
                'stock_disponible_num' => max(0, $stockDisponible),
                'estado'               => $prod['estado'],
                'vendido_hoy'          => $vendido
            ];
        }

        return $resultado;
    }

    // 2. Obtener resumen financiero separando efectivo y transferencias (AJAX)
    public function resumenJson() {
        $hoy = date('Y-m-d');
        $db = \Config\Database::connect();

        // Ventas exclusivas en EFECTIVO
        $ventasEfectivoQuery = $db->table('pedidos')
            ->selectSum('total', 'total_efectivo')
            ->where("DATE(created_at)", $hoy)
            ->where("estado !=", "cancelado")
            ->where("tipo_pago", "efectivo")
            ->get()
            ->getRowArray();

        // Ventas exclusivas por TRANSFERENCIA
        $ventasTransfQuery = $db->table('pedidos')
            ->selectSum('total', 'total_transferencia')
            ->where("DATE(created_at)", $hoy)
            ->where("estado !=", "cancelado")
            ->where("tipo_pago", "transferencia")
            ->get()
            ->getRowArray();

        // Total de gastos del día
        $gastosTotalQuery = $db->table('gastos_caja')
            ->selectSum('monto', 'total_gastos')
            ->where("DATE(created_at)", $hoy)
            ->get()
            ->getRowArray();

        // 🔍 OBTENER EL DETALLE DE LOS GASTOS PARA MOSTRARLOS EN LISTA
        $gastosDetalle = $db->table('gastos_caja')
            ->where("DATE(created_at)", $hoy)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $productosStock = $this->calcularStockActualArray();

        $stockAves = 0;
        $stockCortesKg = 0;
        $detalleProductos = [];

        foreach ($productosStock as $prod) {
            if ($prod['tipo_unidad'] === 'piezas') {
                $stockAves += $prod['stock_disponible_num'];
            } else {
                $stockCortesKg += $prod['stock_disponible_num'];
            }

            if ($prod['stock_disponible_num'] > 0) {
                $detalleProductos[] = [
                    'nombre' => $prod['nombre'],
                    'unidad' => $prod['tipo_unidad'],
                    'stock'  => $prod['stock_disponible_num']
                ];
            }
        }

        $ventasEfectivo = (float) ($ventasEfectivoQuery['total_efectivo'] ?? 0);
        $ventasTransferencia = (float) ($ventasTransfQuery['total_transferencia'] ?? 0);
        $totalGastos = (float) ($gastosTotalQuery['total_gastos'] ?? 0);
        $fondoInicial = 1000.00; 

        $efectivoEsperado = ($fondoInicial + $ventasEfectivo) - $totalGastos;

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'fondo_inicial'        => $fondoInicial,
                'ventas_efectivo'      => $ventasEfectivo,
                'ventas_transferencia' => $ventasTransferencia,
                'total_gastos'         => $totalGastos,
                'gastos_detalle'       => $gastosDetalle, // 👈 Enviamos el desglose
                'efectivo_esperado'    => $efectivoEsperado,
                'stock_aves'           => (int) $stockAves,
                'stock_cortes_kg'      => (float) $stockCortesKg,
                'detalle_productos'    => $detalleProductos
            ]
        ]);
    }

    // 3. Procesar Cierre Final del Día con validación de pendientes
    public function procesar() {
        $hoy = date('Y-m-d');
        $db = \Config\Database::connect();

        // 🛑 FRENO DE SEGURIDAD: Verificar si existen pedidos pendientes o en camino
        $pedidosPendientesCount = $db->table('pedidos')
            ->where("DATE(created_at)", $hoy)
            ->whereIn('estado', ['pendiente', 'en_camino'])
            ->countAllResults();

        if ($pedidosPendientesCount > 0) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => "⚠️ No se puede realizar el Cierre de Jornada.\n\nExisten {$pedidosPendientesCount} pedido(s) pendientes de entrega o en ruta (En Camino). Debes entregar o cancelar todos los pedidos del día antes de cerrar caja."
            ]);
        }

        $efectivoReal = (float) ($this->request->getPost('efectivo_real') ?? 0);
        $observaciones = trim($this->request->getPost('observaciones') ?? '');

        $db->transStart();

        $db->query("CREATE TABLE IF NOT EXISTS cierres_diarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fecha_cierre DATE NOT NULL,
            fondo_inicial DECIMAL(10,2) DEFAULT 0,
            ventas_efectivo DECIMAL(10,2) DEFAULT 0,
            ventas_transferencia DECIMAL(10,2) DEFAULT 0,
            total_gastos DECIMAL(10,2) DEFAULT 0,
            efectivo_esperado DECIMAL(10,2) DEFAULT 0,
            efectivo_real DECIMAL(10,2) DEFAULT 0,
            diferencia DECIMAL(10,2) DEFAULT 0,
            observaciones TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $db->query("CREATE TABLE IF NOT EXISTS inventario_cierre_diario (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cierre_id INT NOT NULL,
            producto_id INT NOT NULL,
            fecha_cierre DATE NOT NULL,
            stock_remanente DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Ventas en efectivo
        $ventasEfectivoQuery = $db->table('pedidos')
            ->selectSum('total', 'total_efectivo')
            ->where("DATE(created_at)", $hoy)
            ->where("estado !=", "cancelado")
            ->where("tipo_pago", "efectivo")
            ->get()
            ->getRowArray();

        // Ventas por transferencia
        $ventasTransfQuery = $db->table('pedidos')
            ->selectSum('total', 'total_transferencia')
            ->where("DATE(created_at)", $hoy)
            ->where("estado !=", "cancelado")
            ->where("tipo_pago", "transferencia")
            ->get()
            ->getRowArray();

        $gastos = $db->table('gastos_caja')
            ->selectSum('monto', 'total_gastos')
            ->where("DATE(created_at)", $hoy)
            ->get()
            ->getRowArray();

        $ventasEfectivo = (float) ($ventasEfectivoQuery['total_efectivo'] ?? 0);
        $ventasTransferencia = (float) ($ventasTransfQuery['total_transferencia'] ?? 0);
        $totalGastos = (float) ($gastos['total_gastos'] ?? 0);
        $fondoInicial = 1000.00; 

        $efectivoEsperado = ($fondoInicial + $ventasEfectivo) - $totalGastos;
        $diferencia = $efectivoReal - $efectivoEsperado;

        $db->table('cierres_diarios')->insert([
            'fecha_cierre'        => $hoy,
            'fondo_inicial'       => $fondoInicial,
            'ventas_efectivo'     => $ventasEfectivo,
            'ventas_transferencia'=> $ventasTransferencia,
            'total_gastos'        => $totalGastos,
            'efectivo_esperado'   => $efectivoEsperado,
            'efectivo_real'       => $efectivoReal,
            'diferencia'          => $diferencia,
            'observaciones'       => $observaciones
        ]);
        $cierreId = $db->insertID();

        // Archivar pedidos entregados del día
        $db->table('pedidos')
            ->where('DATE(created_at)', $hoy)
            ->where('estado', 'entregado')
            ->update(['estado' => 'archivado']);

        // Guardar remanente de stock para el día siguiente
        $resumenStock = $this->calcularStockActualArray();
        if (!empty($resumenStock)) {
            foreach ($resumenStock as $itemStock) {
                $db->table('inventario_cierre_diario')->insert([
                    'cierre_id'        => $cierreId,
                    'producto_id'      => $itemStock['id'],
                    'fecha_cierre'     => $hoy,
                    'stock_remanente'  => $itemStock['stock_disponible_num']
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al procesar la transacción de cierre.']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => '¡Día cerrado con éxito! Los pedidos entregados han sido archivados y el inventario restante fue registrado para la siguiente jornada.']);
    }
}