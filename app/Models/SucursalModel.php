<?php

namespace App\Models;

use CodeIgniter\Model;

class SucursalModel extends Model
{
    protected $table = 'sucursales';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_municipio', 'direccion', 'telefono', 'estado'];

    // Obtener todas las sucursales activas
    public function getActivas()
    {
        return $this->where('estado', 1)->findAll();
    }
}