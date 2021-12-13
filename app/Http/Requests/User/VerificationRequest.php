<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class VerificationRequest extends FormRequest
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
            case 'confirm':
                return $this->confirmValidations();
            case 'resend':
                return $this->resendValidations();
            case 'check':
                return $this->checkCodeValidations();
            default:
                return [];
        }
    }

    private function confirmValidations()
    {
        return [
            'phone' => 'required|min:11|max:11|exists:users,phone',
            'code'  => 'required|max:5'
        ];
    }


    private function resendValidations()
    {
        return [
            'phone' => 'required|min:11|max:11|exists:users,phone',
        ];
    }

    private function checkCodeValidations()
    {
        return [
            'phone' => 'required|min:11|max:11|exists:users,phone',
            'code'  => 'required|max:5'
        ];
    }
}
