<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('cia_documentos', function (Blueprint $table) {

            $table->id();

            // ── Identificación del documento ──────────────────
            $table->string('doc', 10);           // código del tipo de documento
            $table->string('bodega', 10);         // bodega
            $table->integer('tipo')->default(1);  // tipo: 1=venta
            $table->double('numero')->default(0); // consecutivo
            $table->date('fecreg');               // fecha de registro
            $table->date('fecvto')->nullable();   // fecha de vencimiento

            // ── Cliente y condiciones ─────────────────────────
            $table->string('cliente', 20);        // código del cliente
            $table->string('vendedor', 10)->default('0');
            $table->unsignedBigInteger('id_forma_pago')->default(0);
            $table->integer('dias')->default(0);  // días de crédito
            $table->integer('cartera')->default(0);
            $table->integer('cod_lista_precios')->default(1);

            // ── Valores ───────────────────────────────────────
            $table->double('vr_bruto')->default(0);      // subtotal sin IVA
            $table->double('vr_decto')->default(0);      // valor descuento
            $table->double('vr_pordecto')->default(0);   // % descuento
            $table->double('vr_iva')->default(0);        // valor IVA
            $table->double('vr_retenciones')->default(0);// valor retenciones
            $table->double('vr_total')->default(0);      // total factura
            $table->double('recibido')->default(0);      // total recibido
            $table->double('items')->default(0);         // cantidad de ítems

            // ── Estado ────────────────────────────────────────
            $table->integer('anulado')->default(0);      // 0=activo, 1=anulado
            $table->integer('estado')->default(0);       // 0=grabado, 1=cerrado
            $table->string('impreso', 1)->default('N');  // S=impreso

            // ── DIAN ──────────────────────────────────────────
            $table->string('cufe', 200)->nullable();

            // ── Extra ─────────────────────────────────────────
            $table->string('obs', 250)->nullable();
            $table->string('codus', 10)->nullable();     // usuario que grabó
            $table->date('feccreacion')->nullable();
            $table->time('hora')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('cia_documentos');
    }
};