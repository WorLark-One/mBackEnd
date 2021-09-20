<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValoracionProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valoracion_productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('value');
            $table->string('comentario', 500);
            $table->string('nombre_usuario', 50);
            $table->text('nombre_producto');
            $table->bigInteger('producto_id')->unsigned();
            $table->bigInteger('usuario_id')->unsigned();
            $table->foreign('producto_id')->references('id')->on('producto')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('valoracion_productos');
    }
}
