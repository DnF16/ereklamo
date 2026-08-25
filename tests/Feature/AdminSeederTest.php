<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSeederTest extends TestCase
{
    public function test_database_seeder_creates_admin_and_staff_accounts(): void
    {
        $this->artisan('migrate:fresh');
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'staff1@example.com',
        ]);

        $admin = User::where('email', 'admin@example.com')->first();

        $this->assertNotNull($admin);
        $this->assertTrue(Hash::check('password', $admin->password));
    }

    public function test_admin_and_staff_users_are_redirected_to_management_dashboard_after_login(): void
    {
        $this->artisan('migrate:fresh');
        $this->seed(DatabaseSeeder::class);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        $staffResponse = $this->post('/login', [
            'email' => 'staff1@example.com',
            'password' => 'password',
        ]);

        $staffResponse->assertRedirect('/dashboard');
    }

    public function test_management_sidebar_hides_user_feature_for_staff_users(): void
    {
        $this->artisan('migrate:fresh');

        $admin = User::factory()->create([
            'name' => 'Admin Sidebar',
            'email' => 'admin-sidebar@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Auth::login($admin);
        $adminSidebar = view('partials.sidebar.management')->render();
        $this->assertStringContainsString('Manage Users', $adminSidebar);

        Auth::logout();

        $staff = User::factory()->create([
            'name' => 'Staff Sidebar',
            'email' => 'staff-sidebar@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        Auth::login($staff);
        $staffSidebar = view('partials.sidebar.management')->render();

        $this->assertStringNotContainsString('Manage Users', $staffSidebar);
        $this->assertStringContainsString('Complaints', $staffSidebar);
    }
}
