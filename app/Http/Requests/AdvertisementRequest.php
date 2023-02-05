<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementRequest extends FormRequest
{
    /**
     * store validations
     */
    private function storeRequest()
    {
        return [
            'img'     => 'required',
            'link'    => 'nullable',
            'unit_id' => 'nullable|integer',
            'renew'   => 'nullable|integer',
            'grade'   => 'nullable|integer',
            'project_id'  => 'required',
            'advertisement_expire'  => 'nullable',
        ];
    }

    /**
     * update validations
     */
    private function updateRequest()
    {
        return [
            'img'     => 'nullable',
            'link'    => 'nullable',
            'unit_id' => 'nullable|integer',
            'renew'   => 'nullable|integer',
            'grade'   => 'nullable|integer',
            'project_id'  => 'required',
            'advertisement_expire'  => 'nullable',
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
            'name.required'       => 'يجب ادخال اسم',
            'img.required'        => 'يجب اختيار صورة',
            'unit_id.required'    => 'يجب اختيار وحدة',
            'project_id.required' => 'يجب اختيار مشروع',
        ];
    }
}
