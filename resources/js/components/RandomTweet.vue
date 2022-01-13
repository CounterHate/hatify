<template>
    <div class="row">
        <div class="col">
            <hate-speech-definition></hate-speech-definition>
        </div>
        <div class="col">
            <tweet
                v-if="this.tweet != null"
                :random="true"
                :data="this.tweet"
                @process_tweet="handleProcessTweet"
                @skip_tweet="handleSkipTweet"
            ></tweet>
        </div>
    </div>
</template>

<script>
import Tweet from './Tweet.vue'
import { getRandomTweets } from '../es.js'
import HateSpeechDefinition from './HateSpeechDefinition.vue'
export default {
    components: { Tweet, HateSpeechDefinition },
    props: {
        user: Number
    },
    data () {
        return {
            is_loading: false,
            tweet: null,
            url: process.env.MIX_ES,
            index: process.env.MIX_INDEX,
            auth: {
                username: process.env.MIX_ES_USER,
                password: process.env.MIX_ES_PASS
            }
        }
    },
    async mounted () {
        await getRandomTweets(1, this.url + '/' + this.index, this.auth).then(
            result => (this.tweet = result[0])
        )
    },
    methods: {
        getRandomTweets,
        async handleSkipTweet () {
            await getRandomTweets(1, this.url, this.auth).then(
                result => (this.tweet = result[0])
            )
        },
        async handleProcessTweet (data) {
            if (data.is_hate_speech) {
                var tweet = {
                    tweet_id: data.tweet.tweet_id,
                    author: data.tweet.author_username,
                    content: data.tweet.content,
                    date: (data.tweet.posted_utime * 1000).toString(),
                    topics: JSON.stringify({
                        user: this.user,
                        topics: data.topics
                    })
                }
                axios
                    .post('api/tweets', tweet)
                    .then(response => console.log(response.data))
                    .catch(error => console.error(error))
            }

            await getRandomTweets(1, this.url, this.auth).then(
                result => (this.tweet = result[0])
            )
        }
    }
}
</script>

<style></style>
