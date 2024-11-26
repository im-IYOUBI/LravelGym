<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class PaymentCheckController extends Controller
{
    public function index()
    {
        $customers = Customer::all(); // Fetch all customers
        return view('paymentcheck', ['customers' => $customers]);
    }

    public function filter(Request $request)
    {
        $status = $request->input('status'); // 'paid' or 'not_paid'
        $customers = Customer::where('payment_status', $status)->get(); // Filter customers based on status
        return view('paymentcheck', ['customers' => $customers]);
    }
}

