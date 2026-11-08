<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CatalogoProductosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre'              => 'Pollo Entero',
                'tipo_unidad'         => 'piezas',
                'precio_venta_actual' => 150.00,
                'estado'              => 1,
                'created_at'          => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'              => 'Gallina',
                'tipo_unidad'         => 'piezas',
                'precio_venta_actual' => 230.00,
                'estado'              => 1,
                'created_at'          => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'              => 'Alitas Naturales',
                'tipo_unidad'         => 'kilos',
                'precio_venta_actual' => 110.00,
                'estado'              => 1,
                'created_at'          => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'              => 'Alitas Adobadas',
                'tipo_unidad'         => 'kilos',
                'precio_venta_actual' => 130.00,
                'estado'              => 1,
                'created_at'          => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'              => 'Milanesa Natural',
                'tipo_unidad'         => 'kilos',
                'precio_venta_actual' => 130.00,
                'estado'              => 1,
                'created_at'          => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'              => 'Milanesa Marinada',
                'tipo_unidad'         => 'kilos',
                'precio_venta_actual' => 140.00,
                'estado'              => 1,
                'created_at'          => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'              => 'Milanesa Empanizada',
                'tipo_unidad'         => 'kilos',
                'precio_venta_actual' => 150.00,
                'estado'              => 1,
                'created_at'          => date('Y-m-d H:i:s'),
            ],
        ];

        // Inserta los registros en la tabla catalogo_productos
        $this->db->table('catalogo_productos')->insertBatch($data);
    }
}