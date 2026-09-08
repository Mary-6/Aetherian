@php
$meta = old('meta', $shipment->meta ?? []);
$countryOptions = [
    'United States', 'United Kingdom', 'Canada', 'Australia', 'Germany',
    'France', 'Netherlands', 'China', 'Japan', 'India', 'Brazil', 'Mexico',
    'South Africa', 'Nigeria', 'United Arab Emirates',
];
$shipmentModes = [
    'AIR_FREIGHT' => 'Air Freight',
    'SEA_FREIGHT' => 'Sea Freight',
    'ROAD_FREIGHT' => 'Road Freight',
    'EXPRESS' => 'Express',
    'OVERNIGHT' => 'Overnight',
];
$paymentModes = ['Cash', 'Card', 'Bank transfer', 'PayPal', 'Cash App', 'Zelle', 'Apple Gift Card', 'Gift Card'];
$currencyOptions = ['USD' => 'USD ($)', 'EUR' => 'EUR (€)', 'GBP' => 'GBP (£)'];
$statusOptions = ['PENDING' => 'Pending', 'ON_HOLD' => 'On Hold', 'IN_TRANSIT' => 'In Transit', 'DELIVERED' => 'Delivered'];
@endphp

@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <h3 class="font-bold mb-3 text-navy">Shipper Information</h3>
        <div class="mb-3"><label class="block text-sm font-medium">Name <span class="text-red-500">*</span></label><input type="text" name="sender_name" value="{{ old('sender_name', $shipment->sender_name ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Email <span class="text-red-500">*</span></label><input type="email" name="sender_email" value="{{ old('sender_email', $shipment->sender_email ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Phone <span class="text-red-500">*</span></label><input type="text" name="sender_phone" value="{{ old('sender_phone', $shipment->sender_phone ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Address <span class="text-red-500">*</span></label><input type="text" name="sender_address" value="{{ old('sender_address', $shipment->sender_address ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">City <span class="text-red-500">*</span></label><input type="text" name="sender_city" value="{{ old('sender_city', $shipment->sender_city ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Country <span class="text-red-500">*</span></label>
            <select name="sender_country" class="w-full border rounded px-3 py-2" required>
                <option value="">Select country</option>
                @foreach ($countryOptions as $c)
                    <option value="{{ $c }}" @selected(old('sender_country', $shipment->sender_country ?? '') === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <h3 class="font-bold mb-3 text-navy">Receiver Information</h3>
        <div class="mb-3"><label class="block text-sm font-medium">Name <span class="text-red-500">*</span></label><input type="text" name="recipient_name" value="{{ old('recipient_name', $shipment->recipient_name ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Email <span class="text-red-500">*</span></label><input type="email" name="recipient_email" value="{{ old('recipient_email', $shipment->recipient_email ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Phone <span class="text-red-500">*</span></label><input type="text" name="recipient_phone" value="{{ old('recipient_phone', $shipment->recipient_phone ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Address <span class="text-red-500">*</span></label><input type="text" name="recipient_address" value="{{ old('recipient_address', $shipment->recipient_address ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">City <span class="text-red-500">*</span></label><input type="text" name="recipient_city" value="{{ old('recipient_city', $shipment->recipient_city ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Country <span class="text-red-500">*</span></label>
            <select name="recipient_country" class="w-full border rounded px-3 py-2" required>
                <option value="">Select country</option>
                @foreach ($countryOptions as $c)
                    <option value="{{ $c }}" @selected(old('recipient_country', $shipment->recipient_country ?? '') === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="mt-8">
    <h3 class="font-bold mb-3 text-navy">Shipment Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="mb-3"><label class="block text-sm font-medium">Origin</label><input type="text" name="origin" value="{{ old('origin', $shipment->origin ?? '') }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Destination <span class="text-red-500">*</span></label><input type="text" name="destination" value="{{ old('destination', $shipment->destination ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Carrier</label><input type="text" name="carrier" value="{{ old('carrier', $shipment->carrier ?? config('app.name')) }}" readonly class="w-full border rounded px-3 py-2 bg-slate-100"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Type of Shipment</label><input type="text" name="meta[shipment_type]" value="{{ $meta['shipment_type'] ?? '' }}" placeholder="e.g. Van Move" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Shipment Mode <span class="text-red-500">*</span></label>
            <select name="service" class="w-full border rounded px-3 py-2" required>
                <option value="">Select shipment mode</option>
                @foreach ($shipmentModes as $value => $label)
                    <option value="{{ $value }}" @selected(old('service', $shipment->service ?? 'AIR_FREIGHT') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label class="block text-sm font-medium">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                @foreach ($statusOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $shipment->status ?? 'PENDING') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label class="block text-sm font-medium">Package</label><input type="text" name="meta[package_type]" value="{{ $meta['package_type'] ?? '' }}" placeholder="e.g. Pet" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Product</label><input type="text" name="meta[product]" value="{{ $meta['product'] ?? '' }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Quantity</label><input type="number" min="1" name="meta[quantity]" value="{{ $meta['quantity'] ?? '' }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Carrier Reference No.</label><input type="text" name="meta[carrier_reference]" value="{{ $meta['carrier_reference'] ?? '' }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Payment Mode</label>
            <select name="meta[payment_mode]" class="w-full border rounded px-3 py-2">
                @foreach ($paymentModes as $m)
                    <option value="{{ $m }}" @selected(($meta['payment_mode'] ?? 'Cash') === $m)>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label class="block text-sm font-medium">Total Freight</label><input type="text" name="meta[total_freight]" value="{{ $meta['total_freight'] ?? '' }}" placeholder="e.g. 5hrs drive" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Amount Due</label><input type="number" step="0.01" name="payment_amount" value="{{ old('payment_amount', $shipment->payment_amount ?? '') }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Currency</label>
            <select name="currency" class="w-full border rounded px-3 py-2">
                @foreach ($currencyOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('currency', $shipment->currency ?? 'USD') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label class="block text-sm font-medium">Expected Delivery Date</label><input type="date" name="estimated_delivery_at" value="{{ old('estimated_delivery_at', isset($shipment) && $shipment->estimated_delivery_at ? $shipment->estimated_delivery_at->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Departure Time</label><input type="datetime-local" name="departure_time" value="{{ old('departure_time', isset($shipment) && $shipment->departure_time ? $shipment->departure_time->format('Y-m-d\\TH:i') : '') }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Pick-up Time</label><input type="time" name="meta[pickup_time]" value="{{ $meta['pickup_time'] ?? '' }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Pick-up Date</label><input type="date" name="pickup_date" value="{{ old('pickup_date', isset($shipment) && $shipment->pickup_date ? $shipment->pickup_date->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2"></div>
    </div>
    <div class="mt-4"><label class="block text-sm font-medium">Comments</label><textarea name="meta[comments]" rows="3" class="w-full border rounded px-3 py-2">{{ $meta['comments'] ?? '' }}</textarea></div>
</div>

<div class="mt-8">
    <h3 class="font-bold mb-3 text-navy">Package</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="mb-3"><label class="block text-sm font-medium">Piece Type</label><input type="text" name="meta[piece_type]" value="{{ $meta['piece_type'] ?? '' }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Weight (kg) <span class="text-red-500">*</span></label><input type="number" step="0.01" name="weight" value="{{ old('weight', $shipment->weight ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Declared Value <span class="text-red-500">*</span></label><input type="number" step="0.01" name="declared_value" value="{{ old('declared_value', $shipment->declared_value ?? '') }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label class="block text-sm font-medium">Length (cm)</label><input type="number" step="0.01" name="meta[length_cm]" value="{{ $meta['length_cm'] ?? '' }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Width (cm)</label><input type="number" step="0.01" name="meta[width_cm]" value="{{ $meta['width_cm'] ?? '' }}" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Height (cm)</label><input type="number" step="0.01" name="meta[height_cm]" value="{{ $meta['height_cm'] ?? '' }}" class="w-full border rounded px-3 py-2"></div>
    </div>
</div>

<div class="mt-6">
    <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">Save</button>
    <a href="{{ route('admin.shipments.index') }}" class="ml-2 text-slate-600 hover:underline">Cancel</a>
</div>
