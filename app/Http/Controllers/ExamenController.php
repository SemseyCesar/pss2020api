<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examen;
use App\Models\User;

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
            'fecha' => ['required', 'date', 'date_format:"Y-m-d"'],
            'hora' => ['required', 'date_format:"H:i"'],
            'aula' => ['required','string']
        ]);
    }

    public function examenesprofesor(Request $request){
        return response(['message'=>'Acceso Denegado'],403);
    }

    public function index(Request $request){
        $examenes = null;
        switch(auth()->user()->type){
            case 'admin':
                $examenes = Examen::with('materia');
                break;
            case 'docente':
                $examenes = User::find(auth()->user()->id)->profesor_examenes()
                    ->with('materia');
                break;
            case 'alumno':
                $examenes = Examen::with('materia')->with('profesor');
                break;
        }

        if($request->has('materia'))
            $examenes = $examenes->searchSame('materia_id', $request->materia);
        if($request->has('code'))
            $examenes = $examenes->search('identificador', $request->code);

        return response(['examenes' => $examenes->get()->each(function($model){
            $model->inscripto = $model->isInscripto(auth()->user()->id) ? 'Si': 'No' ;
        })],200);
    }

    public function delete(Request $request, $id){
        $examen = Examen::find($id);
        if($examen != null){
            $examen->delete();
            return response(['examen' => 'examen id: '.$id.' fue eliminado'],200);
        }else 
            return response(['examen' => "not found"], 404);
        
    }

    public function detail(Request $request, $id){
        $examen = Examen::with('materia')->find($id);
        if($examen != null)
            return response(['examen' => $examen ],200);
        else 
            return response(['examen' => "not found"], 404);
    }

    public function inscripcion(Request $request){
        $examen = Examen::find($request->examen_id);
        $examen->anotados()->attach(auth()->user()->id);
        $examen->save();
        return response(['examen' => 'anotado'],200);
    }

    public function desinscripcion(Request $request, $id){
        $examen = Examen::find($id);
        $examen->anotados()->detach(auth()->user()->id);
        $examen->save();
        return response(['examen' => 'inscripción eliminada'],200);
    }
}
