<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'created_by',
        'shipment_id',
        'client_name',
        'client_email',
        'client_phone',
        'client_address',
        'description',
        'amount',
        'currency',
        'invoice_date',
        'due_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
