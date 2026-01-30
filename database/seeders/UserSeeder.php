<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $companyRole = Role::where('name', 'company')->first();
        $customerRole = Role::where('name', 'jobseeker')->first();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'phone' => '9711324501',
            'address' => 'Pokhara',
        ]);
        $admin->roles()->attach($adminRole->id);

        $company = User::create([
            'name' => 'Company',
            'email' => 'company@gmail.com',
            'password' => bcrypt('password'),
            'phone' => '9811256501',
            'address' => 'Kathmandu',
        ]);
        $company->roles()->attach($companyRole->id);

        $customer = User::create([
            'name' => 'JobSeeker',
            'email' => 'jobseeker@gmail.com',
            'password' => bcrypt('password'),
            'phone' => '9811267801',
            'address' => 'Lalitpur',
        ]);
        $customer->roles()->attach($customerRole->id);
    }
}
