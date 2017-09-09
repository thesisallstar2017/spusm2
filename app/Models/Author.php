<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'authors';

    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function getAuthors()
    {
        return $this->orderBy('name', 'ASC')->lists('name', 'name')->toArray();
    }

}
