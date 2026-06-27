<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('productos', function (Blueprint $table) {

            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('codbarras', 20)->nullable();
            $table->string('descripcion', 100);
            $table->string('unidad', 10)->nullable();

            // ── Clasificación ─────────────────────────────────
            $table->integer('tipproducto')->default(0);
            $table->integer('grupo')->default(0);
            $table->integer('subgrupo')->default(0);
            $table->integer('linea')->default(0);

            // ── Precios e impuestos ───────────────────────────
            $table->double('vrcompra')->default(0);
            $table->integer('poriva')->default(19);      // % IVA por defecto 19%
            $table->double('pordecto')->default(0);      // % descuento

            // ── Flags ─────────────────────────────────────────
            $table->boolean('servicio')->default(false); // true = servicio, no maneja stock
            $table->boolean('preciomod')->default(true); // permite modificar precio en factura

            // ── Control ───────────────────────────────────────
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('productos');
    }
};