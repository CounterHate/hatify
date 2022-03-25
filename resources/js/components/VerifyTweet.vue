<template>
  <div class="row">
    <div class="col-4">
      <hate-speech-definition></hate-speech-definition>
    </div>
    <div class="col-8">
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

      <!-- display data -->
      <tweet
        v-if="this.tweet != null"
        :data="this.tweet"
        :show_reasons="this.show_reasons"
        :show_topics="this.show_topics"
        @process_is_hate_speech="processIsHateSpeech"
        @process_not_sure="processNotSure"
      ></tweet>
      <fb-post
        v-if="this.fb_post != null"
        :data="this.fb_post"
        :show_reasons="this.show_reasons"
        :show_topics="this.show_topics"
        :verification_view="true"
        :anotation_view="false"
        @process_is_hate_speech="processIsHateSpeech"
        @process_not_sure="processNotSure"
      ></fb-post>
      <fb-comment
        v-if="this.fb_comment != null"
        :data="this.fb_comment"
        :show_reasons="this.show_reasons"
        :show_topics="this.show_topics"
        :verification_view="true"
        :anotation_view="false"
        @process_is_hate_speech="processIsHateSpeech"
        @process_not_sure="processNotSure"
      ></fb-comment>
      <tweet-anotation-buttons
        @is_hate_speech_pressed="handleIsHateSpeechPressed"
        @is_not_hate_speech_pressed="handleIsNotHateSpeechPressed"
        @skip_tweet_pressed="handleSkipTweetPressed"
        @not_sure_pressed="handleNotSurePressed"
      ></tweet-anotation-buttons>
    </div>
  </div>
</template>

