<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; padding: 24px; }
        h2 { color: #111; margin-top: 0; }
        .field { margin-bottom: 12px; }
        .label { font-weight: bold; color: #666; }
        .message { background: #f9f9f9; padding: 16px; border-radius: 6px; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="container">
        <h2>New contact form message</h2>
        <div class="field"><span class="label">Name:</span> {{ $contact->name }}</div>
        <div class="field"><span class="label">Email:</span> {{ $contact->email }}</div>
        @if($contact->phone)
        <div class="field"><span class="label">Phone:</span> {{ $contact->phone }}</div>
        @endif
        @if($contact->subject)
        <div class="field"><span class="label">Subject:</span> {{ $contact->subject }}</div>
        @endif
        <div class="field"><span class="label">Message:</span></div>
        <div class="message">{{ $contact->message }}</div>
        <p style="margin-top: 20px; font-size: 12px; color: #999;">
            Received from the Aetherian Cargo contact form.
        </p>
    </div>
</body>
</html>
