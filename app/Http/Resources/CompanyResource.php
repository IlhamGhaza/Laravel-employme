<?php

// app/Http/Resources/CompanyResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'website' => $this->website,
            'industry' => $this->industry,
            'location' => $this->location,
            'founded_year' => $this->founded_year,
            'logo_path' => $this->logo_path,
            'description' => $this->description,
            'is_deleted' => $this->is_deleted,
            'jobs' => JobResource::collection($this->whenLoaded('jobs')), // Include jobs relationship
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
