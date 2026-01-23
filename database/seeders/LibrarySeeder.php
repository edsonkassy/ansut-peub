<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibrarySeeder extends Seeder
{
    public function run()
    {
        $this->call([
            LibraryCategorySeeder::class,
            LibraryResourceSeeder::class,
            LibraryInteractionsSeeder::class,
        ]);
    }
}