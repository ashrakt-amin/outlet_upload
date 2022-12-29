<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    /**
     * store validations
     */
    private function storeRequest()
    {
        return [
            'name' => 'required',
            'category_id' => 'required',
            'item_unit_id' => 'nullable',
            'unit_parts_count' => 'nullable',
            'sale_price' => 'nullable',
            'description' => 'required',
            'item_code' => 'unique:items,item_code',
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
            'name.required' => 'يجب ادخال اسم',
            'category_id.required' => 'التصنيف مطلوب',
            'item_unit_id' => 'مطلوب',
            'unit_parts_count' => 'مطلوب',
            'sale_price' => 'مطلوب',
            'item_code' => 'موجود من قبل',
        ];
    }
}
