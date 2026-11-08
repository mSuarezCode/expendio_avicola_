<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoDetalleModel extends Model
{
    protected $table = 'detalle_pedidos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pedido_id', 'nombre', 'cantidad', 'precio_unitario', 'subtotal'];
}