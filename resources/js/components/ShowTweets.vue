<template>
  <tweet :data="tweet" v-for="tweet in tweets" v-bind:key="tweet" />
</template>

<script>
import Tweet from "./Tweet.vue";
export default {
  props: {
    url: String,
    index: String,
  },
  data() {
    return {
      tweets: [],
    };
  },
  components: {
    Tweet,
  },
  methods: {
    getTweets() {
      var query = {
        query: {
          bool: {
            must: [{ match: { lang: "pl" } }, { match: { is_retweet: false } }],
          },
        },
        size: 20,
      };
      axios
        .post(this.url + "/" + this.index + "/_search", query)
        .then((response) => {
          console.log(response.data);
          response.data.hits.hits.forEach((t) => {
            this.tweets.push(t._source);
          });
        })
        .catch((error) => console.error(error));
    },
  },
  mounted() {
    this.getTweets();
  },
};
</script>

<style></style>
