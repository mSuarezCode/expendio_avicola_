<?php

namespace App\Models;

use CodeIgniter\Model;

class RepartidorModel extends Model
{
    protected $table = 'repartidores';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'telefono', 'vehiculo', 'estado'];

    public function getActivos()
    {
        return $this->where('estado', 1)->findAll();
    }
}