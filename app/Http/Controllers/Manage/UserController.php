<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Manage\BaseController;
use Illuminate\Http\Request;
use DB;

class UserController extends BaseController
{
    public function index(Request $request)
    {
        $users = DB::table('users')
            ->select('id', 'mobile', 'name', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('manage.user.index', ['users' => $users]);
    }

}

?>