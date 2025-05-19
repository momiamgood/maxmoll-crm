<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'customer' => ['required', 'string'],
            'items' => ['required', 'array'],
            'warehouseId' => ['required', 'integer', 'exists:warehouses,id'],
            'items.*.productId' => ['required', 'exists:products,id'],
            'items.*.count' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'customer' => 'customer name',
            'warehouseId' => 'warehouse id',
            'items' => 'order items',
            'items.*.productId' => 'product',
            'items.*.count' => 'quantity',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'customer.required' => 'The :attribute is required.',
            'warehouseId.required' => 'The :attribute is required.',
            'items.required' => 'You must provide at least one order item.',

            'items.*.productId.required' => 'Product ID is required for each item.',
            'items.*.productId.exists' => 'Product with id - :input does not exist.',

            'items.*.warehouseId.required' => 'Warehouse ID is required for each item.',
            'items.*.warehouseId.exists' => 'Warehouse with id - :input does not exist.',

            'items.*.count.required' => 'Quantity is required for each item.',
            'items.*.count.integer' => 'Quantity must be an integer.',
            'items.*.count.min' => 'Quantity must be at least :min.',
        ];
    }
}
