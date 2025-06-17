<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $fillable = [
    'pname',
    'city',
];

public function books()
{
    return $this->hasMany(\App\Models\Book::class, 'pub_id');
}


}
