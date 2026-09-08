<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ConsolidatedController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::query()->latest();

        if ($request->filled('shipment_type')) {
            $query->where('meta->shipment_type', $request->input('shipment_type'));
        }

        $shipments = $query->paginate(20);

        return view('admin.consolidated.index', compact('shipments'));
    }
}
