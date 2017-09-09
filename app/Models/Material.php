<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';

    protected $fillable = ['name'];

    public function getMaterials()
    {
        return $this->orderBy('name', 'ASC')->lists('name', 'id')->toArray();
    }
}
