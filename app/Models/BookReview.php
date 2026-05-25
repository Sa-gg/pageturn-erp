<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReview extends Model
{
    protected $fillable = ['book_id', 'customer_name', 'customer_email', 'rating', 'comment'];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
