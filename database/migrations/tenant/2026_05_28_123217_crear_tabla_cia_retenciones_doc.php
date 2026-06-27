<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('cia_retenciones_doc', function (Blueprint $table) {

            $table->id();

            // ── Relación con cabecera ─────────────────────────
            $table->unsignedBigInteger('iddoc');      // referencia a cia_documentos.id
            $table->unsignedBigInteger('idtiporete'); // referencia a tipretenciones.id

            // ── Valores ───────────────────────────────────────
            $table->double('base')->default(0);      // base sobre la que se calcula
            $table->double('por_rete')->default(0);  // porcentaje aplicado
            $table->double('valor')->default(0);     // valor calculado

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('cia_retenciones_doc');
    }
};