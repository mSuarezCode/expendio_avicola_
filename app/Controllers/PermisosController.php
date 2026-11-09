<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;

class PermisosController extends BaseController
{
    // Vista principal para listar usuarios y sus roles/permisos actuales
    public function index()
    {
        $usersModel = new UserModel();
        // Obtenemos los usuarios con sus identidades y grupos
        $usuarios = $usersModel->findAll();

        $data = [
            'usuarios' => $usuarios
        ];

        return view('admin/permisos/index', $data);
    }

    // Método para asignar un grupo (rol) a un usuario vía AJAX o Formulario
    public function asignarGrupo()
    {
        $userId = $this->request->getPost('user_id');
        $grupo  = $this->request->getPost('grupo'); // ej: 'gerente', 'repartidor'

        $usersModel = new UserModel();
        $usuario = $usersModel->findById($userId);

        if (!$usuario) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        }

        // Si ya pertenece al grupo, se lo quitamos; si no, se lo ponemos (o usa addGroup directamente)
        if ($usuario->inGroup($grupo)) {
            $usuario->removeGroup($grupo);
            $msg = "Se removió el grupo '{$grupo}' al usuario.";
        } else {
            $usuario->addGroup($grupo);
            $msg = "Se asignó el grupo '{$grupo}' al usuario con éxito.";
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $msg
        ]);
    }

    // Método para asignar o revocar un permiso directo a un usuario
    public function gestionarPermisoDirecto()
    {
        $userId   = $this->request->getPost('user_id');
        $permiso  = $this->request->getPost('permiso'); // ej: 'precios.editar'

        $usersModel = new UserModel();
        $usuario = $usersModel->findById($userId);

        if (!$usuario) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        }

        if ($usuario->can($permiso)) {
            $usuario->removePermission($permiso);
            $msg = "Permiso '{$permiso}' revocado.";
        } else {
            $usuario->addPermission($permiso);
            $msg = "Permiso '{$permiso}' concedido.";
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $msg
        ]);
    }

    public function usuariosJson()
    {
        $usersModel = new \CodeIgniter\Shield\Models\UserModel();
        $usuarios = $usersModel->findAll();

        $dataUsuarios = [];
        foreach ($usuarios as $u) {
            $grupos = $u->getGroups();
            $grupoActual = !empty($grupos) ? $grupos[0] : 'sin_rol';

            $dataUsuarios[] = [
                'id'           => $u->id,
                'username'     => $u->username,
                'email'        => $u->getEmail(),
                'grupo'        => $grupoActual,
                'grupo_titulo' => ucfirst($grupoActual)
            ];
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'usuarios' => $dataUsuarios
        ]);
    }

    public function cambiarEstadoUsuario()
    {
        $userId        = $this->request->getPost('user_id');
        $nuevoEstado   = $this->request->getPost('status'); // Ej: 'active' o 'banned'

        $usersModel = new \CodeIgniter\Shield\Models\UserModel();
        
        // Verificamos que el usuario exista
        $usuario = $usersModel->findById($userId);
        if (!$usuario) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        }

        // Actualizamos el estado en la base de datos
        $actualizado = $usersModel->update($userId, [
            'status' => $nuevoEstado
        ]);

        if ($actualizado) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Estado del usuario actualizado a '{$nuevoEstado}' con éxito."
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No se pudo actualizar el estado.'
        ]);
    }

    public function crearUsuario()
    {
        // Opcional: Validar que el usuario actual sea superadmin o gerente
        if (!auth()->user()->inGroup('superadmin', 'gerente')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No tienes permisos para registrar usuarios.']);
        }

        $username = trim($this->request->getPost('username'));
        $email    = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');
        $grupo    = $this->request->getPost('grupo'); // 'gerente', 'repartidor', 'mostrador', etc.

        if (empty($username) || empty($email) || empty($password) || empty($grupo)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
        }

        $users = auth()->getProvider();

        // Crear la entidad de usuario de Shield
        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $username,
            'email'    => $email,
            'password' => $password,
        ]);

        try {
            // Guardar el usuario en la base de datos
            $users->save($user);

            // Volver a buscar el usuario recién creado para asignarle el grupo y activarlo
            $nuevoUsuario = $users->findById($users->getInsertID());
            
            // Asignar el rol seleccionado
            $nuevoUsuario->addGroup($grupo);

            // Asegurar que la cuenta quede activa de inmediato
            $nuevoUsuario->activate();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => '¡Usuario registrado y activado con éxito!'
            ]);

        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error real: ' . $e->getMessage() // <--- Esto mostrará la falla exacta
            ]);
        }
    }

}