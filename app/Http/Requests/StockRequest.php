<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
{
    /**
     * store validations
     */
    private function storeRequest()
    {
        return [
            // 'initial_page' => 'required_with:end_page|integer|min:1|digits_between: 1,5',
            // 'end_page' => 'required_with:initial_page|integer|greater_than_field:initial_page|digits_between:1,5',
            'item_id'       => 'required',
            'stock'         => 'required|integer|min:1|digits_between: 1,5',
            'sale_price'    => 'required',
            'unit_id'       => 'required|integer',
            'barcode'       => 'nullable|integer',
            'spare_barcode' => 'nullable|integer',
            'stock_code'    => 'unique:stocks,stock_code',
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
            'item_id.required'          => 'يجب استخدام منتج',
            'stock.required'            => 'الرصيد مطلوب',
            'unit_id.required'        => 'مطلوب',
            'unit_id.integer'         => 'ارقام فقط',
            'unit_parts_count.required' => 'مطلوب',
            'sale_price.required'       => 'مطلوب',
            'spare_barcode.integer'     => 'ارقام فقط',
            'barcode.integer'           => 'ارقام فقط',
            'stock_code'                => 'موجود من قبل',
        ];
    }
}
