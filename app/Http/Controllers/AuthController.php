<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->intended('/admin');
            } elseif ($user->isCreator()) {
                return redirect()->intended('/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:creator,customer',
            'birth_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $age = \Carbon\Carbon::parse($value)->age;
                    if ($age < 18) {
                        $fail('Você deve ter pelo menos 18 anos de idade para se cadastrar na plataforma.');
                    }
                }
            ],
        ], [
            'birth_date.required' => 'A data de nascimento é obrigatória.',
            'birth_date.date' => 'Insira uma data de nascimento válida.',
        ]);

        // Generate unique username from name
        $baseUsername = Str::slug($validated['name']);
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'birth_date' => $validated['birth_date'],
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        if ($user->isCreator()) {
            return redirect('/dashboard')->with('success', 'Bem-vindo(a) ao Clube do Pack! Configure seu perfil de criador.');
        }

        return redirect('/')->with('success', 'Bem-vindo(a) ao Clube do Pack!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
