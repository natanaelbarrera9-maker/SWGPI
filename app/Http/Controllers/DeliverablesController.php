<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliverablesController extends Controller
{
    public function index()
    {
        $deliverables = DB::table('entregables')
            ->join('competencias', 'entregables.competencia_id', '=', 'competencias.id')
            ->select('entregables.*', 'competencias.nombre as competencia_nombre')
            ->paginate(10);

        return view('admin.deliverables.index', compact('deliverables'));
    }

    public function create()
    {
        $competencias = DB::table('competencias')->get();
        return view('admin.deliverables.create', compact('competencias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'competencia_id' => 'required|integer|exists:competencias,id',
            'nombre' => 'required|string|max:255',
            'fecha_limite' => 'required|date',
            'formatos_aceptados' => 'nullable|string|max:255',
        ]);

        DB::table('entregables')->insert([
            'competencia_id' => $validated['competencia_id'],
            'nombre' => $validated['nombre'],
            'fecha_limite' => $validated['fecha_limite'],
            'formatos_aceptados' => $validated['formatos_aceptados'] ?? null,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.deliverables.index')->with('success', 'Entregable creado correctamente.');
    }

    public function show($id)
    {
        $deliverable = DB::table('entregables')
            ->join('competencias', 'entregables.competencia_id', '=', 'competencias.id')
            ->select('entregables.*', 'competencias.nombre as competencia_nombre')
            ->where('entregables.id', $id)
            ->first();
        if (!$deliverable) abort(404);
        return view('admin.deliverables.show', compact('deliverable'));
    }

    public function edit($id)
    {
        $deliverable = DB::table('entregables')
            ->where('entregables.id', $id)
            ->first();
        $competencias = DB::table('competencias')->get();
        if (!$deliverable) abort(404);
        return view('admin.deliverables.edit', compact('deliverable', 'competencias'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'competencia_id' => 'required|integer|exists:competencias,id',
            'nombre' => 'required|string|max:255',
            'fecha_limite' => 'required|date',
            'formatos_aceptados' => 'nullable|string|max:255',
        ]);

        DB::table('entregables')->where('id', $id)->update([
            'competencia_id' => $validated['competencia_id'],
            'nombre' => $validated['nombre'],
            'fecha_limite' => $validated['fecha_limite'],
            'formatos_aceptados' => $validated['formatos_aceptados'] ?? null,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.deliverables.index')->with('success', 'Entregable actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('entregables')->where('id', $id)->delete();
        return redirect()->route('admin.deliverables.index')->with('success', 'Entregable eliminado correctamente.');
    }
}
