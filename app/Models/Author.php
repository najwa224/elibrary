<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'fname',
        'lname',
        'country',
        'city',
        'address',
    ];

    public function books()
{
    return $this->hasMany(\App\Models\Book::class, 'author_id');
}

}
