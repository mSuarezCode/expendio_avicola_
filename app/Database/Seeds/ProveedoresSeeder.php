<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProveedoresSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre_empresa' => 'Avícola San Antonio',
                'contacto'       => 'Juan Pérez',
                'telefono'       => '9931234567',
                'created_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nombre_empresa' => 'Granja El Gallero',
                'contacto'       => 'María López',
                'telefono'       => '9937654321',
                'created_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('proveedores')->insertBatch($data);
    }
}