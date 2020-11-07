<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('type',['alumno', 'admin', 'docente']);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            #custom
            $table->enum('tipo_documento',['DNI', 'pasaporte', 'LE', 'LC']);
            $table->string('nombre_apellido');
            $table->date('fecha_nacimiento');
            $table->string('lugar_nacimiento');
            $table->string('DNI');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('legajo')->unique();
            $table->string('escuela')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
