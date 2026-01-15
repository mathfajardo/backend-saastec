<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    use HttpResponses;


    public function index(Request $request) 
    {
        return (new Lead())->filter($request);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'numero' => 'required',
            'status' => 'required',
            'observacoes' => 'nullable'
        ]);

        

        if ($validator->fails()) {
            return $this->error('Erro na validação', 422, $validator->errors());
        }

        // buscando a empresa id
        $empresa_id = auth()->user()->empresa_id;

        $data = $validator->validate();
        $data['empresa_id'] = $empresa_id;
        $criado = Lead::create($data);

        if ($criado) {
            return $this->response("Lead adicionado com sucesso", 200, $criado);
        }

        return $this->error("Não foi possível adicionar", 400);
    }

    public function show(string $id) 
    {   
        $empresa_id = auth()->user()->empresa_id;
        return new LeadResource(Lead::where('id', $id)->where('empresa_id', $empresa_id)->first());
    }

    public function update(Request $request, string $id) 
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'numero' => 'required',
            'status' => 'required',
            'observacoes' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->error('Erro na validação', 422, $validator->errors());
        }

        $validated = $validator->validated();

        $atualiza = Lead::find($id)->update([
            'nome' => $validated['nome'],
            'numero' => $validated['numero'],
            'status' => $validated['status'],
            'observacoes' => $validated['observacoes']
        ]);

        if ($atualiza) {
            return $this->response("Lead atualizado com sucesso", 200, $request->all());
        }

        return $this->error("Não foi possível atualizar", 400);
    }

    public function destroy(Lead $lead) {
        $deleta = $lead->delete();

        if ($deleta) {
            return $this->response("Deletado com sucesso", 200);
        }

        return $this->response("Não foi possível deletar", 400);
    }

    public function leadsMes() {

        $empresa_id = auth()->user()->empresa_id;

        // pegando o mes atual
        $mes = now()->month;
        $ano = now()->year;

        // query
        $total = Lead::whereMonth('created_at', $mes)->whereYear('created_at', $ano)->where('empresa_id', $empresa_id)->count();

        return $this->response("Query leads no mes realizada com sucesso", 200, ['total' => $total]);
    }

    public function leadsMesTodos() {

        $empresa_id = auth()->user()->empresa_id;

        
        
        $ano = now()->year;

        $meses = [];

        for ($i = 1; $i < 13; $i++) {
            $total = Lead::whereMonth('created_at', $i)->whereYear('created_at', $ano)->where('empresa_id', $empresa_id)->count();

            $meses[$i] = $total;
        }

        return $this->response("Query leads no mes realizada com sucesso", 200, [$meses]);
    }

    public function disparoCampanhaLeads(Request $request)
    {   
        // array para armazenar dados
        $dados_enviar = [];

        // variavel para armazenar a mensagem
        $mensagem = $request->input('mensagem');

        // variavel para armazenar os leads
        $leads = $request->input('leads');

        // loop para percorrer todos os leads
        foreach($leads as $lead) {
            $dados_enviar[] = [
                'mensagem' => $mensagem,
                'nome' => $lead['nome'],
                'numero' => $lead['numero']
            ];
        }

        // envio para o n8n
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post('https://n8n.fajatech.com.br/webhook/4c3f68ed-56b6-49fa-a5af-6da47957a72b', $dados_enviar);
        
        return $this->response("Deu certo", 200);

    }
}
