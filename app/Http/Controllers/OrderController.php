<?php

namespace App\Http\Controllers;

use App\Jobs\DownloadOrderList;
use App\Jobs\ExportOrderData;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


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
            ])->addSelect([
                'items_count' => DB::table('order_items')
                    ->whereColumn('order_items.order_id', 'orders.id')
                    ->selectRaw('COUNT(*)')
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

        $items = DB::table('order_items')
            ->whereIn('order_id', $orderIds)
            ->select('order_id', 'quantity', 'price')
            ->get()
            ->groupBy('order_id');

        $orders->each(function ($order) use ($users, $payments, $items) {
            $user  = $users->get($order->user_id);
            $pay   = $payments->get($order->id);
            $itemList = $items->get($order->id) ?? collect();

            $order->user_name       = $user?->name;
            $order->user_email      = $user?->email;
            $order->payment_method  = $pay?->payment_method;
            $order->payment_status  = $pay?->status ?? $order->payment_status;
            $order->items_order     = $itemList;
        });

        $exportBatchId = Cache::get('export_order' . auth()->id()) ?? null;

        return view('pages.order.index', compact('orders', 'exportBatchId'));
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

    public function downloadCsv(Request $request)
    {
        $fileName = $fileName = 'orders_export_' .
            substr(md5(auth()->id() . config('app.key')), 0, 10)
            . '.csv';

        $batch = Bus::batch([])->name($fileName)->dispatch();

        if (!isset($batch->id)) {
            return response()->json(['status' => 'error', 'message' => "File Exports is in Progress", 'data' => $batch->id]);
        }

        if (Storage::disk('order_export')->exists($fileName)) {
            Storage::disk('order_export')->delete($fileName);
        }

        ExportOrderData::dispatch(auth()->id(), $request->all(), $batch->id);
        Cache::put('export_order' . auth()->id(), $batch->id, now()->addMinutes(60));

        return response()->json(['status' => 'success', 'message' => "File Exports is in Progress", 'data' => $batch->id]);
    }

    /**
     * checkdownloadCsvStatus function
     *
     * @param Request $request
     * @return array
     */
    public function checkdownloadCsvStatus(Request $request)
    {
        $batchProgress = 0;
        $batchName = '';
        if (isset($request->batch_id) && !empty($request->batch_id)) {
            $batch = Bus::findBatch($request->batch_id);
            $batchProgress = $batch->progress();
            $batchName = $batch?->name;
            if (!empty($batch->failedJobs)) {
                Cache::forget('export_order' . auth()->id());
                if (Storage::disk('order_export')->exists($batch->name)) {
                    Storage::disk('order_export')->delete($batch->name);
                }
                throw new Exception("Something went wrong.");
            }
        }

        $data = ['progress' => $batchProgress, 'filename' => $batchName];

        return response()->json(['status' => 'success', 'message' => "File Exports is in Progress", 'data' => $data]);
    }

    /**
     * downloadBuylist function
     *
     * @param Request $request
     * @return void
     */
    public function downloadCsvLink(Request $request)
    {
        $file = '';
        if (isset($request->batch_id) && !empty($request->batch_id)) {
            $batch = Bus::findBatch($request->batch_id);

            Cache::forget('export_order' . auth()->id());

            if (Storage::disk('order_export')->exists($batch->name)) {
                $file = Storage::disk('order_export')->path($batch->name);
            }
        }
        return response()->download($file)->deleteFileAfterSend(false);
    }
}
