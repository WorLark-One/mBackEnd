<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo');
            $table->string('descripcion');
            $table->bigInteger('precio');
            $table->string('imagen');
            $table->string('ubicacion');
            $table->string('link')->unique();
            $table->string('marketplace');
            $table->float('valoracion');
            $table->bigInteger('cantidad_valoraciones');
            $table->bigInteger('visualizaciones');
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
        Schema::dropIfExists('productos');
    }
}
