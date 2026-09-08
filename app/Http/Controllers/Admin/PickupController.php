<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::query()
            ->whereIn('status', ['PENDING', 'ON_HOLD', 'IN_TRANSIT'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('sender_name', 'like', "%{$search}%")
                  ->orWhere('origin', 'like', "%{$search}%");
            });
        }

        $pickups = $query->paginate(20);

        return view('admin.pickups.index', compact('pickups'));
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $request->validate([
            'status' => 'required|string|in:PENDING,PICKED_UP,OUT_FOR_DELIVERY,DELIVERED',
        ]);

        $selected = $request->input('status');
        $newStatus = match ($selected) {
            'PICKED_UP', 'OUT_FOR_DELIVERY' => 'IN_TRANSIT',
            'DELIVERED' => 'DELIVERED',
            default => 'PENDING',
        };

        $oldStatus = $shipment->status;
        $meta = $shipment->meta ?? [];
        $meta['pickup_status'] = $selected;
        $shipment->meta = $meta;
        $shipment->status = $newStatus;
        $shipment->save();

        if ($oldStatus !== $newStatus || ($shipment->getChanges()['status'] ?? null)) {
            $shipment->events()->create([
                'status' => $newStatus,
                'location' => $shipment->origin,
                'description' => 'Pickup status updated to ' . $selected . '.',
                'occurred_at' => now(),
            ]);
        }

        return back()->with('success', 'Pickup status updated.');
    }
}
