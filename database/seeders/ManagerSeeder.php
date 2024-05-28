<?php

namespace Database\Seeders;

use App\Models\Manager;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "name" =>"Manager",
            "email" => "manager@gmail.com",
            "phone" => "9876543210",
            "type" => "pro",
            "dp" => "https://picsum.photos/989/203",
            "status" => "1",
            "password" => "123456789"
        ];
        
        Manager::create($data);
    }
}
