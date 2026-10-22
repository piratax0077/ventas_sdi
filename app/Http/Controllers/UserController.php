<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("usuarios.usuarios_index", ["usuarios" => User::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("usuarios.usuarios_create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        $usuario = new User($data);
        $usuario->password = Hash::make($data['password']);
        $usuario->saveOrFail();
        $usuario->assignRole('Vendedor');
        return redirect()->route("usuarios.index")->with("mensaje", "Usuario guardado");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return redirect()->route('usuarios.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view("usuarios.usuarios_edit", ["usuario" => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8',
        ]);
        $user->fill(collect($data)->except('password')->all());
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->saveOrFail();
        return redirect()->route("usuarios.index")->with("mensaje", "Usuario actualizado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route("usuarios.index")->with("mensaje", "Usuario eliminado");
    }

    /**
     * Display the profile edit form for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function perfil()
    {
        return view("perfil");
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatePerfil(Request $request)
    {
        $user = auth()->user();

        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'required_with:password|nullable|current_password:web',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Actualizar nombre y email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Si se proporciona una nueva contraseña
        if (!empty($validated['password'])) {
            // Verificar contraseña actual si se proporciona
            if (!empty($validated['current_password'])) {
                if (!Hash::check($validated['current_password'], $user->password)) {
                    return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta']);
                }
            }
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('perfil')->with('mensaje', 'Perfil actualizado correctamente');
    }
}
