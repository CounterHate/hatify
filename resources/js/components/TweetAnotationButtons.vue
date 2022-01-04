<template>
    <div class="row">
        <div class="col-auto" style="padding-top: 8px; padding-right: 4px">
            <button
                class="btn btn-success"
                type="button"
                 @click="processTweet(true)"
            >
                Mowa nienawiści
            </button>
        </div>
        <div
            class="col-auto"
            style="padding-top: 8px; padding-left: 4px; padding-right: 4px"
        >
            <button
                class="btn btn-danger"
                type="button"
                @click="processTweet(false)"
            >
                Nie jest to mowa nienawiści
            </button>
        </div>
        <div class="col-auto" style="padding-top: 8px; padding-left: 4px">
            <button class="btn btn-primary" type="button" @click="skipTweet">
                Pomiń
            </button>
        </div>
    </div>
</template>

<script>
export default {
    emits: ['is_hate_speech_pressed', 'skip_tweet_pressed'],
    methods: {
        processTweet (is_hate_speech) {
            this.$emit('is_hate_speech_pressed', {
                is_hate_speech: is_hate_speech,
                // selected_topics: null,
            })
        },
        skipTweet () {
            this.$emit('skip_tweet_pressed')
        }
    }
}
</script>

<style></style>
