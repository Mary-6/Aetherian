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
        $shipment = new Shipment();
        return view('admin.shipments.create', compact('shipment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email|max:255',
            'sender_phone' => 'required|string|max:50',
            'sender_address' => 'required|string',
            'sender_city' => 'required|string|max:100',
            'sender_country' => 'required|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'required|email|max:255',
            'recipient_phone' => 'required|string|max:50',
            'recipient_address' => 'required|string',
            'recipient_city' => 'required|string|max:100',
            'recipient_country' => 'required|string|max:100',
            'origin' => 'nullable|string|max:100',
            'destination' => 'required|string|max:100',
            'carrier' => 'nullable|string|max:100',
            'weight' => 'required|numeric',
            'service' => 'required|string|in:AIR_FREIGHT,SEA_FREIGHT,ROAD_FREIGHT,EXPRESS,OVERNIGHT',
            'declared_value' => 'required|numeric',
            'payment_amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'pickup_date' => 'nullable|date',
            'departure_time' => 'nullable|date',
            'estimated_delivery_at' => 'nullable|date',
            'status' => 'required|string|in:PENDING,ON_HOLD,IN_TRANSIT,DELIVERED',
            'meta' => 'nullable|array',
        ]);

        $data['tracking_number'] = $this->generateTrackingNumber();
        $data['created_by'] = auth()->id();
        $data['carrier'] = $data['carrier'] ?: config('app.name');
        $data['currency'] = $data['currency'] ?: 'USD';
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
        return view('admin.shipments.edit', compact('shipment'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email|max:255',
            'sender_phone' => 'required|string|max:50',
            'sender_address' => 'required|string',
            'sender_city' => 'required|string|max:100',
            'sender_country' => 'required|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'required|email|max:255',
            'recipient_phone' => 'required|string|max:50',
            'recipient_address' => 'required|string',
            'recipient_city' => 'required|string|max:100',
            'recipient_country' => 'required|string|max:100',
            'origin' => 'nullable|string|max:100',
            'destination' => 'required|string|max:100',
            'carrier' => 'nullable|string|max:100',
            'weight' => 'required|numeric',
            'service' => 'required|string|in:AIR_FREIGHT,SEA_FREIGHT,ROAD_FREIGHT,EXPRESS,OVERNIGHT',
            'declared_value' => 'required|numeric',
            'payment_amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'pickup_date' => 'nullable|date',
            'departure_time' => 'nullable|date',
            'estimated_delivery_at' => 'nullable|date',
            'status' => 'required|string|in:PENDING,ON_HOLD,IN_TRANSIT,DELIVERED',
            'meta' => 'nullable|array',
        ]);

        $data['carrier'] = $data['carrier'] ?: config('app.name');
        $data['currency'] = $data['currency'] ?: 'USD';
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
