<?php

namespace App\Models;

use App\Filters\EmpresasFilter;
use App\Http\Resources\EmpresaResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Empresa extends Model
{
    /** @use HasFactory<\Database\Factories\EmpresaFactory> */
    use HasFactory;

    protected $fillable = [
        'nome',
        'plano',
        'status',
        'instancia',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function prompt()
    {
        return $this->hasMany(Prompt::class);
    }

    public function filter(Request $request)
    {
        $queryFilter = (new EmpresasFilter)->filter($request);

        $empresa_id = auth()->user()->empresa_id;

        if (empty($queryFilter)) {
            return EmpresaResource::collection(Empresa::where('id', $empresa_id)->get());
        }

        $data = Empresa::where('id', $empresa_id);

        if (! empty($queryFilter['whereIn'])) {
            foreach ($queryFilter['whereIn'] as $value) {
                $data->whereIn($value[0], $value[1]);
            }
        }

        $resource = $data->where($queryFilter['where'])->get();

        return EmpresaResource::collection($resource);
    }
}
