<template>
    <div v-if="this.is_hate_speech == null || random">
        <br />
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ data.author_username }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ new Date(data.posted_utime * 1000) }}
                </h6>
                <p>{{ data.content }}</p>
                <a
                    :href="
                        'https://twitter.com/' +
                        data.author_username +
                        '/status/' +
                        data.tweet_id
                    "
                    target="_blank"
                    class="btn btn-primary"
                    >Zobacz tweet</a
                >
            </div>
        </div>
        <div
            class="modal fade"
            id="topicModal"
            tabindex="-1"
            aria-labelledby="topicModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="topicModalLabel">
                            Wybierz temat
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div style="padding-top: 8px">
                                <select
                                    class="form-select"
                                    aria-label="Default select example"
                                    v-model="selected_topic"
                                >
                                    <option
                                        :value="topic.name"
                                        v-for="topic in topics"
                                        :key="topic"
                                    >
                                        {{ topic.name }}
                                    </option>
                                    <option value="inny">Inny</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cofnij
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="processTweet(true)"
                            data-bs-dismiss="modal"
                        >
                            Zapisz
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-auto" style="padding-top: 8px; padding-right: 4px">
                <button
                    class="btn btn-success"
                    type="button"
                    data-bs-target="#topicModal"
                    data-bs-toggle="modal"
                >
                    Mowa nienawiści
                </button>
            </div>
            <div class="col-auto" style="padding-top: 8px; padding-left: 4px">
                <button
                    class="btn btn-danger"
                    type="button"
                    @click="processTweet(false)"
                >
                    Nie jest to mowa nienawiści
                </button>
            </div>
        </div>
        <br />
    </div>
    <div v-else>Tweet przeprocesowany</div>
</template>

<script>
export default {
    props: {
        data: Object,
        random: Boolean,
    },
    data() {
        return {
            url: process.env.MIX_ES,
            index: process.env.MIX_INDEX,
            auth: {
                username: process.env.MIX_ES_USER,
                password: process.env.MIX_ES_PASS,
            },
            is_hate_speech: null,
            selected_topic: "inny",
            topics: [],
        };
    },
    emits: ["process_tweet"],
    async mounted() {
        await this.getTopics();
    },
    methods: {
        renderNewTweet() {
            this.is_hate_speech = false;
        },
        async getTopics() {
            await axios
                .get("/api/topics")
                .then((response) => (this.topics = response.data.data))
                .catch((error) => console.log(error));
        },
        async processTweet(is_hate_speech) {
            this.is_hate_speech = is_hate_speech;

            // if is hate speech add to database
            this.$emit("process_tweet", {
                topic: this.selected_topic,
                tweet: this.data,
                is_hate_speech: this.is_hate_speech,
            });
            this.selected_topic = "inny";
            this.data.is_hate_speech = is_hate_speech;
            axios
                .put(
                    this.url + "/" + this.index + "/_doc/" + this.data.tweet_id,
                    this.data,
                    {
                        auth: this.auth,
                    }
                )
                .then((response) => console.log(response))
                .catch((error) => console.log(error));
        },
    },
};
</script>

<style></style>
