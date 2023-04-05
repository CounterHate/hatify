<template>
  <br />
  <div class="card">
    <div class="card-body">
      <!-- <p>Dokładność {{ data.score }}</p> -->
      <h5 class="card-title" v-if="data.author_username">
        {{ data.author_username }}
      </h5>
      <h5 class="card-title" v-else>
        {{ data.author }}
      </h5>
      <h6 class="card-subtitle mb-2 text-muted">
        <div v-if="data.posted_utime">
          {{
            new Date(+data.posted_utime).toLocaleDateString("pl-Pl")
          }}
        </div>
        <div v-else>{{ new Date(parseInt(data.date)) }}</div>
      </h6>
      <!-- <div v-if="this.data.hate_category && this.data.hate_category.length > 0">
        kategorie:
        <span
          class="badge rounded-pill bg-primary"
          v-for="hc in this.data.hate_category"
          :key="hc"
          >{{ hc }}</span
        >
      </div> -->
      <div v-if="this.data.keywords && this.data.keywords.length > 0">
        Słowa kluczowe:
        <span
          class="badge rounded-pill bg-success"
          v-for="kw in this.data.keywords"
          :key="kw"
          >{{ kw }}</span
        >
      </div>

      <hr />
      <p>{{ data.content }}</p>
      <div v-if="this.public_metrics">
        <div class="row">
          <div class="col-auto">
            <b>Polubienia: </b>{{ this.public_metrics.like_count }}
          </div>
          <div class="col-auto">
            <b>Odpowiedzi:</b> {{ this.public_metrics.reply_count }}
          </div>
          <div class="col-auto">
            <b>Retweety:</b> {{ this.public_metrics.retweet_count }}
          </div>
          <div class="col-auto">
            <b>Cytaty:</b> {{ this.public_metrics.quote_count }}
          </div>
        </div>
      </div>

      <div class="row">
        <!-- show tweet button -->
        <div class="col-auto padded">
          <button class="btn btn-primary" @click="getStats">
            Statystyki tweeta
          </button>
        </div>
        <div class="col-auto padded">
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
        <div class="col-auto padded">
          <a
            :href="'/stats/author=' + data.author_username"
            class="btn btn-primary"
            target="_blank"
            >Zobacz statystyki użytkownika</a
          >
        </div>
        <!-- show tweets with similar content button -->
        <div
          class="col-auto padded"
          v-if="this.verification_view || this.anotation_view"
        >
          <a
            :href="'/search/twitter/data_id=' + data.tweet_id"
            class="btn btn-primary"
            target="_blank"
            >Zobacz podobne</a
          >
        </div>
        <!-- show other tweets from this author button -->
        <div
          class="col-auto padded"
          v-if="this.verification_view || this.anotation_view"
        >
          <a
            :href="'/search/twitter/author_username=' + data.author_username"
            class="btn btn-primary"
            target="_blank"
            >Zobacz inne tweety tego użytkownika</a
          >
        </div>
      </div>

      <topic-select
        :show="show_topics"
        @process_tweet_hate_speech="processIsHateSpeech"
      ></topic-select>
      <not-sure-select
        :show="show_reasons"
        @process_not_sure="processNotSure"
      ></not-sure-select>
    </div>
  </div>
</template>

<script>
import TweetAnotationButtons from "../TweetAnotationButtons.vue";
import TopicSelect from "../TopicSelect.vue";
import NotSureSelect from "../NotSureSelect.vue";

export default {
  props: {
    data: Object,
    show_topics: Boolean,
    show_reasons: Boolean,
    anotation_view: Boolean,
    verification_view: Boolean,
  },
  components: {
    TweetAnotationButtons,
    TopicSelect,
    NotSureSelect,
  },
  emits: ["process_is_hate_speech", "process_not_sure"],
  data() {
    return {
      public_metrics: null,
      date_options: {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
      },
    };
  },
  watch: {
    data(oldData, newData) {
      this.public_metrics = null;
    },
  },
  methods: {
    async processNotSure(not_sure_reasons) {
      this.$emit("process_not_sure", {
        not_sure_reason: not_sure_reasons.selected_reason,
        other_reason: not_sure_reasons.other_reason,
      });
    },
    async processIsHateSpeech(topics) {
      this.$emit("process_is_hate_speech", {
        selected_topics: topics.selected_topics,
        other_topic: topics.other_topic,
      });
    },
    async getStats() {
      await axios
        .get("/tweetStats/" + this.data.tweet_id)
        .then((response) => {
          console.log(response.data.data.public_metrics);
          this.public_metrics = response.data.data.public_metrics;
        })
        .catch((error) => {});
    },
  },
};
</script>

<style>
.padded {
  padding-top: 8px;
  padding-left: 6px;
  padding-right: 6px;
}
</style>
