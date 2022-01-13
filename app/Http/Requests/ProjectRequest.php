<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case "POST":
                return $this->createValidations();
            case "PUT":
                return $this->updateValidations();
            default:
                return [];
        }
    }

    public function createValidations()
    {
        return [
            "name_ar" => "required",
            "name_en" => "required",
            'image'   => 'required|image',
        ];
    }

    public function updateValidations()
    {
        return [
            "name_ar" => "required",
            "name_en" => "required",
            'image'   => 'image'
        ];
    }
}
