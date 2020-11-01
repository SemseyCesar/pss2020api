<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examen;

class ExamenController extends Controller
{
    function store(Request $request){
        $validatedRequest = $this->examen_validate($request, null);
        $examen = Examen::Create($validatedRequest);
        $examen->profesor_id  = auth()->user()->id;
        $examen->save();
    
        return response(['examen' => Examen::with('materia')->with('profesor')->find($examen->id)],200);
    }

    public function update(Request $request, $id){
        $examen = Examen::find($id);
        $validatedRequest = $this->examen_validate($request, $examen);
        $examen->identificador = $request->identificador;
        $examen->materia_id = $request->materia_id ;       
        $examen->fecha = $request->fecha;
        $examen->hora = $request->hora;
        $examen->aula = $request->aula;
        $examen->save();
        return response(['examen' => $examen],200);
    }

    public function examen_validate($request, $examen){
        $identificador_validate = 'unique:examens';
        if($examen!= null){
            $identificador_validate = 'unique:examens,identificador,'.$examen->id;
        }
        return $request->validate([
            'identificador' => ['required', 'string', 'max:255', $identificador_validate],
            'materia_id' => ['required'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'time'],
            'aula' => ['required','string']
        ]);
    }
}
