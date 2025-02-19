<?php

// app/Http/Resources/JobResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'title' => $this->title,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'category' => $this->category,
            'location' => $this->location,
            'work_arrangement' => $this->work_arrangement,
            'job_type' => $this->job_type,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'responsibilities' => $this->responsibilities,
            'benefits' => $this->benefits,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
