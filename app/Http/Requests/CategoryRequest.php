<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:10',
                Rule::unique('categories', 'name')->ignore($this->category),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'カテゴリーを入力してください',
            'name.string' => 'カテゴリーを文字列で入力してください',
            'name.max' => 'カテゴリーを10文字以下で入力してください',
            'name.unique' => 'カテゴリーが既に存在しています',
        ];
    }
}
