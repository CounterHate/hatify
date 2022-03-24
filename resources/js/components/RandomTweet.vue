<template>
  <div class="row">
    <div class="col-5">
      <hate-speech-definition></hate-speech-definition>
    </div>
    <div class="col-7">
      <!-- choose between fb and tt -->

      <div class="form-check" style="margin-top: 16px">
        <input
          class="form-check-input"
          type="radio"
          @click="changeMode('twitter')"
          name="modeRadio"
          checked
        />
        <label class="form-check-label"> Twitter </label>
      </div>
      <div class="form-check">
        <input
          class="form-check-input"
          type="radio"
          @click="changeMode('facebook')"
          name="modeRadio"
        />
        <label class="form-check-label"> Facebook </label>
      </div>

      <!-- account search form -->
      <label class="form-label">Konto do anotowania</label>
      <div class="row">
        <div class="col">
          <input
            type="text"
            class="form-control"
            v-model="username_to_anotation"
          />
        </div>
        <div class="col-auto">
          <button class="btn btn-primary" @click="handleSkipTweetPressed">
            Szukaj
          </button>
        </div>
      </div>

      <!-- display data -->
      <tweet
        v-if="this.tweet != null"
        :data="this.tweet"
        :show_reasons="this.show_reasons"
        :show_topics="this.show_topics"
        :anotation_view="true"
        :verification_view="false"
        @process_is_hate_speech="processIsHateSpeech"
        @process_not_sure="processNotSure"
      ></tweet>
      <tweet-anotation-buttons
        @is_hate_speech_pressed="handleIsHateSpeechPressed"
        @is_not_hate_speech_pressed="handleIsNotHateSpeechPressed"
        @skip_tweet_pressed="handleSkipTweetPressed"
        @not_sure_pressed="handleNotSurePressed"
      ></tweet-anotation-buttons>
      <fb-post v-if="this.fb_post != null"></fb-post>
      <fb-comment v-if="this.fb_comment != null"></fb-comment>
    </div>
  </div>
</template>

<script>
import Tweet from "./Tweet.vue";
import FBPost from "./FBPost.vue";
import TweetAnotationButtons from "./TweetAnotationButtons.vue";
import { getRandomTweets, updateInIndex, getRandomFBdata } from "../es.js";
import HateSpeechDefinition from "./HateSpeechDefinition.vue";
import FBComment from "./FBComment.vue";
export default {
  components: {
    Tweet,
    HateSpeechDefinition,
    TweetAnotationButtons,
    FBPost,
    FBComment,
  },
  props: {
    user: Number,
  },
  data() {
    return {
      show_topics: false,
      show_reasons: false,
      tweet: null,
      fb_post: null,
      fb_comment: null,
      username_to_anotation: "",
      url: process.env.MIX_ES,
      tweets_index: process.env.MIX_TWEETS_INDEX,
      fb_posts_index: process.env.MIX_FBPOSTS_INDEX,
      fb_comments_index: process.env.MIX_FBCOMMENTS_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      facebook_mode: false,
      twitter_mode: true,
    };
  },
  async mounted() {
    await getRandomTweets(
      1,
      this.url + "/" + this.tweets_index,
      this.auth,
      this.username_to_anotation
    ).then((result) => (this.tweet = result[0]));
  },
  methods: {
    // es methods
    getRandomTweets,
    updateInIndex,
    getRandomFBdata,

    // handling anotation buttons events
    handleIsHateSpeechPressed() {
      this.show_reasons = false;
      this.show_topics = true;
    },
    async handleIsNotHateSpeechPressed() {
      await updateInIndex(
        this.url + "/" + this.index,
        this.auth,
        this.tweet,
        false
      );
      await getRandomTweets(
        1,
        this.url,
        this.auth,
        this.username_to_anotation
      ).then((result) => (this.tweet = result[0]));

      this.show_reasons = false;
      this.show_topics = false;
    },
    async handleSkipTweetPressed() {
      await getRandomTweets(
        1,
        this.url,
        this.auth,
        this.username_to_anotation
      ).then((result) => (this.tweet = result[0]));

      this.show_topics = false;
      this.show_reasons = false;
    },
    handleNotSurePressed(data) {
      this.show_topics = false;
      this.show_reasons = true;
    },
    changeMode(mode) {
      if (mode == "twitter") {
        this.twitter_mode = true;
        this.facebook_mode = false;
      } else {
        this.facebook_mode = true;
        this.twitter_mode = false;
      }
    },

    // processing tweet
    async processIsHateSpeech(data) {
      var tweet = {
        tweet_id: this.tweet.tweet_id,
        author: this.tweet.author_username,
        content: this.tweet.content,
        date: (this.tweet.posted_utime * 1000).toString(),
        topics: JSON.stringify(data.selected_topics),
        other_topic: data.other_topic,
        user_id: this.user,
        is_hate_speech: true,
      };
      axios
        .post("api/tweets", tweet)
        .then((response) => console.log(response.data))
        .catch((error) => console.error(error));

      await updateInIndex(
        this.url + "/" + this.index,
        this.auth,
        this.tweet,
        true
      );
      await getRandomTweets(1, this.url, this.auth).then(
        (result) => (this.tweet = result[0])
      );
      this.show_topics = false;
    },
    async processNotSure(data) {
      var tweet = {
        tweet_id: this.tweet.tweet_id,
        author: this.tweet.author_username,
        content: this.tweet.content,
        date: (this.tweet.posted_utime * 1000).toString(),
        topics: JSON.stringify([]),
        not_sure_reason: data.not_sure_reason,
        other_reason: data.other_reason,
        user_id: this.user,
        is_hate_speech: false,
      };
      axios
        .post("api/tweets", tweet)
        .then((response) => console.log(response.data))
        .catch((error) => console.error(error));
      await getRandomTweets(1, this.url, this.auth).then(
        (result) => (this.tweet = result[0])
      );
      this.show_reasons = false;
    },
  },
};
</script>

<style></style>
