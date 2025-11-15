<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUserSeeder extends Seeder
{
    /**
     * Seed the application's database with the demo teacher account.
     */
    public function run(): void
    {
        $email = (string) env('DEMO_TEACHER_EMAIL');
        $password = (string) env('DEMO_TEACHER_PASSWORD');

        if ($email === '' || $password === '') {
            $this->command?->warn('Lewati pembuatan akun demo guru karena kredensial belum dikonfigurasi.');

            return;
        }

        $username = (string) env('DEMO_TEACHER_USERNAME');
        $name = env('DEMO_TEACHER_NAME', 'Guru Terminal Pintar');
        $shortName = env('DEMO_TEACHER_SHORT_NAME');
        $organization = env('DEMO_TEACHER_ORGANIZATION', 'Terminal Pintar');
        $avatar = env('DEMO_TEACHER_AVATAR');

        $user = User::query()->firstOrNew(['email' => strtolower($email)]);

        $user->forceFill([
            'name' => $name,
            'username' => $username !== '' ? $username : null,
            'role' => 'teacher',
            'short_name' => $shortName !== '' ? $shortName : ($user->short_name ?: $name),
            'organization_name' => $organization,
            'avatar_url' => $avatar !== '' ? $avatar : null,
            'password' => Hash::make($password),
        ]);

        if (! $user->email_verified_at) {
            $user->email_verified_at = now();
        }

        if (! $user->remember_token) {
            $user->remember_token = Str::random(60);
        }

        $user->save();
    }
}
