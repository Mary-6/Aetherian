@extends('layouts.admin')

@section('title', 'Create Invoice')

@section('content')
    <div class="bg-white p-6 rounded shadow max-w-4xl">
        <h1 class="text-2xl font-bold mb-4">Create Invoice for Client</h1>
        <form action="{{ route('admin.invoices.store') }}" method="POST">
            @include('admin.invoices._form')
        </form>
    </div>
@endsection
