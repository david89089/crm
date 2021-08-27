<?php

namespace Database\Seeders;

use anlutro\cURL\cURL;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Manager',
            'email' => 'manager@admin.ru',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'birth_date' => now(),
            'phone' => '987654321'
        ]);

        $user->assignRole(Role::where('name', '=', 'manager')->first());

        $curl = new cURL();
        $response = $curl->get('https://api.randomuser.me/?results=10');
        $users = json_decode($response, true);

        $roleUser = Role::where('name', '=', 'user')->first();

        foreach ($users['results'] as $user) {
            $userInfo = [
                'name' => $user['name']['first'],
                'password' => Hash::make($user['login']['password']),
                'email' => $user['email'],
                'email_verified_at' => now(),
                'birth_date' => Carbon::parse($user['dob']['date']),
                'age' => $user['dob']['age'],
                'phone' => $user['phone'],
                'photo' => $user['picture']['large'],
            ];

            $user = User::create($userInfo);
            $user->assignRole($roleUser);
        }

    }
}
