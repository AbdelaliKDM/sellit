<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get total counts
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $totalIncome = Order::sum('total_amount');

        // Get total profit from orders table
        $totalProfit = Order::sum('total_profit');

        // Get today's stats
        $today = now()->format('Y-m-d');
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayIncome = Order::whereDate('created_at', $today)->sum('total_amount');

        // Get today's profit from orders table
        $todayProfit = Order::whereDate('created_at', $today)->sum('total_profit');

        return view('content.home.index', compact(
            'totalProducts',
            'totalCustomers',
            'totalOrders',
            'totalIncome',
            'totalProfit',
            'todayOrders',
            'todayIncome',
            'todayProfit'
        ));
    }
}
