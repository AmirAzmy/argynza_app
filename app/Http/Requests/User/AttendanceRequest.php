<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            case 'checkin-and-out':
            case 'check-location':
                return $this->checkInAndOut();
            case 'export':
            case 'user-attendances':
                return $this->userIdValidation();
            default:
                return [];
        }
    }

    private function checkInAndOut(): array
    {
        return [
            'lat'     => 'required',
            'lng'     => 'required',
            'site_id' => 'required|exists:sites,id',
        ];
    }

    private function userIdValidation(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
        ];
    }
}
