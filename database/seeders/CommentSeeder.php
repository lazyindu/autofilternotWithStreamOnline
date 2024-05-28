<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "name" => "Indu",
            "email" => "indu.ia@gmail.com",
            "phone" => "9876543210",
            "dp" => "https://picsum.photos/904/883",
            "comment" => "Lot's of Love ♥",
            "is_verified_user" => true,
            "has_login" => true,
        ];
        Comment::create($data);
    }
}
