<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\User;
use App\Models\ContactMessage;
use App\Models\SupportTicket;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $statusCounts = Shipment::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $serviceCounts = Shipment::query()
            ->selectRaw('service, count(*) as total')
            ->groupBy('service')
            ->pluck('total', 'service');

        $totalShipments = Shipment::count();
        $totalClients = User::count();
        $totalMessages = ContactMessage::count();
        $totalTickets = SupportTicket::count();
        $totalChats = ChatRoom::count();

        $recentShipments = Shipment::with('creator')->latest()->limit(10)->get();

        return view('admin.reports.index', compact(
            'statusCounts',
            'serviceCounts',
            'totalShipments',
            'totalClients',
            'totalMessages',
            'totalTickets',
            'totalChats',
            'recentShipments'
        ));
    }
}
