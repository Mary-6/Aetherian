<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Driver;
use App\Models\Setting;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Mail\ShipmentCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $shipment->meta = ['carrier_reference' => $this->generateCarrierReference()];

        return view('admin.shipments.create', compact('shipment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'nullable|email|max:255',
            'sender_phone' => 'nullable|string|max:50',
            'sender_address' => 'nullable|string',
            'sender_city' => 'nullable|string|max:100',
            'sender_country' => 'nullable|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_phone' => 'nullable|string|max:50',
            'recipient_address' => 'nullable|string',
            'recipient_city' => 'nullable|string|max:100',
            'recipient_country' => 'nullable|string|max:100',
            'origin' => 'nullable|string|max:100',
            'destination' => 'nullable|string|max:100',
            'carrier' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric',
            'service' => 'nullable|string|in:AIR_FREIGHT,SEA_FREIGHT,ROAD_FREIGHT,EXPRESS,OVERNIGHT',
            'declared_value' => 'nullable|numeric',
            'pickup_date' => 'nullable|date',
            'departure_time' => 'nullable|date',
            'estimated_delivery_at' => 'nullable|date',
            'status' => 'nullable|string|in:PENDING,ON_HOLD,IN_TRANSIT,DELIVERED',
            'meta' => 'nullable|array',
        ]);

        $data['tracking_number'] = $this->generateTrackingNumber();
        $data['created_by'] = auth()->id();
        $data['carrier'] = ($data['carrier'] ?? null) ?: config('app.name');
        $data['service'] = ($data['service'] ?? null) ?: 'AIR_FREIGHT';
        $data['status'] = ($data['status'] ?? null) ?: 'PENDING';
        $data['meta'] = $request->input('meta', []);
        $data['meta']['carrier_reference'] = ($data['meta']['carrier_reference'] ?? null) ?: $this->generateCarrierReference();

        $shipment = Shipment::create($data);

        $shipment->events()->create([
            'status' => $shipment->status,
            'location' => $shipment->origin,
            'description' => 'Shipment created.',
            'occurred_at' => now(),
        ]);

        $this->sendShipmentCreatedEmail($shipment);

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
            'sender_email' => 'nullable|email|max:255',
            'sender_phone' => 'nullable|string|max:50',
            'sender_address' => 'nullable|string',
            'sender_city' => 'nullable|string|max:100',
            'sender_country' => 'nullable|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_phone' => 'nullable|string|max:50',
            'recipient_address' => 'nullable|string',
            'recipient_city' => 'nullable|string|max:100',
            'recipient_country' => 'nullable|string|max:100',
            'origin' => 'nullable|string|max:100',
            'destination' => 'nullable|string|max:100',
            'carrier' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric',
            'service' => 'nullable|string|in:AIR_FREIGHT,SEA_FREIGHT,ROAD_FREIGHT,EXPRESS,OVERNIGHT',
            'declared_value' => 'nullable|numeric',
            'pickup_date' => 'nullable|date',
            'departure_time' => 'nullable|date',
            'estimated_delivery_at' => 'nullable|date',
            'status' => 'nullable|string|in:PENDING,ON_HOLD,IN_TRANSIT,DELIVERED',
            'meta' => 'nullable|array',
        ]);

        $data['carrier'] = ($data['carrier'] ?? null) ?: config('app.name');
        $data['service'] = ($data['service'] ?? null) ?: $shipment->service;
        $data['status'] = ($data['status'] ?? null) ?: $shipment->status;
        $data['meta'] = $request->input('meta', []);
        if (! $data['meta'] && $shipment->meta) {
            $data['meta'] = $shipment->meta;
        }
        if (empty($data['meta']['carrier_reference'])) {
            $data['meta']['carrier_reference'] = $shipment->meta['carrier_reference'] ?? $this->generateCarrierReference();
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
            'email' => Setting::get('company_email', config('mail.from.address', 'aetheriancargo@gmail.com')),
            'phone' => Setting::get('company_phone', '+1 (423) 277-8587'),
            'address' => Setting::get('company_address', 'Aetherian Cargo HQ'),
        ];

        return view('admin.shipments.invoice', compact('shipment', 'company'));
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return back()->with('success', 'Shipment deleted.');
    }

    private function sendShipmentCreatedEmail(Shipment $shipment): void
    {
        $recipients = collect([
            [$shipment->sender_email, $shipment->sender_name],
            [$shipment->recipient_email, $shipment->recipient_name],
        ])
            ->filter(fn ($r) => filled($r[0]))
            ->map(fn ($r) => [strtolower(trim($r[0])), $r[1]])
            ->unique(fn ($r) => $r[0]);

        foreach ($recipients as [$email, $name]) {
            try {
                Mail::to($email)->send(new ShipmentCreated($shipment, $name));
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }

    private function generateTrackingNumber(): string
    {
        return 'AC' . strtoupper(Str::random(8));
    }

    private function generateCarrierReference(): string
    {
        return 'CARGO-' . strtoupper(Str::random(6));
    }
}
