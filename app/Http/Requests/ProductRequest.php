<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProductRequest represents the request for managing product information.
 *
 * @package App\Http\Requests
 */
class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'title' => ['required', 'max:2000'],
            'image' => ['nullable', 'image'],
            'price' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
        ];
    }
}
