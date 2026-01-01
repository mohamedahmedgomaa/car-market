<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;

class PassportClientsSeeder extends Seeder
{
    public function run(): void
    {
        // providers you have in config/auth.php
        $providers = ['users', 'sellers', 'admins'];

        /** @var ClientRepository $repo */
        $repo = app(ClientRepository::class);

        foreach ($providers as $provider) {
            // Check if there is already a personal access client for this provider
            $exists = DB::table('oauth_clients')
                ->where('personal_access_client', true)
                ->where('provider', $provider)
                ->exists();

            if (! $exists) {
                // Create personal access client for that provider
                $repo->createPersonalAccessClient(
                    null,
                    "Personal Access Client ({$provider})",
                    config('app.url'),
                    $provider
                );
            }
        }
    }
}
