<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'sku'            => 'required|string|max:50|unique:products,sku,' . $this->route('product')->id,
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock'          => 'required|integer|min:0',
            'featured'       => 'boolean',
            'status'         => 'boolean',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }
}
