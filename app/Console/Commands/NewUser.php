<?php

namespace App\Console\Commands;

use anlutro\cURL\cURL;
use App\Events\NewUserEvent;
use App\Events\NotificationEvent;
use App\Models\User;
use App\Service\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class NewUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:randomuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public NotificationService $notificationService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $curl = new cURL();
        $response = $curl->get('https://api.randomuser.me/');
        $user = json_decode($response, true);

        $userInfo = [
            'name' => $user['results'][0]['name']['first'],
            'password' => Hash::make($user['results'][0]['login']['password']),
            'email' => $user['results'][0]['email'],
            'email_verified_at' => now(),
            'birth_date' => Carbon::parse($user['results'][0]['dob']['date']),
            'age' => $user['results'][0]['dob']['age'],
            'phone' => $user['results'][0]['phone'],
            'photo' => $user['results'][0]['picture']['large']
        ];

        $roleUser = Role::where('name', '=', 'user')->first();
        $user = User::create($userInfo);
        $user->assignRole($roleUser);

        NewUserEvent::dispatch($user);

        $this->notificationService->storeUserNotify($user);
    }
}
