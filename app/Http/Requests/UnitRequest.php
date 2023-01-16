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
            'category_id' => 'required|integer',
            'img'         => 'required',
            'site_id'     => 'nullable|integer',
            'status'      => 'nullable',
            'package_id'  => 'nullable|integer',
            'finance_id'  => 'nullable|integer',
            'space'       => 'nullable|integer',
            'price_m'     => 'nullable|integer',
            'unit_value'  => 'nullable|integer',
            'rents_count' => 'nullable|integer',
            'rent_value'  => 'nullable|integer',
            'discount'    => 'nullable|integer',
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
            'category_id' => 'required|integer',
            'site_id'     => 'nullable|integer',
            'status'      => 'nullable',
            'package_id'  => 'nullable|integer',
            'finance_id'  => 'nullable|integer',
            'space'       => 'nullable|integer',
            'price_m'     => 'nullable|integer',
            'unit_value'  => 'nullable|integer',
            'rents_count' => 'nullable|integer',
            'rent_value'  => 'nullable|integer',
            'discount'    => 'nullable|integer',
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
            'trader_id.required' => 'التاجر مطلوب',
            'description.required' => 'الوصف مطلوب',
        ];
    }
}
