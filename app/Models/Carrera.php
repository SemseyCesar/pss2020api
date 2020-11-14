<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'identificador',
        'dpto',
        'docente',
        'duracion',
    ];

    public function materias()
    {
        return $this->belongsToMany('App\Models\Materia','materia_carrera')
            ->withPivot('anio', 'cuatrimestre');
    }

    public function scopeSearch($query, $field, $search)
    {
        return $query->where($field,'ilike', '%'.$search.'%');
    }

    public function anotados()
    {
        return $this->belongsToMany('App\Models\User', 'alumno_carrera', 'carrera_id', 'alumno_id');
    }
}
