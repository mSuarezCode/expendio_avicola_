<?php

namespace App\Models;

use CodeIgniter\Model;

class ProcesoModel extends Model
{
    protected $table = 'procesamiento_mermas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'lote_id',
        'peso_inicial',
        'peso_util',
        'merma_kg',
        'merma_porc',
        'fecha_proceso'
    ];

    public function getProcesosConDetalles()
    {
        return $this->select('procesamiento_mermas.*, lotes.tipo_ave, proveedores.nombre_empresa')
                    ->join('lotes', 'lotes.id = procesamientos_mermas.lote_id', 'left') // ajusta si tu alias difiere
                    ->join('proveedores', 'proveedores.id = lotes.proveedor_id', 'left')
                    ->orderBy('procesamiento_mermas.id', 'DESC')
                    ->findAll();
    }
}