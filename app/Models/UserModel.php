<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected $allowedFields = [
        'username',
        'status',
        'status_message',
        'active',
        'last_active',
        'sucursal_id', // 👈 ¡Importante! Aquí agregamos tu campo personalizado
    ];
}