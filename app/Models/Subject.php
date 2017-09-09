<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function getSubjects($id)
    {
        return $this->find($id)->orderBy('name', 'ASC')->lists('name', 'id');
    }
}
