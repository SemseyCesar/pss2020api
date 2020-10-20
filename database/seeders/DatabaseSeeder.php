<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        include 'data.php';
        // \App\Models\User::factory(10)->create();
        foreach($data_carreras as &$carrera){
            \App\Models\Carrera::create($carrera);
        }
        foreach($data_materias as &$materias){
            \App\Models\Materia::create($materias);
        }
        foreach($data_users as &$user){
            \App\Models\User::create($user);
        }
    }
}
