<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Personal_Details;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AppManager extends Controller {
    function login() {
        if (Auth::check()) {
            return redirect(route('homepage'));
        }

        return view('login');
    }

    

    public function showLoginForm()
    {
        return view('login');
    }

    function loginPost(Request $request) {
        $credentials  = $request->only('id', 'password');
        if (Auth::attempt($credentials)) {
            $admin = Auth::user();
            session(['name' => $admin->name]);
            $customers = DB::select(
                "SELECT Customers.*, Personal_Details.*
                FROM Customers
                LEFT JOIN Personal_Details ON Customers.c_id = Personal_Details.c_id"
            );
    
            return view('.includes.Customers.customersview', ['customers' => $customers]);
        }

        return redirect(route('login'))->with('error', 'Login details are not valid.');
    }

    

    function logout() {
        Session::flush();
        Auth::logout();

        return redirect(route('welcome'));
    }

    public function showHomepage()
    {
        $customers = DB::select(
            "SELECT Customers.*, Personal_Details.*
            FROM Customers
            LEFT JOIN Personal_Details ON Customers.c_id = Personal_Details.c_id"
        );

        return view('.includes.Customers.customersview', ['customers' => $customers]);
    }

   
}