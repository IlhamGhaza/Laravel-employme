<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'job' => [
                'id' => $this->job->id,
                'title' => $this->job->title,
                'company' => [
                    'id' => $this->job->company->id,
                    'name' => $this->job->company->name,
                    'logo_path' => $this->job->company->logo_path
                        ? asset('storage/' . $this->job->company->logo_path)
                        : null,
                ],
                'location' => $this->job->location,
                'job_type' => $this->job->job_type,
                'is_active' => (bool) $this->job->is_active,
            ],
            'status' => $this->status,
            'cover_letter' => $this->cover_letter,
            'applied_at' => $this->created_at->toISOString(),
            'applied_at_human' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
