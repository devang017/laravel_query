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

                    <!-- Left Side Filters -->
                    <div class="flex flex-wrap items-center gap-3">

                        <input type="text" placeholder="Search users..." class="w-72 border border-gray-300 rounded-lg pl-4 pr-10 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                        <select class="border border-gray-300 rounded-lg pl-4 pr-10 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option>All Status</option>
                            <option>Active</option>
                            <option>Inactive</option>
                        </select>

                        <select class="border border-gray-300 rounded-lg pl-4 pr-10 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option>All Roles</option>
                            <option>Admin</option>
                            <option>Manager</option>
                            <option>User</option>
                        </select>

                        <button class="bg-gray-800 hover:bg-gray-700 text-white px-5 py-2 rounded-lg transition">
                            Search
                        </button>

                        <button class="border border-gray-300 hover:bg-gray-100 px-5 py-2 rounded-lg transition">
                            Reset
                        </button>

                    </div>

                    <!-- Right Side Action -->
                    <div>
                        <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow transition">
                            + Add User
                        </a>
                    </div>

                </div>

            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <table class="w-full">

                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">#</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Company</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Role</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Created At</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4 font-medium">John Doe</td>
                            <td class="px-6 py-4">john@example.com</td>
                            <td class="px-6 py-4">Google LLC</td>
                            <td class="px-6 py-4">Admin</td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4">01 Jun 2026</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-3">
                                    <a href="#" class="text-blue-600 hover:text-blue-800">View</a>
                                    <a href="#" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-800">Delete</a>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">2</td>
                            <td class="px-6 py-4 font-medium">Jane Smith</td>
                            <td class="px-6 py-4">jane@example.com</td>
                            <td class="px-6 py-4">Amazon</td>
                            <td class="px-6 py-4">Manager</td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4">28 May 2026</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-3">
                                    <a href="#" class="text-blue-600 hover:text-blue-800">View</a>
                                    <a href="#" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-800">Delete</a>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">3</td>
                            <td class="px-6 py-4 font-medium">Bob Wilson</td>
                            <td class="px-6 py-4">bob@example.com</td>
                            <td class="px-6 py-4">Meta</td>
                            <td class="px-6 py-4">User</td>
                            <td class="px-6 py-4">
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                    Inactive
                                </span>
                            </td>
                            <td class="px-6 py-4">15 May 2026</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-3">
                                    <a href="#" class="text-blue-600 hover:text-blue-800">View</a>
                                    <a href="#" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-800">Delete</a>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">4</td>
                            <td class="px-6 py-4 font-medium">Sara Khan</td>
                            <td class="px-6 py-4">sara@example.com</td>
                            <td class="px-6 py-4">Microsoft</td>
                            <td class="px-6 py-4">User</td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4">10 May 2026</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-3">
                                    <a href="#" class="text-blue-600 hover:text-blue-800">View</a>
                                    <a href="#" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-800">Delete</a>
                                </div>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- Footer -->
            <div class="mt-6 flex justify-between items-center">

                <p class="text-gray-500 text-sm">
                    Showing 1 to 10 of 12,540 users
                </p>

                <div class="flex gap-2">

                    <button class="border px-4 py-2 rounded-lg hover:bg-gray-100">
                        Previous
                    </button>

                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                        1
                    </button>

                    <button class="border px-4 py-2 rounded-lg hover:bg-gray-100">
                        2
                    </button>

                    <button class="border px-4 py-2 rounded-lg hover:bg-gray-100">
                        3
                    </button>

                    <button class="border px-4 py-2 rounded-lg hover:bg-gray-100">
                        Next
                    </button>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>