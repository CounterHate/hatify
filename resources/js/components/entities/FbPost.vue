<template>
  <br />
  <div class="card">
    <div class="card-body">
      <h2>Fanpage: {{ data.page_name }}</h2>

      <h5 class="card-title">
        ID authora
        <a
          :href="'https://facebook.com/' + data.author_id"
          target="_blank"
          v-if="this.anotation_view"
          >{{ data.author_id }}</a
        >
        <a :href="'https://facebook.com/' + data.author" v-else target="_blank">{{
          data.author
        }}</a>
      </h5>
      <h6 class="card-subtitle mb-2 text-muted">
        <div v-if="data.posted_utime">
          {{ new Date(data.posted_utime * 1000) }}
        </div>
        <div v-else>{{ new Date(parseInt(data.date * 1000)) }}</div>
      </h6>
      <p>{{ data.content }}</p>
      <div class="row">
        <div class="col-auto padded" v-if="this.anotation_view">
          <!-- show post button -->
          <a
            :href="
              'https://facebook.com/' + data.page_id + '/posts/' + data.post_id
            "
            class="btn btn-primary"
            v-if="data.page_id"
            target="_blank"
            >Zobacz post</a
          >
          <a
            :href="
              'https://facebook.com/' +
              data.group_id[0] +
              '/posts/' +
              data.post_id
            "
            class="btn btn-primary"
            v-else
            target="_blank"
            >Zobacz post</a
          >
        </div>

        <div class="col-auto padded" v-if="this.verification_view">
          <!-- show post button -->
          <a :href="data.fb_url" class="btn btn-primary" target="_blank"
            >Zobacz post</a
          >
        </div>

        <div
          class="col-auto padded"
          v-if="this.anotation_view || this.verification_view"
        >
          <!-- show similar content -->
          <a
            :href="'/search/facebook/data_id=' + data.post_id"
            class="btn btn-primary"
            target="_blank"
            >Zobacz podobne</a
          >
        </div>
        <div
          class="col-auto padded"
          v-if="this.anotation_view || this.verification_view"
        >
          <!-- show other content from user -->
          <a
            :href="'/search/facebook/author_username=' + data.author_id"
            class="btn btn-primary"
            target="_blank"
            >Zobacz inne posty użytkownika</a
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
