@extends('layouts.teacher')

@section('title', 'Earnings')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Earnings & Payments</h1>
            <p class="text-gray-600 mt-2">Track your teaching income</p>
        </div>
        <button class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
            Request Withdrawal
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Total Earnings</p>
            <p class="text-3xl font-bold mt-2">Rs. 850,000</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Current Balance</p>
            <p class="text-3xl font-bold mt-2 text-green-600">Rs. 125,000</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-3xl font-bold mt-2 text-orange-600">Rs. 45,000</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">This Month</p>
            <p class="text-3xl font-bold mt-2">Rs. 98,500</p>
            <p class="text-sm text-green-600">+12% from last month</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Payment History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Session</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 text-sm">Dec 15, 2025</td>
                        <td class="px-6 py-4 text-sm">Physics - Mechanics</td>
                        <td class="px-6 py-4 text-sm">Amara Perera</td>
                        <td class="px-6 py-4 text-sm">Rs. 3,000</td>
                        <td class="px-6 py-4 text-sm text-gray-500">-Rs. 300</td>
                        <td class="px-6 py-4 text-sm font-medium">Rs. 2,700</td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs">Paid</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection