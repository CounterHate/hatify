<template>
    <div class="row" style="padding-left: 12px">
        <div class="col-auto padded">
            <button class="btn btn-success" @click="isHateSpeech">
                Mowa nienawiści
            </button>
        </div>
        <div class="col-auto padded">
            <button class="btn btn-danger" @click="isNotHateSpeech">
                Nie jest to mowa nienawiści
            </button>
        </div>
        <div class="col-auto padded">
            <button class="btn btn-warning" @click="notSure">
                Nie jest to dla mnie jasne
            </button>
        </div>
        <div class="col-auto padded">
            <button class="btn btn-primary" @click="skipTweet">
                Pomiń
            </button>
        </div>
    </div>
</template>

<script>
const IS_HATE_SPEECH_PRESSED = 'is_hate_speech_pressed'
const IS_NOT_HATE_SPEECH_PRESSED = 'is_not_hate_speech_pressed'
const SKIP_TWEET_PRESSED = 'skip_tweet_pressed'
const NOT_SURE_PRESSED = 'not_sure_pressed'

export default {
    emits: [
        IS_HATE_SPEECH_PRESSED,
        IS_NOT_HATE_SPEECH_PRESSED,
        SKIP_TWEET_PRESSED,
        NOT_SURE_PRESSED
    ],
    methods: {
        isHateSpeech () {
            this.$emit('is_hate_speech_pressed')
        },
        isNotHateSpeech () {
            this.$emit('is_not_hate_speech_pressed')
        },
        notSure () {
            this.$emit('not_sure_pressed')
        },
        skipTweet () {
            this.$emit('skip_tweet_pressed')
        }
    }
}
</script>

<style>
.padded {
    padding-top: 8px;
    padding-left: 0px;
}
</style>
