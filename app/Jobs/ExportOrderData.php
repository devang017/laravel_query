<?php

namespace App\Jobs;

use App\Jobs\DownloadOrderList;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExportOrderData implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $userId, private array $requestData, private string $batchId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filters = $this->requestData;
        $baseQuery = Order::query()->when($filters['search'], function ($query) use ($filters) {
            $query->where(function ($query) use ($filters) {
                $query->where('order_number', 'like', "%{$filters['search']}%");
                $userIds = DB::table('users')->whereAny(['name', 'email'], 'LIKE', "%{$filters['search']}%")->pluck('id');
                if ($userIds->isNotEmpty()) {
                    $query->orWhereIn('user_id', $userIds);
                }
            })->when($filters['status'], function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })->when($filters['payment_status'], function ($query) use ($filters) {
                $query->where('payment_status', $filters['payment_status']);
            });
        });

        $orders = $baseQuery->select([
            'id',
            'user_id',
            'order_number',
            'total_amount',
            'status',
            'payment_status',
            'created_at'
        ]);

        $batch = Bus::findBatch($this->batchId);

        $orders->chunkById(10000, function ($ordersData) use ($batch) {
            $batch->add(new DownloadOrderList($ordersData, $batch->name));
        });

        if (empty($batch)) {
            Cache::forget('export_order' . $this->userId);
        }
    }
}
