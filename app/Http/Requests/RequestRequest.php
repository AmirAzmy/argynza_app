<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestRequest extends FormRequest
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
            "type"    => "required|in:late_and_leave,vacation,errand,loan,reduction",
            'is_late' => 'required_if:type,late_and_leave|in:0,1',
            'from'    => 'required_if:type,late_and_leave,errand|date_format:H:i',
            'to'      => 'required_if:type,late_and_leave,errand|date_format:H:i|after:from',
            'start'   => 'required_if:type,vacation|date_format:Y-m-d',
            'end'     => 'required_if:type,vacation|date_format:Y-m-d|after:start',
            'date'    => 'required_unless:type,vacation|date_format:Y-m-d|after:yesterday',
            'amount'  => 'required_if:type,loan,reduction|gte:1|lte:20000',
            'user_id' => 'required_if:type,reduction|exists:users,id',
            'notes'   => 'required|max:500',
        ];
    }

    public function updateValidations()
    {
        return [
            "status"           => "required|in:pending,approved,rejected",
            "rejection_reason" => "required_if:status,rejected|max:500"
        ];
    }
}
