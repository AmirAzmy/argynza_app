<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
        $k = count($this->segments());
        $endPoint = $this->segment($k);
        switch ($endPoint) {
            case 'login':
                return $this->loginValidations();
            case 'edit-admin':
                return $this->editAdmin();
            default:
                return [];
        }

    }

    private function loginValidations(): array
    {
        return [
            'phone'    => 'required|min:11|max:11',
            'password' => 'required|min:8|max:25'
        ];
    }

    private function editAdmin(): array
    {
        return [
            'phone'    => 'required',
            'password' => 'min:8|max:25|confirmed',
        ];
    }
}
