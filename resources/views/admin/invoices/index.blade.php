@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Invoices</h1>
        <a href="{{ route('admin.invoices.create') }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Create Invoice</a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3">Invoice #</th>
                    <th class="px-6 py-3">Client</th>
                    <th class="px-6 py-3">Amount</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoices as $invoice)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-3">{{ $invoice->client_name }}</td>
                        <td class="px-6 py-3">{{ $invoice->currency }} {{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-6 py-3"><span class="px-2 py-1 rounded text-xs font-bold {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : ($invoice->status === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($invoice->status) }}</span></td>
                        <td class="px-6 py-3">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                        <td class="px-6 py-3 space-x-2">
                            <a href="{{ route('admin.invoices.show', $invoice) }}" target="_blank" class="text-green-600 hover:underline">View</a>
                            <a href="{{ route('admin.invoices.edit', $invoice) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center">No invoices found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $invoices->links() }}</div>
@endsection
