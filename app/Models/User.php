<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'type',
        'nombre_apellido',
        'fecha_nacimiento',
        'tipo_documento',
        'lugar_nacimiento',
        'DNI',
        'direccion',
        'telefono',
        'legajo',
        'escuela',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('nombre_apellido','ilike', '%'.$search.'%')
            ->orWhere('legajo','ilike', '%'.$search.'%')
            ->orWhere('DNI','ilike', '%'.$search.'%');
    }
    
    public function materias_profesor(){
        return $this->hasMany('App\Models\Materia', 'profesor_id', 'id');
    }

    public function materias_asistente(){
        return $this->hasMany('App\Models\Materia', 'asistente_id', 'id');
    }

    public function scopeDocentes($query){
        return $query->where('type','=','docente');
    }

    public function carreras()
    {
        return $this->belongsToMany('App\Models\Carrera', 'alumno_carrera', 'alumno_id','carrera_id');
    }

    public function materias()
    {
        return $this->belongsToMany('App\Models\Materia', 'alumno_materia', 'alumno_id','materia_id')
            ->withPivot('nota_cursado', 'nota_final');;
    }

    public function profesor_examenes(){
        return $this->hasMany('App\Models\Examen', 'profesor_id', 'id');
    }

    public function alumno_examenes()
    {
        return $this->belongsToMany('App\Models\Examen', 'alumno_examen', 'alumno_id','examen_id')
            ->withPivot('fecha_inscripcion');
    }
}
