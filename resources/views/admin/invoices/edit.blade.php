@extends('layouts.admin')

@section('title', 'Edit Invoice')

@section('content')
    <div class="bg-white p-6 rounded shadow max-w-4xl">
        <h1 class="text-2xl font-bold mb-4">Edit Invoice</h1>
        <form action="{{ route('admin.invoices.update', $invoice) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.invoices._form')
        </form>
    </div>
@endsection
