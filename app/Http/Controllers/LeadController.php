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
            'observacoes' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error('Erro ao adicionar', 422, $validator->errors());
        }

        $criado = Lead::create($validator->validated());

        if ($criado) {
            return $this->response("Lead adicionado com sucesso", 200, $criado);
        }

        return $this->error("Não foi possível adicionar", 400);
    }

    public function show(String $id) 
    {
        return new LeadResource(Lead::where('id', $id)->first());
    }
}
