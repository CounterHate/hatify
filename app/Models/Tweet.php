<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = ['tweet_id', 'author', 'content', 'date', 'topics', 'verified', 'not_sure_reason', 'other_reason', 'other_topic', 'is_hate_speech', 'user_id', 'is_tweet', 'is_facebook_post', 'is_facebook_comment', 'fb_url', 'page_id', 'page_name'];
}
