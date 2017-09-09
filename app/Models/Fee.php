<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $table = 'fees';

    protected $fillable = ['transaction_id', 'user_id', 'type', 'amount', 'is_paid', 'receipt_no', 'overdue_day_counts', 'with_change', 'change'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
