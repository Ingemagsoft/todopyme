<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('cia_fpagos_factura', function (Blueprint $table) {

            $table->id();

            // ── Relación con cabecera ─────────────────────────
            $table->unsignedBigInteger('iddoc');       // referencia a cia_documentos.id
            $table->unsignedBigInteger('codtippago');  // referencia a tippagos.id

            // ── Valores ───────────────────────────────────────
            $table->double('valor')->default(0);     // valor del pago
            $table->double('recibido')->default(0);  // valor recibido

            // ── Referencia opcional ───────────────────────────
            $table->string('doc', 50)->nullable();   // número de referencia (cheque, tarjeta)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('cia_fpagos_factura');
    }
};