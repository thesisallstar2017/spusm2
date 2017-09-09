<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    const PENDING = 'Pending';
    const GENERATING = 'Generating';
    const READY = 'Ready';
    const FAILED = 'Failed';

    protected $fillable = [
        'type',
        'created_by_id',
        'status',
        'rows'
    ];
}
