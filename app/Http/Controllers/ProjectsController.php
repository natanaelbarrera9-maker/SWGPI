<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\BackWithErrorsException;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = DB::table('projects')->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        // Exclude students already assigned to any project
        $assigned = DB::table('project_user')->pluck('user_id')->toArray();
        $studentsQuery = DB::table('users')->where('perfil_id', 3);
        if (!empty($assigned)) {
            $studentsQuery->whereNotIn('id', $assigned);
        }
        $students = $studentsQuery->get();
        return view('admin.projects.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|string|exists:users,id',
        ]);

        // Verify all students are actually perfil_id = 3
        $studentIds = $validated['student_ids'];
        $validStudents = DB::table('users')->whereIn('id', $studentIds)->where('perfil_id', 3)->pluck('id')->toArray();
        $invalid = array_diff($studentIds, $validStudents);
        if (!empty($invalid)) {
            throw new BackWithErrorsException(['student_ids' => 'Solo se pueden registrar estudiantes válidos.']);
        }

        // Create project with authenticated user as creator
        $creatorId = Auth::id();
        $projectId = DB::table('projects')->insertGetId([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'created_by' => $creatorId,
            'created_at' => now(),
        ]);

        // Add each selected student in project_user pivot
        $inserts = [];
        foreach ($studentIds as $sid) {
            $inserts[] = [
                'project_id' => $projectId,
                'user_id' => $sid,
                'rol_asesor' => null, // Author, not an adviser
            ];
        }
        if (!empty($inserts)) {
            DB::table('project_user')->insert($inserts);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proyecto creado correctamente.');
    }

    public function show($id)
    {
        $project = DB::table('projects')->find($id);
        if (!$project) abort(404);

        // Creator info
        $creator = null;
        if (!empty($project->created_by)) {
            $creator = DB::table('users')->where('id', $project->created_by)->select('id', 'nombres', 'apa', 'ama', 'email')->first();
        }

        // Authors (students): project_user where rol_asesor is null
        $students = DB::table('project_user')
            ->join('users', 'project_user.user_id', '=', 'users.id')
            ->where('project_user.project_id', $id)
            ->whereNull('project_user.rol_asesor')
            ->select('users.id', 'users.nombres', 'users.apa', 'users.ama', 'users.email')
            ->get();

        // Subjects linked via project_asignatura
        $subjects = DB::table('project_asignatura')
            ->join('asignaturas', 'project_asignatura.asignatura_id', '=', 'asignaturas.id')
            ->where('project_asignatura.project_id', $id)
            ->select('asignaturas.id', 'asignaturas.nombre', 'asignaturas.clave')
            ->get();

        // Advisers: primary and secondary
        $primaryAdviser = DB::table('project_user')
            ->join('users', 'project_user.user_id', '=', 'users.id')
            ->where('project_user.project_id', $id)
            ->where('project_user.rol_asesor', 'primario')
            ->select('users.id', 'users.nombres', 'users.apa', 'users.ama', 'users.email')
            ->first();

        $secondaryAdviser = DB::table('project_user')
            ->join('users', 'project_user.user_id', '=', 'users.id')
            ->where('project_user.project_id', $id)
            ->where('project_user.rol_asesor', 'secundario')
            ->select('users.id', 'users.nombres', 'users.apa', 'users.ama', 'users.email')
            ->first();

        return view('admin.projects.show', compact('project', 'creator', 'students', 'subjects', 'primaryAdviser', 'secondaryAdviser'));
    }

    public function edit($id)
    {
        $project = DB::table('projects')->find($id);
        if (!$project) abort(404);

        // Get assigned students for this project (rol_asesor is null => students)
        $assigned = DB::table('project_user')->where('project_id', $id)->whereNull('rol_asesor')->pluck('user_id')->toArray();

        // Get user_ids assigned to other projects
        $others = DB::table('project_user')->where('project_id', '!=', $id)->pluck('user_id')->toArray();

        // Students available: perfil_id = 3 AND (not in others OR in assigned)
        $studentsQuery = DB::table('users')->where('perfil_id', 3);
        if (!empty($others)) {
            $studentsQuery->where(function($q) use ($others, $assigned) {
                $q->whereNotIn('id', $others);
                if (!empty($assigned)) {
                    $q->orWhereIn('id', $assigned);
                }
            });
        }
        $students = $studentsQuery->get();

        return view('admin.projects.edit', compact('project', 'students', 'assigned'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::table('projects')->where('id', $id)->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        // If student selection submitted, sync project_user for students
        if ($request->has('student_ids')) {
            $studentIds = (array) $request->input('student_ids');

            // Validate that selected students exist and are perfil_id = 3 and not assigned to other projects
            $validStudents = DB::table('users')->whereIn('id', $studentIds)->where('perfil_id', 3)->pluck('id')->toArray();
            $invalid = array_diff($studentIds, $validStudents);
            if (!empty($invalid)) {
                throw new BackWithErrorsException(['student_ids' => 'Solo se pueden asignar estudiantes válidos.']);
            }

            // Remove existing student (non-adviser) relations for this project
            DB::table('project_user')->where('project_id', $id)->whereNull('rol_asesor')->delete();

            // Insert new relations
            $inserts = [];
            foreach ($studentIds as $sid) {
                $inserts[] = [
                    'project_id' => $id,
                    'user_id' => $sid,
                    'rol_asesor' => null,
                ];
            }
            if (!empty($inserts)) {
                DB::table('project_user')->insert($inserts);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('projects')->where('id', $id)->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Proyecto eliminado correctamente.');
    }
}
