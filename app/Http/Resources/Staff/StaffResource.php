<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->nombre,
            'email' => $this->email,
            'hotels' => $this->hotels,
            'verified' => $this->activo,
            'role' => $this->role
        ];
    }
}
