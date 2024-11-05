<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = [
        'imdbID', // Tambahkan ini
        'Title',
        'Year',
        'Poster',
        'Plot',
        'imdbRating',
        'Runtime',
        // Tambahkan field lainnya yang diperlukan
    ];
}
