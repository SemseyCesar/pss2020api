<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = [
        'identificador',
        'materia_id',
        'fecha',
        'hora',
        'aula'
    ];
    
    public function anotados()
    {
        return $this->belongsToMany('App\Models\User', 'alumno_examen')
            ->withPivot('fecha_inscripcion');
    }

    public function materia()
    {
        return $this->belongsTo('App\Models\Materia', 'materia_id');
    }
    
    public function profesor()
    {
        return $this->belongsTo('App\Models\User', 'profesor_id');
    }
}
