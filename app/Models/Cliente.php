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
        'nome',
        'numero',
        'plano',
        'mensalidade',
        'observacoes'
    ];


    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function filter(Request $request) {
        $queryFilter = (new ClientesFilter)->filter($request);

        if (empty($queryFilter)) {
            return ClienteResource::collection(Cliente::orderBy('nome', 'ASC')->all());
        }

        $data = Cliente::query();

        if (!empty($queryFilter['whereIn'])) {
            foreach ($queryFilter['whereIn'] as $value) {
                $data->whereIn($value[0], $value[1]);
            }
        }

        $resource = $data->where($queryFilter['where'])->get();

        return ClienteResource::collection($resource);
    }
}
