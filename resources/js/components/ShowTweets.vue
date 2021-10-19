<template>
    <div class="input-group mb-3" v-if="search">
        <input
            type="text"
            class="form-control"
            placeholder="Fraza do wyszukiwania"
            v-model="content_query"
        />
        <span class="input-group-text" id="basic-addon2"
            ><button class="btn btn-clear" @click="getTweets">
                <i class="bi bi-search"></i></button
        ></span>
    </div>
    <div class="d-flex justify-content-center" v-if="is_loading">
        <div class="spinner-border" role="status">
            <span class="sr-only"></span>
        </div>
    </div>
    <tweet :data="tweet" v-for="tweet in tweets" v-bind:key="tweet" />
</template>

<script>
import Tweet from "./Tweet.vue";
export default {
    props: {
        url: String,
        index: String,
        search: Boolean,
    },
    data() {
        return {
            tweets: [],
            content_query: null,
            is_loading: false,
        };
    },
    components: {
        Tweet,
    },
    methods: {
        async getTweets() {
            this.is_loading = true;
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
            console.log(query);
            await axios
                .post(this.url + "/" + this.index + "/_search", query)
                .then((response) => {
                    this.tweets = [];
                    console.log(response.data);
                    response.data.hits.hits.forEach((t) => {
                        this.tweets.push(t._source);
                    });
                    this.is_loading = false;
                })
                .catch((error) => console.error(error));
        },
    },
    mounted() {
        if (!this.search) this.getTweets();
    },
};
</script>

<style>
.btn-clear {
    background-color: transparent;
    border-color: transparent;
}
</style>
