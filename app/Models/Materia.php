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
        return $this->belongsToMany('App\Models\Carrera');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nombre','ilike', '%'.$search.'%')
            ->orWhere('identificador','ilike', '%'.$search.'%');
    }
}
