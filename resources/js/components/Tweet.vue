<template>
  <div v-if="this.is_hate_speech == null || random">
    <br />
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ data.author_username }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">
          {{ new Date(data.posted_utime * 1000) }}
        </h6>
        <p>{{ data.content }}</p>
        <a
          :href="
            'https://twitter.com/' +
            data.author_username +
            '/status/' +
            data.tweet_id
          "
          class="btn btn-primary"
          target="_blank"
          >Zobacz tweet</a
        >
      </div>
    </div>
    <topic-select
      :topics="this.topics"
      @process_tweet_hate_speech="processTweet"
    ></topic-select>

    <tweet-anotation-buttons
      @is_hate_speech_pressed="processTweet"
      @skip_tweet_pressed="skipTweet"
    ></tweet-anotation-buttons>
    <br />
  </div>
  <div v-else>Tweet przeprocesowany</div>
</template>

<script>
import TweetAnotationButtons from "./TweetAnotationButtons.vue";
import TopicSelect from "./TopicSelect.vue";
export default {
  props: {
    data: Object,
    random: Boolean,
  },
  components: {
    TweetAnotationButtons,
    TopicSelect,
  },
  data() {
    return {
      url: process.env.MIX_ES,
      index: process.env.MIX_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      is_hate_speech: null,
      topics: [],
    };
  },
  emits: ["process_tweet", "skip_tweet"],
  async mounted() {
    await this.getTopics();
  },
  methods: {
    renderNewTweet() {
      this.is_hate_speech = false;
    },
    skipTweet() {
      this.$emit("skip_tweet", {});
    },
    async getTopics() {
      await axios
        .get("/api/topics")
        .then((response) => (this.topics = response.data.data))
        .catch((error) => console.log(error));
    },
    async processTweet(data) {
      this.is_hate_speech = data.is_hate_speech;
      // if is hate speech add to database
      this.$emit("process_tweet", {
        topics: data.selected_topics,
        input_topic: data.input_topic,
        tweet: this.data,
        is_hate_speech: this.is_hate_speech,
      });
      this.selected_topic = "inny";
      this.data.is_hate_speech = this.is_hate_speech;
      axios
        .put(
          this.url + "/" + this.index + "/_doc/" + this.data.tweet_id,
          this.data,
          {
            auth: this.auth,
          }
        )
        .then((response) => console.log(response))
        .catch((error) => console.log(error));
    },
  },
};
</script>

<style></style>
