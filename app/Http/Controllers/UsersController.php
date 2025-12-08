<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Aplicar middlewares a todas las acciones
     */
    public function __construct()
    {
        // Autenticación obligatoria. El control de permisos por perfil se maneja dentro
        // de cada método o mediante policies en caso de que estén implementadas.
        $this->middleware(['auth']);
    }

    /**
     * GET /admin/users
     * Listar todos los usuarios
     */
    public function index()
    {
        $users = User::orderBy('activo', 'desc')
            ->orderBy('nombres', 'asc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * GET /admin/users/create
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * POST /admin/users
     * Guardar nuevo usuario
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = bcrypt($validated['password']);
            
            $user = User::create($validated);
            
            return redirect()
                ->route('admin.users.index')
                ->with('success', "Usuario {$user->nombres} creado exitosamente");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear usuario: ' . $e->getMessage()]);
        }
    }

    /**
     * GET /admin/users/{id}
     * Ver detalles del usuario
     */
    public function show(User $user)
    {
        // Si es AJAX, retornar JSON
        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $user->only([
                    'id', 'nombres', 'apa', 'ama', 'email', 'curp', 
                    'direccion', 'telefonos', 'perfil_id', 'activo', 'created_at'
                ]),
                'perfil' => $user->getPerfilName(),
            ]);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * GET /admin/users/{id}/edit
     * Mostrar formulario de edición
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * PUT /admin/users/{id}
     * Actualizar usuario
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        try {
            $validated = $request->validated();

            if (!empty($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    /**
     * DELETE /admin/users/{id}
     * Eliminar (desactivar) usuario
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        try {
            $user->update(['activo' => false]);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Usuario desactivado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al desactivar usuario']);
        }
    }

    /**
     * POST /admin/users/{id}/toggle-active
     * Cambiar estado activo/inactivo
     */
    public function toggleActive(User $user)
    {
        $this->authorize('update', $user);

        try {
            $user->update(['activo' => !$user->activo]);
            $status = $user->activo ? 'activado' : 'desactivado';

            return back()->with('success', "Usuario {$status} exitosamente");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al cambiar estado']);
        }
    }

    /**
     * GET /admin/inactive-users
     * Obtener usuarios inactivos
     */
    public function getInactive()
    {
        $users = User::where('activo', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.inactive', compact('users'));
    }

    /**
     * GET /admin/users/search (AJAX)
     * Buscar usuarios
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $users = User::where('id', 'like', "%$query%")
            ->orWhere('nombres', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->limit(10)
            ->get(['id', 'nombres', 'apa', 'email', 'perfil_id', 'activo']);

        return response()->json(['results' => $users]);
    }
}
