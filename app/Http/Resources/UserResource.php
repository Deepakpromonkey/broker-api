<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'designation' => $this->designation,
            'is_owner' => $this->is_owner,
            'status' => $this->status,

            'role' => $this->roles->pluck('name')->first(),

            'company' => new CompanyResource($this->whenLoaded('company')),
        ];
    }
}
