<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnoExamen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_examen', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('examen_id')->unsigned()->nullable();
            $table->foreign('examen_id')->references('id')->on('examens')->onDelete('cascade');

            $table->integer('alumno_id')->unsigned()->nullable();
            $table->foreign('alumno_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_examen');
    }
}
