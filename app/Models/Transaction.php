<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'invoice_code',
    ];
    public function items()
{
    return $this->hasMany(TransactionItems::class);
}
    public static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $datePart = now()->format('Ymd');
            $randomPart = strtoupper(substr(uniqid(), -4));
            $transaction->invoice_code = "INV-{$datePart}-{$randomPart}";
        });

    }
     protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Scope untuk status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    public function deleteRecords()
    {
        $this->items()->delete();
        $this->delete();
    }

}
