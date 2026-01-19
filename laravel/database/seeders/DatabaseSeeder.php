<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make(1),
            'ipAddress' => request()->ip()
        ]);

        Device::create([
            'name' => 'Device 1',
            'ipAddress' => '192.168.1.100'
        ]);

        CompanyProfile::create([
            'name' => 'Company Name',
            'domainIp' => '192.168.1.100',
            'database' => 'mycompany_db',
            'username' => 'dbuser',
            'password' => 'dbpassword',
            'ipAddress' => request()->ip()
        ]);
    }
}
