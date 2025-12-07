<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            TeamSeeder::class,
        ]);

        // Fetch the default team
        $team = \App\Models\Team::where('slug', 'paypay-hq')->first();

        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@paypay.com',
            'password' => bcrypt('password'), // password
        ]);
        $superAdmin->assignRole('Super Admin');
        $superAdmin->teams()->attach($team);

        $finance = User::factory()->create([
            'name' => 'Finance Staff',
            'email' => 'finance@paypay.com',
            'password' => bcrypt('password'), // password
        ]);
        $finance->assignRole('Finance');
        $finance->teams()->attach($team);

        $admin = User::factory()->create([
            'name' => 'HR Manager',
            'email' => 'hr@paypay.com',
            'password' => bcrypt('password'), // password
        ]);
        $admin->assignRole('Administrator');
        $admin->teams()->attach($team);

        $ops = User::factory()->create([
            'name' => 'Operations Manager',
            'email' => 'operations@paypay.com',
            'password' => bcrypt('password'), // password
        ]);
        $ops->assignRole('Operations');
        $ops->teams()->attach($team);

        $marketing = User::factory()->create([
            'name' => 'Marketing Staff',
            'email' => 'marketing@paypay.com',
            'password' => bcrypt('password'), // password
        ]);
        $marketing->assignRole('Marketing');
        $marketing->teams()->attach($team);
    }
}
