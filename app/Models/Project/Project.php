<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name_ar', 'name_en', 'image'];

    public function setImageAttribute($value)
    {
        $this->attributes['image'] = (is_file($value)) ? $value->store('Project') : null;
    }

    public function getImageAttribute($value)
    {
        return ($value) ? url('uploads/'.$value) : url('/No_Image_Available.jpg');
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }
}
