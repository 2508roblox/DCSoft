<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Models\Projects;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HomeController extends BaseController {
	public function index() {
        $projects = Projects::get();
        //get revenue
        $revenues = DB::table('revenue')
        ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as total_amount'))
        ->where('type', 'revenue')
        ->groupBy('month')
        ->pluck('total_amount', 'month')
        ->toArray();
        $expenses = DB::table('revenue')
        ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as total_amount'))
        ->where('type', 'expense')
        ->groupBy('month')
        ->pluck('total_amount', 'month')
        ->toArray();

    // Fill missing months with 0 total amount
    for ($month = 1; $month <= 12; $month++) {
        if (!isset($revenues[$month])) {
            $revenues[$month] = 0;

        }
        if (!isset($expenses[$month])) {
            $expenses[$month] = 0;

        }
    }
    $revenuesJson = json_encode($revenues);
    $expensesJson = json_encode($expenses);
		return view('revenue.index', compact('projects', 'revenuesJson', 'expensesJson'));
	}
}
