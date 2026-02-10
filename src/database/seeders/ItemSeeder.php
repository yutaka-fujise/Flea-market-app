<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $userId = 1;      // ←必要なら自分のIDに変更
        $categoryId = 1;  // ←仮で1（カテゴリ仕様が分かれば後で振り分け）

        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'images/items/Clock.jpg',
                'condition_id' => 1, // 良好
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'images/items/HDDHardDisk.jpg',
                'condition_id' => 2, // 目立った傷や汚れなし
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'images/items/iLoveIMG+d.jpg',
                'condition_id' => 3, // やや傷や汚れあり
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'image' => 'images/items/Leather+Shoes+Product+Photo.jpg',
                'condition_id' => 4, // 状態が悪い
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'image' => 'images/items/Living+Room+Laptop.jpg',
                'condition_id' => 1, // 良好
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image' => 'images/items/Music+Mic+4632231.jpg',
                'condition_id' => 2, // 目立った傷や汚れなし
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'images/items/Purse+fashion+pocket.jpg',
                'condition_id' => 3, // やや傷や汚れあり
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'image' => 'images/items/Tumbler+souvenir.jpg',
                'condition_id' => 4, // 状態が悪い
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image' => 'images/items/Waitress+with+Coffee+Grinder.jpg',
                'condition_id' => 1, // 良好
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'image' => 'images/items/make.jpg',
                'condition_id' => 2, // 目立った傷や汚れなし
            ],
            // ↓画像の行をここに追加していく
        ];

        foreach ($items as $item) {
            Item::create([
                'name' => $item['name'],
                'description' => $item['description'],
                'brand' => $item['brand'],
                'price' => $item['price'],
                'category_id' => $categoryId,
                'condition_id' => $item['condition_id'],
                'image' => $item['image'],
                'user_id' => $userId,
            ]);
        }
    }
}
