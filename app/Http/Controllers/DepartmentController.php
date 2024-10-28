<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use MongoDB\Client;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::where('removed', '!=', true)->get();
        // return response()->json($departments);

        if ($departments->isEmpty()) {
            return response()->json(['message' => 'No departments found'], 404);
        }
    
        return response()->json($departments);
    }

    public function view($id)
    {
        $department = Department::find($id);
        return response()->json($department);
    }

    public function createForm()
    {
        return response()->json(['message' => 'Crear nuevo departamento']);
    }

    public function createProcess(Request $request)
    {
        $department = Department::create($request->all());
        return redirect('/viviendas');
    }

    public function remove($id)
    {
        $department = Department::find($id);
        $department->removed = true;
        $department->save();

        return redirect('/viviendas');
    }

    public function editForm($id)
    {
        $department = Department::find($id);
        return response()->json(['department' => $department, 'message' => 'Editar departamento']);
    }

    public function editProcess(Request $request, $id)
    {
        $department = Department::find($id);
        $department->update($request->all());
        return redirect('/viviendas');
    }
}
