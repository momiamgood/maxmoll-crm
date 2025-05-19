<?php

namespace App\Http\Requests;

use App\Enums\OrderStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderListRequest extends PaginatableRequest
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
        return array_merge(parent::rules(), [
            'status' => ['sometimes', 'in:' . implode(',', OrderStatusEnum::values())],
            'dateFrom' => ['sometimes', 'date', 'before_or_equal:dateTo'],
            'dateTo' => ['sometimes', 'date', 'after_or_equal:dateFrom'],
            'id' => ['sometimes', 'integer', 'exists:orders,id']
        ]);
    }
}
