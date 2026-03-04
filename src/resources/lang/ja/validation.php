<?php

return [

  'required' => ':attribute を入力してください。',

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
  ],

  'attributes' => [
    'image' => '商品画像',
    'name' => 'お名前',
    'email' => 'メールアドレス',
    'password' => 'パスワード',
    'comment' => 'コメント',
  ],
];