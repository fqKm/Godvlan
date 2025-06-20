<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'email'=>$this->email,
            'name'=>$this->name,
            'company'=>$this->company,
            'token'=>$this->whenNotNull($this->token)
        ];
    }


}
