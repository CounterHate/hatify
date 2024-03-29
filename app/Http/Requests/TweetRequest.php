<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TweetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tweet_id' => ['required'],
            'author' => ['required'],
            'content' => ['required'],
            'date' => ['required'],
            'topics' => ['required'],
            'verified' => [],
            'not_sure_reason' => [],
            'other_reason' => [],
            'other_topic' => [],
            'is_hate_speech' => ['required'],
            'user_id' => ['required'],
            'is_tweet' => ['required'],
            'is_facebook_post' => ['required'],
            'is_facebook_comment' => ['required'],
            'fb_url' => [],
            'page_id' => [],
            'page_name' => []
        ];
    }
}
