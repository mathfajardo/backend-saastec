<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use HttpResponses;


    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error('erro ao adicionar', 422, $validator->errors());
        }

        $created = User::create($validator->validated());

        if ($created) {
            return $this->response('Usuario adicionado com sucesso', 200, $created);
        }
        return $this->response('Usuario não adicionado', 400);
    }
}
