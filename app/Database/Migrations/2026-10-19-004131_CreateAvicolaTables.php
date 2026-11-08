<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAvicolaTables extends Migration
{
    public function up()
    {
        // 1. Tabla Proveedores
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nombre_empresa' => ['type' => 'VARCHAR', 'constraint' => 120],
            'contacto'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'telefono'       => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('proveedores');

        // 2. Tabla Lotes de Compra
        $this->forge->addField([
            'id'                     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'proveedor_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'usuario_id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tipo_ave'               => ['type' => 'ENUM', 'constraint' => ['pollo', 'gallina']],
            'cantidad_piezas'        => ['type' => 'INT', 'constraint' => 11],
            'peso_total_kg'          => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'precio_compra_unitario' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'fecha_ingreso'          => ['type' => 'DATE'],
            'observaciones'          => ['type' => 'TEXT', 'null' => true],
            'created_at'             => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('proveedor_id', 'proveedores', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lotes_compra');

        // 3. Tabla Catálogo de Productos
        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nombre'             => ['type' => 'VARCHAR', 'constraint' => 100],
            'tipo_unidad'        => ['type' => 'ENUM', 'constraint' => ['piezas', 'kilos']],
            'precio_venta_actual'=> ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'estado'             => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('catalogo_productos');

        // 4. Tabla Historial de Precios
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'producto_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'precio_anterior' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'precio_nuevo'    => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'fecha_cambio'    => ['type' => 'DATETIME'],
            'usuario_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('producto_id', 'catalogo_productos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('historial_precios');
    }

    public function down()
    {
        $this->forge->dropTable('historial_precios');
        $this->forge->dropTable('catalogo_productos');
        $this->forge->dropTable('lotes_compra');
        $this->forge->dropTable('proveedores');
    }
}