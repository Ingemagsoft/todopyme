<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection('tenant')->create('formaspago', function (Blueprint $table) {

            $table->id();
            $table->string('descripcion', 50)->unique();

            // ── Condiciones ───────────────────────────────────
            $table->integer('dias')->default(0);     // días de plazo
            $table->integer('cartera')->default(0);  // 1=cartera, 0=contado

            // ── Control ───────────────────────────────────────
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('formaspago');
    }
};