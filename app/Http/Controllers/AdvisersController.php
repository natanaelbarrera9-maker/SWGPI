<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\BackWithErrorsException;

class AdvisersController extends Controller
{
    /**
     * Show projects with assigned advisers
     */
    public function index()
    {
        $projects = DB::table('projects')
            ->leftJoin('project_user', 'projects.id', '=', 'project_user.project_id')
            ->select('projects.*', DB::raw('COUNT(CASE WHEN project_user.rol_asesor IS NOT NULL THEN 1 END) as adviser_count'))
            ->groupBy('projects.id', 'projects.title', 'projects.description', 'projects.created_by', 'projects.created_at', 'projects.activo')
            ->paginate(10);

        return view('admin.advisers.index', compact('projects'));
    }

    /**
     * Show a project with its current advisers
     */
    public function show($id)
    {
        $project = DB::table('projects')->find($id);
        if (!$project) abort(404);

        $advisers = DB::table('project_user')
            ->join('users', 'project_user.user_id', '=', 'users.id')
            ->where('project_user.project_id', $id)
            ->where('project_user.rol_asesor', '!=', null)
            ->select('project_user.*', 'users.nombres', 'users.apa', 'users.ama')
            ->get();

        $students = DB::table('project_user')
            ->join('users', 'project_user.user_id', '=', 'users.id')
            ->where('project_user.project_id', $id)
            ->where('project_user.rol_asesor', null)
            ->select('project_user.*', 'users.nombres', 'users.apa', 'users.ama')
            ->get();

        return view('admin.advisers.show', compact('project', 'advisers', 'students'));
    }

    /**
     * Assign adviser to project
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'user_id' => 'required|string|exists:users,id',
            'rol_asesor' => 'required|in:primario,secundario',
        ]);

        // Verify user is a teacher (perfil_id = 2)
        $teacher = DB::table('users')->where('id', $validated['user_id'])->where('perfil_id', 2)->first();
        if (!$teacher) {
            throw new BackWithErrorsException(['user_id' => 'Solo se pueden asignar docentes como asesores.']);
        }

        // Check if there's already a primary or secondary adviser
        $existing = DB::table('project_user')
            ->where('project_id', $validated['project_id'])
            ->where('rol_asesor', $validated['rol_asesor'])
            ->first();

        if ($existing) {
            throw new BackWithErrorsException(['rol_asesor' => "Ya existe un asesor {$validated['rol_asesor']} para este proyecto."]);
        }

        // Check if teacher is already an adviser (any role) for this project
        $alreadyAssigned = DB::table('project_user')
            ->where('project_id', $validated['project_id'])
            ->where('user_id', $validated['user_id'])
            ->where('rol_asesor', '!=', null)
            ->first();

            if ($alreadyAssigned) {
                throw new BackWithErrorsException(['user_id' => 'Este docente ya es asesor de este proyecto.']);
            }

            // Insert or update adviser assignment
            DB::table('project_user')->updateOrInsert(
                [
                    'project_id' => $validated['project_id'],
                    'user_id' => $validated['user_id'],
                ],
                [
                    'rol_asesor' => $validated['rol_asesor'],
                ]
            );

            return redirect()->route('admin.advisers.show', $validated['project_id'])
                ->with('success', "Asesor {$validated['rol_asesor']} asignado correctamente.");
        } catch (BackWithErrorsException $e) {
            // rethrow validation/business exceptions to be handled by framework
            throw $e;
        } catch (\Exception $e) {
            Log::error('AdvisersController@store error: ' . $e->getMessage());
            throw new BackWithErrorsException(['error' => 'Error al asignar asesor: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove adviser from project
     */
    public function destroy($projectId, $userId)
    {
        try {
            DB::table('project_user')
                ->where('project_id', $projectId)
                ->where('user_id', $userId)
                ->update(['rol_asesor' => null]);

            return redirect()->route('admin.advisers.show', $projectId)
                ->with('success', 'Asesor desasignado correctamente.');
        } catch (\Exception $e) {
            Log::error('AdvisersController@destroy error: ' . $e->getMessage());
            throw new BackWithErrorsException(['error' => 'Error al desasignar asesor']);
        }
    }
}

