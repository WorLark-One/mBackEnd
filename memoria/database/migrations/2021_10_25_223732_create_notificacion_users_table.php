<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacionUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacion_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('producto_id')->unsigned();
            $table->bigInteger('usuario_id')->unsigned();
            $table->string('nombre_producto');
            $table->bigInteger('precio_producto');
            $table->integer('descuento_producto');
            $table->boolean('notificacion_leida')->default(0);
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('producto')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificacion_users');
    }
}
