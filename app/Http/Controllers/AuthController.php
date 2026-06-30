<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Função para registrar novos usuários (Clientes)
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telefone' => 'required|string|max:255',
            'password' => 'required|string|min:3',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer', // Por padrão, todo registro é cliente
        ]);

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ], 201);
    }

    // Função para entrar e receber o Token
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Dados de acesso inválidos'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function listAllUsers()
    {
        return \App\Models\User::all();
    }

    // Método para alterar a função (role) de um utilizador
    public function updateRole(Request $request, $id)
    {
        // 1. Validar se a role enviada é permitida
        $validated = $request->validate([
            'role' => 'required|string|in:admin,customer'
        ]);

        // 2. Encontrar o utilizador
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilizador não encontrado'], 404);
        }

        // 3. Impedir que o admin logado altere a sua própria role (opcional, por segurança)
        if (Auth::id() == $user->id) {
            return response()->json(['message' => 'Não pode alterar a sua própria função'], 403);
        }

        // 4. Atualizar e salvar
        $user->role = $validated['role'];
        $user->save();

        return response()->json([
            'message' => 'Função atualizada com sucesso',
            'user' => $user
        ]);
    }

}
