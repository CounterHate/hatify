<template>
  <div class="input-group mb-3">
    <input
      type="text"
      class="form-control"
      placeholder="Konto do wyszukania"
      v-model="username_to_search"
      v-on:keyup.enter="getQueryTweets"
    />
    <span class="input-group-text" id="basic-addon2"
      ><button class="btn btn-clear" @click="getQueryTweets">
        <i class="bi bi-search"></i></button
    ></span>
  </div>
  <div class="form-check" v-if="this.tweets.length > 0">
    <input
      class="form-check-input"
      type="checkbox"
      :value="show_only_hate_speech"
      v-model="show_only_hate_speech"
    />
    <label class="form-check-label">
      Pokaz tylko oznaczone jako mowa nienawiści
    </label>
  </div>

  <div v-if="this.no_results == true">
    Brak wyników dla konta "{{ this.username_to_search }}"
  </div>
  <div v-if="!show_only_hate_speech">
    <tweet
      v-for="t in this.tweets"
      :key="t"
      :data="t"
      :anotation_view="false"
      :verification_view="false"
    ></tweet>
  </div>
  <div v-else>
    <tweet
      v-for="t in this.hate_speech_tweets"
      :key="t"
      :data="t"
      :anotation_view="false"
      :verification_view="false"
    ></tweet>
  </div>
</template>
<script>
import Tweet from "./Tweet.vue";
import { getUserTweets } from "../es.js";
export default {
  props: { username: String },
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
      hate_speech_tweets: [],
      username_to_search: null,
      no_results: false,
      show_only_hate_speech: false,
    };
  },
  methods: {
    getUserTweets,
    async getQueryTweets() {
      await getUserTweets(
        this.url + "/" + this.index,
        this.auth,
        this.username_to_search
      ).then((data) => {
        this.tweets = data;
        this.hate_speech_tweets = [];
        this.tweets.forEach((t) => {
          if (t.is_hate_speech) this.hate_speech_tweets.push(t);
        });
        if (this.tweets.length == 0) {
          this.no_results = true;
        } else {
          this.no_results = false;
        }
        this.is_loading = false;
      });
    },
  },
  async mounted() {
    if (this.username) {
      await getUserTweets(
        this.url + "/" + this.index,
        this.auth,
        this.username
      ).then((data) => {
        this.tweets = data;
        this.hate_speech_tweets = [];
        this.tweets.forEach((t) => {
          if (t.is_hate_speech) this.hate_speech_tweets.push(t);
        });
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