<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_de_compra', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('usuario_id')->unsigned();
            $table->string('direccion');
            $table->integer('numero');
            $table->string('otra_info');
            $table->integer('celular');
            $table->bigInteger('region_id')->unsigned();
            $table->bigInteger('comuna_id')->unsigned();
            $table->json('pedido');
            $table->bigInteger('estado_id')->unsigned();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regiones')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('comuna_id')->references('id')->on('comunas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('estado_id')->references('id')->on('estados')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes_de_compra');
    }
}
