<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('productos_precios', function (Blueprint $table) {

            $table->id();

            // ── Relaciones ────────────────────────────────────
            $table->string('codproducto', 20);   // referencia a productos.codigo
            $table->integer('codlista');          // referencia a tiplistaprecios.codigo

            // ── Precio ────────────────────────────────────────
            $table->double('precio')->default(0);
            $table->double('pordecto')->default(0); // % descuento por lista

            // ── Control ───────────────────────────────────────
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // ── Índice único producto + lista ─────────────────
            $table->unique(['codproducto', 'codlista']);
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('productos_precios');
    }
};