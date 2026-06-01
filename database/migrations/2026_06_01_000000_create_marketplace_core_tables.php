<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('industry')->index();
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->after('email')->constrained()->nullOnDelete();
            $table->foreignId('company_id')->nullable()->after('country_id')->constrained()->nullOnDelete();
            $table->string('status', 20)->default('active')->after('company_id')->index();

            $table->index(['country_id', 'status']);
            $table->index(['company_id', 'status']);
            $table->index(['status', 'created_at']);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();

            $table->index(['parent_id', 'name']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('name')->index();
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();

            $table->index('category_id');
            $table->index('created_at');
            $table->index(['category_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['price', 'status']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->string('status', 20)->index();
            $table->string('payment_status', 20)->index();
            $table->timestamps();

            $table->index('user_id');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'payment_status']);
            $table->index(['status', 'created_at']);
            $table->index(['payment_status', 'created_at']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity');
            $table->decimal('price', 12, 2);
            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
            $table->index(['product_id', 'order_id']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('amount', 14, 2);
            $table->string('payment_method', 30)->index();
            $table->string('status', 20)->index();
            $table->timestamp('paid_at')->nullable()->index();
            $table->timestamps();

            $table->index('order_id');
            $table->index(['status', 'paid_at']);
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('product_id');
            $table->index('created_at');
            $table->index(['product_id', 'rating']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('city')->index();
            $table->timestamps();
        });

        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamps();

            $table->primary(['product_id', 'warehouse_id']);
            $table->index('warehouse_id');
            $table->index(['warehouse_id', 'quantity']);
            $table->index(['product_id', 'quantity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_warehouse');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['company_id']);
            $table->dropColumn(['country_id', 'company_id', 'status']);
        });

        Schema::dropIfExists('countries');
        Schema::dropIfExists('companies');
    }
};
