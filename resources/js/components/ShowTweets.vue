<template>
  <div class="input-group mb-3" v-if="search">
    <input
      type="text"
      class="form-control"
      placeholder="Fraza do wyszukiwania"
      v-model="content_query"
      v-on:keyup.enter="getQueryTweets"
    />
    <span class="input-group-text" id="basic-addon2"
      ><button class="btn btn-clear" @click="getQueryTweets">
        <i class="bi bi-search"></i></button
    ></span>
  </div>
  <div class="d-flex justify-content-center" v-if="is_loading">
    <div class="spinner-border" role="status">
      <span class="sr-only"></span>
    </div>
  </div>
  <tweet
    :random="false"
    :data="tweet"
    :auth="{ username: this.es_user, password: this.es_pass }"
    :url="this.url + '/_doc/' + tweet.tweet_id"
    @process_tweet="handleProcessTweet"
    v-for="tweet in tweets"
    v-bind:key="tweet"
  />
</template>

<script>
import Tweet from "./Tweet.vue";
import { getRandomTweets } from "../es.js";
export default {
  props: {
    url: String,
    search: Boolean,
    es_user: String,
    es_pass: String,
    user: Object,
  },
  data() {
    return {
      tweets: [],
      content_query: null,
      is_loading: false,
      auth: null,
      topics: [],
    };
  },
  components: {
    Tweet,
  },
  async mounted() {
    this.auth = {
      username: this.es_user,
      password: this.es_pass,
    };
    if (!this.search)
      await getRandomTweets(20, this.url, this.auth).then(
        (result) => (this.tweets = result)
      );
  },
  methods: {
    getRandomTweets,
    handleProcessTweet(data) {
      if (data.is_hate_speech) {
        var tweet = {
          tweet_id: data.tweet.tweet_id,
          author: data.tweet.author_username,
          content: data.tweet.content,
          date: (data.tweet.posted_utime * 1000).toString(),
          topics: JSON.stringify({ user: this.user.id, topic: data.topic }),
        };
        axios
          .post("api/tweets", tweet)
          .then((response) => console.log(response.data))
          .catch((error) => console.error(error));
      }
    },
    async getQueryTweets() {
      this.is_loading = true;
      var query = {
        query: {
          bool: {
            must: [{ match: { lang: "pl" } }, { match: { is_retweet: false } }],
          },
        },
        size: 20,
      };
      if (this.content_query) {
        query.query.bool.must.push({
          match: { content: this.content_query },
        });
      }
      await axios
        .post(this.url + "/_search", query, {
          auth: this.auth,
        })
        .then((response) => {
          this.tweets = [];
          response.data.hits.hits.forEach((t) => {
            this.tweets.push(t._source);
          });
        })
        .catch((error) => console.error(error));
      this.is_loading = false;
    },
  },
};
</script>

<style>
.btn-clear {
  background-color: transparent;
  border-color: transparent;
}
</style>
