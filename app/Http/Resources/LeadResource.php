<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "cliente_id" => $this->cliente_id,
            "nome" => $this->nome,
            "numero" => $this->numero,
            "status" => $this->status,
            "observacoes" => $this->observacoes,
            "created_at" => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            "updated_at" => Carbon::parse($this->updated_at)->format('d/m H:i')//Carbon::parse($this->updated_at)->format('d/m/Y H:i:s')
        ];
    }
}
