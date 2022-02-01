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
                @not_sure="handleNotSure"
            ></tweet>
            <div v-else>
                Braków tweetów do weryfikacji
            </div>
        </div>
    </div>
</template>

<script>
import HateSpeechDefinition from './HateSpeechDefinition.vue'
import Tweet from './Tweet.vue'

export default {
    components: { HateSpeechDefinition, Tweet },
    props: {
        user: Number
    },
    data () {
        return {
            tweet: null
        }
    },
    methods: {
        async getTweetToVerify () {
            await axios
                .get('/api/tweetToVerify')
                .then(response => {
                    if (response.data != '') this.tweet = response.data
                })
                .catch(error => console.log(error))
        },
        async handleNotSure (data) {
            var tweet = {
                tweet_id: this.tweet.tweet_id,
                author: this.tweet.author,
                content: this.tweet.content,
                date: this.tweet.date,
                topics: JSON.stringify({
                    user: null,
                    topics: null
                }),
                not_sure_reason: data.not_sure_reason,
                other_reason: data.other_reason
            }
            console.log(tweet)
            axios
                .patch('api/tweets/' + this.tweet.id, tweet)
                .then(response => console.log(response.data))
                .catch(error => console.error(error))
            this.getTweetToVerify()
        },
        handleProcessTweet (data) {
            if (data.is_hate_speech) {
                // add new record
                var tweet = {
                    tweet_id: data.tweet.tweet_id,
                    author: data.tweet.author,
                    content: data.tweet.content,
                    date: data.tweet.date,
                    topics: JSON.stringify({
                        user: this.user,
                        topics: data.topics
                    }),
                    verified: true
                }
                axios
                    .post('api/tweets', tweet)
                    .then(response => console.log(response.data))
                    .catch(error => console.error(error))
            }
            // update old record
            var tweet = {
                tweet_id: this.tweet.tweet_id,
                author: this.tweet.author,
                content: this.tweet.content,
                date: this.tweet.date,
                topics: JSON.stringify({
                    user: this.user,
                    topics: this.tweet.topics
                }),
                verified: true
            }
            axios
                .patch('api/tweets/' + this.tweet.id, tweet)
                .then(response => console.log(response.data))
                .catch(error => console.error(error))
            this.getTweetToVerify()
        },
        async handleSkipTweet () {
            await this.getTweetToVerify()
        }
    },
    async mounted () {
        await this.getTweetToVerify()
    }
}
</script>

<style></style>
