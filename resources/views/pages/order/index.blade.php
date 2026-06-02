<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Orders
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">

                <div class="flex flex-wrap items-center justify-between gap-4">

                    <div class="flex flex-wrap items-center gap-3">

                        <form action="" method="get">
                            <input type="text" name="search" placeholder="Search Order Number..." class="w-72 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ request('search') }}">

                            <select name="status" class="border border-gray-300 rounded-lg px-8 py-2">
                                <option value="">All Order Status</option>

                                <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="processing" {{ request('status')==='processing' ? 'selected' : '' }}>
                                    Processing
                                </option>

                                <option value="completed" {{ request('status')==='completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                                <option value="cancelled" {{ request('status')==='cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                            </select>

                            <select name="payment_status" class="border border-gray-300 rounded-lg px-8 py-2">
                                <option value="">All Payment Status</option>

                                <option value="paid" {{ request('payment_status')==='paid' ? 'selected' : '' }}>
                                    Paid
                                </option>

                                <option value="unpaid" {{ request('payment_status')==='unpaid' ? 'selected' : '' }}>
                                    Unpaid
                                </option>

                                <option value="refunded" {{ request('payment_status')==='refunded' ? 'selected' : '' }}>
                                    Refunded
                                </option>
                            </select>

                            <button class="bg-gray-800 hover:bg-gray-700 text-white px-5 py-2 rounded-lg">
                                Search
                            </button>
                        </form>

                        <a href="{{ route('orders.index') }}" class="border border-gray-300 hover:bg-gray-100 px-5 py-2 rounded-lg">
                            Reset
                        </a>

                    </div>

                </div>

            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-gray-50 border-b">

                            <tr>

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                    Order
                                </th>

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                    Customer
                                </th>

                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                    Items
                                </th>

                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">
                                    Amount
                                </th>

                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                    Payment
                                </th>

                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                    Order Status
                                </th>

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                    Created
                                </th>

                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @forelse($orders as $order)

                            <tr class="hover:bg-gray-50">

                                <!-- Order -->
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ $order->order_number }}
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        #{{ $order->id }}
                                    </div>

                                </td>

                                <!-- Customer -->
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ $order->user_name ?? '-' }}
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        {{ $order->user_email ?? '-' }}
                                    </div>

                                </td>

                                <!-- Items -->
                                <td class="px-6 py-4 text-center">

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $order->items_count }}
                                    </span>

                                </td>

                                <!-- Amount -->
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">

                                    ₹{{ number_format($order->total_amount, 2) }}

                                </td>

                                <!-- Payment -->
                                <td class="px-6 py-4 text-center">

                                    @if($order->payment_status === 'paid')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                        Paid
                                    </span>
                                    @elseif($order->payment_status === 'refunded')
                                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-medium">
                                        Refunded
                                    </span>
                                    @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                        Unpaid
                                    </span>
                                    @endif

                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $order->payment_method ?? '-' }}
                                    </div>

                                </td>

                                <!-- Order Status -->
                                <td class="px-6 py-4 text-center">

                                    @if($order->status === 'completed')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                        Completed
                                    </span>
                                    @elseif($order->status === 'processing')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                        Processing
                                    </span>
                                    @elseif($order->status === 'cancelled')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                        Cancelled
                                    </span>
                                    @else
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">
                                        Pending
                                    </span>
                                    @endif

                                </td>

                                <!-- Created -->
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-3">

                                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">
                                            View
                                        </a>

                                        <a href="{{ route('orders.edit', $order) }}" class="text-yellow-600 hover:text-yellow-800">
                                            Edit
                                        </a>

                                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" onclick="return confirm('Delete order?')" class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">

                                    No orders found.

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- Pagination -->

            <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4">

                {{ $orders->links() }}

            </div>

        </div>
    </div>
</x-app-layout>