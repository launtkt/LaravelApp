<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\User;
class DashboardController extends Controller
{
    public function index()
    {
        $paidOrders = Order::with(['voucher', 'items.product'])
            ->where('status', 'delivered')
            ->get();
        $revenue = $paidOrders->sum(function ($order) {
            return $order->final_price;
        });

        // Calculate the total number of products, users, and orders with is_active status
        $products = Product::where('is_active', true)->count();
        $users = User::where('status', true)->count();
        $orders = Order::where('is_active', true)->count();
        // Get the latest 10 orders with their related data with is_active status

        $latestOrders = Order::with(['user', 'voucher', 'items.product'])
            ->where('is_active', true)
            ->latest()
            ->take(10)
            ->get();
        return view('admin.dashboard', compact('products', 'orders', 'revenue', 'latestOrders', 'users'));
    }
}
