<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    public function anotados()
    {
        return $this->belongsToMany('App\Models\User', 'alumno_examen')
            ->withPivot('fecha_inscripcion');
    }
}
