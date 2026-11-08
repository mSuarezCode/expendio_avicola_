<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table            = 'catalogo_productos';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nombre', 'tipo_unidad', 'precio_venta_actual', 'estado'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';
}