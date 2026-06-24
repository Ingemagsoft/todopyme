<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla central de empresas (tenants).
     * Vive en la BD todopyme_central — única fuente de verdad
     * sobre qué empresas existen y a qué BD conecta cada una.
     */
    public function up(): void
    {
        Schema::connection('mysql')->create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();   // ej: DEMO, PRUEBA
            $table->string('nombre');                  // razón social
            $table->string('nit')->nullable();
            $table->string('db_name');                 // BD del tenant
            $table->string('db_host')->default('127.0.0.1');
            $table->string('db_port')->default('3306');
            $table->string('db_user')->default('root');
            $table->string('db_password')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};