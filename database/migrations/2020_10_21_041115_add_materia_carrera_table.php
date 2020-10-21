<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMateriaCarreraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materia_carrera', function(BluePrint $table){
            $table->id();

            $table->integer('materia_id')->unsigned()->nullable();
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
            
            $table->integer('carrera_id')->unsigned()->nullable();
            $table->foreign('carrera_id')->references('id')->on('carreras')->onDelete('cascade');;
            
            $table->string('anio');
            $table->string('cuatrimestre');
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
        //
    }
}
