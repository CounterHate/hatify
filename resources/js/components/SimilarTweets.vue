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
  <div v-if="twitter_mode">
    <tweet v-for="t in this.tweets" :key="t" :data="t"></tweet>
  </div>
  <div v-else>
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
</template>
<script>
import Tweet from "./Tweet.vue";
import FbPost from "./FbPost.vue";
import FbComment from "./FbComment.vue";
import { getSimilarTweets, getSimilarFbData } from "../es.js";
export default {
  props: { data_id: String, media: String },
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
      twitter_mode: true,
      facebook_mode: false,
      content_to_search: null,
      is_loading: false,
      no_results: false,
    };
  },
  methods: {
    getSimilarTweets,
    getSimilarFbData,
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
      if (this.content_to_search) {
        if (this.twitter_mode) {
          console.log("getting twitter content");
          await getSimilarTweets(
            this.url + "/" + this.tweets_index,
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
        } else {
          console.log("getting fb content");
          var indices = [this.fb_posts_index, this.fb_comments_index];
          var index = indices[Math.floor(Math.random() * indices.length)];
          await getSimilarFbData(
            this.url + "/" + index,
            this.auth,
            null,
            this.content_to_search,
            index
          ).then((data) => {
            this.fb_posts = [];
            this.fb_comments = [];
            data.forEach((d) => {
              if (index == this.fb_posts_index) {
                this.fb_posts.push(d);
                if (this.fb_posts.length == 0) {
                  this.no_results = true;
                } else {
                  this.no_results = false;
                }
              } else {
                this.fb_comments.push(d);
                if (this.fb_comments.length == 0) {
                  this.no_results = true;
                } else {
                  this.no_results = false;
                }
              }
            });
          });
        }
      }
    },
  },

  async mounted() {
    console.log(this.media);
    if (this.media != "twitter") {
      this.facebook_mode = true;
      this.twitter_mode = false;
    }
    if (this.twitter_mode) {
      if (this.data_id) {
        this.is_loading = true;
        await getSimilarTweets(
          this.url + "/" + this.tweets_index,
          this.auth,
          this.data_id,
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
    } else {
      if (this.data_id) {
        this.is_loading = true;
        var indices = [this.fb_posts_index, this.fb_comments_index];
        var index = indices[Math.floor(Math.random() * indices.length)];

        await getSimilarFbData(
          this.url,
          this.auth,
          this.data_id,
          null,
          index
        ).then((data) => {
          this.is_loading = false;
          console.log("vue data:", data);
          data.forEach((d) => {
            if (index == this.fb_posts_index) {
              this.fb_posts.push(d);
              if (this.fb_posts.length == 0) {
                this.no_results = true;
              } else {
                this.no_results = false;
              }
            } else {
              this.fb_comments.push(d);
              if (this.fb_comments.length == 0) {
                this.no_results = true;
              } else {
                this.no_results = false;
              }
            }
          });
        });
      }
    }
  },
};
</script>
<style>
</style>