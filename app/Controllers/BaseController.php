<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $usuario = auth()->user();
        if ($usuario) {
            // Si no es superadmin y tiene una sucursal asignada, fijamos su sucursal activa automáticamente
            if (!$usuario->inGroup('superadmin') && !empty($usuario->sucursal_id)) {
                session()->set('sucursal_activa_id', $usuario->sucursal_id);
                
                // Opcional: Obtener el nombre para la vista
                $sucursalModel = new \App\Models\SucursalModel();
                $suc = $sucursalModel->find($usuario->sucursal_id);
                if ($suc) {
                    session()->set('sucursal_activa_nombre', $suc['nombre_municipio']);
                }
            }
        }
    }
}
