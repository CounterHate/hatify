<template>
    <div v-if="show" style="padding-top: 4px">
        <hr />
        Kogo atakuje ten wpis

        <div
            class="form-check"
            v-for="(topic, index) in this.topics"
            :key="index"
        >
            <input
                class="form-check-input"
                type="checkbox"
                @change="toggleTopic(topic, index)"
            />
            <label class="form-check-label">
                {{ topic }}
            </label>
        </div>
        <input
            class="form-control"
            v-if="show_input"
            v-model="other_topic"
            type="text"
            placeholder="Inna ofiara"
        />

        <div style="padding-top: 4px;">
            <button
                type="button"
                class="btn btn-primary"
                @click="processIsHateSpeech"
            >
                Zapisz
            </button>
        </div>
    </div></template
>

<script>
export default {
    props: {
        show: Boolean
    },
    emits: ['process_tweet_hate_speech'],
    data () {
        return {
            selected_topics: [],
            other_topic: null,
            show_input: false,
            topics: [
                'Żydów',
                'Muzułmanów',
                'Romów',
                'Osoby o innym kolorze skóry',
                'Osoby o migranckim pochodzeniu',
                'Osoby nieheteronormatywne',
                'Osoby niepełnosprawne',
                'Kobiety',
                'Osoby starsze',
                'Inna ofiara mowy nienawiści (wpisz)'
            ]
        }
    },

    methods: {
        resetCheckboxes () {
            document
                .querySelectorAll('input[type=checkbox]')
                .forEach(el => (el.checked = false))
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
        },
        processIsHateSpeech () {
            this.$emit('process_tweet_hate_speech', {
                selected_topics: this.selected_topics,
                other_topic: this.other_topic
            })
        }
    }
}
</script>

<style></style>
