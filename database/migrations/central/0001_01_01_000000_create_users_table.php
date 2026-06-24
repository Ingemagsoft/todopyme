<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de sesiones — vive en la BD central.
     * Necesaria desde antes de identificar el tenant
     * (ej. pantalla de login, rutas públicas).
     *
     * user_id se guarda como referencia informativa simple —
     * NO es una foreign key real, porque la tabla 'users' vive
     * en cada BD de tenant, no en la central.
     */
    protected $connection = 'mysql';

    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};