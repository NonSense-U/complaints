<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'phone'       => $this->phone,
            'NID'       => $this->NID,
            'is_verified' => (bool) $this->is_verified,
            'role_id' => $this->role_id,
            'gov_id' => $this->gov_id,
        ];
    }
}
