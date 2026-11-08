<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ReportesController extends BaseController
{
    // Método concentrado para alimentar la vista principal de reportes por rango de fechas
    public function datosGeneralesJson()
    {
        $db = \Config\Database::connect();
        $fInicio = $this->request->getGet('inicio') ?? date('Y-m-01');
        $fFin = $this->request->getGet('fin') ?? date('Y-m-d');

        // 1. Fluctuación de lotes y costos
        $lotes = $db->table('lotes l')
            ->select('l.id, l.tipo_ave, l.costo_unitario, l.fecha_recepcion, p.nombre_empresa')
            ->join('proveedores p', 'p.id = l.proveedor_id', 'left')
            ->where('l.fecha_recepcion >=', $fInicio)
            ->where('l.fecha_recepcion <=', $fFin)
            ->orderBy('l.id', 'DESC')
            ->get()
            ->getResultArray();

        // 2. Resumen de Mermas y Despieces en el rango
        $totalesMerma = $db->table('procesamiento_mermas') // <--- Debe ser este nombre exacto
            ->selectSum('peso_inicial', 'total_inicial')
            ->selectSum('peso_util', 'total_util')
            ->selectSum('merma_kg', 'total_merma')
            ->where("fecha_proceso >=", $fInicio)
            ->where("fecha_proceso <=", $fFin)
            ->get()
            ->getRow();

        $promedioMermaPorc = ($totalesMerma && $totalesMerma->total_inicial > 0) 
            ? ($totalesMerma->total_merma / $totalesMerma->total_inicial) * 100 
            : 0;

        $resumenMerma = [
            'inicial'    => number_format((float)($totalesMerma->total_inicial ?? 0), 2),
            'util'       => number_format((float)($totalesMerma->total_util ?? 0), 2),
            'merma_kg'   => number_format((float)($totalesMerma->total_merma ?? 0), 2),
            'merma_porc' => number_format((float)$promedioMermaPorc, 1)
        ];

        // 3. Concentrado por Colonia (Zonas y Campañas)
        $zonas = $db->table('pedidos')
            ->select('colonia, COUNT(id) as total_pedidos, SUM(total) as volumen_venta')
            ->where("DATE(created_at) >=", $fInicio)
            ->where("DATE(created_at) <=", $fFin)
            ->where('colonia !=', '')
            ->groupBy('colonia')
            ->orderBy('volumen_venta', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'fluctuacion' => $lotes,
            'merma' => $resumenMerma,
            'zonas' => $zonas
        ]);
    }

    // Historial de pedidos pasados con filtros de fecha y búsqueda
    public function historialPedidosJson()
    {
        $db = \Config\Database::connect();
        $fInicio = $this->request->getGet('inicio') ?? date('Y-m-01');
        $fFin = $this->request->getGet('fin') ?? date('Y-m-d');
        $buscar = trim($this->request->getGet('buscar') ?? '');

        $builder = $db->table('pedidos');
        $builder->where("DATE(created_at) >=", $fInicio);
        $builder->where("DATE(created_at) <=", $fFin);

        if (!empty($buscar)) {
            $builder->groupStart()
                    ->like('id', $buscar)
                    ->orLike('cliente', $buscar)
                    ->orLike('telefono', $buscar)
                    ->orLike('colonia', $buscar)
                    ->groupEnd();
        }

        $builder->orderBy('id', 'DESC');
        $pedidos = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'pedidos' => $pedidos
        ]);
    }

    // Detalle de productos de un pedido histórico
    public function detallePedidoHistoricoJson($pedidoId)
    {
        $db = \Config\Database::connect();
        $items = $db->table('detalle_pedidos')
            ->where('pedido_id', $pedidoId)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'items' => $items
        ]);
    }
    public function concentradoColonias()
    {
        // Capturar filtros de fecha si se envían por GET o POST
        $fInicio = $this->request->getGet('inicio') ?? date('Y-m-01');
        $fFin = $this->request->getGet('fin') ?? date('Y-m-t');

        $db = \Config\Database::connect();
        
        // Consulta adaptada a tus tablas reales (ejemplo: pedidos y clientes)
        $builder = $db->table('pedidos');
        $builder->select('clientes.colonia, COUNT(pedidos.id) as total_pedidos, SUM(pedidos.total) as volumen_venta');
        $builder->join('clientes', 'clientes.id = pedidos.cliente_id');
        $builder->where('pedidos.created_at >=', $fInicio . ' 00:00:00');
        $builder->where('pedidos.created_at <=', $fFin . ' 23:59:59');
        $builder->groupBy('clientes.colonia');
        
        $resultado = $builder->get()->getResultArray();

        return $this->response->setJSON($resultado);
    }
}