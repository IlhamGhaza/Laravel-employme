<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'title' => $this->title,
            'company' => [
                'id' => $this->company->id,
                'name' => $this->company->name,
                'logo_path' => $this->company->logo_path
                    ? asset('storage/' . $this->company->logo_path)
                    : null,
                'industry' => $this->company->industry,
                'location' => $this->company->location,
            ],
            'salary' => [
                'min' => $this->salary_min,
                'max' => $this->salary_max,
                'formatted' => $this->formatSalaryRange()
            ],
            'category' => $this->category,
            'location' => $this->location,
            'work_arrangement' => $this->work_arrangement,
            'job_type' => $this->job_type,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'responsibilities' => $this->responsibilities,
            'benefits' => $this->benefits,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'expires_at' => $this->expires_at ? $this->expires_at->toISOString() : null,
            'posted_at_human' => $this->created_at->diffForHumans(),
            'expires_in_days' => $this->expires_at
                ? now()->diffInDays($this->expires_at, false)
                : null,
        ];
    }

    /**
     * Format salary range for display
     *
     * @return string
     */
    protected function formatSalaryRange()
    {
        if ($this->salary_min && $this->salary_max) {
            return '$' . number_format($this->salary_min) . ' - $' . number_format($this->salary_max);
        } elseif ($this->salary_min) {
            return 'From $' . number_format($this->salary_min);
        } elseif ($this->salary_max) {
            return 'Up to $' . number_format($this->salary_max);
        } else {
            return 'Not disclosed';
        }
    }
}
