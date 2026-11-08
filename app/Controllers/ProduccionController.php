<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ProcesoModel;
use App\Models\LoteModel;
use App\Models\ProveedorModel;

class ProduccionController extends BaseController
{
    public function index()
    {
        $loteModel = new LoteModel();
        $proveedorModel = new ProveedorModel();
        $procesoModel = new ProcesoModel();

        $data['lotes'] = $loteModel->orderBy('id', 'DESC')->findAll();
        $data['lotesDisponibles'] = $loteModel->where("id NOT IN (SELECT lote_id FROM procesamiento_mermas)", null, false)
                                     ->orderBy('id', 'DESC')
                                     ->findAll();
        $data['proveedores'] = $proveedorModel->findAll();
        
        // Obtenemos los registros de procesamiento y mermas
        $data['procesos'] = $procesoModel->select('procesamiento_mermas.*, lotes.tipo_ave, proveedores.nombre_empresa')
                                         ->join('lotes', 'lotes.id = procesamiento_mermas.lote_id')
                                         ->join('proveedores', 'proveedores.id = lotes.proveedor_id')
                                         ->orderBy('procesamiento_mermas.id', 'DESC')
                                         ->findAll();

        return view('produccion/index', $data);
    }

    // Endpoint AJAX: Obtener lista de proveedores para el select
    public function obtenerProveedores()
    {
        $db = \Config\Database::connect();
        $proveedores = $db->table('proveedores')->select('id, nombre_empresa')->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $proveedores
        ]);
    }

    // Endpoint AJAX: Listar lotes ingresados
    public function listarLotes()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('lotes_compra l');
        $builder->select('l.*, p.nombre_empresa, u.username');
        $builder->join('proveedores p', 'p.id = l.proveedor_id');
        $builder->join('users u', 'u.id = l.usuario_id');
        $builder->orderBy('l.id', 'DESC');
        
        $lotes = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $lotes
        ]);
    }

    // Endpoint AJAX: Guardar nuevo lote
    public function guardarLote()
    {
        $db = \Config\Database::connect();
        $loteModel = new \App\Models\LoteModel();

        // 1. Recibir los datos del formulario de nuevo lote
        $proveedorId    = $this->request->getPost('mockProveedor');
        $tipoAve        = $this->request->getPost('mockTipo'); // 'pollo' o 'gallina'
        $jaulas         = (int) $this->request->getPost('jaulas');
        $piezasPorJaula = (int) $this->request->getPost('piezas_por_jaula');
        $piezasTotales  = (int) $this->request->getPost('mockPiezas'); // Calculado por jaulas * piezas_por_jaula
        $pesoTotal      = (float) ($this->request->getPost('mockPeso') ?? 0);
        $costoUnitario  = (float) $this->request->getPost('mockCosto');

        // Validación básica de seguridad
        if (empty($proveedorId) || empty($tipoAve) || $piezasTotales <= 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Faltan datos obligatorios o el cálculo de piezas es inválido.'
            ]);
        }

        // Si es pollo, el peso total es obligatorio por regla de negocio
        if ($tipoAve === 'pollo' && $pesoTotal <= 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'El peso total es obligatorio para la compra de pollos.'
            ]);
        }

        // 2. Gestión Opcional del Archivo (Comprobante / Ticket)
        $comprobante = $this->request->getFile('mockComprobante');
        $nombreArchivo = null;

        if ($comprobante && $comprobante->isValid() && !$comprobante->hasMoved()) {
            $nombreArchivo = $comprobante->getRandomName();
            // Asegúrate de que la carpeta public/uploads/lotes exista
            $comprobante->move(ROOTPATH . 'public/uploads/lotes', $nombreArchivo);
        }

        // 3. Inserción en la Base de Datos con las nuevas columnas
        try {
            $loteModel->insert([
                'proveedor_id'     => $proveedorId,
                'tipo_ave'         => $tipoAve,
                'jaulas'           => $jaulas,
                'piezas_por_jaula' => $piezasPorJaula,
                'piezas'           => $piezasTotales,
                'peso'             => $pesoTotal, // Referencial en gallinas, obligatorio en pollos
                'costo_unitario'   => $costoUnitario,
                'comprobante'      => $nombreArchivo, // Puede ser null si no se subió archivo
                'fecha_recepcion'  => date('Y-m-d')
            ]);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => '¡Lote capturado y registrado con éxito!'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al guardar en base de datos: ' . $e->getMessage()
            ]);
        }
    }
    
    public function guardar()
    {
        $loteModel = new LoteModel();

        // Validación de archivo obligatorio (comprobante)
        $file = $this->request->getFile('mockComprobante');
        $nombreArchivo = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $nombreArchivo = $file->getRandomName();
            $file->move(FCPATH . 'uploads/lotes', $nombreArchivo);
        } else {
            return redirect()->back()->with('error', 'El comprobante del proveedor es obligatorio.');
        }

        $loteModel->save([
            'proveedor_id'    => $this->request->getPost('mockProveedor'),
            'tipo_ave'        => $this->request->getPost('mockTipo'),
            'piezas'          => $this->request->getPost('mockPiezas'),
            'peso'            => $this->request->getPost('mockPeso'),
            'costo_unitario'  => $this->request->getPost('mockCosto'),
            'comprobante'     => $nombreArchivo,
            'observaciones'   => $this->request->getPost('obs') ?? 'Registro desde panel operativo',
            'fecha_recepcion' => date('Y-m-d')
        ]);

        return redirect()->to('/produccion')->with('mensaje', 'Lote registrado con éxito.');
    }

    // Dentro de tu ProduccionController o un método específico:
    public function procesarLote()
    {
        $procesoModel = new ProcesoModel();

        $inicial = (float) $this->request->getPost('procPesoInicial');
        $util = (float) $this->request->getPost('procPesoUtil');

        if ($util >= $inicial) {
            return redirect()->back()->with('error', 'El peso útil no puede ser mayor o igual al peso inicial.');
        }

        $mermaKg = $inicial - $util;
        $mermaPorc = ($mermaKg / $inicial) * 100;

        $procesoModel->save([
            'lote_id'       => $this->request->getPost('procLoteOrigen'),
            'peso_inicial'  => $inicial,
            'peso_util'     => $util,
            'merma_kg'      => $mermaKg,
            'merma_porc'    => $mermaPorc,
            'fecha_proceso' => date('Y-m-d')
        ]);

        return redirect()->to('/produccion')->with('mensaje', 'Procesamiento registrado y merma calculada con éxito.');
    }

    public function guardarProceso()
    {
        //dd($this->request->getPost());
        $procesoModel = new ProcesoModel();

        $inicial = (float) $this->request->getPost('procPesoInicial');
        $util = (float) $this->request->getPost('procPesoUtil');

        if ($util >= $inicial) {
            return redirect()->back()->with('error', 'El peso útil no puede ser mayor o igual al peso inicial.');
        }

        $mermaKg = $inicial - $util;
        $mermaPorc = ($mermaKg / $inicial) * 100;

        $procesoModel->save([
            'lote_id'       => $this->request->getPost('procLoteOrigen'),
            'peso_inicial'  => $inicial,
            'peso_util'     => $util,
            'merma_kg'      => $mermaKg,
            'merma_porc'    => $mermaPorc,
            'fecha_proceso' => date('Y-m-d')
        ]);

        return redirect()->to('/produccion')->with('mensaje', 'Procesamiento registrado y merma calculada con éxito.');
    }

    public function subirComprobante()
    {
        $loteId = $this->request->getPost('lote_id');
        $comprobante = $this->request->getFile('mockComprobante');

        if (!$loteId || !$comprobante || !$comprobante->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Datos o archivo inválido.']);
        }

        $nombreArchivo = $comprobante->getRandomName();
        $comprobante->move(ROOTPATH . 'public/uploads/lotes', $nombreArchivo);

        $db = \Config\Database::connect();
        $db->table('lotes')->where('id', $loteId)->update(['comprobante' => $nombreArchivo]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => '¡Comprobante adjuntado con éxito!',
            'url_archivo' => base_url('uploads/lotes/' . $nombreArchivo)
        ]);
    }
}