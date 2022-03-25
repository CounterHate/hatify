<template>
  <div class="form-check" style="margin-top: 16px">
    <input
      v-if="this.media == 'twitter'"
      class="form-check-input"
      type="radio"
      @click="changeMode('twitter')"
      name="modeRadio"
      checked
    />
    <input
      v-else
      class="form-check-input"
      type="radio"
      @click="changeMode('twitter')"
      name="modeRadio"
    />
    <label class="form-check-label"> Twitter </label>
  </div>
  <div class="form-check">
    <input
      v-if="this.media == 'facebook'"
      class="form-check-input"
      type="radio"
      @click="changeMode('facebook')"
      name="modeRadio"
      checked
    />
    <input
      v-else
      class="form-check-input"
      type="radio"
      @click="changeMode('facebook')"
      name="modeRadio"
    />
    <label class="form-check-label"> Facebook </label>
  </div>
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
  <div v-if="this.twitter_mode">
    <!-- Show tweets -->
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
  </div>
  <div v-else>
    <!-- Show fb data -->
    <div v-if="!show_only_hate_speech">
      <div v-if="this.fb_posts.length > 0">
        <fb-post
          v-for="p in this.fb_posts"
          :key="p"
          :data="p"
          :anotation_view="false"
          :verification_view="false"
        ></fb-post>
      </div>
      <div v-if="this.fb_comments.length > 0">
        <fb-comment
          v-for="c in this.fb_comments"
          :key="c"
          :data="c"
          :anotation_view="false"
          :verification_view="false"
        ></fb-comment>
      </div>
    </div>
    <div v-else>
      <div v-if="this.fb_posts.length > 0"></div>
      <div v-if="this.fb_comments.length > 0"></div>
    </div>
  </div>
</template>
<script>
import Tweet from "./Tweet.vue";
import FbPost from "./FbPost.vue";
import FbComment from "./FbComment.vue";
import { getUserTweets, getUserFbData } from "../es.js";
export default {
  props: { username: String, media: String },
  components: { Tweet, FbPost, FbComment },
  data() {
    return {
      url: process.env.MIX_ES,
      tweets_index: process.env.MIX_TWEETS_INDEX,
      fb_posts_index: process.env.MIX_FBPOSTS_INDEX,
      fb_comments_index: process.env.MIX_FBCOMMENTS_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      tweets: [],
      fb_posts: [],
      fb_comments: [],
      hate_speech_tweets: [],
      hate_speech_comments: [],
      hate_speech_posts: [],
      username_to_search: null,
      no_results: false,
      show_only_hate_speech: false,
      twitter_mode: true,
      facebook_mode: false,
      fb_indices: ["fb_posts", "fb_comments"],
    };
  },
  methods: {
    getUserTweets,
    getUserFbData,
    changeMode(mode) {
      if (mode == "twitter") {
        this.twitter_mode = true;
        this.facebook_mode = false;
      } else {
        this.facebook_mode = true;
        this.twitter_mode = false;
      }
    },
    async getQueryTweets() {
      if (this.twitter_mode) {
        await getUserTweets(
          this.url + "/" + this.tweets_index,
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
      } else if (this.facebook_mode) {
        var indices = [this.fb_posts_index, this.fb_comments_index];
        var index = indices[Math.floor(Math.random() * indices.length)];

        await getUserFbData(
          this.url + "/" + index,
          this.auth,
          this.username_to_search
        ).then((data) => {
          this.fb_posts = [];
          this.fb_comments = [];
          this.hate_speech_comments = [];
          this.hate_speech_posts = [];
          data.forEach((d) => {
            if (index == "fb_posts") {
              this.fb_posts.push(d);
              if (d.is_hate_speech) this.hate_speech_posts.push(d);
              if (this.fb_posts.length == 0) {
                this.no_results = true;
              } else {
                this.no_results = false;
              }
            } else {
              this.fb_comments.push(d);
              if (d.is_hate_speech) this.hate_speech_comments.push(d);
              if (this.fb_comments.length == 0) {
                this.no_results = true;
              } else {
                this.no_results = false;
              }
            }
          });

          this.is_loading = false;
        });
      } else {
        console.log("What mode is selected?!");
      }
    },
  },
  async mounted() {
    console.log(this.username);
    if (this.media != "twitter") {
      this.facebook_mode = true;
      this.twitter_mode = false;
    }
    if (this.username) {
      this.username_to_search = this.username;
      this.getQueryTweets();
    }
  },
};
</script>
<style>
</style>