<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date'=>$this->date,
            'jenisTransaksi'=>$this->jenisTransaksi,
            'nominal'=>$this->nominal,
            'deskripsi'=>$this->deskripsi,
        ];
    }
}
