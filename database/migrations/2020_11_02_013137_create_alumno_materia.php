<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnoMateria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_materia', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('materia_id')->unsigned()->nullable();
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');

            $table->integer('alumno_id')->unsigned()->nullable();
            $table->foreign('alumno_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->integer('nota_final')->nullable();
            $table->integer('nota_cursado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_materia');
    }
}
