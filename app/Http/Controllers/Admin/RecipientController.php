<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::query()
            ->selectRaw('MAX(id) as id, recipient_name, recipient_email, recipient_phone, recipient_address, recipient_city, recipient_country')
            ->groupBy('recipient_email', 'recipient_name', 'recipient_phone', 'recipient_address', 'recipient_city', 'recipient_country')
            ->orderBy('recipient_name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('recipient_name', 'like', "%{$search}%")
                  ->orWhere('recipient_email', 'like', "%{$search}%")
                  ->orWhere('recipient_phone', 'like', "%{$search}%");
            });
        }

        $recipients = $query->paginate(20);

        return view('admin.recipients.index', compact('recipients'));
    }
}
