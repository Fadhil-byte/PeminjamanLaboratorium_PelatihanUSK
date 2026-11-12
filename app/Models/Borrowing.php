<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_code',
        'user_id',
        'equipment_id',
        'amount',
        'borrow_date',
        'return_date',
        'purpose',
        'supervisor',
        'status',
        'is_damaged',
        'damage_description',
        'damage_photo',
        'damage_reported_at',
        'rejection_reason',
        'approved_at',
        'returned_at',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
        'damage_reported_at' => 'datetime',
        'is_damaged' => 'boolean',
        'amount' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}

