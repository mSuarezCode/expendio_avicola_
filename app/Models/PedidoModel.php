<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table            = 'pedidos'; // Ajusta al nombre real de tu tabla
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['cliente', 'telefono', 'tipo_entrega', 'colonia', 'direccion', 'repartidor', 'total', 'estado', 'tipo_pago', 'pago_verificado', 'sucursal_id'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';
}