<?php

return [

    'required' => ':attribute を入力してください。',

    'max' => [
        'string' => ':attribute は:max文字以内で入力してください。',
    ],

    'min' => [
        'string' => ':attribute は:min文字以上で入力してください。',
        'numeric' => ':attribute は:min以上で入力してください。',
    ],

    'image' => ':attribute は画像ファイルを選択してください。',

    'mimes' => ':attribute は:values形式のファイルを選択してください。',

    'custom' => [

        'image' => [
            'required' => '商品画像を選択してください',
        ],

        'name' => [
            'required' => 'お名前を入力してください',
        ],

        'email' => [
            'required' => 'メールアドレスを入力してください',
            'email' => 'メールアドレスはメール形式で入力してください',
            'regex' => 'メールアドレスはメール形式で入力してください',
            'unique' => 'このメールアドレスは既に登録されています',
        ],

        'password' => [
            'required' => 'パスワードを入力してください',
            'min' => 'パスワードは8文字以上で入力してください',
            'confirmed' => 'パスワードと一致しません',
        ],

        'comment' => [
            'required' => 'コメントを入力してください',
            'max' => 'コメントは255文字以内で入力してください',
        ],

        'price' => [
            'min' => '価格は1円以上で入力してください',
        ],

    ],

    'attributes' => [
        'image' => '商品画像',
        'name' => 'お名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'comment' => 'コメント',

        'postal_code' => '郵便番号',
        'address' => '住所',
        'building' => '建物名',
        'profile_image' => 'プロフィール画像',

        'brand' => 'ブランド名',
        'description' => '商品の説明',
        'price' => '価格',
        'categories' => 'カテゴリー',
        'condition_id' => '商品の状態',
    ],

];