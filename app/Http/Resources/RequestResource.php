<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
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
            'id'             => $this->id,
            'notes'          => $this->notes,
            'type'           => $this->type,
            'status'         => $this->status,
            'created_at'     => $this->created_at,
            'employee'       => $this->whenLoaded('employee'),
            'late_and_leave' => $this->whenLoaded('lateAndLeave'),
            'loan'           => $this->whenLoaded('loan'),
            'errand'         => $this->whenLoaded('errand'),
            'vacation'       => $this->whenLoaded('vacation'),
        ];
    }
}
