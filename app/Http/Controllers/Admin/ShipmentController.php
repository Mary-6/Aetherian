<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Driver;
use App\Models\Setting;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with('branch', 'driver')->latest()->paginate(20);

        return view('admin.shipments.index', compact('shipments'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        $drivers = Driver::where('is_active', true)->get();

        return view('admin.shipments.create', compact('branches', 'drivers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'nullable|email|max:255',
            'sender_phone' => 'nullable|string|max:50',
            'sender_address' => 'nullable|string',
            'sender_country' => 'nullable|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_phone' => 'nullable|string|max:50',
            'recipient_address' => 'nullable|string',
            'recipient_country' => 'nullable|string|max:100',
            'origin' => 'nullable|string|max:100',
            'destination' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric',
            'dimensions' => 'nullable|string|max:100',
            'service' => 'nullable|string|max:50',
            'declared_value' => 'nullable|numeric',
            'shipping_cost' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'total_cost' => 'nullable|numeric',
            'payment_status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'branch_id' => 'nullable|exists:branches,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'status' => 'required|string|max:50',
            'meta' => 'nullable|array',
        ]);

        $data['tracking_number'] = $this->generateTrackingNumber();
        $data['created_by'] = auth()->id();
        $data['meta'] = $request->input('meta', []);

        $shipment = Shipment::create($data);

        $shipment->events()->create([
            'status' => $shipment->status,
            'location' => $shipment->origin,
            'description' => 'Shipment created.',
            'occurred_at' => now(),
        ]);

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment created.');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load('events', 'branch', 'driver');

        return view('admin.shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $branches = Branch::where('is_active', true)->get();
        $drivers = Driver::where('is_active', true)->get();

        return view('admin.shipments.edit', compact('shipment', 'branches', 'drivers'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'nullable|email|max:255',
            'sender_phone' => 'nullable|string|max:50',
            'sender_address' => 'nullable|string',
            'sender_country' => 'nullable|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_phone' => 'nullable|string|max:50',
            'recipient_address' => 'nullable|string',
            'recipient_country' => 'nullable|string|max:100',
            'origin' => 'nullable|string|max:100',
            'destination' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric',
            'dimensions' => 'nullable|string|max:100',
            'service' => 'nullable|string|max:50',
            'declared_value' => 'nullable|numeric',
            'shipping_cost' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'total_cost' => 'nullable|numeric',
            'payment_status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'branch_id' => 'nullable|exists:branches,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'status' => 'required|string|max:50',
            'meta' => 'nullable|array',
        ]);

        $data['meta'] = $request->input('meta', []);
        if (! $data['meta'] && $shipment->meta) {
            $data['meta'] = $shipment->meta;
        }

        $oldStatus = $shipment->status;
        $shipment->update($data);

        if ($shipment->wasChanged('status') || $oldStatus !== $data['status']) {
            $shipment->events()->create([
                'status' => $data['status'],
                'location' => $shipment->origin,
                'description' => 'Shipment status updated to ' . $data['status'] . '.',
                'occurred_at' => now(),
            ]);
        }

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment updated.');
    }

    public function invoice(Shipment $shipment)
    {
        $shipment->load('events', 'branch', 'driver', 'creator');

        $company = [
            'name' => config('app.name'),
            'logo' => asset('brand-logo.png'),
            'email' => Setting::get('company_email', config('mail.from.address', 'support@aetheriancargo.com')),
            'phone' => Setting::get('company_phone', '1-800-AETHER'),
            'address' => Setting::get('company_address', 'Aetherian Cargo HQ'),
        ];

        return view('admin.shipments.invoice', compact('shipment', 'company'));
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return back()->with('success', 'Shipment deleted.');
    }

    private function generateTrackingNumber(): string
    {
        return 'AC' . strtoupper(Str::random(8));
    }
}
