<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetFilamentUserPassword extends Command
{
    protected $signature = 'filament:reset-admin-password {email} {password}';

    protected $description = 'Reset a Filament admin user password';

    public function handle(): int
    {
        $user = \App\Models\User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('User not found.');
            return self::FAILURE;
        }

        $user->password = $this->argument('password');
        $user->save();

        $this->info("Password updated for {$user->email}");
        return self::SUCCESS;
    }
}

