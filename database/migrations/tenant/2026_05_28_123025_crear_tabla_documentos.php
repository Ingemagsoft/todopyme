<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('documentos', function (Blueprint $table) {

            $table->id();
            $table->string('codigo', 10)->unique();

            // ── Tipo de documento ─────────────────────────────
            // 1=Ventas, 2=Compras, 3=Inventarios, etc.
            $table->integer('tipo')->default(1);

            $table->string('descripcion', 100);  // nombre interno
            $table->string('titulo', 100);        // nombre que aparece en el documento impreso

            // ── Numeración ────────────────────────────────────
            $table->double('ultimo_numero')->default(0);  // último consecutivo usado
            $table->string('bodega', 10)->default('01');  // bodega por defecto

            // ── Configuración ─────────────────────────────────
            $table->boolean('predeterminado')->default(false); 
            $table->string('obs', 250)->nullable();

            // ── Control ───────────────────────────────────────
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('documentos');
    }
};