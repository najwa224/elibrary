<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'type', 'price', 'pub_id', 'author_id'];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'pub_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }



}
