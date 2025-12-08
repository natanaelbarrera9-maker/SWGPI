<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompetenciesController extends Controller
{
    public function index()
    {
        $competencies = DB::table('competencias')
            ->leftJoin('asignaturas', 'competencias.asignatura_id', '=', 'asignaturas.id')
            ->select('competencias.*', 'asignaturas.nombre as asignatura_nombre', 'asignaturas.clave as asignatura_clave')
            ->paginate(10);

        return view('admin.competencies.index', compact('competencies'));
    }

    public function create()
    {
        $asignaturas = DB::table('asignaturas')->orderBy('nombre')->get();
        return view('admin.competencies.create', compact('asignaturas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asignatura_id' => 'required|exists:asignaturas,id',
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        DB::table('competencias')->insert([
            'asignatura_id' => $validated['asignatura_id'],
            'nombre' => $validated['nombre'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'created_at' => now(),
        ]);

        return redirect()->route('admin.competencies.index')->with('success', 'Competencia creada correctamente.');
    }

    public function show($id)
    {
        $competency = DB::table('competencias')
            ->leftJoin('asignaturas', 'competencias.asignatura_id', '=', 'asignaturas.id')
            ->select('competencias.*', 'asignaturas.nombre as asignatura_nombre', 'asignaturas.clave as asignatura_clave')
            ->where('competencias.id', $id)
            ->first();

        if (!$competency) abort(404);
        return view('admin.competencies.show', compact('competency'));
    }

    public function edit($id)
    {
        $competency = DB::table('competencias')->find($id);
        if (!$competency) abort(404);
        $asignaturas = DB::table('asignaturas')->orderBy('nombre')->get();
        return view('admin.competencies.edit', compact('competency', 'asignaturas'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'asignatura_id' => 'required|exists:asignaturas,id',
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        DB::table('competencias')->where('id', $id)->update([
            'asignatura_id' => $validated['asignatura_id'],
            'nombre' => $validated['nombre'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.competencies.index')->with('success', 'Competencia actualizada correctamente.');
    }

    public function destroy($id)
    {
        DB::table('competencias')->where('id', $id)->delete();
        return redirect()->route('admin.competencies.index')->with('success', 'Competencia eliminada correctamente.');
    }
}
