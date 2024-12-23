<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payment_description', 'payment_receipt', 'user_id'
    ];

    // Relationship: Payment belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}