<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            if( Auth::attempt($request->only('email', 'password')) ) {
                $user = Auth::user();

                $token = $user->createToken($user->email.'-'.now())->toArray();

                return response()->json([
                    'token' => $token['accessToken'],
                    'expires_in' => $token['token']->expires_at->timestamp
                ]);
            }
            else {
                return response()->json(['error' => 'E-mail ou senha incorreta. Verifique os dados informados e tente novamente!'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->tokens()->delete();
        }

        return response()->json('Logout efetuado com sucesso');
    }
}
