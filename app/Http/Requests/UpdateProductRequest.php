<?php

namespace App\Http\Requests;

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
            'company_id' => 'required|integer',
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'メーカー名を選択してください。',
            'company_id.integer' => 'メーカーIDは数値で指定してください。',
            'product_name.required' => '商品名を入力してください。',
            'product_name.max' => '商品名は255文字以内で入力してください。',
            'price.required' => '価格を入力してください。',
            'price.numeric' => '価格は数値で入力してください。',
            'stock.required' => '在庫数を入力してください。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'img_path.image' => '画像ファイルを選択してください。',
        ];
    }
}