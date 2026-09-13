@php
$currencyOptions = ['USD' => 'USD ($)', 'EUR' => 'EUR (€)', 'GBP' => 'GBP (£)'];
$statusOptions = ['unpaid' => 'Unpaid', 'paid' => 'Paid', 'overdue' => 'Overdue'];
@endphp

@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="mb-3">
        <label class="block text-sm font-medium">Invoice Number</label>
        <input type="text" name="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" readonly class="w-full border rounded px-3 py-2 bg-slate-100">
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Link to Shipment (optional)</label>
        <select name="shipment_id" class="w-full border rounded px-3 py-2">
            <option value="">None</option>
            @foreach ($shipments as $shipment)
                <option value="{{ $shipment->id }}" @selected(old('shipment_id', $invoice->shipment_id) == $shipment->id)>{{ $shipment->tracking_number }} - {{ $shipment->recipient_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Client Name <span class="text-red-500">*</span></label>
        <input type="text" name="client_name" value="{{ old('client_name', $invoice->client_name) }}" class="w-full border rounded px-3 py-2" required>
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Client Email</label>
        <input type="email" name="client_email" value="{{ old('client_email', $invoice->client_email) }}" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Client Phone</label>
        <input type="text" name="client_phone" value="{{ old('client_phone', $invoice->client_phone) }}" class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Amount <span class="text-red-500">*</span></label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount', $invoice->amount) }}" class="w-full border rounded px-3 py-2" required>
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Currency <span class="text-red-500">*</span></label>
        <select name="currency" class="w-full border rounded px-3 py-2" required>
            @foreach ($currencyOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('currency', $invoice->currency ?? 'USD') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Status <span class="text-red-500">*</span></label>
        <select name="status" class="w-full border rounded px-3 py-2" required>
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $invoice->status ?? 'unpaid') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Invoice Date <span class="text-red-500">*</span></label>
        <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : now()->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2" required>
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Due Date</label>
        <input type="date" name="due_date" value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2">
    </div>
    <div class="md:col-span-2 mb-3">
        <label class="block text-sm font-medium">Client Address</label>
        <textarea name="client_address" rows="2" class="w-full border rounded px-3 py-2">{{ old('client_address', $invoice->client_address) }}</textarea>
    </div>
    <div class="md:col-span-2 mb-3">
        <label class="block text-sm font-medium">Description / Notes</label>
        <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description', $invoice->description) }}</textarea>
    </div>
</div>

<div class="mt-6">
    <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">Save</button>
    <a href="{{ route('admin.invoices.index') }}" class="ml-2 text-slate-600 hover:underline">Cancel</a>
</div>
