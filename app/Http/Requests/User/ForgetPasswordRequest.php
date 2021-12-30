<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
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
        $k        = count($this->segments());
        $endPoint = $this->segment($k);
        switch ($endPoint) {
            case 'forget':
                return $this->forgetValidation();
            case 'reset':
                return $this->resetValidation();
            case 'resend':
                return $this->resend();
            default:
                return [];
        }
    }

    private function forgetValidation()
    {
        return [
            'phone' => 'required',
        ];
    }

    private function resetValidation()
    {
        return [
            'phone'    => 'required|exists:users,phone',
            'password' => 'required|min:8|max:25|confirmed',
            'code'     => 'required|exists:password_resets,token'
        ];
    }

    private function resend()
    {
        return [
            'phone' => 'required|exists:users,phone',
        ];
    }

}
