<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('vendedores', function (Blueprint $table) {

            $table->id();
            $table->string('codigo', 10)->unique();
            $table->string('nombre', 100);
            $table->string('documento', 30)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('email', 50)->nullable();

            // ── Comisiones ────────────────────────────────────
            $table->double('comision_ventas')->default(0);
            $table->double('comision_recaudo')->default(0);

            // ── Control ───────────────────────────────────────
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('vendedores');
    }
};