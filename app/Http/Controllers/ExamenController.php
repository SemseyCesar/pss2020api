<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examen;

class ExamenController extends Controller
{
    function store(Request $request){
        $validatedRequest = $this->examen_validate($request, null);
    } 

    public function examen_validate($request, $examen){
        $identificador_validate = 'unique:examens';
        if($examen!= null){
            $identificador_validate = 'unique:examens,identificador,'.$examen->id;
        }
        return $request->validate([
            'identificador' => ['required', 'string', 'max:255', $identificador_validate],
            'materia_id' => ['required'],
            'fecha' => ['required','date'],
            'aula' => ['required','string']
        ]);
    }
}
