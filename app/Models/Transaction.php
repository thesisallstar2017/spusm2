<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    CONST RESERVED = 'reserved';
    CONST BORROWED = 'borrowed';
    CONST RETURNED = 'returned';

    protected $table = 'transaction';

    protected $fillable = ['book_id', 'user_id',
      'type',
      'status',
      'reserved_at',
      'borrowed_at',
      'is_lost',
      'is_expired',
      'expired_at',
      'is_damaged',
      'is_rejected'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

}
