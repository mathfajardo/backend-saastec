<?php

namespace App\Models;

use App\Filters\PromptFilter;
use App\Http\Resources\PromptResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Prompt extends Model
{
    /** @use HasFactory<\Database\Factories\PromptFactory> */
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'mensagem'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function filter(Request $request) {
        $queryFilter = (new PromptFilter)->filter($request);

        $empresa_id = auth()->user()->empresa_id;

        if (empty($queryFilter)) {
            return PromptResource::collection(Prompt::where('empresa_id', $empresa_id)->get());
        }

        $data = Prompt::where('empresa_id', $empresa_id);

        if (!empty($queryFilter['whereIn'])) {
            foreach ($queryFilter['whereIn'] as $value) {
                $data->whereIn($value[0], $value[1]);
            }
        }

        $resource = $data->where($queryFilter['where'])->get();

        return PromptResource::collection($resource);
    }
}
