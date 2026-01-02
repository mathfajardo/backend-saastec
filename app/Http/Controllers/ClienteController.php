<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        return (new Cliente())->filter($request);
    }

    public function store(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'numero' => 'required',
            'plano' => 'required',
            'observacoes' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->error("Erro na validação", 422, $validator->errors());
        }

        $criado = Cliente::create($validator->validate());

        if ($criado) {
            return $this->response("Cliente adicionado com sucesso", 200, $criado);
        }

        return $this->error("Não foi possível adicionar", 400);
    }

    public function show(string $id) 
    {
        return new ClienteResource(Cliente::where('id', $id)->first());
    }
}
