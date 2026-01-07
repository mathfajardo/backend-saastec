<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    use HttpResponses;

    public function store(Request $request)
    {
    
        // validator para validar se foi enviado tudo que precisa
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'plano' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error("Erro na validação", 422, $validator->errors());
        }

        $criado = Empresa::create($validator->validate());

        if ($criado) {
            return $this->response("Empresa adicionada com sucesso", 200, $criado);
        }

        return $this->error("Não foi possível adicionar", 400);


    }
}
