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
    <tweet
        :data="tweet"
        v-for="tweet in tweets"
        v-bind:key="tweet"
        @process-tweet="processTweet(tweet)"
    />
</template>

<script>
import Tweet from "./Tweet.vue";
export default {
    props: {
        url: String,
        index: String,
        search: Boolean,
        es_user: String,
        es_pass: String,
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
            var auth = {
                username: this.es_user,
                password: this.es_pass,
            };
            await axios
                .post(this.url + "/" + this.index + "/_search", query, {
                    auth: auth,
                })
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
        async processTweet(tweet, is_hate_speech) {
            // if is hate speech add to database
            if (is_hate_speech) {
                var data = {
                    tweet_id: tweet.tweet_id,
                    is_hate_speech: is_hate_speech,
                    author: tweet.author_username,
                    content: tweet.content,
                    date: (tweet.posted_utime * 1000).toString(),
                };
                axios
                    .post("api/tweets", data)
                    .then((response) => console.log(response.data))
                    .catch((error) => console.error(error));
            }
            // // update doc in index
            tweet.is_hate_speech = is_hate_speech;
            var auth = {
                username: this.es_user,
                password: this.es_pass,
            };
            axios
                .put(
                    this.url + "/" + this.index + "/_doc/" + tweet.tweet_id,
                    tweet,
                    {
                        auth: auth,
                    }
                )
                .then((response) => console.log(response))
                .catch((error) => console.log(error));
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
