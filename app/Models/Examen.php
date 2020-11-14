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

    public function isInscripto($id){
        return in_array($id, $this->anotados()->pluck('alumno_id')->all());
    }

    public function anotados()
    {
        return $this->belongsToMany('App\Models\User', 'alumno_examen', 'examen_id','alumno_id')
            ->withPivot('fecha_inscripcion')->withTimestamps();
    }

    public function scopeSearch($query, $field, $search)
    {
        return $query->where($field,'ilike', '%'.$search.'%');
    }

    public function scopeSearchSame($query, $field, $search)
    {
        return $query->where($field,'=', $search);
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
