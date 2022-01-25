<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id'                      => $this->id,
            'notifiable_id'           => $this->notifiable_id,
            'title'                   => $this->data['title_'.app()->getLocale()],
            'body'                    => $this->data['body_'.app()->getLocale()],
            'action_type'             => $this->data['payload']['actionType'],
            'action_id'               => $this->data['payload']['actionId'],
            'image'                   => url($this->data['payload']['image']),
            'read_at'                 => $this->read_at,
            'created_at'              => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'created_at_human_format' => Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}
