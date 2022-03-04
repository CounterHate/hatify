<template>
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
            <div class="row">
                <div class="col-auto padded">
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
                </div>
                <div class="col-auto padded">
                    <a
                        :href="
                            'https://twitter.com/' +
                                data.author_username +
                                '/status/' +
                                data.tweet_id
                        "
                        class="btn btn-primary"
                        target="_blank"
                        >Zobacz podobne</a
                    >
                </div>
            </div>

            <topic-select
                :show="show_topics"
                @process_tweet_hate_speech="processIsHateSpeech"
            ></topic-select>
            <not-sure-select
                :show="show_reasons"
                @process_not_sure="processNotSure"
            ></not-sure-select>
        </div>
    </div>
</template>

<script>
import TweetAnotationButtons from './TweetAnotationButtons.vue'
import TopicSelect from './TopicSelect.vue'
import NotSureSelect from './NotSureSelect.vue'

export default {
    props: {
        data: Object,
        show_topics: Boolean,
        show_reasons: Boolean
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
            }
        }
    },
    emits: ['process_is_hate_speech', 'process_not_sure'],
    methods: {
        async processNotSure (not_sure_reasons) {
            this.$emit('process_not_sure', {
                not_sure_reason: not_sure_reasons.selected_reason,
                other_reason: not_sure_reasons.other_reason
            })
        },
        async processIsHateSpeech (topics) {
            this.$emit('process_is_hate_speech', {
                selected_topics: topics.selected_topics,
                other_topic: topics.other_topic
            })
        }
    }
}
</script>

<style>
.padded {
    padding-top: 8px;
    padding-left: 6px;
    padding-right: 6px;
}
</style>
