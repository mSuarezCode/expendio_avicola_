<?php

namespace App\Models;

use CodeIgniter\Model;

class ProveedorModel extends Model
{
    protected $table = 'proveedores';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_empresa', 'contacto', 'telefono', 'estado', 'created_at'];

    // Método para obtener solo los proveedores activos
    public function getActivos()
    {
        return $this->where('estado', 1)->orderBy('id', 'ASC')->findAll();
    }
}