<?php

namespace App\Models;

use App\Filters\LeadsFilter;
use App\Http\Resources\LeadResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;

class Lead extends Model
{
    /** @use HasFactory<\Database\Factories\LeadFactory> */
    use HasFactory;

    //relacionametno
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    protected $fillable = [
        'empresa_id',
        'nome',
        'numero',
        'status',
        'observacoes'
    ];

    public function filter(Request $request) {
        $queryFilter = (new LeadsFilter)->filter($request);

        if (empty($queryFilter)) {
            return LeadResource::collection(Lead::all());
        }

        $data = Lead::query();

        if (!empty($queryFilter['whereIn'])) {
            foreach ($queryFilter['whereIn'] as $value) {
                $data->whereIn($value[0], $value[1]);
            }
        }

        $resource = $data->where($queryFilter['where'])->get();

        return LeadResource::collection($resource);
    }
}
