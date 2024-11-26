<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayTransaction;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\Trainer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PDF;


class PayTransactionsController extends Controller
{
    public function paytransactionsView() 
    {
        $paytransactions = DB::select("SELECT * FROM Pay_Transactions");

        return view('.includes.Pay_Transactions.paytransactionsview', ['paytransactions'=> $paytransactions]);
    }
    
    public function createPayTransaction() 
    {
        $result = DB::select("SELECT MAX(id) as max_id FROM Pay_Transactions");
        if (empty($result) || $result[0]->max_id === null) {
            $id = 1;
        } 
        else {
            $id = $result[0]->max_id + 1;
        }

        return view('.includes.Pay_Transactions.paytransactionform', ['id' => $id]);
    } 

    public function storePayTransaction(Request $request)
    {
        $request->validate([
            'payer_id' => [
                'required',
                'max:10',
                function ($attribute, $value, $fail) {
                    if (!Customer::where('c_id', $value)->exists() && !Admin::where('id', $value)->exists()) {
                        $fail("Invalid Payer ID");
                    }
                },
            ],
            'payee_id' => [
                'required',
                'max:10',
                'different:payer_id',
                function ($attribute, $value, $fail) use ($request) {
                    $payerId = $request->input('payer_id');
    
                    if (Customer::where('c_id', $payerId)->exists() && !Admin::where('id', $value)->exists()) {
                        $fail("Invalid Payee ID.");
                    }
    
                    if (!Admin::where('id', $value)->exists() && !Trainer::where('t_id', $value)->exists()) {
                        $fail("Invalid Payee ID");
                    }
                },
            ],
            'payment_mode' => 'required|max:20',
            'pay_date' => 'required|date',
            'amount' => 'required|numeric',
            'transaction_id' => 'nullable|max:20|unique:pay_transactions,transaction_id',
        ]);
        DB::insert(
            "INSERT INTO Pay_Transactions (id, payer_id, payer_name, payee_id, payment_mode, pay_date, amount, transaction_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                DB::table('Pay_Transactions')->max('id') + 1,
                $request->input('payer_id'),
                $request->input('payer_name'),
                $request->input('payee_id'),
                $request->input('payment_mode'),
                $request->input('pay_date'),
                $request->input('amount'),
                $request->input('transaction_id'),
            ]
        );

        return redirect()->route('paytransactions')->with('success', 'Payment Transaction created successfully.');
    }

    public function deletePayTransaction($id)
    {
        DB::delete("DELETE FROM Pay_Transactions WHERE id = :id", ['id' => $id]);

        return redirect()->route('paytransactions')->with('success', 'Payment Transaction deleted successfully!');
    }

    public function downloadPDF()
{
    $paytransactions = DB::select("SELECT * FROM Pay_Transactions");

    $pdf = PDF::loadView('.includes.Pay_Transactions.pdf', compact('paytransactions'));

    return $pdf->download('Pay_Transactions.pdf');
}

}
