<?php

namespace App\Http\Controllers;

use App\Enums\UsersEnum;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [
            'users' => User::where('status', UsersEnum::STATUS_ACCESS)->paginate(10),
        ]);
    }
}
