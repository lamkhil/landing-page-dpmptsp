<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DefaultUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'superadmin@dpmptsp.surabaya.go.id';

        $existing = User::query()->where('email', $email)->first();
        if ($existing) {
            $existing->assignRole('super_admin');
            $this->command?->info("Super-admin already exists: {$email}");
            return;
        }

        // Generate a strong random password and surface it ONCE in the console.
        $password = config('app.env') === 'local' ? 'dpmptsp123' : Str::random(20);

        $user = User::create([
            'name'     => 'Super Admin DPMPTSP',
            'email'    => $email,
            'password' => Hash::make($password),
            'is_active' => true,
        ]);

        $user->assignRole('super_admin');

        $this->command?->info('=== Super-admin created ===');
        $this->command?->info("Email:    {$email}");
        $this->command?->info("Password: {$password}");
        $this->command?->info('Login at  /admin');
        if (config('app.env') !== 'local') {
            $this->command?->warn('Save the password now — it will not be shown again.');
        }
    }
}
