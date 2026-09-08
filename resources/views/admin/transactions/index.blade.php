@php
    $symbol = ['USD' => '$', 'EUR' => '€', 'GBP' => '£'];
@endphp
@extends('layouts.admin')

@section('title', 'Transactions')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h1 class="text-2xl font-bold">Transactions</h1>
        <div class="bg-white rounded shadow px-4 py-2 text-sm">
            Total collected: <span class="font-bold">{{ $totalCollected ? '$' . number_format($totalCollected, 2) : '—' }}</span>
        </div>
    </div>

    <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex gap-2 mb-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..." class="border rounded px-3 py-2 text-sm">
        <select name="status" class="border rounded px-3 py-2 text-sm">
            <option value="">All payment statuses</option>
            <option value="paid" @selected(request('status') === 'paid')>Paid</option>
            <option value="unpaid" @selected(request('status') === 'unpaid')>Unpaid</option>
            <option value="partial" @selected(request('status') === 'partial')>Partial</option>
        </select>
        <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded text-sm hover:bg-blue-800">Search</button>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3">Tracking #</th>
                    <th class="px-6 py-3">Sender</th>
                    <th class="px-6 py-3">Recipient</th>
                    <th class="px-6 py-3">Payment Mode</th>
                    <th class="px-6 py-3">Amount</th>
                    <th class="px-6 py-3">Currency</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    @php $meta = $transaction->meta ?? []; @endphp
                    <tr class="border-t">
                        <td class="px-6 py-3 font-mono">{{ $transaction->tracking_number }}</td>
                        <td class="px-6 py-3">{{ $transaction->sender_name }}</td>
                        <td class="px-6 py-3">{{ $transaction->recipient_name }}</td>
                        <td class="px-6 py-3">{{ $meta['payment_mode'] ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $transaction->payment_amount ? ($symbol[$transaction->currency] ?? $transaction->currency . ' ') . number_format($transaction->payment_amount, 2) : '—' }}</td>
                        <td class="px-6 py-3">{{ $transaction->currency }}</td>
                        <td class="px-6 py-3">{{ ucfirst($transaction->payment_status ?? 'unpaid') }}</td>
                        <td class="px-6 py-3">{{ $transaction->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-6 py-8 text-center text-slate-500">No transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $transactions->links() }}</div>
@endsection
