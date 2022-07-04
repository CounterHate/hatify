<template>
  <tweet
    v-if="this.tweet != null"
    :data="this.tweet"
    :show_reasons="false"
    :show_topics="false"
    :anotation_view="true"
    :verification_view="false"
  ></tweet>
  <div class="row" style="margin-top: 8px">
    <div class="col-auto" style="margin-right: 8px; padding-right: 0px">
      <button class="btn btn-primary" @click="getTweet">Kolejny</button>
    </div>
    <div class="col-auto" style="margin-left: 8px; padding-left: 0px">
      <button class="btn btn-primary" @click="checkIfHateSpeech">
        Procesuj
      </button>
    </div>
  </div>

  <div
    v-if="this.score"
    class="alert alert-success"
    role="alert"
    style="margin;top: 8px"
  >
    Jest {{ this.score }} prawdopodobieństwa, ze treść zawiera mowę nienawiści
  </div>
</template>
<script>
import { getRandomTweets } from "../es.js";
import Tweet from "./Tweet.vue";

export default {
  data() {
    return {
      tweet: null,
      url: process.env.MIX_ES,
      tweets_index: process.env.MIX_TWEETS_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      score: null,
      url: "http://counterhate.info/api/evaluate",
    };
  },
  components: {
    Tweet,
  },
  methods: {
    getRandomTweets,
    async checkIfHateSpeech() {
      await axios
        .post(this.url, this.tweet.content)
        .then((response) => (this.score = response.score))
        .catch((error) => {
          console.error(error);
        });
    },
    async getTweet() {
      await this.getRandomTweets(
        1,
        this.url + "/" + this.tweets_index,
        this.auth,
        ""
      ).then((response) => {
        this.tweet = response[0];
        this.score = null;
      });
    },
  },
  async mounted() {
    await this.getTweet();
  },
};
</script>
<style>
</style>