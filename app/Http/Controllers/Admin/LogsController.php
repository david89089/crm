<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logs;
use Illuminate\Http\Request;

/**
 * Class LogsController
 * @package App\Http\Controllers\Admin
 */
class LogsController extends Controller
{
    public function index()
    {
        $logs = Logs::orderByDesc('created_at')
            ->with('user', 'owner')
            ->paginate(15);

        return view('admin.logs.index', ['logs' => $logs]);
    }
}
