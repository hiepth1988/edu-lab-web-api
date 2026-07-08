<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EditorUserSeeder extends Seeder
{
    public function run(): void
    {
        $editor = User::updateOrCreate(
            ['email' => 'editor@xoedulab.local'],
            [
                'name' => 'XO EdTech Editor',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $editor->syncRoles(['editor']);
    }
}
