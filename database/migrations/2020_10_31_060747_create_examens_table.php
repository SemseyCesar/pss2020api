<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('materia_id')->unsigned()->nullable();
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');

            $table->integer('profesor_id')->unsigned()->nullable();
            $table->foreign('profesor_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('identificador')->unique();
            $table->dateTime('fecha');
            $table->string('aula');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examens');
    }
}
