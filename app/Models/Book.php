<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = ['title',
      'barcode',
      'publisher',
      'published_year',
      'card_number',
      'call_number',
      'quantity',
      'material_id',
      'publish_place',
      'isbn',
      'etal',
      'edition',
      'physical_desc',
      'aetitle',
      'stitle',
      'book_level',
      'editor',
      'illustrator',
      'compiler',
      'note',
      'available_quantity'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function generateBarcode()
    {
        $barcode = '';
        while (true) {
            $barcode = date('Ymd').sprintf("%06d", mt_rand(1, 999999));

            try {
                $book = $this->where('barcode', '=', $barcode)->firstOrFail();

            } catch(ModelNotFoundException $e) {
                break;
            }
        }

        return $barcode;
    }

    public function getSubjectListAttribute()
    {
        return $this->subjects->lists('id');
    }

}
