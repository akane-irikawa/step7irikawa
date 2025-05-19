<?php

return [
    'required' => ':attribute は必須です。',
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'max' => [
        'string' => ':attribute は :max 文字以内で入力してください。',
    ],
    'email' => ':attribute は有効なメールアドレスでなければなりません。',
    'unique' => ':attribute はすでに登録されています。',
    'confirmed' => ':attribute と確認が一致しません。',
    // 他のルールの追加...
    
    'custom' => [
        'product_name' => [
            'required' => '商品名を入力してください。',
        ],
        'company_id' => [
            'required' => 'メーカー名は必須です。',
        ],
        'price' => [
            'required' => '価格は必須です。',
        ],
        'stock' => [
            'required' => '在庫数は必須です。',
        ],
        'comment' => [
            'required' => 'コメントは必須です。',
        ],
        'img_path' => [
            'required' => '商品画像は必須です。',
        ],
    ],

    'attributes' => [
        'product_name' => '商品名',
        'company_id' => 'メーカー名',
        'price' => '価格',
        'stock' => '在庫数',
        'comment' => 'コメント',
        'img_path' => '商品画像',
    ],
];