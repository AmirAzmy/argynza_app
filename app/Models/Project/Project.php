<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

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
