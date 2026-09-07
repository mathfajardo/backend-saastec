<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->response('Autorizado', 200, [
                'token' => $request->user()->createToken('user', ['*'], now()->addHours(8))->plainTextToken,
            ]);
        }

        return $this->response('Não autorizado', 403);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response('token removido', 200);
    }

    public function verifica_token(Request $request)
    {
        return $this->response('Token válido', 200, [
            'user' => $request->user(),
        ]);
    }
}
