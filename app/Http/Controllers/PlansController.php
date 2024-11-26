<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use Carbon\Carbon;

class PlansController extends Controller
{
    public function plansView()
    {
        $plans = DB::select("SELECT * FROM Plans");

        return view('.includes.Plans.plansview', ['plans' => $plans]);
    }

    public function createPlan()
    {
        $result = DB::select("SELECT MAX(id) as max_id FROM Plans");
        if (empty($result) || $result[0]->max_id === null) {
            $id = 1;
        } 
        else {
            $id = $result[0]->max_id + 1;
        }
        $p_id = 'PID' . str_pad($id, 3, '0', STR_PAD_LEFT);

        return view('.includes.Plans.planform', ['p_id' => $p_id]);
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'p_id' => 'required|unique:plans,p_id',
            'name' => 'required|max:50',
            'period' => 'required|numeric',
            'price' => 'required|numeric',
        ]);
        DB::insert(
            "INSERT INTO Plans (id, p_id, name, period, price) 
            VALUES (?, ?, ?, ?, ?)",
            [
                DB::table('Plans')->max('id') + 1,
                $request->input('p_id'),
                $request->input('name'),
                $request->input('period'),
                $request->input('price'),
            ]
        );

        return redirect()->route('plans')->with('success', 'Plan created successfully.');
    }

    public function editPlan($id)
    {
        $plan = DB::select("SELECT * FROM Plans WHERE id = ?", [$id])[0];

        return view('.includes.Plans.editplan', ['plan' => $plan]);
    }


    public function updatePlan(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:50',
            'period' => 'required|numeric',
            'price' => 'required|numeric',
        ]);
        DB::update(
            "UPDATE Plans 
            SET name = ?, period = ?, price = ? 
            WHERE id = ?",
            [
                $request->input('name'), 
                $request->input('period'), 
                $request->input('price'), 
                $id
            ]
        );

        return redirect()->route('plans')->with('success', 'Plan updated successfully!');
    }

    public function deletePlan($id)
    {
        $plan = DB::select("SELECT * FROM Plans WHERE id = ?", [$id])[0];
        $customers = DB::select("SELECT * FROM Customers WHERE p_id = ?", [$plan->p_id]);
        foreach ($customers as $customer) {
            DB::update(
                "UPDATE Customers 
                SET p_id = null, p_start = null, p_end = null, p_status = ''
                WHERE id = ?",
                [$customer->id]
            );
        }
        DB::delete("DELETE FROM Plans WHERE id = ?", [$id]);

        return redirect()->route('plans')->with('success', 'Plan deleted successfully!');
    }}