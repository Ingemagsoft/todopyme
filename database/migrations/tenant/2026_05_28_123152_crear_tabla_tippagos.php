<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('tippagos', function (Blueprint $table) {

            $table->id();
            $table->integer('codigo')->unique();
            $table->string('descripcion', 100);

            // ── Configuración ─────────────────────────────────
            $table->string('cuenta', 30)->nullable();  // cuenta contable
            // naturaleza: D=débito, C=crédito
            $table->char('naturaleza', 1)->default('D');
            // codfe: código DIAN para facturación electrónica
            $table->string('codfe', 10)->nullable();

            // ── Control ───────────────────────────────────────
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('tippagos');
    }
};