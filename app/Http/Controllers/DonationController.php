<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// tambahkan User

class DonationController extends Controller
{
    public function index($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return view('donation', compact('user'));
    }
}
