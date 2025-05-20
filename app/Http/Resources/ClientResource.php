<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'id_number' => $this->id_number,
            'email' => $this->email,
            'phone' => $this->phone,
            'department' => $this->department,
            'city' => $this->city,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'is_winner' => $this->is_winner,
        ];
    }
}
