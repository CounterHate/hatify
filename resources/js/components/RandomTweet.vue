<template>
  <tweet
    :data="this.tweet"
    v-if="this.tweet != null"
    :auth="{ username: this.es_user, password: this.es_pass }"
    :url="this.url + '/_doc/' + this.tweet.tweet_id"
  ></tweet>
</template>

<script>
import Tweet from "./Tweet.vue";
import { getRandomTweets } from "../es.js";
export default {
  components: { Tweet },
  props: {
    url: String,
    es_user: String,
    es_pass: String,
  },
  data() {
    return { is_loading: false, tweet: null };
  },
  async mounted() {
    await getRandomTweets(1, this.url, {
      username: this.es_user,
      password: this.es_pass,
    }).then((result) => (this.tweet = result[0]));
  },
  methods: {
    getRandomTweets,
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
      console.log(auth);
      await axios
        .post(this.url + "/_search", query, {
          auth: auth,
        })
        .then((response) => {
          this.tweets = [];
          console.log(response.data);
          this.tweet = response.data.hits.hits[0]._source;
          this.is_loading = false;
        })
        .catch((error) => console.error(error));
    },
  },
};
</script>

<style></style>
