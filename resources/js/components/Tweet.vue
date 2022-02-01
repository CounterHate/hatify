<template>
    <div v-if="this.is_hate_speech == null || random">
        <br />
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" v-if="data.author_username">
                    {{ data.author_username }}
                </h5>
                <h5 class="card-title" v-else>
                    {{ data.author }}
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    <div v-if="data.posted_utime">
                        {{ new Date(data.posted_utime * 1000) }}
                    </div>
                    <div v-else>{{ new Date(parseInt(data.date)) }}</div>
                </h6>
                <p>{{ data.content }}</p>
                <a
                    :href="
                        'https://twitter.com/' +
                            data.author_username +
                            '/status/' +
                            data.tweet_id
                    "
                    class="btn btn-primary"
                    target="_blank"
                    >Zobacz tweet</a
                >
                <topic-select
                    :topics="this.topics"
                    :show="show_topics"
                    @process_tweet_hate_speech="processTweet"
                ></topic-select>
                <not-sure-select
                    :show="show_not_sure"
                    @process_not_sure="processNotSure"
                ></not-sure-select>
            </div>
        </div>
        <tweet-anotation-buttons
            @is_hate_speech_pressed="showTopics"
            @skip_tweet_pressed="skipTweet"
            @not_sure_pressed="notSure"
        ></tweet-anotation-buttons>
    </div>
</template>

<script>
import TweetAnotationButtons from './TweetAnotationButtons.vue'
import TopicSelect from './TopicSelect.vue'
import NotSureSelect from './NotSureSelect.vue'

export default {
    props: {
        data: Object,
        random: Boolean
    },
    components: {
        TweetAnotationButtons,
        TopicSelect,
        NotSureSelect
    },
    data () {
        return {
            url: process.env.MIX_ES,
            index: process.env.MIX_INDEX,
            auth: {
                username: process.env.MIX_ES_USER,
                password: process.env.MIX_ES_PASS
            },
            is_hate_speech: null,
            topics: [],
            show_topics: false,
            show_not_sure: false
        }
    },
    emits: ['process_tweet', 'skip_tweet'],
    async mounted () {
        await this.getTopics()
    },

    methods: {
        renderNewTweet () {
            this.is_hate_speech = false
        },
        skipTweet () {
            this.$emit('skip_tweet', {})
        },
        async getTopics () {
            await axios
                .get('/api/topics')
                .then(response => (this.topics = response.data.data))
                .catch(error => console.log(error))
        },
        showTopics (data) {
            if (data.is_hate_speech) this.show_topics = true
            else
                this.$emit('process_tweet', {
                    topics: null,
                    input_topic: null,
                    tweet: this.data,
                    is_hate_speech: this.is_hate_speech
                })
        },
        notSure () {
            this.show_not_sure = true
        },
        async processNotSure (not_sure_reasons) {
            this.$emit('not_sure', {
                not_sure_reason: not_sure_reasons.selected_reason,
                other_reason: not_sure_reasons.other_reason
            })
            this.show_not_sure = false
        },
        async processTweet (data) {
            this.is_hate_speech = data.is_hate_speech
            // if is hate speech add to database
            this.$emit('process_tweet', {
                topics: data.selected_topics,
                input_topic: data.input_topic,
                tweet: this.data,
                is_hate_speech: this.is_hate_speech
            })
            this.selected_topic = 'inny'
            this.data.is_hate_speech = this.is_hate_speech
            this.show_topics = false
            axios
                .put(
                    this.url + '/' + this.index + '/_doc/' + this.data.tweet_id,
                    this.data,
                    {
                        auth: this.auth
                    }
                )
                .then(response => console.log(response))
                .catch(error => console.log(error))
        }
    }
}
</script>

<style></style>
