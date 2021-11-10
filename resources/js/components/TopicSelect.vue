<template>
    <div
        class="modal fade"
        id="topicModal"
        tabindex="-1"
        aria-labelledby="topicModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg">
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
                        <div class="col">
                            <div style="padding-bottom: 8px">
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
                        <div class="col-auto">
                            <button class="btn btn-primary" @click="addTopic">
                                Dodaj
                            </button>
                        </div>
                    </div>
                    <table
                        class="table table-striped"
                        v-if="this.selected_topics.length != 0"
                    >
                        <thead>
                            <tr>
                                <th scope="col" style="width: 90%">
                                    Wybrane tematy
                                </th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="st in this.selected_topics" :key="st">
                                <th scope="row">{{ st }}</th>
                                <td>
                                    <button
                                        class="btn btn-danger"
                                        @click="remoteTopic(st)"
                                    >
                                        Usuń
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                        @click="processTweet"
                        data-bs-dismiss="modal"
                    >
                        Zapisz
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        topics: Array,
    },
    emits: ["process_tweet_hate_speech"],
    data() {
        return {
            selected_topic: "inny",
            selected_topics: [],
        };
    },
    methods: {
        processTweet() {
            this.$emit("process_tweet_hate_speech", {
                is_hate_speech: true,
                selected_topics: this.selected_topics,
            });
            this.selected_topics = [];
            this.selected_topic = "inny";
        },
        addTopic() {
            this.selected_topics.push(this.selected_topic);
        },
        remoteTopic(topic) {
            var index = this.selected_topics.indexOf(topic);
            this.selected_topics.splice(index, 1);
        },
    },
};
</script>

<style></style>
