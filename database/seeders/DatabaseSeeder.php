<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Akun Admin
        User::create([
            'name' => 'Admin Pustakawan',
            'email' => 'admin@mail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // 2. Seed Data Kategori Buku
        $categories = [
            'Biografi',
            'Politik',
            'Sains & Teknologi',
            'Fiksi & Sastra',
            'Sejarah',
            'Ensiklopedia',
            'Buku Pelajaran',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }

    }
}
