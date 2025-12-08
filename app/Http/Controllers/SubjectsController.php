<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectsController extends Controller
{
    public function index()
    {
        $subjects = DB::table('asignaturas')->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clave' => 'required|string|max:20|unique:asignaturas',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        DB::table('asignaturas')->insert([
            'clave' => $validated['clave'],
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'created_at' => now(),
        ]);

        return redirect()->route('admin.subjects.index')->with('success', 'Asignatura creada correctamente.');
    }

    public function show($id)
    {
        $subject = DB::table('asignaturas')->find($id);
        if (!$subject) abort(404);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit($id)
    {
        $subject = DB::table('asignaturas')->find($id);
        if (!$subject) abort(404);
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'clave' => 'required|string|max:20|unique:asignaturas,clave,' . $id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        DB::table('asignaturas')->where('id', $id)->update([
            'clave' => $validated['clave'],
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
        ]);

        return redirect()->route('admin.subjects.index')->with('success', 'Asignatura actualizada correctamente.');
    }

    public function destroy($id)
    {
        DB::table('asignaturas')->where('id', $id)->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Asignatura eliminada correctamente.');
    }
}
