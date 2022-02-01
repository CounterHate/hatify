<template>
    <div v-if="show" style="padding-top: 4px">
        <hr />
        Kogo atakuje ten wpis

        <div class="form-check" v-for="(topic, index) in topics" :key="index">
            <input
                class="form-check-input"
                type="checkbox"
                @change="toggleTopic(topic.name, index)"
            />
            <label class="form-check-label">
                {{ topic.name }}
            </label>
        </div>
        <input
            class="form-control"
            v-if="show_input"
            v-model="input_topic"
            type="text"
            placeholder="Inna ofiara"
        />

        <div style="padding-top: 4px;">
            <button type="button" class="btn btn-primary" @click="processTweet">
                Zapisz
            </button>
        </div>
    </div></template
>

<script>
export default {
    props: {
        topics: Array,
        show: Boolean
    },
    emits: ['process_tweet_hate_speech'],
    data () {
        return {
            selected_topics: [],
            input_topic: null,
            show_input: false
        }
    },

    methods: {
        resetCheckboxes () {
            document
                .querySelectorAll('input[type=checkbox]')
                .forEach(el => (el.checked = false))
        },
        processTweet () {
            var data = {
                is_hate_speech: true,
                selected_topics: this.selected_topics,
                input_topic: this.input_topic
            }
            this.$emit('process_tweet_hate_speech', data)
            this.selected_topics = []
            this.resetCheckboxes()
        },
        toggleTopic (topic, index) {
            if (index == 9) {
                this.show_input = !this.show_input
            }
            if (this.selected_topics.includes(topic)) {
                var index = this.selected_topics.indexOf(topic)
                this.selected_topics.splice(index, 1)
            } else {
                this.selected_topics.push(topic)
            }
        }
    }
}
</script>

<style></style>
