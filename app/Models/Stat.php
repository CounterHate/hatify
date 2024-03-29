<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;

    protected $fillable = ['short_date', 'count', 'growth', 'entity', 'entity_value', 'author', 'hate_speech_category'];
}
