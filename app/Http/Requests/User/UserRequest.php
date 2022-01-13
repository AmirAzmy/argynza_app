<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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
        $count = count($this->segments());
        $endPoint = $this->segment($count);
        if ($endPoint == 'change-password') {
            return $this->changePasswordValidations();
        }
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
            'name'       => "required",
            'phone'      => "required|min:11|max:11|unique:users,phone",
            'password'   => "required|confirmed|min:8|max:22",
            'type'       => "required|in:1,2,3,4,5",
            'project_id' => "required|exists:projects,id",
            "image"      => "nullable",
            "active"     => "required|in:0,1",
            "job_title"  => "nullable"
        ];
    }

    public function updateValidations()
    {
        return [
            'name'       => "min:1",
            'phone'      => "min:11|max:11|unique:users,phone,".$this->id,
            'type'       => "in:1,2,3,4,5",
            'project_id' => "exists:projects,id",
            "image"      => "nullable",
            "active"     => "required|in:0,1",
            "job_title"  => "nullable"
        ];
    }

    private function changePasswordValidations()
    {
        $validation = [
            "password" => "required|min:8|max:22|confirmed"
        ];
        if (!in_array(Auth::user()->type, [1, 2])) {
            $validation['old_password'] = 'required|min:8|max:22';
        }
        return $validation;
    }

    public function attributes()
    {
        return [
            'project_id' => 'مشروع'
        ];
    }
}
