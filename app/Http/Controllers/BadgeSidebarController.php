<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BadgeSidebar;
use Illuminate\Http\Request;

class BadgeSidebarController extends Controller
{
    public static function send($name,$user = null)
    {
        if ($user === null) {
            $user = User::where('role', 1)->first();
        }


        if ($user) {
            $userId = is_numeric($user) ? $user : $user->id;

            BadgeSidebar::updateOrInsert(
                ['user_id' => $userId, 'name' => $name],
                ['total' => \DB::raw('total + 1')]
            );
        }
    }
}
