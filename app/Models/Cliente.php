<?php

namespace App\Models;

use App\Filters\ClientesFilter;
use App\Http\Resources\ClienteResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'lead_id',
        'nome',
        'numero',
        'plano',
        'mensalidade',
        'observacoes'
    ];


    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function filter(Request $request) {
        $queryFilter = (new ClientesFilter)->filter($request);

        $empresa_id = auth()->user()->empresa_id;

        if (empty($queryFilter)) {
            return ClienteResource::collection(Cliente::where('empresa_id', $empresa_id)->orderBy('nome', 'ASC')->get());
        }

        $data = Cliente::where('empresa_id', $empresa_id);

        if (!empty($queryFilter['whereIn'])) {
            foreach ($queryFilter['whereIn'] as $value) {
                $data->whereIn($value[0], $value[1]);
            }
        }

        $resource = $data->where($queryFilter['where'])->get();

        return ClienteResource::collection($resource);
    }
}
