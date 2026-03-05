<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $userId = 1; // 必要なら変更

        // 画像の元データ（Gitに入れる場所）
        $sourceDir = database_path('seeders/sample_images/items');

        // もしフォルダが無い or 画像が無い場合でも落ちないようにする
        $files = File::exists($sourceDir) ? File::files($sourceDir) : [];

        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'Clock.jpg',
                'condition_id' => 1,
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'HDDHardDisk.jpg',
                'condition_id' => 2,
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'iLoveIMG+d.jpg',
                'condition_id' => 3,
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'image' => 'Leather+Shoes+Product+Photo.jpg',
                'condition_id' => 4,
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'image' => 'Living+Room+Laptop.jpg',
                'condition_id' => 1,
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image' => 'Music+Mic+4632231.jpg',
                'condition_id' => 2,
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'Purse+fashion+pocket.jpg',
                'condition_id' => 3,
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'image' => 'Tumbler+souvenir.jpg',
                'condition_id' => 4,
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image' => 'Waitress+with+Coffee+Grinder.jpg',
                'condition_id' => 1,
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'image' => 'make.jpg',
                'condition_id' => 2,
            ],
        ];

        foreach ($items as $item) {

            // 画像ファイルが存在する場合だけ storage にコピー
            $srcPath = $sourceDir . DIRECTORY_SEPARATOR . $item['image'];
            if (File::exists($srcPath)) {
                Storage::disk('public')->put('items/' . $item['image'], File::get($srcPath));
            }

            // DBには "items/ファイル名" を保存（asset('storage/'.$item->image) と一致）
            Item::create([
                'name' => $item['name'],
                'description' => $item['description'],
                'brand' => $item['brand'],
                'price' => $item['price'],
                'condition_id' => $item['condition_id'],
                'image' => 'items/' . $item['image'],
                'user_id' => $userId,
            ]);
        }
    }
}