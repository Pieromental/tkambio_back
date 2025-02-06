<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Utils\Response;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    public function login(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->input('email'))
                ->where('active', 1)
                ->first();

            
            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return response()->json([
                    'code' => 400,
                    'title' => 'Credenciales Incorrectas',
                    'message' => 'Usuario y/o Contraseña Inválidos',
                ], 400);
            }

            $token = JWTAuth::fromUser($user);

            return response()->json(Response::success(200, 'Inicio de sesión exitoso', [
                'usuario' => $user,
                'token' => $token
            ]), 200);

        } catch (GeneralException $e) {
            return response()->json(Response::error(500, $e->getMessage(), __FUNCTION__), 500);
        }
    }
}


