<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest
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
            case "PUT":
                return $this->createValidations();
            default:
                return [];
        }

    }

    public function createValidations(): array
    {
        return [
            'name_ar'    => 'required',
            'name_en'    => 'required',
            'redius'     => 'required|gt:0',
            'lat'        => 'required|gt:0',
            'lng'        => 'required|gt:0',
            'project_id' => 'required|exists:projects,id'
        ];
    }
    public function attributes()
    {
        return [
            'redius'     => 'مساحه المسموح بالتحرك',
            'lat'        => 'خط العرض',
            'lng'        => 'خط الطول',
        ];
    }
}
