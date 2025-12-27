<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
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
            'cliente_id' => 'nullable',
            'nome' => 'required',
            'numero' => 'required',
            'status' => 'required',
            'observacoes' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->error('Erro na validação', 422, $validator->errors());
        }

        $criado = Lead::create($validator->validated());

        if ($criado) {
            return $this->response("Lead adicionado com sucesso", 200, $criado);
        }

        return $this->error("Não foi possível adicionar", 400);
    }

    public function show(string $id) 
    {
        return new LeadResource(Lead::where('id', $id)->first());
    }

    public function update(Request $request, string $id) 
    {
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'nullable',
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
            'cliente_id' => $validated['cliente_id'] ?? null,
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
}
