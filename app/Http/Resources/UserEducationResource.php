<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserEducationResource extends JsonResource
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
            'institution' => $this->institution,
            'degree' => $this->degree,
            'field_of_study' => $this->field_of_study,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date ? $this->end_date->format('Y-m-d') : null,
            'grade' => $this->grade,
            'description' => $this->description,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'duration' => $this->calculateDuration(),
        ];
    }

    /**
     * Calculate the duration of education
     *
     * @return string
     */
    protected function calculateDuration()
    {
        if (!$this->end_date) {
            return 'Current';
        }

        $start = $this->start_date;
        $end = $this->end_date;

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

