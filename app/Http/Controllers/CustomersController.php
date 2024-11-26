<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    public function customersView()
    {
        $customers = DB::select(
            "SELECT Customers.*, Personal_Details.*
            FROM Customers
            LEFT JOIN Personal_Details ON Customers.c_id = Personal_Details.c_id"
        );

        return view('.includes.Customers.customersview', ['customers' => $customers]);
    }

    public function createCustomer()
    {
        $result = DB::select("SELECT MAX(id) as max_id FROM Customers");
        if (empty($result) || $result[0]->max_id === null) {
            $id = 1;
        } 
        else {
            $id = $result[0]->max_id + 1;
        }
        $c_id = 'CID' . str_pad($id, 3, '0', STR_PAD_LEFT);
        $plans = DB::select("SELECT * FROM Plans");

        return view('.includes.Customers.customerform', ['c_id' => $c_id, 'plans' => $plans]);
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'address' => 'required|max:50',
            'mobile' => 'required|max:15',
            'p_id' => 'required|exists:plans,p_id', 
            'p_start' => 'required|date',
        ]);

        $dob = new \DateTime($request->input('dob'));
        $currentDate = new \DateTime(now());
        $age = $dob->diff($currentDate)->y;
        $plan = DB::table('Plans')->where('p_id', $request->input('p_id'))->first();
        $p_start = Carbon::parse($request->input('p_start'));
        $p_end = $p_start->copy()->addDays($plan->period);
        $p_status = 'ACTIVE';

        DB::insert(
            "INSERT INTO Customers (id, c_id, p_id, p_start, p_end, p_status) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                DB::table('Customers')->max('id') + 1,
                $request->input('c_id'),
                $request->input('p_id'),
                $request->input('p_start'),
                $p_end,
                $p_status
            ]
        );

        $t_id = null;
        DB::insert(
            "INSERT INTO Personal_Details (c_id, t_id, name, dob, age, gender, height, weight, address, mobile) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        [
            $request->input('c_id'),
            $t_id,
            $request->input('name'),
            $request->input('dob'),
            $age,
            $request->input('gender'),
            $request->input('height'),
            $request->input('weight'),
            $request->input('address'),
            $request->input('mobile'),
        ]
    );
    
        return redirect()->route('customers')->with('success', 'Customer created successfully.');
    } 

    public function editCustomer($id)
    {
        $customerdetails = DB::select(
            "SELECT Customers.*, Personal_Details.*
            FROM Customers
            LEFT JOIN Personal_Details ON Customers.c_id = Personal_Details.c_id
            WHERE Customers.id = ?",[$id]
        );
        $customer = $customerdetails[0];
        $plans = DB::select("SELECT * FROM Plans");

        return view('.includes.Customers.editcustomer', ['customer' => $customer, 'plans' => $plans]);
    }

    public function updateCustomer(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:50',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'address' => 'required|max:50',
            'mobile' => 'required|max:15',
            'p_id' => 'required|exists:plans,p_id', 
            'p_start' => 'required|date',
        ]);

        $dob = new \DateTime($request->input('dob'));
        $currentDate = new \DateTime(now());
        $age = $dob->diff($currentDate)->y;
        $plan = DB::table('Plans')->where('p_id', $request->input('p_id'))->first();
        $p_start = Carbon::parse($request->input('p_start'));
        $p_end = $p_start->copy()->addDays($plan->period);
        $p_status = 'ACTIVE';
        
        DB::update(
            "UPDATE Customers 
            SET p_id = ?, p_start = ?, p_end = ?, p_status = ?, updated_at = NOW()
            WHERE id = ?",
            [
                $request->input('p_id'),
                $request->input('p_start'),
                $p_end,
                $p_status,
                $id
            ]
        );   

        $c_id = DB::select(
            "SELECT c_id from Customers
            where id = ?",[$id]
        )[0]->c_id;

        DB::update(
            "UPDATE Personal_Details 
            SET name = ?, dob = ?, age = ?, gender = ?, height = ?, weight = ?, address = ?, mobile = ?, updated_at = NOW()
            WHERE c_id = ?",
            [
                $request->input('name'),
                $request->input('dob'),
                $age,
                $request->input('gender'),
                $request->input('height'),
                $request->input('weight'),
                $request->input('address'),
                $request->input('mobile'),
                $c_id
            ]
        );   

        return redirect()->route('customers')->with('success', 'Customer updated successfully!');
    }

    public function deleteCustomer($id)
    {
        DB::delete("DELETE FROM Customers WHERE id = :id", ['id' => $id]);

        return redirect()->route('customers')->with('success', 'Customer deleted successfully!');
    }
}
