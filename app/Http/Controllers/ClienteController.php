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
            'lead_id' => 'nullable',
            'nome' => 'required',
            'numero' => 'required',
            'plano' => 'required',
            'mensalidade' => 'required',
            'observacoes' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->error("Erro na validação", 422, $validator->errors());
        }

        
        // buscando a empresa id
        $empresa_id = auth()->user()->empresa_id;

        $data = $validator->validate();
        $data['empresa_id'] = $empresa_id;
        $criado = Cliente::create($data);

        if ($criado) {
            return $this->response("Cliente adicionado com sucesso", 200, $criado);
        }

        return $this->error("Não foi possível adicionar", 400);
    }

    public function show(string $id) 
    {   
        // buscando a empresa id
        $empresa_id = auth()->user()->empresa_id;
        return new ClienteResource(Cliente::where('id', $id)->where('empresa_id', $empresa_id)->first());
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

        // buscando a empresa id
        $empresa_id = auth()->user()->empresa_id;

        // pegando o mes atual
        $mes = now()->month;

        // query
        $total = Cliente::whereMonth('created_at', $mes)->where('empresa_id', $empresa_id)->count();

        return $this->response("Query clientes no mes realizada com sucesso", 200, ['total' => $total]);
    }


    public function clientesTotal() {

        // buscando a empresa id
        $empresa_id = auth()->user()->empresa_id;

        // query
        $total = Cliente::where('empresa_id', $empresa_id)->count();

        return $this->response("Query clientes no total realizada com sucesso", 200, ['total' => $total]);
    }
}
