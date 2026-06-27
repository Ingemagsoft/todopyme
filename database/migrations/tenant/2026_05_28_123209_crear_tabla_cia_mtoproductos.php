<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('cia_mtoproductos', function (Blueprint $table) {

            $table->id();

            // ── Relación con cabecera ─────────────────────────
            $table->unsignedBigInteger('iddoc');  // referencia a cia_documentos.id

            // ── Identificación del documento ──────────────────
            $table->string('doc', 10);
            $table->integer('tipo')->default(1);
            $table->double('numero')->default(0);
            $table->string('bodega', 10);

            // ── Producto ──────────────────────────────────────
            $table->string('codproducto', 20);
            $table->string('detalle', 200)->nullable(); // detalle adicional
            $table->date('fecha');

            // ── Cantidades y valores ──────────────────────────
            $table->double('cant')->default(0);       // cantidad
            $table->double('vr_venta')->default(0);   // precio unitario
            $table->double('por_iva')->default(0);    // % IVA
            $table->double('vr_iva')->default(0);     // valor IVA
            $table->double('por_decto')->default(0);  // % descuento
            $table->double('vr_decto')->default(0);   // valor descuento
            $table->double('vr_total')->default(0);   // total ítem

            // ── Control ───────────────────────────────────────
            $table->string('vendedor', 10)->default('0');
            $table->date('feccreacion')->nullable();
            $table->time('hora')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('cia_mtoproductos');
    }
};