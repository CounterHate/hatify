<template>
  <div class="input-group mb-3">
    <input
      type="text"
      class="form-control"
      placeholder="Treść do wyszukania"
      v-model="content_to_search"
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
  <div v-if="this.no_results == true">
    Brak wyników dla treści "{{ this.content_to_search }}"
  </div>
  <tweet v-for="t in this.tweets" :key="t" :data="t"></tweet>
</template>
<script>
import Tweet from "./Tweet.vue";
import { getSimilarTweets } from "../es.js";
export default {
  props: { tweet_id: String },
  components: { Tweet },
  data() {
    return {
      url: process.env.MIX_ES,
      index: process.env.MIX_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      tweets: [],
      content_to_search: null,
      is_loading: false,
      no_results: false,
    };
  },
  methods: {
    getSimilarTweets,
    async getQueryTweets() {
      if (this.content_to_search) {
        await getSimilarTweets(
          this.url + "/" + this.index,
          this.auth,
          null,
          this.content_to_search
        ).then((data) => {
          this.tweets = data;
          if (this.tweets.length == 0) {
            this.no_results = true;
          } else {
            this.no_results = false;
          }
          this.is_loading = false;
        });
      }
    },
  },

  async mounted() {
    if (this.tweet_id) {
      this.is_loading = true;
      await getSimilarTweets(
        this.url + "/" + this.index,
        this.auth,
        this.tweet_id,
        null
      ).then((data) => {
        this.tweets = data;
        if (this.tweets.length == 0) {
          this.no_results = true;
        } else {
          this.no_results = false;
        }
        this.is_loading = false;
      });
    }
  },
};
</script>
<style>
</style>