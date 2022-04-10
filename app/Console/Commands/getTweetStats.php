<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tweet;
use Atymic\Twitter\Twitter as TwitterContract;
use Atymic\Twitter\Facade\Twitter;

class getTweetStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets tweets stats';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tweets = Tweet::inRandomOrder()->where('is_tweet', 1)->limit(100)->select('tweet_id')->get();
        $params = [
            'place.fields' => '',
            'tweet.fields' => 'public_metrics',
            'expansions' => '',
            TwitterContract::KEY_RESPONSE_FORMAT => TwitterContract::RESPONSE_FORMAT_JSON,
        ];

        $tweet_ids = [];
        foreach ($tweets as $t) {
            array_push($tweet_ids, $t->tweet_id);
        }
        $data = json_decode(Twitter::forApiV2()->getTweets($tweet_ids, $params))->data;
        foreach ($data as $d) {
            $t = Tweet::where('tweet_id', $d->id)->first();
            $t->reply_count = $d->public_metrics->reply_count;
            $t->retweet_count = $d->public_metrics->retweet_count;
            $t->like_count = $d->public_metrics->like_count;
            // $t->quote_count = 17;
            $t->quote_count = $d->public_metrics->quote_count;
            $t->save();
        }
        return Command::SUCCESS;
    }
}
