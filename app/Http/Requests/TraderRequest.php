<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TraderRequest extends FormRequest
{
    /**
     * store validations
     */
    private function storeRequest()
    {
        return [
            'f_name'      => 'required',
            'l_name'      => 'required',
            'phone'       => 'required|unique:traders,phone|regex:/^(01)[0-9]{9}$/',
            'code'        => 'unique:traders,code',
            'national_id' => 'required|unique:traders,national_id|regex:/^[0-9]{14}$/',
        ];
    }

    /**
     * update validations
     */
    private function updateRequest()
    {
        return [
            'name' => 'nullable',
        ];
    }

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
        return request()->method() == 'PUT' ? $this->updateRequest() : $this->storeRequest();
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'f_name.required'     => 'الاسم الاول مطلوب',
            'l_name.required'     => 'الاسم الاخير مطلوب',
            'phone.required'     => 'الهاتف مطلوب',
            'phone.unique'       => 'الهاتف مسجل من قبل',
            'phone.regex'        => 'صيغة الهاتف غير صحيحة',
            'code.unique'        => 'الكود مسجل من قبل',
            'national_id.required' => 'الرقم القومي مطلوب',
            'national_id.unique' => 'الرقم القومي مسجل من قبل',
            'national_id.regex'  => 'صيغة الرقم القومي غير صحيحة',
        ];
    }

}
