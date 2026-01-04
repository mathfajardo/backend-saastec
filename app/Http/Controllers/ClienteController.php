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
            'mensalidade' => 'required',
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

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'numero' => 'required',
            'plano' => 'required',
            'mensalidade' => 'required',
            'observacoes' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->error("Erro na validação", 422, $validator->errors());
        }

        $validated = $validator->validate();

        $atualiza = Cliente::find($id)->update([
            'nome' => $validated['nome'],
            'numero' => $validated['numero'],
            'plano' => $validated['plano'],
            'mensalidade' => $validated['mensalidade'],
            'observacoes' => $validated['observacoes']
        ]);

        if ($atualiza) {
            return $this->response("Cliente atualizado com sucesso", 200, $request->all());
        }

        return $this->error("Não foi possível atualizar", 400);
    }

    public function destroy(Cliente $cliente)
    {
        $deleta = $cliente->delete();

        if ($deleta) {
            return $this->response("Deletado com sucesso", 200);
        }

        return $this->response("Não foi possível deletar", 400);
    }



    public function clientesMes() {

        // pegando o mes atual
        $mes = now()->month;

        // query
        $total = Cliente::whereMonth('created_at', $mes)->count();

        return $this->response("Query clientes no mes realizada com sucesso", 200, ['total' => $total]);
    }


    public function clientesTotal() {

        // query
        $total = Cliente::count();

        return $this->response("Query clientes no total realizada com sucesso", 200, ['total' => $total]);
    }
}
