<?php

namespace App\Jobs;


use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DownloadOrderList implements ShouldQueue
{
    use Queueable, Batchable;

    public $tries = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Collection $ordersData, protected string $fileName)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            # code...
            $filePath = Storage::disk('order_export')->path('');

            $handle = fopen($filePath . $this->fileName, 'a');

            $fileSize = Storage::disk('order_export')->size($this->fileName);

            if ($fileSize == 0) {
                $headers = $this->getHeadings();
                fputcsv($handle, $headers);
            }

            foreach ($this->ordersData->chunk(100) as $orderChunk) {

                $userIds = $orderChunk->pluck('user_id');
                $orderIds = $orderChunk->pluck('id');

                $users = DB::table('users')
                    ->whereIn('id', $userIds)
                    ->select('id', 'name', 'email')
                    ->get()
                    ->keyBy('id');

                $payments = DB::table('payments')
                    ->whereIn('order_id', $orderIds)
                    ->select('order_id', 'payment_method', 'status')
                    ->get()
                    ->keyBy('order_id');

                $itemCount = DB::table('order_items')
                    ->whereIn('order_id', $orderIds)
                    ->select('order_id', DB::raw('COUNT(*) as item_count'))
                    ->groupBy('order_id')
                    ->get()
                    ->keyBy('order_id');

                $orderChunk->each(function ($order) use ($users, $payments, $itemCount, $handle) {
                    $user  = $users->get($order->user_id);
                    $pay   = $payments->get($order->id);
                    $itemCountData = $itemCount->get($order->id);

                    $order->user_name       = $user?->name;
                    $order->user_email      = $user?->email;
                    $order->payment_method  = $pay?->payment_method;
                    $order->payment_status  = $pay?->status ?? $order->payment_status;
                    $order->item_count     = $itemCountData->item_count ?? 0;

                    $csvData = $this->mapping($order);

                    fputcsv($handle, $csvData, ',', '"');
                });
            }

            fclose($handle);
        } catch (\Throwable $e) {
            dump($e->getMessage());
        }
    }

    /**
     * getHeadings function
     *
     * @return array
     */
    public function getHeadings()
    {
        // headers
        $headers = [
            'Order',
            'Customer Name',
            'Customer Email',
            'Total Items',
            'Order Amount',
            'Payment Status',
            'Payment Method',
            'Order Status',
            'Created At'
        ];

        return $headers;
    }

    /**
     * mapping function
     *
     * @param object $data
     * @return array
     */
    public function mapping(object $data)
    {
        $mappingArr = [
            isset($data->order_number) ? $data->order_number : '',
            isset($data->user_name) ? $data->user_name : '',
            isset($data->user_email) ? $data->user_email : '',
            isset($data->item_count) ? $data->item_count : '',
            isset($data->total_amount) ? $data->total_amount : '',
            isset($data->payment_status) ? $data->payment_status : '',
            isset($data->payment_method) ? $data->payment_method : '',
            isset($data->status) ? $data->status : '',
            isset($data->created_at) ? $data->created_at->format('d-m-Y h:i A') : ''
        ];

        return $mappingArr;
    }
}
