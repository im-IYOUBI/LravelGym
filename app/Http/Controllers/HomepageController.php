<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Personal_Details;

class HomepageController extends Controller
{
    public function showHomepage()
    {
        $customers = DB::select(
            "SELECT Customers.*, Personal_Details.*
            FROM Customers
            LEFT JOIN Personal_Details ON Customers.c_id = Personal_Details.c_id"
        );

        return view('homepage', ['customers' => $customers]);
    }
}
