<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Users
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-4">

                    <div class="flex flex-wrap items-center gap-3">

                        <input type="text" placeholder="Search users..." class="w-72 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                        <select class="border border-gray-300 rounded-lg px-10 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>

                        <button class="bg-gray-800 hover:bg-gray-700 text-white px-5 py-2 rounded-lg transition">
                            Search
                        </button>

                        <button class="border border-gray-300 hover:bg-gray-100 px-5 py-2 rounded-lg transition">
                            Reset
                        </button>

                    </div>

                    <div>
                        <a href="{{ route('users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow transition">
                            + Add User
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
                                    User
                                </th>

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                    Company
                                </th>

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                    Country
                                </th>

                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                    Orders
                                </th>

                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                    Reviews
                                </th>

                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                    Status
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

                            @forelse($users as $user)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ $user->name }}
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        {{ $user->email }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    {{ $user->company?->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $user->country?->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-medium">
                                        {{ $user->orders_count }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-md text-xs font-medium">
                                        {{ $user->reviews_count }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($user->status === 'active')
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        Active
                                    </span>
                                    @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                        Inactive
                                    </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-3">

                                        <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:text-blue-800">
                                            View
                                        </a>

                                        <a href="{{ route('users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-800">
                                            Edit
                                        </a>

                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" onclick="return confirm('Delete this user?')" class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                    No users found.
                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- Footer -->
            <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4">

                {{ $users->links() }}

            </div>

        </div>
    </div>
</x-app-layout>