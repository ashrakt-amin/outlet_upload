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
            'img'         => 'nullable',
            'email'       => 'nullable|unique:users,email',
            'national_id' => 'nullable|unique:traders,national_id|regex:/^[0-9]{14}$/',
            'password'    => 'required|min:8|confirmed',
            'password_confirmation' => 'required|same:password',
        ];
    }

    /**
     * update validations
     */
    private function updateRequest()
    {
        return [
            'f_name'      => 'required',
            'l_name'      => 'required',
            'code'        => 'nullable',
            'phone'       => 'required|unique:traders,phone|regex:/^(01)[0-9]{9}$/',
            'img'         => 'nullable',
            'email'       => 'nullable|unique:users,email',
            'national_id' => 'nullable|unique:traders,national_id|regex:/^[0-9]{14}$/',
            'password'    => 'required|min:8|confirmed',
            'password_confirmation' => 'required|same:password',
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
            'f_name.required'      => 'الاسم الاول مطلوب',
            'l_name.required'      => 'الاسم الاخير مطلوب',
            'phone.required'       => 'الهاتف مطلوب',
            'phone.unique'         => 'الهاتف مسجل من قبل',
            'phone.regex'          => 'صيغة الهاتف غير صحيحة',
            'national_id.required' => 'الرقم القومي مطلوب',
            'national_id.unique'   => 'الرقم القومي مسجل من قبل',
            'national_id.regex'    => 'صيغة الرقم القومي غير صحيحة',
            'password.required'    => 'الرقم السري مطلوب',
            'password.min'         => 'الرقم السري يجب الا يقل 8 احرف',
            'password.regex'       => 'الرقم السري يجب ان يحتوي على حروف و ارقام و رموز',
            'password_confirmation.required' => 'يرجى تأكيدالرقم السري',
        ];
    }

}
