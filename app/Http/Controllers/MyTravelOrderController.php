<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyTravelOrderController extends Controller
{
    public function index()
    {
        $perPage = 10;
        $travelOrders = TravelOrder::with(['status', 'latestStatusUpdate.user', 'employee', 'recommenderEmployee', 'approverEmployee'])
            ->where('employee_email', Auth::user()->email)
            ->latest()
            ->paginate($perPage);

        return view('travel-orders.index', [
            'travelOrders' => $travelOrders
        ]);
    }
}
