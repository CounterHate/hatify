<template>
  <div v-if="this.is_hate_speech == null">
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
          >Zobacz tweet</a
        >
      </div>
    </div>
    <div class="row">
      <div class="col-auto" style="padding-top: 8px; padding-right: 4px">
        <button
          class="btn btn-success"
          type="button"
          @click="processTweet(true)"
        >
          Mowa nienawiści
        </button>
      </div>
      <div class="col-auto" style="padding-top: 8px; padding-left: 4px">
        <button
          class="btn btn-danger"
          type="button"
          @click="processTweet(false)"
        >
          Nie jest to mowa nienawiści
        </button>
      </div>
    </div>
    <br />
  </div>
  <div v-else>Tweet przeprocesowany</div>
</template>

<script>
export default {
  props: {
    data: Object,
    url: String,
    auth: Object,
  },
  data() {
    return {
      is_hate_speech: null,
    };
  },
  methods: {
    async processTweet(is_hate_speech) {
      this.is_hate_speech = is_hate_speech;
      // if is hate speech add to database
      if (is_hate_speech) {
        var data = {
          tweet_id: this.data.tweet_id,
          author: this.data.author_username,
          content: this.data.content,
          date: (this.data.posted_utime * 1000).toString(),
        };
        axios
          .post("api/tweets", data)
          .then((response) => console.log(response.data))
          .catch((error) => console.error(error));
      }
      // // update doc in index
      this.data.is_hate_speech = is_hate_speech;

      axios
        .put(this.url, this.data, {
          auth: this.auth,
        })
        .then((response) => console.log(response))
        .catch((error) => console.log(error));
    },
  },
};
</script>

<style></style>
