<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Country;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MarketplaceSeeder extends Seeder
{
    private const USER_COUNT = 100_000;
    private const COMPANY_COUNT = 5_000;
    private const COUNTRY_COUNT = 200;
    private const CATEGORY_COUNT = 500;
    private const PRODUCT_COUNT = 200_000;
    private const ORDER_COUNT = 1_000_000;
    private const ORDER_ITEM_COUNT = 5_000_000;
    private const PAYMENT_COUNT = 1_000_000;
    private const REVIEW_COUNT = 500_000;
    private const WAREHOUSE_COUNT = 100;
    private const PRODUCT_WAREHOUSE_COUNT = 500_000;

    public function run(): void
    {
        Model::unguard();

        $this->seedCountries();
        $this->seedCompanies();
        $this->seedUsers();
        $this->seedCategories();
        $this->seedProducts();
        $this->seedWarehouses();
        $this->seedProductWarehouse();
        $this->seedOrders();
        $this->seedOrderItems();
        $this->syncOrderTotals();
        $this->seedPayments();
        $this->seedReviewsFromCompletedOrders();
    }

    private function seedCountries(): void
    {
        collect(Country::factory()->count(self::COUNTRY_COUNT)->raw())
            ->chunk(500)
            ->each(fn($countries) => DB::table('countries')->insert($countries->all()));
    }

    private function seedCompanies(): void
    {
        collect(Company::factory()->count(self::COMPANY_COUNT)->raw())
            ->chunk(1_000)
            ->each(fn($companies) => DB::table('companies')->insert($companies->all()));
    }

    private function seedUsers(): void
    {
        $faker = fake();
        $countryIds = Country::query()->pluck('id')->all();
        $companyIds = Company::query()->pluck('id')->all();
        $password = Hash::make('password');
        $now = now()->format('Y-m-d H:i:s');
        $rows = [];

        foreach (range(1, self::USER_COUNT) as $offset) {
            $rows[] = [
                'name' => $faker->name(),
                'email' => "customer{$offset}@example.test",
                'country_id' => $countryIds[array_rand($countryIds)],
                'company_id' => $companyIds[array_rand($companyIds)],
                'status' => $faker->randomElement(['active', 'active', 'active', 'inactive', 'blocked']),
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => Str::random(10),
                'created_at' => $this->randomPastDate(),
                'updated_at' => $now,
            ];

            if (count($rows) === 5_000) {
                DB::table('users')->insert($rows);
                $rows = [];
            }
        }

        if (! empty($rows)) {
            DB::table('users')->insert($rows);
        }
    }

    private function seedCategories(): void
    {
        $faker = fake();
        $rootCount = 75;
        $now = now()->format('Y-m-d H:i:s');

        $roots = collect(range(1, $rootCount))->map(fn($index) => [
            'name' => "Department {$index} " . Str::title($faker->word()),
            'parent_id' => null,
            'created_at' => $this->randomPastDate(),
            'updated_at' => $now,
        ]);

        DB::table('categories')->insert($roots->all());

        $parentIds = Category::query()->pluck('id')->all();
        $rows = [];

        for ($index = $rootCount + 1; $index <= self::CATEGORY_COUNT; $index++) {
            $rows[] = [
                'name' => "Category {$index} " . Str::title($faker->word()),
                'parent_id' => $parentIds[array_rand($parentIds)],
                'created_at' => $this->randomPastDate(),
                'updated_at' => $now,
            ];
        }

        DB::table('categories')->insert($rows);
    }

    private function seedProducts(): void
    {
        $faker = fake();
        $categoryIds = Category::query()->pluck('id')->all();
        $now = now()->format('Y-m-d H:i:s');
        $rows = [];

        for ($index = 1; $index <= self::PRODUCT_COUNT; $index++) {
            $rows[] = [
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'sku' => 'SKU-' . str_pad((string) $index, 9, '0', STR_PAD_LEFT),
                'name' => Str::title($faker->words(random_int(2, 5), true)),
                'price' => $faker->randomFloat(2, 5, 2500),
                'stock_quantity' => random_int(0, 2000),
                'status' => $faker->randomElement(['active', 'active', 'active', 'inactive', 'draft']),
                'created_at' => $this->randomPastDate(),
                'updated_at' => $now,
            ];

            if (count($rows) === 5_000) {
                DB::table('products')->insert($rows);
                $rows = [];
            }
        }

        if (! empty($rows)) {
            DB::table('products')->insert($rows);
        }
    }

    private function seedWarehouses(): void
    {
        collect(Warehouse::factory()->count(self::WAREHOUSE_COUNT)->raw())
            ->chunk(500)
            ->each(fn($warehouses) => DB::table('warehouses')->insert($warehouses->all()));
    }

    private function seedProductWarehouse(): void
    {
        $productMinId = (int) Product::query()->min('id');
        $productMaxId = (int) Product::query()->max('id');
        $warehouseMinId = (int) Warehouse::query()->min('id');
        $warehouseMaxId = (int) Warehouse::query()->max('id');
        $productRange = $productMaxId - $productMinId + 1;
        $warehouseRange = $warehouseMaxId - $warehouseMinId + 1;
        $now = now()->format('Y-m-d H:i:s');
        $rows = [];

        for ($index = 0; $index < self::PRODUCT_WAREHOUSE_COUNT; $index++) {
            $rows[] = [
                'product_id' => $productMinId + ($index % $productRange),
                'warehouse_id' => $warehouseMinId + (intdiv($index, $productRange) % $warehouseRange),
                'quantity' => random_int(0, 500),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($rows) === 5_000) {
                DB::table('product_warehouse')->insert($rows);
                $rows = [];
            }
        }

        if (! empty($rows)) {
            DB::table('product_warehouse')->insert($rows);
        }
    }

    private function seedOrders(): void
    {
        $faker = fake();
        $userMinId = (int) User::query()->min('id');
        $userMaxId = (int) User::query()->max('id');
        $now = now()->format('Y-m-d H:i:s');
        $rows = [];

        for ($index = 1; $index <= self::ORDER_COUNT; $index++) {
            $status = $faker->randomElement(['pending', 'processing', 'completed', 'completed', 'completed', 'cancelled']);

            $rows[] = [
                'user_id' => random_int($userMinId, $userMaxId),
                'order_number' => 'ORD-' . str_pad((string) $index, 12, '0', STR_PAD_LEFT),
                'total_amount' => 0,
                'status' => $status,
                'payment_status' => $this->paymentStatusForOrderStatus($status),
                'created_at' => $this->randomPastDate(),
                'updated_at' => $now,
            ];

            if (count($rows) === 5_000) {
                DB::table('orders')->insert($rows);
                $rows = [];
            }
        }

        if (! empty($rows)) {
            DB::table('orders')->insert($rows);
        }
    }

    private function seedOrderItems(): void
    {
        $faker = fake();
        $orderMinId = (int) DB::table('orders')->min('id');
        $orderMaxId = (int) DB::table('orders')->max('id');
        $productMinId = (int) DB::table('products')->min('id');
        $productMaxId = (int) DB::table('products')->max('id');
        $rows = [];

        // for ($index = 1; $index <= self::ORDER_ITEM_COUNT; $index++) {
        //     $createdAt = $this->randomPastDate();

        //     $rows[] = [
        //         'order_id' => random_int($orderMinId, $orderMaxId),
        //         'product_id' => random_int($productMinId, $productMaxId),
        //         'quantity' => random_int(1, 5),
        //         'price' => $faker->randomFloat(2, 5, 2500),
        //         'created_at' => $createdAt,
        //         'updated_at' => $createdAt,
        //     ];

        //     if (count($rows) === 10_000) {
        //         DB::table('order_items')->insert($rows);
        //         $rows = [];
        //     }
        // }

        // if (! empty($rows)) {
        //     DB::table('order_items')->insert($rows);
        // }

        Order::select('id')
            ->chunkById(10000, function ($orders) use ($faker, $productMinId, $productMaxId) {

                $rows = [];

                foreach ($orders as $order) {
                    $createdAt = now();

                    $rows[] = [
                        'order_id' => $order->id,
                        'product_id' => random_int($productMinId, $productMaxId),
                        'quantity' => random_int(1, 5),
                        'price' => $faker->randomFloat(2, 5, 2500),
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ];
                }

                DB::table('order_items')->insert($rows);
            });
    }

    private function syncOrderTotals(): void
    {
        DB::statement(<<<'SQL'
            UPDATE orders o
            INNER JOIN (
                SELECT
                    order_id,
                    SUM(quantity * price) AS total
                FROM order_items
                GROUP BY order_id
            ) oi ON oi.order_id = o.id
            SET o.total_amount = oi.total
            WHERE o.total_amount = 0.00
        SQL);
    }

    private function seedPayments(): void
    {
        DB::table('orders')
            ->select(['id', 'total_amount', 'payment_status', 'created_at'])
            ->orderBy('id')
            ->chunkById(5_000, function ($orders): void {
                $now = now()->format('Y-m-d H:i:s');
                $rows = [];

                foreach ($orders as $order) {
                    $paid = $order->payment_status === 'paid';
                    $createdAt = $order->created_at;

                    $rows[] = [
                        'order_id' => $order->id,
                        'amount' => $order->total_amount,
                        'payment_method' => fake()->randomElement(['card', 'paypal', 'bank_transfer', 'wallet', 'cod']),
                        'status' => $order->payment_status === 'unpaid' ? 'failed' : $order->payment_status,
                        'paid_at' => $paid ? $createdAt : null,
                        'created_at' => $createdAt,
                        'updated_at' => $now,
                    ];
                }

                DB::table('payments')->insert($rows);
            });
    }

    private function seedReviewsFromCompletedOrders(): void
    {
        $inserted = 0;

        DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->select(['orders.user_id', 'order_items.product_id', 'orders.created_at'])
            ->orderBy('order_items.id')
            ->chunk(5_000, function ($items) use (&$inserted): bool {
                $now = now()->format('Y-m-d H:i:s');
                if ($inserted >= self::REVIEW_COUNT) {
                    return false;
                }

                $rows = [];

                foreach ($items as $item) {
                    if ($inserted >= self::REVIEW_COUNT) {
                        break;
                    }

                    if (random_int(1, 100) <= 35) {
                        $rows[] = [
                            'user_id' => $item->user_id,
                            'product_id' => $item->product_id,
                            'rating' => random_int(1, 5),
                            'comment' => fake()->optional(0.85)->sentence(random_int(6, 18)),
                            'created_at' => $item->created_at,
                            'updated_at' => $now,
                        ];
                        $inserted++;
                    }
                }

                if (! empty($rows)) {
                    DB::table('reviews')->insert($rows);
                }

                return true;
            });
    }

    private function paymentStatusForOrderStatus(string $status): string
    {
        return match ($status) {
            'completed' => fake()->randomElement(['paid', 'paid', 'paid', 'refunded']),
            'cancelled' => fake()->randomElement(['unpaid', 'refunded']),
            default => fake()->randomElement(['unpaid', 'paid']),
        };
    }

    private function randomPastDate(): string
    {
        return fake()->dateTimeBetween('-3 years')->format('Y-m-d H:i:s');
    }
}
