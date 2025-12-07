<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Payroll;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $team = Team::firstOrCreate([
            'slug' => 'paypay-hq'
        ], [
            'name' => 'PayPay HQ',
        ]);

        // Assign all users to this team
        $users = User::all();
        foreach ($users as $user) {
            if (!$user->teams->contains($team)) {
                $user->teams()->attach($team);
            }
        }

        // Update all resources to belong to this team if they don't have one
        Client::whereNull('team_id')->update(['team_id' => $team->id]);
        Project::whereNull('team_id')->update(['team_id' => $team->id]);
        Invoice::whereNull('team_id')->update(['team_id' => $team->id]);
        Employee::whereNull('team_id')->update(['team_id' => $team->id]);
        Leave::whereNull('team_id')->update(['team_id' => $team->id]);
        Payroll::whereNull('team_id')->update(['team_id' => $team->id]);
        \App\Models\Attendance::whereNull('team_id')->update(['team_id' => $team->id]);
        \App\Models\Task::whereNull('team_id')->update(['team_id' => $team->id]);
        \App\Models\Allowance::whereNull('team_id')->update(['team_id' => $team->id]);
        \App\Models\Deduction::whereNull('team_id')->update(['team_id' => $team->id]);
    }
}
