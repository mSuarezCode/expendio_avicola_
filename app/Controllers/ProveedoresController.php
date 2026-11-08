<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\ProveedorModel;

class ProveedoresController extends BaseController
{
    public function index()
    {
        $proveedorModel = new ProveedorModel();
        $data['proveedores'] = $proveedorModel->orderBy('id', 'DESC')->findAll();

        return view('proveedores/index', $data); // O la vista donde tengas integrada la sección
    }
    
    public function guardar()
    {
        $proveedorModel = new ProveedorModel();

        $proveedorModel->save([
            'nombre_empresa' => $this->request->getPost('provEmpresa'),
            'contacto'       => $this->request->getPost('provContacto'),
            'telefono'       => $this->request->getPost('provTelefono')
        ]);

        return redirect()->to('/proveedores')->with('mensaje', 'Proveedor registrado con éxito.');
    }

    public function eliminar($id)
    {
        $proveedorModel = new ProveedorModel();
        $proveedorModel->delete($id);

        return redirect()->to('/proveedores')->with('mensaje', 'Proveedor eliminado correctamente.');
    }
}
