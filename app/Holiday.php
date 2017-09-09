<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    //
    protected $fillable = ['name', 'event_date'];

    protected $dates    = ['created_at', 'updated_at', 'event_date'];
}
