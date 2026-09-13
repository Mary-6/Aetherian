@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <div class="bg-white p-6 rounded shadow max-w-2xl">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium">Company Name</label>
                <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Company Email</label>
                <input type="email" name="company_email" value="{{ old('company_email', $settings['company_email']) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Company Phone</label>
                <input type="text" name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Company Address</label>
                <textarea name="company_address" class="w-full border rounded px-3 py-2">{{ old('company_address', $settings['company_address']) }}</textarea>
            </div>
            <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">Save Settings</button>
        </form>
    </div>
@endsection
