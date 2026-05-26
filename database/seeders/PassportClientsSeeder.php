<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\DB;

class PassportClientsSeeder extends Seeder
{
    public function run(): void
    {
        $providers = ['users', 'sellers', 'admins'];

        $repo = app(ClientRepository::class);

        foreach ($providers as $provider) {
            $exists = DB::table('oauth_clients')
                ->where('personal_access_client', true)
                ->where('provider', $provider)
                ->exists();

            if (! $exists) {
                $repo->createPersonalAccessGrantClient(
                    "Personal Access Client ({$provider})",
                    $provider
                );
            }
        }
    }
}
