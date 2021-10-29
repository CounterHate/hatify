<template>
  <tweet
    v-if="this.tweet != null"
    ref="tweetComponent"
    :random="true"
    :data="this.tweet"
    :auth="{ username: this.es_user, password: this.es_pass }"
    :url="this.url + '/_doc/' + this.tweet.tweet_id"
    @process_tweet="handleProcessTweet"
  ></tweet>
</template>

<script>
import Tweet from "./Tweet.vue";
import { getRandomTweets } from "../es.js";
import { ref } from "vue";
export default {
  components: { Tweet },
  props: {
    url: String,
    es_user: String,
    es_pass: String,
    user: Object,
  },
  data() {
    return { is_loading: false, tweet: null, auth: {} };
  },
  async mounted() {
    this.auth = {
      username: this.es_user,
      password: this.es_pass,
    };
    await getRandomTweets(1, this.url, this.auth).then(
      (result) => (this.tweet = result[0])
    );
  },
  methods: {
    getRandomTweets,
    async handleProcessTweet(data) {
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

      await getRandomTweets(1, this.url, this.auth).then(
        (result) => (this.tweet = result[0])
      );
    },
  },
};
</script>

<style></style>
