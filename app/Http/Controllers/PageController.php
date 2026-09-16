<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ContactMessageReceived;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function services()
    {
        return view('pages.services');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = ContactMessage::create($data);

        try {
            Mail::send(new ContactMessageReceived($contact));
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('success', 'Thank you for contacting us. We will reply shortly.');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function cookies()
    {
        return view('pages.cookies');
    }

    public function support()
    {
        return view('pages.support');
    }

    public function supportStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'tracking_number' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $data['ticket_number'] = 'SUP-' . Str::upper(Str::random(8));

        SupportTicket::create($data);

        return back()->with('success', 'Your support ticket has been created. We will contact you soon.');
    }

    public function testimonials()
    {
        return view('pages.testimonials');
    }
}
