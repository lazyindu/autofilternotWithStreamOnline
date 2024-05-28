<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "name" =>"Manager",
            "email" => "lazydeveloperr@gmail.com",
            "phone" => "9876543210",
            "type" => "admin",
            "status" => "1",
            "lucky_no" => "6",
            "dp" => "https://picsum.photos/789/503",
            "address" => "Purnea",
            "remarks" => "no remarks",
            "active" => false,
            "admin_id" => "A_c9GfaPfpUE_IN",
            "password" => "123456789"
        ];

        Admin::create($data);
    }
}
