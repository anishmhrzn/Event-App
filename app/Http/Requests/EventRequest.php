<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
        return [
            'title' => ['required'],
            'description' => ['required'],
            'dateRange.startDate' => ['required', 'date'],
            'dateRange.endDate'=> ['required', 'date', 'after_or_equal:dateRange.startDate']
        ];
    }

    public function messages()
    {
        return [
            'dateRange.startDate.required' => 'Event Start Date is Required',
            'dateRange.endDate.required' => 'Event End Date is Required',
            'dateRange.startDate.date' => 'Event Start Date must be a date',
            'dateRange.endDate.date' => 'Event End Date must be a date',
            'dateRange.endDate.after_or_equal' => 'Event End Date must be a date after Start date',
        ];
    }
}
