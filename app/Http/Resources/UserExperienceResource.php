<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserExperienceResource extends JsonResource
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
            'company_name' => $this->company_name,
            'title' => $this->title,
            'location' => $this->location,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date ? $this->end_date->format('Y-m-d') : null,
            'is_current' => (bool) $this->is_current,
            'description' => $this->description,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'duration' => $this->calculateDuration(),
        ];
    }

    /**
     * Calculate the duration of the experience
     *
     * @return string
     */
    protected function calculateDuration()
    {
        $start = $this->start_date;
        $end = $this->is_current ? now() : $this->end_date;

        $years = $end->diffInYears($start);
        $months = $end->copy()->subYears($years)->diffInMonths($start);

        $result = [];
        if ($years > 0) {
            $result[] = $years . ' ' . ($years == 1 ? 'year' : 'years');
        }

        if ($months > 0 || count($result) == 0) {
            $result[] = $months . ' ' . ($months == 1 ? 'month' : 'months');
        }

        return implode(', ', $result);
    }
}
