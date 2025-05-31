<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResources extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'tanggalTransaksi'=>$this->tanggalTransaksi,
            'jenisTransaksi'=>$this->jenisTransaksi,
            'nominal'=>$this->nominal,
            'deskripsi'=>$this->deskripsi,
        ];
    }
}
