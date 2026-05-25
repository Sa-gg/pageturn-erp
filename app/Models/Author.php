<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name', 'bio', 'photo_url'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
