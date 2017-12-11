<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin', $request->user());

        $user = User::paginate(10);
        return view('admin.index', [
            'users' => $user,
        ]);
    }
}
