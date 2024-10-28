<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use MongoDB\Client;

class UserController extends Controller
{
/**
     * Obtiene todos los usuarios de la base de datos.
     * Devuelve estado 200 si tiene éxito, o 404 si no encuentra recursos.
     */
    public function index(Request $request)
    {
        // $filtros = $request->query();
        // $users = User::where($filtros)->where('removed', '!=', true)->get();
        $users = User::where('removed', '!=', true)->get();

        if ($users->isEmpty()) {
            return response()->json(['mensaje' => 'Recursos no encontrados.'], 404);
        }

        return response()->json($users, 200);
    }

    /**
     * Obtiene un usuario por ID.
     * Devuelve estado 200 si tiene éxito, o 404 si no encuentra el recurso.
     */
    public function view($id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json(['mensaje' => 'Recurso no encontrado'], 404);
        }

        return response()->json($user, 200);
    }

    public function createForm()
    {
        return response()->json(['message' => 'Crear nuevo usuario']);
    }

    /**
     * Agrega un nuevo usuario a la base de datos.
     * Devuelve estado 201 si tiene éxito, o 400 en caso de error.
     */
    public function createProcess(Request $request)
    {
        $user = User::create($request->all());

        if (!$user) {
            return response()->json(['mensaje' => 'No se pudo agregar'], 400);
        }

        return response()->json($user, 201);
    }

    /**
     * Elimina un usuario de manera lógica por ID.
     * Devuelve estado 204 si tiene éxito, o 404 si no encuentra el recurso.
     */
    public function remove($id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json(['mensaje' => 'No se pudo eliminar'], 404);
        }

        $user->removed = true;
        $user->save();

        return response()->json(['mensaje' => 'Usuario eliminado'], 204);
    }

    public function editForm($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user, 'message' => 'Editar usuario']);
    }

    /**
     * Actualiza el usuario buscado por ID con nuevos datos.
     * Devuelve estado 204 si tiene éxito, o 404 si no encuentra el recurso.
     */
    public function editProcess(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json(['mensaje' => 'Recurso no encontrado'], 404);
        }

        $user->update($request->all());
        return response()->json($user, 204);
    }
}
