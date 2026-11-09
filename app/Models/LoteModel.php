<?php

namespace App\Models;

use CodeIgniter\Model;

class LoteModel extends Model
{
    protected $table = 'lotes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'proveedor_id', 
        'tipo_ave', 
        'piezas', 
        'peso', 
        'costo_unitario', 
        'comprobante', 
        'observaciones', 
        'fecha_recepcion', 
        'sucursal_id'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
