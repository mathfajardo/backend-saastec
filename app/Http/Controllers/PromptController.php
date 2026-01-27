<?php

namespace App\Http\Controllers;

use App\Http\Resources\PromptResource;
use App\Models\Prompt;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromptController extends Controller
{
     use HttpResponses;

    public function index(Request $request)
    {
        return (new Prompt())->filter($request);
    }

    public function store(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'mensagem' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->error("Erro na validação", 422, $validator->errors());
        }

        
        // buscando a empresa id
        $empresa_id = auth()->user()->empresa_id;

        $data = $validator->validate();
        $data['empresa_id'] = $empresa_id;
        $criado = Prompt::create($data);

        if ($criado) {
            return $this->response("Mensagem adicionada com sucesso", 200, $criado);
        }

        return $this->error("Não foi possível adicionar", 400);
    }

    public function show(string $id) 
    {   
        // buscando a empresa id
        $empresa_id = auth()->user()->empresa_id;
        return new PromptResource(Prompt::where('id', $id)->where('empresa_id', $empresa_id)->first());
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'mensagem' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error("Erro na validação", 422, $validator->errors());
        }

        $validated = $validator->validate();

        $atualiza = Prompt::find($id)->update([
            'mensagem' => $validated['mensagem'],
        ]);

        if ($atualiza) {
            return $this->response("Prompt atualizado com sucesso", 200, $request->all());
        }

        return $this->error("Não foi possível atualizar", 400);
    }
}