<script>
import HateSpeechDefinition from "./HateSpeechDefinition.vue";
import Tweet from "./Tweet.vue";
import FbPost from "./FbPost.vue";
import FbComment from "./FbComment.vue";
import TweetAnotationButtons from "./TweetAnotationButtons.vue";
export default {
  components: {
    HateSpeechDefinition,
    Tweet,
    TweetAnotationButtons,
    FbPost,
    FbComment,
  },
  props: {
    user: Number,
  },
  data() {
    return {
      tweet: null,
      fb_post: null,
      fb_comment: null,
      show_topics: false,
      show_reasons: false,
      facebook_mode: false,
      twitter_mode: true,
    };
  },
  methods: {
    // get tweet

    async getData() {
      this.tweet = null;
      this.fb_post = null;
      this.fb_comment = null;
      if (this.twitter_mode) {
        await this.getTweetToVerify();
      } else {
        await this.getFbDataToVerify();
      }
    },
    async getTweetToVerify() {
      await axios
        .get("/api/tweetToVerify")
        .then((response) => {
          if (response.data != "") this.tweet = response.data;
        })
        .catch((error) => console.log(error));
    },

    async getFbDataToVerify() {
      var options = ["posts", "comments"];
      var option = options[Math.floor(Math.random() * options.length)];

      if (option == "posts") {
        await axios
          .get("/api/fbPostToVerify")
          .then((response) => {
            console.log(response.data);
            if (response.data != "") this.fb_post = response.data;
          })
          .catch((error) => console.log(error));
      } else {
        await axios
          .get("/api/fbCommentToVerify")
          .then((response) => {
            console.log(response.data);
            if (response.data != "") this.fb_comment = response.data;
          })
          .catch((error) => console.log(error));
      }
    },

    // switch between twitter and facebook mode
    changeMode(mode) {
      if (mode == "twitter") {
        this.twitter_mode = true;
        this.facebook_mode = false;
      } else {
        this.facebook_mode = true;
        this.twitter_mode = false;
      }
    },

    // handling anotation buttons events
    handleIsHateSpeechPressed() {
      this.show_reasons = false;
      this.show_topics = true;
    },
    async handleIsNotHateSpeechPressed() {
      var data = {};

      if (this.twitter_mode) {
        data = {
          tweet_id: this.tweet.tweet_id,
          author: this.tweet.author,
          content: this.tweet.content,
          date: this.tweet.date,
          topics: this.tweet.topics,
          verified: true,
          is_hate_speech: false,
          user_id: this.user,
          is_tweet: this.tweet.is_tweet,
          is_facebook_post: this.tweet.is_facebook_post,
          is_facebook_comment: this.tweet.is_facebook_comment,
          page_id: this.tweet.page_id,
          page_name: this.tweet.page_name,
        };
      } else {
        if (this.fb_post) {
          data = {
            tweet_id: this.fb_post.tweet_id,
            author: this.fb_post.author,
            content: this.fb_post.content,
            date: this.fb_post.date,
            topics: this.fb_post.topics,
            verified: true,
            is_hate_speech: false,
            user_id: this.user,
            is_tweet: this.fb_post.is_tweet,
            is_facebook_post: this.fb_post.is_facebook_post,
            is_facebook_comment: this.fb_post.is_facebook_comment,
            fb_url: this.fb_post.fb_url,
            page_id: this.fb_post.page_id,
            page_name: this.fb_post.page_name,
          };
        } else {
          data = {
            tweet_id: this.fb_comment.tweet_id,
            author: this.fb_comment.author,
            content: this.fb_comment.content,
            date: this.fb_comment.date,
            topics: this.fb_comment.topics,
            verified: true,
            is_hate_speech: false,
            user_id: this.user,
            is_tweet: this.fb_comment.is_tweet,
            is_facebook_post: this.fb_comment.is_facebook_post,
            is_facebook_comment: this.fb_comment.is_facebook_comment,
            fb_url: this.fb_comment.fb_url,
            page_id: this.fb_comment.page_id,
            page_name: this.fb_comment.page_name,
          };
        }
      }

      await axios
        .post("api/tweets", data)
        .then((response) => console.log(response.data))
        .catch((error) => console.error(error));
      await this.getData();

      this.show_reasons = false;
      this.show_topics = false;
    },
    async handleSkipTweetPressed() {
      await this.getData();
      this.show_topics = false;
      this.show_reasons = false;
    },
    handleNotSurePressed(data) {
      this.show_topics = false;
      this.show_reasons = true;
    },

    // processing tweet

    async processNotSure(data) {
      var not_sure_data;
      console.log(data);

      if (this.twitter_mode) {
        not_sure_data = {
          tweet_id: this.tweet.tweet_id,
          author: this.tweet.author,
          content: this.tweet.content,
          date: this.tweet.date,
          topics: this.tweet.topics,
          not_sure_reason: data.not_sure_reason,
          verified: true,
          is_hate_speech: true,
          user_id: this.user,
          is_tweet: this.tweet.is_tweet,
          is_facebook_post: this.tweet.is_facebook_post,
          is_facebook_comment: this.tweet.is_facebook_comment,
          page_id: this.tweet.page_id,
          page_name: this.tweet.page_name,
        };
      } else {
        if (this.fb_post) {
          not_sure_data = {
            tweet_id: this.fb_post.tweet_id,
            author: this.fb_post.author,
            content: this.fb_post.content,
            date: this.fb_post.date,
            topics: this.fb_post.topics,
            not_sure_reason: data.not_sure_reason,
            verified: true,
            is_hate_speech: true,
            user_id: this.user,
            is_tweet: this.fb_post.is_tweet,
            is_facebook_post: this.fb_post.is_facebook_post,
            is_facebook_comment: this.fb_post.is_facebook_comment,
            fb_url: this.fb_post.fb_url,
            page_id: this.fb_post.page_id,
            page_name: this.fb_post.page_name,
          };
        } else {
          not_sure_data = {
            tweet_id: this.fb_comment.tweet_id,
            author: this.fb_comment.author,
            content: this.fb_comment.content,
            date: this.fb_comment.date,
            topics: this.fb_comment.topics,
            not_sure_reason: data.not_sure_reason,
            verified: true,
            is_hate_speech: true,
            user_id: this.user,
            is_tweet: this.fb_comment.is_tweet,
            is_facebook_post: this.fb_comment.is_facebook_post,
            is_facebook_comment: this.fb_comment.is_facebook_comment,
            fb_url: this.fb_comment.fb_url,
            page_id: this.fb_comment.page_id,
            page_name: this.fb_comment.page_name,
          };
        }
      }

      axios
        .post("api/tweets/", not_sure_data)
        .then((response) => console.log(response.data))
        .catch((error) => console.error(error));
      this.getData();
      this.show_reasons = false;
    },
    processIsHateSpeech(data) {
      var is_hate_speech_data;
      if (this.twitter_mode) {
        is_hate_speech_data = {
          tweet_id: this.tweet.tweet_id,
          author: this.tweet.author,
          content: this.tweet.content,
          date: this.tweet.date,
          topics: JSON.stringify(data.selected_topics),
          verified: true,
          is_hate_speech: true,
          user_id: this.user,
          is_tweet: this.tweet.is_tweet,
          is_facebook_post: this.tweet.is_facebook_post,
          is_facebook_comment: this.tweet.is_facebook_comment,
          page_id: this.tweet.page_id,
          page_name: this.tweet.page_name,
        };
      } else {
        if (this.fb_post) {
          is_hate_speech_data = {
            tweet_id: this.fb_post.tweet_id,
            author: this.fb_post.author,
            content: this.fb_post.content,
            date: this.fb_post.date,
            topics: JSON.stringify(data.selected_topics),
            verified: true,
            is_hate_speech: true,
            user_id: this.user,
            is_tweet: this.fb_post.is_tweet,
            is_facebook_post: this.fb_post.is_facebook_post,
            is_facebook_comment: this.fb_post.is_facebook_comment,
            fb_url: this.fb_post.fb_url,
            page_id: this.fb_post.page_id,
            page_name: this.fb_post.page_name,
          };
        } else {
          is_hate_speech_data = {
            tweet_id: this.fb_comment.tweet_id,
            author: this.fb_comment.author,
            content: this.fb_comment.content,
            date: this.fb_comment.date,
            topics: JSON.stringify(data.selected_topics),
            verified: true,
            is_hate_speech: true,
            user_id: this.user,
            is_tweet: this.fb_comment.is_tweet,
            is_facebook_post: this.fb_comment.is_facebook_post,
            is_facebook_comment: this.fb_comment.is_facebook_comment,
            fb_url: this.fb_comment.fb_url,
            page_id: this.fb_comment.page_id,
            page_name: this.fb_comment.page_name,
          };
        }
      }
      // add new record
      axios
        .post("api/tweets", is_hate_speech_data)
        .then((response) => console.log(response.data))
        .catch((error) => console.error(error));

      // // update old record
      var update_data;
      if (this.twitter_mode) {
        update_data = this.tweet;
      } else {
        if (this.fb_post) {
          update_data = this.fb_post;
        } else {
          update_data = this.fb_comment;
        }
      }

      update_data.verified = true;
      axios
        .patch("api/tweets/" + update_data.id, update_data)
        .then((response) => console.log(response.data))
        .catch((error) => console.error(error));
      this.show_reasons = false;
      this.show_topics = false;
      this.getData();
    },
  },
  async mounted() {
    await this.getData();
  },
};
</script>
<style></style>
