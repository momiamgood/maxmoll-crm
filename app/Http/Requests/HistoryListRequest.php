<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class HistoryListRequest extends PaginatableRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'dateFrom' => ['sometimes', 'date', 'before_or_equal:dateTo'],
            'dateTo' => ['sometimes', 'date', 'after_or_equal:dateFrom'],
            'warehouseId' => ['sometimes', 'integer', 'exists:warehouses,id'],
            'productId' => ['sometimes', 'integer', 'exists:products,id'],
        ];
    }
}
