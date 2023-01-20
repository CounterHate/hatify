<template>
  <div class="row">
    <div class="col-5">
      <hate-speech-definition></hate-speech-definition>
    </div>
    <div class="col-7">
      <!-- choose between fb and tt -->

      <div class="form-check" style="margin-top: 16px">
        <input class="form-check-input" type="radio" @click="changeMode('twitter')" name="modeRadio" checked />
        <label class="form-check-label  text-white"> Twitter </label>
      </div>
      <!-- <div class="form-check">
        <input class="form-check-input" type="radio" @click="changeMode('facebook')" name="modeRadio" />
        <label class="form-check-label  text-white"> Facebook </label>
      </div> -->
      <label class="form-label text-white">Konto do anotowania</label>
      <div class="row">
        <div class="col">
          <input type="text" class="form-control" v-model="username_to_anotation" />
        </div>
        <div class="col-auto">
          <button class="btn btn-success" @click="handleSkipTweetPressed">
            Szukaj
          </button>
        </div>
      </div>

      <!-- display data -->
      <tweet v-if="this.tweet != null" :data="this.tweet" :show_reasons="this.show_reasons"
        :show_topics="this.show_topics" :anotation_view="true" :verification_view="false"
        @process_is_hate_speech="processIsHateSpeech" @process_not_sure="processNotSure"></tweet>

      <fb-post v-if="this.fb_post != null" :data="this.fb_post" :show_topics="this.show_topics"
        :show_reasons="this.show_reasons" :anotation_view="true" @process_is_hate_speech="processIsHateSpeech"
        @process_not_sure="processNotSure"></fb-post>

      <fb-comment v-if="this.fb_comment != null" :data="this.fb_comment" :show_topics="this.show_topics"
        :show_reasons="this.show_reasons" :anotation_view="true" @process_is_hate_speech="processIsHateSpeech"
        @process_not_sure="processNotSure"></fb-comment>

      <tweet-anotation-buttons @is_hate_speech_pressed="handleIsHateSpeechPressed"
        @is_not_hate_speech_pressed="handleIsNotHateSpeechPressed" @skip_tweet_pressed="handleSkipTweetPressed"
        @not_sure_pressed="handleNotSurePressed"></tweet-anotation-buttons>
    </div>
  </div>
</template>

<script>
import Tweet from "../entities/Tweet.vue";
import FbPost from "../entities/FbPost.vue";
import FbComment from "../entities/FbComment.vue";
import TweetAnotationButtons from "../TweetAnotationButtons.vue";
import { getRandomTweets, updateInIndex, getRandomFBdata } from "../../es.js";
import HateSpeechDefinition from "../HateSpeechDefinition.vue";
export default {
  components: {
    Tweet,
    HateSpeechDefinition,
    TweetAnotationButtons,
    FbPost,
    FbComment,
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
      // handling not hate speech for tweet
      if (this.twitter_mode) {
        //updating tweet in index, setting is_hate_speech to false
        await updateInIndex(
          this.url + "/" + this.tweets_index + "/_doc/" + this.tweet.tweet_id,
          this.auth,
          this.tweet,
          false
        );
        this.fb_post = null;
        this.fb_comment = null;
        // getting new tweet
        await getRandomTweets(
          1,
          this.url + "/" + this.tweets_index,
          this.auth,
          this.username_to_anotation
        ).then((result) => (this.tweet = result[0]));

        // handling not hate speech for facebook content
      } else if (this.facebook_mode) {
        // checking if comment or post
        if (this.fb_post) {
          // updating post in index, setting is_hate_speech to false
          await updateInIndex(
            this.url +
            "/" +
            this.fb_posts_index +
            "/_doc/" +
            this.fb_post.post_id,
            this.auth,
            this.fb_post,
            false
          ).then((_) => {
            // getting new post
            var indices = ["fb_posts", "fb_comments"];
            var index = indices[Math.floor(Math.random() * indices.length)];

            getRandomFBdata(
              1,
              this.url + "/" + index,
              this.auth,
              this.username_to_anotation
            ).then((result) => {
              this.tweet = null;
              this.fb_comment = null;
              this.fb_post = result[0];
            });
          });
        } else if (this.fb_comment) {
          // updating post in index, setting is_hate_speech to false
          await updateInIndex(
            this.url +
            "/" +
            this.fb_comments_index +
            "/_doc/" +
            this.fb_comment.comment_id,
            this.auth,
            this.fb_comment,
            false
          );
          // getting new post
          var indices = ["fb_posts", "fb_comments"];
          var index = indices[Math.floor(Math.random() * indices.length)];

          await getRandomFBdata(
            1,
            this.url + "/" + index,
            this.auth,
            this.username_to_anotation
          ).then((result) => {
            this.tweet = null;
            this.fb_comment = null;
            this.fb_post = result[0];
          });
        }
      }

      this.show_reasons = false;
      this.show_topics = false;
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

    async handleSkipTweetPressed() {
      if (this.twitter_mode) {

        this.fb_post = null;
        this.fb_comment = null;
        await getRandomTweets(
          1,
          this.url + "/" + this.tweets_index,
          this.auth,
          this.username_to_anotation
        ).then((result) => (this.tweet = result[0]));

      } else if (this.facebook_mode) {
        // randomize between post and comment
        var indices = ["fb_posts", "fb_comments"];
        var index = indices[Math.floor(Math.random() * indices.length)];

        await getRandomFBdata(
          1,
          this.url + "/" + index,
          this.auth,
          this.username_to_anotation
        ).then((result) => {
          this.tweet = null;
          if (index == "fb_posts") {
            this.fb_comment = null;
            this.fb_post = result[0];
          } else {
            this.fb_comment = result[0];
            this.fb_post = null;
          }
        });
      }

      this.show_topics = false;
      this.show_reasons = false;
    },
    handleNotSurePressed(data) {
      this.show_topics = false;
      this.show_reasons = true;
    },

    // processing tweet
    async processIsHateSpeech(data) {
      if (this.twitter_mode) {
        // process tweet

        var tweet = {
          tweet_id: this.tweet.tweet_id,
          author: this.tweet.author_username,
          content: this.tweet.content,
          date: (this.tweet.posted_utime * 1000).toString(),
          topics: JSON.stringify(data.selected_topics),
          other_topic: data.other_topic,
          user_id: this.user,
          is_hate_speech: true,
          is_tweet: true,
          is_facebook_post: false,
          is_facebook_comment: false,
          fb_url: null,
          page_id: null,
        };

        axios
          .post("api/tweets", tweet)
          .then((response) => console.log(response.data))
          .catch((error) => console.error(error));

        await updateInIndex(
          this.url + "/" + this.tweets_index + "/_doc/" + this.tweet.tweet_id,
          this.auth,
          this.tweet,
          true
        );

        await getRandomTweets(
          1,
          this.url + "/" + this.tweets_index,
          this.auth,
          this.username_to_anotation
        ).then((result) => (this.tweet = result[0]));
      } else if (this.facebook_mode) {
        // process facebook content

        if (this.fb_post) {
          var fbdata = {
            tweet_id: this.fb_post.post_id.toString(),
            author: this.fb_post.author_id,
            content: this.fb_post.content,
            date: (this.fb_post.date * 1000).toString(),
            topics: JSON.stringify(data.selected_topics),
            other_topic: data.other_topic,
            user_id: this.user,
            is_hate_speech: true,
            is_facebook_post: true,
            is_tweet: false,
            is_facebook_comment: false,
            fb_url:
              "https://facebook.com/" +
              this.fb_post.page_id +
              "/posts/" +
              this.fb_post.post_id,
            page_id: this.fb_post.page_id,
            page_name: this.fb_post.page_name,
          };

          axios
            .post("api/tweets", fbdata)
            .then((response) => console.log(response.data))
            .catch((error) => console.error(error));

          await updateInIndex(
            this.url +
            "/" +
            this.fb_posts_index +
            "/_doc/" +
            this.fb_post.post_id,
            this.auth,
            this.fb_post,
            true
          );

          await getRandomFBdata(
            1,
            this.url + "/" + this.fb_posts_index,
            this.auth,
            this.username_to_anotation
          ).then((result) => {
            this.tweet = null;
            this.fb_comment = null;
            this.fb_post = result[0];
          });
        } else if (this.fb_comment) {
          var fbdata = {
            tweet_id: this.fb_comment.comment_id.toString(),
            author: this.fb_comment.author_id,
            content: this.fb_comment.content,
            date: (this.fb_comment.date * 1000).toString(),
            topics: JSON.stringify(data.selected_topics),
            other_topic: data.other_topic,
            user_id: this.user,
            is_hate_speech: true,
            is_facebook_post: false,
            is_tweet: false,
            is_facebook_comment: true,
            fb_url:
              "https://facebook.com/" +
              this.fb_comment.page_id +
              "/posts/" +
              this.fb_comment.original_post_id +
              "/?comment_id=" +
              data.comment_id,
            page_id: this.fb_comment.page_id,
            page_name: this.fb_comment.page_name,
          };

          axios
            .post("api/tweets", fbdata)
            .then((response) => console.log(response.data))
            .catch((error) => console.error(error));

          await updateInIndex(
            this.url +
            "/" +
            this.fb_comments_index +
            "/_doc/" +
            this.fb_comment.comment_id,
            this.auth,
            this.fb_comment,
            true
          );

          await getRandomFBdata(
            1,
            this.url + "/" + this.fb_comments_index,
            this.auth,
            this.username_to_anotation
          ).then((result) => {
            this.tweet = null;
            this.fb_comment = result[0];
            this.fb_post = null;
          });
        } else {
          console.log("How did we get here?");
        }
      }

      this.show_topics = false;
    },

    // processi when user is not sure if hate speech
    async processNotSure(data) {
      if (this.twitter_mode) {
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
          is_facebook_post: false,
          is_tweet: true,
          is_facebook_comment: false,
          fb_url: null,
        };

        axios
          .post("api/tweets", tweet)
          .then((response) => console.log(response.data))
          .catch((error) => console.error(error));
        await getRandomTweets(
          1,
          this.url,
          this.auth,
          this.username_to_anotation
        ).then((result) => (this.tweet = result[0]));
      } else {
        if (this.fb_post) {
          var fbdata = {
            tweet_id: this.fb_post.post_id.toString(),
            author: this.fb_post.author_id,
            content: this.fb_post.content,
            date: (this.fb_post.date * 1000).toString(),
            topics: JSON.stringify([]),
            not_sure_reason: data.not_sure_reason,
            other_topic: data.other_topic,
            user_id: this.user,
            is_hate_speech: false,
            is_facebook_post: true,
            is_tweet: false,
            is_facebook_comment: false,
            fb_url:
              "https://facebook.com/" +
              this.fb_post.page_id +
              "/posts/" +
              this.fb_post.post_id,
          };

          axios
            .post("api/tweets", fbdata)
            .then((response) => console.log(response.data))
            .catch((error) => console.error(error));

          await getRandomFBdata(
            1,
            this.url + "/" + this.fb_posts_index,
            this.auth,
            this.username_to_anotation
          ).then((result) => {
            this.tweet = null;
            this.fb_comment = null;
            this.fb_post = result[0];
          });
        } else {
          {
            var fbdata = {
              tweet_id: this.fb_comment.comment_id.toString(),
              author: this.fb_comment.author_id,
              content: this.fb_comment.content,
              date: (this.fb_comment.date * 1000).toString(),
              topics: JSON.stringify([]),
              not_sure_reason: data.not_sure_reason,
              other_topic: data.other_topic,
              user_id: this.user,
              is_hate_speech: false,
              is_facebook_post: false,
              is_tweet: false,
              is_facebook_comment: true,
              fb_url:
                "https://facebook.com/" +
                this.fb_comment.page_id +
                "/posts/" +
                this.fb_comment.original_post_id +
                "/?comment_id=" +
                data.comment_id,
            };

            axios
              .post("api/tweets", fbdata)
              .then((response) => console.log(response.data))
              .catch((error) => console.error(error));

            await getRandomFBdata(
              1,
              this.url + "/" + this.fb_comments_index,
              this.auth,
              this.username_to_anotation
            ).then((result) => {
              this.tweet = null;
              this.fb_comment = result[0];
              this.fb_post = null;
            });
          }
        }
      }

      this.show_reasons = false;
    },
  },
};
</script>

<style>
</style>
