<?php

return [

    'required' => ':attribute は必須項目です。',
    'email' => ':attribute は有効なメールアドレス形式で入力してください。',
    'string' => ':attribute は文字列で入力してください。',
    'integer' => ':attribute は整数で入力してください。',
    'numeric' => ':attribute は数値で入力してください。',
    'max' => [
        'string' => ':attribute は :max 文字以内で入力してください。',
    ],
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'confirmed' => ':attribute が一致しません。',
    'image' => ':attribute は画像ファイルを選択してください。',
    'mimes' => ':attribute は :values 形式でアップロードしてください。',
    'unique' => 'この :attribute は既に登録されています。',

    'attributes' => [
        'name' => 'ユーザー名',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'price' => '価格',
        'description' => '商品説明',
        'image' => '商品画像',
    ],

];