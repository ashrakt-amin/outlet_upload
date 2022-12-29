<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTraderRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'f_name'      => 'required',
            'm_name'      => 'required',
            'l_name'      => 'required',
            'phone'       => 'required|unique:traders,phone|regex:/^(01)[0-9]{9}$/',
            'code'        => 'unique:traders,code',
            'national_id' => 'unique:traders,national_id',
            ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'phone.required'     => 'الهاتف مسجل من قبل',
            'phone.unique'       => 'الهاتف مسجل من قبل',
            'code.unique'        => 'الكود مسجل من قبل',
            'phone.regex'        => 'صيغة الهاتف غير صحيحة',
            'national_id.unique' => 'الرقم القومي مسجل من قبل',
        ];
    }
}
