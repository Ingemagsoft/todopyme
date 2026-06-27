<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('tipretenciones', function (Blueprint $table) {

            $table->id();
            $table->string('descripcion', 100);

            // ── Configuración ─────────────────────────────────
            $table->double('base')->default(0);   // base mínima para aplicar
            $table->double('por')->default(0);    // porcentaje de retención
            $table->string('cuenta', 20)->nullable(); // cuenta contable

            // ── Clasificación ─────────────────────────────────
            // tipo: 1=Reteica, 2=Retefuente, 3=ReteIVA, 4=ReteICA
            $table->integer('tipo')->default(1);
            // naturaleza: D=débito, C=crédito
            $table->char('naturaleza', 1)->default('D');

            // ── Control ───────────────────────────────────────
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('tipretenciones');
    }
};