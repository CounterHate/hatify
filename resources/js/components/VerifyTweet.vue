<template>
    <div class="row">
        <div class="col-4">
            <hate-speech-definition></hate-speech-definition>
        </div>
        <div class="col-8">
            <tweet
                v-if="this.tweet != null"
                :data="this.tweet"
                :show_reasons="this.show_reasons"
                :show_topics="this.show_topics"
                @process_is_hate_speech="processIsHateSpeech"
                @process_not_sure="processNotSure"
            ></tweet>
            <tweet-anotation-buttons
                @is_hate_speech_pressed="handleIsHateSpeechPressed"
                @is_not_hate_speech_pressed="handleIsNotHateSpeechPressed"
                @skip_tweet_pressed="handleSkipTweetPressed"
                @not_sure_pressed="handleNotSurePressed"
            ></tweet-anotation-buttons>
        </div>
    </div>
</template>

<script>
import HateSpeechDefinition from './HateSpeechDefinition.vue'
import Tweet from './Tweet.vue'
import TweetAnotationButtons from './TweetAnotationButtons.vue'
export default {
    components: { HateSpeechDefinition, Tweet, TweetAnotationButtons },
    props: {
        user: Number
    },
    data () {
        return {
            tweet: null,
            show_topics: false,
            show_reasons: false
        }
    },
    methods: {
        // get tweet
        async getTweetToVerify () {
            await axios
                .get('/api/tweetToVerify')
                .then(response => {
                    if (response.data != '') this.tweet = response.data
                })
                .catch(error => console.log(error))
        },

        // handling anotation buttons events
        handleIsHateSpeechPressed () {
            this.show_reasons = false
            this.show_topics = true
        },
        async handleIsNotHateSpeechPressed () {
            var tweet = {
                tweet_id: this.tweet.tweet_id,
                author: this.tweet.author,
                content: this.tweet.content,
                date: this.tweet.date,
                topics: this.tweet.topics,
                verified: true,
                is_hate_speech: false,
                user_id: this.user
            }
            await axios
                .post('api/tweets', tweet)
                .then(response => console.log(response.data))
                .catch(error => console.error(error))
            await this.getTweetToVerify()

            this.show_reasons = false
            this.show_topics = false
        },
        async handleSkipTweetPressed () {
            await this.getTweetToVerify()
            this.show_topics = false
            this.show_reasons = false
        },
        handleNotSurePressed (data) {
            this.show_topics = false
            this.show_reasons = true
        },

        // processing tweet

        async processNotSure (data) {
            var tweet = {
                tweet_id: this.tweet.tweet_id,
                author: this.tweet.author,
                content: this.tweet.content,
                date: this.tweet.date,
                topics: this.tweet.topics,
                not_sure_reason: data.not_sure_reason,
                other_reason: data.other_reason,
                verified: true,
                is_hate_speech: false,
                user_id: this.user
            }
            console.log(tweet)
            axios
                .post('api/tweets/', tweet)
                .then(response => console.log(response.data))
                .catch(error => console.error(error))
            this.getTweetToVerify()
        },
        processIsHateSpeech (data) {
            // add new record
            var tweet = {
                tweet_id: this.tweet.tweet_id,
                author: this.tweet.author,
                content: this.tweet.content,
                date: this.tweet.date,
                topics: JSON.stringify(data.selected_topics),
                other_topic: data.other_topic,
                verified: true,
                is_hate_speech: true,
                user_id: this.user
            }
            axios
                .post('api/tweets', tweet)
                .then(response => console.log(response.data))
                .catch(error => console.error(error))

            // update old record
            var tweet = {
                tweet_id: this.tweet.tweet_id,
                author: this.tweet.author,
                content: this.tweet.content,
                date: this.tweet.date,
                topics: this.tweet.topics,
                verified: true,
                is_hate_speech: true,
                user_id: this.user
            }
            axios
                .patch('api/tweets/' + this.tweet.id, tweet)
                .then(response => console.log(response.data))
                .catch(error => console.error(error))
            this.show_reasons = false
            this.show_topics = false
            this.getTweetToVerify()
        }
    },
    async mounted () {
        await this.getTweetToVerify()
    }
}
</script>
<style></style>
