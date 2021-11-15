<template>
  Liczba wszystkich anotowanych tweetów: {{ this.totalAnotationCount }}
  <br />Liczba anotowanych tweetów uzytkownika: {{ this.userAnotationCount }}
  <tweet
    v-if="this.tweet != null"
    :random="true"
    :data="this.tweet"
    @process_tweet="handleProcessTweet"
    @skip_tweet="handleSkipTweet"
  ></tweet>
</template>

<script>
import Tweet from "./Tweet.vue";
import { getRandomTweets } from "../es.js";
export default {
  components: { Tweet },
  props: {
    user: Number,
  },
  data() {
    return {
      is_loading: false,
      tweet: null,
      url: process.env.MIX_ES,
      index: process.env.MIX_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      totalAnotationCount: 0,
      userAnotationCount: 0,
    };
  },
  async mounted() {
    await getRandomTweets(1, this.url + "/" + this.index, this.auth).then(
      (result) => (this.tweet = result[0])
    );
    await this.getAnotationCount();
  },
  methods: {
    async getAnotationCount() {
      await axios
        .get("/api/tweets")
        .then((response) => {
          var data = response.data.data;
          this.totalAnotationCount = data.length;
          var tmpUserAnotationCount = 0;
          data.forEach((el) => {
            if (JSON.parse(el.topics).user == this.user)
              tmpUserAnotationCount++;
          });
          this.userAnotationCount = tmpUserAnotationCount;
        })
        .catch((error) => console.log(error));
    },
    getRandomTweets,
    async handleSkipTweet() {
      await getRandomTweets(1, this.url, this.auth).then(
        (result) => (this.tweet = result[0])
      );
    },
    async handleProcessTweet(data) {
      if (data.is_hate_speech) {
        var tweet = {
          tweet_id: data.tweet.tweet_id,
          author: data.tweet.author_username,
          content: data.tweet.content,
          date: (data.tweet.posted_utime * 1000).toString(),
          topics: JSON.stringify({
            user: this.user,
            topics: data.topics,
            input_topic: data.input_topic,
          }),
        };
        axios
          .post("api/tweets", tweet)
          .then((response) => console.log(response.data))
          .catch((error) => console.error(error));
      }

      await getRandomTweets(1, this.url, this.auth).then(
        (result) => (this.tweet = result[0])
      );
      await this.getAnotationCount();
    },
  },
};
</script>

<style></style>
