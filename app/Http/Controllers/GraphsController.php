<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphsController extends Controller
{
    public function index()
    {
        $graphs = DB::table('grafos')->paginate(10);
        return view('admin.graphs.index', compact('graphs'));
    }

    public function create()
    {
        $projects = DB::table('projects')->get();
        return view('admin.graphs.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'proyecto_id' => 'nullable|integer|exists:projects,id',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg|max:2048',
            'status' => 'required|in:activo,inactivo',
        ]);

        $nombreArchivo = null;
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/grafos'), $nombreArchivo);
        }

        DB::table('grafos')->insert([
            'titulo' => $validated['titulo'],
            'proyecto_id' => $validated['proyecto_id'],
            'descripcion' => $validated['descripcion'],
            'nombre_archivo' => $nombreArchivo,
            'status' => $validated['status'],
            'fecha_creacion' => now(),
        ]);

        return redirect()->route('admin.graphs.index')->with('success', 'Grafo creado correctamente.');
    }

    public function show($id)
    {
        $graph = DB::table('grafos')->find($id);
        if (!$graph) abort(404);
        return view('admin.graphs.show', compact('graph'));
    }

    public function edit($id)
    {
        $graph = DB::table('grafos')->find($id);
        $projects = DB::table('projects')->get();
        if (!$graph) abort(404);
        return view('admin.graphs.edit', compact('graph', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $graph = DB::table('grafos')->find($id);
        if (!$graph) abort(404);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'proyecto_id' => 'nullable|integer|exists:projects,id',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg|max:2048',
            'status' => 'required|in:activo,inactivo',
        ]);

        $nombreArchivo = $graph->nombre_archivo;
        if ($request->hasFile('imagen')) {
            // Delete old image if exists
            if ($nombreArchivo && file_exists(public_path('uploads/grafos/' . $nombreArchivo))) {
                unlink(public_path('uploads/grafos/' . $nombreArchivo));
            }
            // Upload new image
            $file = $request->file('imagen');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/grafos'), $nombreArchivo);
        }

        DB::table('grafos')->where('id', $id)->update([
            'titulo' => $validated['titulo'],
            'proyecto_id' => $validated['proyecto_id'],
            'descripcion' => $validated['descripcion'],
            'nombre_archivo' => $nombreArchivo,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.graphs.index')->with('success', 'Grafo actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('grafos')->where('id', $id)->delete();
        return redirect()->route('admin.graphs.index')->with('success', 'Grafo eliminado correctamente.');
    }
}
