<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'id'      => $this->id,
            'name'    => $this['name_'.app()->getLocale()],
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'image'   => $this->image,
            'sites'   => $this->formatSites($this->whenLoaded('sites')),
        ];
    }

    private function formatSites($sites)
    {
        $data = [];
        foreach ($sites as $site) {
            $data[] = [
                'id'      => $site['id'], 'redius' => $site['redius'],
                'name'    => $site['name_'.app()->getLocale()],
                'name_ar' => $site['name_ar'], 'name_en' => $site['name_en'],
                'lat'     => $site['lat'], 'lng' => $site['lng']
            ];
        }
        return $data;
    }
}
