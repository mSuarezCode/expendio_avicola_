<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PreciosController extends BaseController
{
    public function index()
    {
        return view('precios/index');
    }

    // Endpoint AJAX: Obtener catálogo de productos y precios
    public function listar()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('catalogo_productos');
        $productos = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $productos
        ]);
    }

    // Endpoint AJAX: Actualizar precio diario y registrar en historial
    public function actualizarPrecio()
    {
        $request = \Config\Services::request();
        $productoId = $request->getPost('producto_id');
        $precioNuevo = $request->getPost('precio_nuevo');
        $userId = auth()->id(); // Obtiene la ID del usuario autenticado vía Shield

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Consultar precio actual
        $producto = $db->table('catalogo_productos')->where('id', $productoId)->get()->getRow();

        if (!$producto) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Producto no encontrado']);
        }

        $precioAnterior = $producto->precio_venta_actual;

        // 2. Actualizar precio en catálogo
        $db->table('catalogo_productos')->where('id', $productoId)->update([
            'precio_venta_actual' => $precioNuevo
        ]);

        // 3. Registrar en Historial
        $db->table('historial_precios')->insert([
            'producto_id'     => $productoId,
            'precio_anterior' => $precioAnterior,
            'precio_nuevo'    => $precioNuevo,
            'fecha_cambio'    => date('Y-m-d H:i:s'),
            'usuario_id'      => $userId
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo actualizar el precio']);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Precio actualizado e historial registrado correctamente'
        ]);
    }
}