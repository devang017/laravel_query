<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;

class MarketplaceAnalyticsService
{
    public function topSellingProducts(int $limit = 25)
    {
        return Product::query()
            ->select('products.id', 'products.name', 'products.sku')
            ->selectRaw('SUM(order_items.quantity) as units_sold')
            ->selectRaw('SUM(order_items.quantity * order_items.price) as gross_revenue')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('units_sold')
            ->limit($limit)
            ->get();
    }

    public function monthlyRevenue(?int $year = null)
    {
        return Order::query()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(total_amount) as revenue')
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->when($year, fn (Builder $query) => $query->whereYear('created_at', $year))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();
    }

    public function companyRevenueLeaderboard(int $limit = 50)
    {
        return DB::table('companies')
            ->join('users', 'users.company_id', '=', 'companies.id')
            ->join('orders', 'orders.user_id', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->where('orders.payment_status', 'paid')
            ->select('companies.id', 'companies.name', 'companies.industry')
            ->selectRaw('COUNT(DISTINCT users.id) as buying_users')
            ->selectRaw('COUNT(orders.id) as completed_orders')
            ->selectRaw('SUM(orders.total_amount) as revenue')
            ->groupBy('companies.id', 'companies.name', 'companies.industry')
            ->havingRaw('SUM(orders.total_amount) > ?', [10_000])
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();
    }

    public function inactiveCustomers(int $days = 180): Builder
    {
        return User::query()
            ->where('status', 'active')
            ->whereDoesntHave('orders', function (Builder $query) use ($days): void {
                $query->where('created_at', '>=', now()->subDays($days));
            })
            ->withCount('orders')
            ->withMax('orders', 'created_at');
    }

    public function repeatCustomersCursor(int $perPage = 100): CursorPaginator
    {
        return User::query()
            ->whereHas('orders', fn (Builder $query) => $query->where('status', 'completed'), '>=', 2)
            ->withCount(['orders as completed_orders_count' => fn (Builder $query) => $query->where('status', 'completed')])
            ->orderBy('id')
            ->cursorPaginate($perPage);
    }

    public function lowInventoryProducts(int $threshold = 20)
    {
        return Product::query()
            ->with('category:id,name,parent_id')
            ->select('products.*')
            ->selectSub(function ($query): void {
                $query->from('product_warehouse')
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('product_warehouse.product_id', 'products.id');
            }, 'warehouse_stock')
            ->having('warehouse_stock', '<=', $threshold)
            ->orderBy('warehouse_stock')
            ->get();
    }
}
