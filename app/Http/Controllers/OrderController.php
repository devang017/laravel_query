<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $baseQuery = Order::query()
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('payment_status'), fn($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%");

                    $userIds = DB::table('users')
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "{$search}%")
                        ->pluck('id');

                    if ($userIds->isNotEmpty()) {
                        $q->orWhereIn('user_id', $userIds);
                    }
                });
            });


        $orders = $baseQuery
            ->select([
                'id',
                'user_id',
                'order_number',
                'total_amount',
                'status',
                'payment_status',
                'created_at',
            ])
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $orderIds  = $orders->pluck('id');
        $userIds   = $orders->pluck('user_id')->unique();

        $users = DB::table('users')
            ->whereIn('id', $userIds)
            ->select('id', 'name', 'email')
            ->get()
            ->keyBy('id');

        $payments = DB::table('payments')
            ->whereIn('order_id', $orderIds)
            ->select('order_id', 'payment_method', 'status')
            ->orderByDesc('id')
            ->get()
            ->keyBy('order_id');

        $itemCounts = DB::table('order_items')
            ->whereIn('order_id', $orderIds)
            ->select('order_id', DB::raw('COUNT(*) as items_count'))
            ->groupBy('order_id')
            ->get()
            ->keyBy('order_id');

        $orders->each(function ($order) use ($users, $payments, $itemCounts) {
            $user  = $users->get($order->user_id);
            $pay   = $payments->get($order->id);
            $count = $itemCounts->get($order->id);

            $order->user_name       = $user?->name;
            $order->user_email      = $user?->email;
            $order->payment_method  = $pay?->payment_method;
            $order->payment_status  = $pay?->status ?? $order->payment_status;
            $order->items_count     = $count?->items_count ?? 0;
        });

        return view('pages.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
