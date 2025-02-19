<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'headline' => $this->headline,
            'phone' => $this->phone,
            'location' => $this->location,
            'about_me' => $this->about_me,
            'skills' => $this->skills,
            'cv_url' => $this->cv_path
                ? asset('storage/' . $this->cv_path)
                : null,
            'social_links' => [
                'linkedin' => $this->linkedin_url,
                'github' => $this->github_url,
                'website' => $this->website_url,
            ],
            'application_stats' => [
                'applied' => $this->applied,
                'reviewed' => $this->reviewed,
                'interview' => $this->interview,
            ],
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
