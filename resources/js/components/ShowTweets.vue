<template>
    <div class="input-group mb-3">
        <input
            type="text"
            class="form-control"
            placeholder="Fraza do wyszukiwania"
            v-model="content_query"
        />
        <span class="input-group-text" id="basic-addon2"
            ><button class="btn btn-clear">
                <i class="bi bi-search" @click="getTweets"></i></button
        ></span>
    </div>
    <tweet :data="tweet" v-for="tweet in tweets" v-bind:key="tweet" />
</template>

<script>
import Tweet from "./Tweet.vue";
export default {
    props: {
        url: String,
        index: String,
    },
    data() {
        return {
            tweets: [],
            content_query: null,
        };
    },
    components: {
        Tweet,
    },
    methods: {
        getTweets() {
            var query = {
                query: {
                    bool: {
                        must: [
                            { match: { lang: "pl" } },
                            { match: { is_retweet: false } },
                        ],
                    },
                },
                size: 20,
            };
            if (this.content_query) {
                query.query.bool.must.push({
                    match: { content: this.content_query },
                });
            }
            axios
                .post(this.url + "/" + this.index + "/_search", query)
                .then((response) => {
                    console.log(response.data);
                    response.data.hits.hits.forEach((t) => {
                        this.tweets.push(t._source);
                    });
                })
                .catch((error) => console.error(error));
        },
    },
    mounted() {
        // this.getTweets();
    },
};
</script>

<style>
.btn-clear {
    background-color: transparent;
    border-color: transparent;
}
</style>
