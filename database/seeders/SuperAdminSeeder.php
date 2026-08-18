<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'bennylovescoffee@gmail.com';
        
        $user = User::where('email', $email)->first();
        
        if ($user) {
            $user->update([
                'role' => 'superadmin',
                'password' => Hash::make('password'), // Default password, user can change it
                'email_verified_at' => now(),
            ]);
            $this->command->info('Superadmin updated successfully.');
        } else {
            User::create([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Superadmin created successfully.');
        }
    }
}
