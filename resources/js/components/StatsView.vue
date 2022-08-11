<template>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Kategoria</th>
        <th scope="col">Liczba wyników</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(category, index) in this.hate_speech_categories" :key="index">
        <th scope="row">{{ index + 1 }}</th>
        <td>{{ category.category }}</td>
        <td>{{ category.count }}</td>
      </tr>
    </tbody>
  </table>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Słowo</th>
        <th scope="col">Kategoria</th>
        <th scope="col">Liczba wyników</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(word, index) in this.words" :key="index">
        <th scope="row">{{ index + 1 }}</th>
        <td>{{ word.word }}</td>
        <td>{{ word.category }}</td>
        <td>{{ word.count }}</td>
      </tr>
    </tbody>
  </table>
</template>
<script>
import {
  getHateCategories,
  countTweetsFromCategory,
  countTweetsWithWord,
} from "../es.js";

export default {
  data() {
    return {
      url: process.env.MIX_ES,
      tweets_index: process.env.MIX_TWEETS_INDEX,
      hate_categories_index: process.env.MIX_HATE_CATEGORIES,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      hate_speech_categories: null,
      words: [],
    };
  },
  methods: {
    getHateCategories,
    countTweetsFromCategory,
    countTweetsWithWord,
  },
  async mounted() {
    await getHateCategories(
      this.url + "/" + this.hate_categories_index,
      this.auth
    ).then((data) => {
      this.hate_speech_categories = data;
      this.hate_speech_categories.forEach((category) => {
        category.words.forEach((word) => {
          this.words.push({
            category: category.category,
            word: word,
            count: 0,
          });
        });
      });
    });

    this.hate_speech_categories.forEach((hsc) => {
      countTweetsFromCategory(
        this.url + "/" + this.tweets_index,
        this.auth,
        hsc
      ).then((data) => {
        hsc.count = data;
      });
    });

    this.words.forEach((word) => {
      countTweetsWithWord(
        this.url + "/" + this.tweets_index,
        this.auth,
        word.word
      ).then((data) => {
        word.count = data;
      });
    });
  },
};
</script>
<style>
</style>