<?php

namespace App\Http\Controllers\Admin;

use App\Hasher;
use App\Http\Controllers\Controller;
use App\Quote;
use App\SubModel;
use App\User;
use App\VehicleModel;
use App\Year;
use Illuminate\Http\Request;
use Nette\Utils\Image;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index() {
        $users = User::get();

        return view('back-office.users')->with('users', $users);
    }

    public function create() {
        return view('back-office.create-user');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return redirect()->back()->with('message', "$request->email account created successfully");
    }

    public function delete($id) {
        User::findOrFail($id)->delete();

        return redirect()->back();
    }
}
