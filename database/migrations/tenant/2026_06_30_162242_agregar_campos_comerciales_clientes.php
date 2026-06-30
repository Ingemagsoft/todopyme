<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega las columnas comerciales y de contacto faltantes
     * en la tabla clientes — ya declaradas en ClienteModel::$fillable
     * pero nunca creadas físicamente en la BD de tenant.
     */
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('codigo_postal', 10)->nullable()->after('ciudad_id');
            $table->unsignedInteger('cod_lista_precios')->default(1)->after('codigo_postal');
            $table->string('cod_vendedor', 10)->default('0')->after('cod_lista_precios');
            $table->unsignedInteger('id_forma_pago')->default(0)->after('cod_vendedor');
            $table->double('descuento')->default(0)->after('id_forma_pago');
            $table->string('observaciones', 250)->nullable()->after('descuento');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn([
                'codigo_postal',
                'cod_lista_precios',
                'cod_vendedor',
                'id_forma_pago',
                'descuento',
                'observaciones',
            ]);
        });
    }
};