<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = ['tweet_id', 'author', 'content', 'date', 'topics', 'verified', 'not_sure_reason','other_reason'];
}
