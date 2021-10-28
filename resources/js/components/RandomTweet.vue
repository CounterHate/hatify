<template>
    <tweet
        :data="this.tweet"
        v-if="this.tweet != null"
        @process-tweet="processTweet"
    ></tweet>
</template>

<script>
import Tweet from "./Tweet.vue";
export default {
    components: { Tweet },
    props: {
        url: String,
        index: String,
        es_user: String,
        es_pass: String,
    },
    data() {
        return { is_loading: false, tweet: null };
    },
    mounted() {
        this.getRandomTweet();
    },
    methods: {
        async getRandomTweet() {
            this.is_loading = true;
            var query = {
                size: 1,
                query: {
                    function_score: {
                        random_score: {},
                        query: {
                            bool: {
                                must: [
                                    {
                                        match: {
                                            lang: "pl",
                                        },
                                    },
                                    {
                                        match: {
                                            is_retweet: false,
                                        },
                                    },
                                ],
                                must_not: [
                                    {
                                        match: {
                                            is_hate_speech: true,
                                        },
                                    },
                                    {
                                        match: {
                                            is_hate_speech: false,
                                        },
                                    },
                                ],
                            },
                        },
                    },
                },
            };
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
                    this.tweet = response.data.hits.hits[0]._source;
                    this.is_loading = false;
                })
                .catch((error) => console.error(error));
        },
        async processTweet(is_hate_speech) {
            // if is hate speech add to database
            console.log(this.tweet.tweet_id)
            if (is_hate_speech) {
                var data = {
                    tweet_id: this.tweet.tweet_id,
                    is_hate_speech: this.is_hate_speech,
                    author: this.tweet.author_username,
                    content: this.tweet.content,
                    date: (this.tweet.posted_utime * 1000).toString(),
                };
                axios
                    .post("api/tweets", data)
                    .then((response) => console.log(response.data))
                    .catch((error) => console.error(error));
            }
            // // update doc in index
            this.tweet.is_hate_speech = is_hate_speech;
            var auth = {
                username: this.es_user,
                password: this.es_pass,
            };
            axios
                .put(
                    this.url +
                        "/" +
                        this.index +
                        "/_doc/" +
                        this.tweet.tweet_id,
                    this.tweet,
                    {
                        auth: auth,
                    }
                )
                .then((response) => console.log(response))
                .catch((error) => console.log(error));
        },
    },
};
</script>

<style></style>
