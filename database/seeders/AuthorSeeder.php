<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Test Author',
            'Faisal Mustafa',
            'Sarah Jordan',
            'Anup Kanti Ghosh',
            'Samsuzzaman Toke',
            'Samiul H Pranto',
            'Zawad Alam',
            'BM Khalid Hasan',
            'Shahoriar Fahim',
            'Admin'
        ];

        foreach ($names as $name) {
            Author::create([
                'name' => $name,
            ]);
        }
    }
}
