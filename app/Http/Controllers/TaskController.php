<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * La función de índice recupera todas las tareas no archivadas de la base de datos y las ordena
     * por ID descendente.
     * @returns una colección de modelos de tareas donde la columna 'archivo' se establece en 0. Las
     * tareas están ordenadas por su columna 'id' en orden descendente.
     */
    public function index()
    {
        return Task::where('archive', 0)->orderBy('id', 'desc')->get();
    }
    
    /**
     * La función recupera todas las tareas archivadas de la base de datos y las devuelve en orden
     * descendente según su ID.
     * @returns una colección de modelos de tareas donde el atributo 'archivo' se establece en 1. Las
     * tareas están ordenadas por su atributo 'id' en orden descendente.
     */
    public function archived()
    {
        return Task::where('archive', 1)->orderBy('id', 'desc')->get();
    }

    /**
     * La función de store en PHP valida una solicitud y crea una nueva Tarea con el cuerpo
     * proporcionado.
     * @param {Request} request - El parámetro  es una instancia de la clase Request, que
     * representa la solicitud HTTP realizada al servidor. Contiene todos los datos e información
     * enviada por el cliente.
     * @returns una instancia del modelo de tarea con el atributo 'cuerpo' establecido en el valor de
     * la entrada 'cuerpo' de la solicitud.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required|max:500'
        ]);

        return Task::create([
            'body' => request('body')
        ]);
    }

    /**
     * La función de edición en PHP valida la solicitud, busca una tarea por su ID, actualiza el cuerpo
     * de la tarea con el cuerpo de la solicitud y guarda los cambios.
     * @param {Request} request - El parámetro  es una instancia de la clase Request, que
     * representa una solicitud HTTP realizada al servidor. Contiene información sobre la solicitud,
     * como el método de solicitud, la URL, los encabezados y cualquier dato enviado con la solicitud.
     */
    public function edit(Request $request)
    {
        $this->validate($request, [
            'body' => 'required|max:500'
        ]);
        $task = Task::findOrFail($request->id);
        $task->body = $request->body;
        $task->save();
    }

    /**
     * La función "archivar" alterna la propiedad "archivar" de un objeto Tarea y guarda los cambios.
     * @param id - El parámetro "id" es el identificador único de la tarea que debe archivarse.
     */
    public function archive($id)
    {
        $task = Task::findOrFail($id);
        $task->archive = !$task->archive;
        $task->save();
    }

    /**
     * La función de destrucción elimina una tarea con el ID especificado.
     * @param id - El parámetro "id" es el identificador único de la tarea que debe eliminarse.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
    }
}
