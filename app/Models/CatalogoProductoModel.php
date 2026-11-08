<?php

namespace App\Models;

use CodeIgniter\Model;

class CatalogoProductoModel extends Model
{
    protected $table = 'catalogo_productos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'tipo_unidad', 'precio_venta_actual', 'estado'];
    
    // IMPORTANTE: Asegúrate de que esto esté en falso o no exista, 
    // de lo contrario ignorará la actualización si no usas Entity classes:
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';

    // Método para obtener solo los productos activos para los selects y listas
    public function getActivos()
    {
        return $this->where('estado', 1)->findAll();
    }
}