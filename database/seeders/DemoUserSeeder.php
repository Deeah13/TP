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
        $email = trim((string) env('DEMO_TEACHER_EMAIL'));
        $password = (string) env('DEMO_TEACHER_PASSWORD');
        $password = $password !== '' ? trim($password) : '';

        if ($email === '' || $password === '') {
            $this->command?->warn('Lewati pembuatan akun demo guru karena kredensial belum dikonfigurasi.');

            return;
        }

        $username = trim((string) env('DEMO_TEACHER_USERNAME'));
        $name = trim((string) env('DEMO_TEACHER_NAME', 'Guru Terminal Pintar')) ?: 'Guru Terminal Pintar';
        $shortName = trim((string) env('DEMO_TEACHER_SHORT_NAME')) ?: null;
        $organization = trim((string) env('DEMO_TEACHER_ORGANIZATION', 'Terminal Pintar')) ?: 'Terminal Pintar';
        $avatar = trim((string) env('DEMO_TEACHER_AVATAR')) ?: null;

        $normalisedEmail = strtolower($email);

        $user = User::query()->firstOrNew(['email' => $normalisedEmail]);

        $user->forceFill([
            'name' => $name,
            'email' => $normalisedEmail,
            'username' => $username !== '' ? strtolower($username) : null,
            'role' => 'teacher',
            'short_name' => $shortName !== null ? $shortName : ($user->short_name ?: $name),
            'organization_name' => $organization,
            'avatar_url' => $avatar,
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
