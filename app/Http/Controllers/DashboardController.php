<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Entregable;
use App\Models\EntregaEstudiante;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET /dashboard
     * Dashboard general (redirige según rol)
     */
    public function index()
    {
        $user = Auth::user();

        // perfil_id: 1=Admin, 2=Teacher, 3=Student
        if ($user->perfil_id == 1) {
            return $this->adminDashboard();
        } elseif ($user->perfil_id == 2) {
            return $this->teacherDashboard();
        } else {
            return $this->studentDashboard();
        }
    }

    /**
     * GET /admin
     * Dashboard administrativo
     */
    public function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('activo', true)->count(),
            'inactive_users' => User::where('activo', false)->count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('activo', true)->count(),
            'total_students' => User::where('perfil_id', 3)->count(),
            'total_teachers' => User::where('perfil_id', 2)->count(),
        ];

        $recent_projects = Project::with('creator', 'advisors')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recent_users = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_projects', 'recent_users'));
    }

    /**
     * GET /teacher
     * Dashboard del docente
     */
    public function teacherDashboard()
    {
        $teacher = Auth::user();

        // Obtener IDs de proyectos donde el docente es asesor
        $projectIds = $teacher->projectsAsAdvisor()->pluck('projects.id')->toArray() ?? [];

        $stats = [
            'my_projects' => count($projectIds),
            'active_projects' => Project::whereIn('id', $projectIds)->where('activo', true)->count(),
            'pending_deliverables' => EntregaEstudiante::whereIn('project_id', $projectIds)
                ->whereNull('calificacion')
                ->count(),
            'total_grades_given' => EntregaEstudiante::whereIn('project_id', $projectIds)
                ->whereNotNull('calificacion')
                ->count(),
        ];

        $my_projects = $teacher->projectsAsAdvisor()
            ->where('activo', true)
            ->with('creator')
            ->limit(5)
            ->get();

        $pending_deliverables = EntregaEstudiante::whereHas('entregable', function ($q) {
            // placeholder: filtrar por condiciones del entregable si aplica
        })->whereNull('calificacion')
        ->with(['entregable', 'estudiante', 'project'])
        ->orderBy('fecha_entrega', 'asc')
        ->limit(10)
        ->get();

        return view('teacher.dashboard', compact('stats', 'my_projects', 'pending_deliverables'));
    }

    /**
     * GET /student
     * Dashboard del estudiante
     */
    public function studentDashboard()
    {
        $student = Auth::user();

        // Algunos proyectos/relaciones pueden no existir (dependiendo de implementación de students↔projects)
        $myProjectsCount = method_exists($student, 'projects') ? $student->projects()->count() : 0;
        $activeProjectsCount = method_exists($student, 'projects') ? $student->projects()->where('activo', true)->count() : 0;

        $stats = [
            'my_projects' => $myProjectsCount,
            'active_projects' => $activeProjectsCount,
            'my_deliverables' => EntregaEstudiante::where('user_id', $student->id)->count(),
            'pending_deliverables' => EntregaEstudiante::where('user_id', $student->id)
                ->whereNull('calificacion')
                ->count(),
            'grades_received' => EntregaEstudiante::where('user_id', $student->id)->whereNotNull('calificacion')->count(),
        ];

        $my_projects = method_exists($student, 'projects') ?
            $student->projects()->where('activo', true)->with('advisors')->limit(5)->get() : collect();

        $my_deliverables = EntregaEstudiante::where('user_id', $student->id)
            ->with(['entregable', 'project'])
            ->orderBy('fecha_entrega', 'desc')
            ->limit(10)
            ->get();

        $upcoming_deadlines = Entregable::whereHas('competencia', function ($q) use ($student) {
            // limitar por proyectos del estudiante si es necesario
        })
        ->where('fecha_limite', '>', now())
        ->orderBy('fecha_limite', 'asc')
        ->limit(5)
        ->get();

        return view('student.dashboard', compact('stats', 'my_projects', 'my_deliverables', 'upcoming_deadlines'));
    }
}
