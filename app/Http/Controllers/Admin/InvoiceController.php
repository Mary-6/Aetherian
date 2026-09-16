<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('shipment')->latest()->paginate(20);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $invoice = new Invoice();
        $invoice->invoice_number = $this->generateInvoiceNumber();
        $invoice->invoice_date = now()->format('Y-m-d');

        $shipments = Shipment::latest()->get();

        return view('admin.invoices.create', compact('invoice', 'shipments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'shipment_id' => 'nullable|exists:shipments,id',
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_address' => 'nullable|string',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:paid,unpaid,overdue',
        ]);

        $data['created_by'] = auth()->id();
        $data['invoice_number'] = $request->input('invoice_number') ?: $this->generateInvoiceNumber();

        $invoice = Invoice::create($data);

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('shipment', 'creator');

        $company = [
            'name' => Setting::get('company_name', config('app.name')),
            'logo' => asset('brand-logo.png'),
            'email' => Setting::get('company_email', config('mail.from.address', 'aetheriancargo@gmail.com')),
            'phone' => Setting::get('company_phone', '+1 (423) 277-8587'),
            'address' => Setting::get('company_address', 'Aetherian Cargo HQ'),
        ];

        return view('admin.invoices.show', compact('invoice', 'company'));
    }

    public function edit(Invoice $invoice)
    {
        $shipments = Shipment::latest()->get();

        return view('admin.invoices.edit', compact('invoice', 'shipments'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'shipment_id' => 'nullable|exists:shipments,id',
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_address' => 'nullable|string',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:paid,unpaid,overdue',
        ]);

        $data['invoice_number'] = $request->input('invoice_number') ?: $invoice->invoice_number;

        $invoice->update($data);

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice updated.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return back()->with('success', 'Invoice deleted.');
    }

    private function generateInvoiceNumber(): string
    {
        return 'INV-' . strtoupper(Str::random(8));
    }
}
