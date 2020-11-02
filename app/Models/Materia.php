<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'identificador',
        'dpto',
    ];

    public function carreras()
    {
        return $this->belongsToMany('App\Models\Carrera', 'materia_carrera')
            ->withPivot('anio', 'cuatrimestre');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nombre','ilike', '%'.$search.'%')
            ->orWhere('identificador','ilike', '%'.$search.'%');
    }

    public function asistente()
    {
        return $this->belongsTo('App\Models\User', 'asistente_id');
    }

    public function profesor()
    {
        return $this->belongsTo('App\Models\User', 'profesor_id');
    }

    public function anotados()
    {
        return $this->belongsToMany('App\Models\User', 'alumno_materia', 'materia_id', 'alumno_id')
            ->withPivot('nota_cursado', 'nota_final');
    }
}
