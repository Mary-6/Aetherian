@extends('layouts.admin')

@section('title', 'Account Profile')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Account Profile</h1>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 border border-green-200 rounded p-4 mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-bold mb-4">Profile Information</h2>
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded px-3 py-2">
                </div>
                <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Update Profile</button>
            </form>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-bold mb-4">Change Password</h2>
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Current Password</label>
                    <input type="password" name="current_password" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">New Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
                </div>
                <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Update Password</button>
            </form>
        </div>
    </div>
@endsection
