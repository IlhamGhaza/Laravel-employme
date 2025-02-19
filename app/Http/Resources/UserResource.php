<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'role' => $this->role,
            'is_verified' => (bool) $this->is_verified,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];

        // Include profile if loaded
        if ($this->relationLoaded('userProfile')) {
            $result['profile'] = new UserProfileResource($this->userProfile);
        }

        return $result;
    }
}
