<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'admin@productSearch.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@productSearch.com',
            ]);
        }

        $this->call(BrazilianProductSeeder::class);
        
        $this->command->info('✅ Database seeded successfully!');
    }
}