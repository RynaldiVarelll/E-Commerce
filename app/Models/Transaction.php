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
    return $this->hasMany(TransactionItem::class);
}
    public static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $datePart = now()->format('Ymd');
            $countToday = self::whereDate('created_at', now()->toDateString())->count() + 1;
            $invoiceNumber = str_pad($countToday, 4, '0', STR_PAD_LEFT);
            $transaction->invoice_code = "INV-{$datePart}-{$invoiceNumber}";
        });
    }

}
