<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'id'               => $this->id,
            'notes'            => $this->notes,
            'type'             => $this->type,
            'status'           => $this->status,
            'status_name'      => $this->status_name,
            'type_name'        => $this->type_name,
            'rejection_reason' => $this->rejection_reason ?? '',
            'created_at'       => Carbon::parse($this->created_at)->format('Y-m-d h:i:s'),
            'employee'         => $this->whenLoaded('employee'),
            'late_and_leave'   => $this->whenLoaded('lateAndLeave'),
            'loan'             => $this->whenLoaded('loan'),
            'errand'           => $this->whenLoaded('errand'),
            'vacation'         => $this->whenLoaded('vacation'),
            'reduction'         => $this->whenLoaded('reduction'),
        ];
    }
}
