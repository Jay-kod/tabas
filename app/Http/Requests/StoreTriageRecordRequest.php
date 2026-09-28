<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTriageRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_name' => ['required', 'string', 'max:255'],
            'patient_age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'patient_sex' => ['nullable', 'string', 'max:20'],
            'patient_hospital_id' => ['nullable', 'string', 'max:100'],
            'patient_contact' => ['nullable', 'string', 'max:100'],
            'presenting_complaint' => ['required', 'string', 'min:3', 'max:1000'],
            'ward_specialization' => ['nullable', 'string', 'max:100'],
            'resp_rate' => ['required', 'integer', 'min:0', 'max:100'],
            'spo2' => ['required', 'integer', 'min:0', 'max:100'],
            'systolic_bp' => ['required', 'integer', 'min:0', 'max:300'],
            'heart_rate' => ['required', 'integer', 'min:0', 'max:250'],
            'consciousness' => ['required', 'string', 'in:A,V,P,U'],
            'temperature' => ['required', 'numeric', 'min:30', 'max:45'],
        ];
    }
}
