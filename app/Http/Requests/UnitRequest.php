<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
{
    /**
     * store validations
     */
    private function storeRequest()
    {
        return [
            // 'initial_page' => 'required_with:end_page|integer|min:1|digits_between: 1,5',
            // 'end_page' => 'required_with:initial_page|integer|greater_than_field:initial_page|digits_between:1,5',
            'name'        => 'required',
            'level_id'    => 'required|integer',
            'trader_id'   => 'required|integer',
            'img'         => 'required',
            'category_id' => 'required',
            'package_id'  => 'nullable|integer',
            'famous'      => 'nullable',
            'online'      => 'nullable',
            'offers'      => 'nullable',
            'description' => 'required',
            'created_date' => 'nullable|integer',
            'canceled_date' => 'nullable|integer',
        ];
    }

    /**
     * update validations
     */
    private function updateRequest()
    {
        return [
            'name'        => 'required',
            'level_id'    => 'required|integer',
            'trader_id'   => 'required|integer',
            'category_id' => 'required',
            'package_id'  => 'nullable|integer',
            'famous'      => 'nullable',
            'online'      => 'nullable',
            'offers'      => 'nullable',
            'description' => 'required',
            'created_date' => 'nullable|integer',
            'canceled_date' => 'nullable|integer',
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
            'name.required'      => 'الاسم مطلوب',
            'level_id.required'  => 'الطابق مطلوب',
            'img.required'       => 'الصور مطلوب',
            'trader_id.required' => 'التاجر مطلوب',
            'description'        => 'وصف الوحدة مطلوب',
            'descrimgiption'     => ' الصور مطلوبة',
            'description.required' => 'الوصف مطلوب',
        ];
    }
}
