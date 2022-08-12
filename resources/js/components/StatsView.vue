<template>
  <categories-count
    :data="this.hate_speech_categories_count"
    :key="this.componentKey"
    v-if="show_categories_count"
  ></categories-count>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Kategoria</th>
        <th scope="col">Liczba wyników</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(category, index) in this.hate_speech_categories_count" :key="index">
        <th scope="row">{{ index + 1 }}</th>
        <td>{{ category.name }}</td>
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
import CategoriesCount from "./stats/CategoriesCount.vue";
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
      hate_categories_index: process.env.MIX_HATE_CATEGORIES_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      hate_speech_categories: [],
      hate_speech_categories_count: [],
      show_categories_count: false,
      words: [],
      componentKey: 0,
    };
  },
  components: { CategoriesCount },
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
      data.forEach((d) => {
        this.hate_speech_categories.push({
          category: d.category,
          count: 0,
          words: d.words,
        });
      });
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

    this.hate_speech_categories.forEach((hsc, index) => {
      this.hate_speech_categories_count = [];

      countTweetsFromCategory(
        this.url + "/" + this.tweets_index,
        this.auth,
        hsc
      ).then((data) => {
        if (data > 0) {
          this.hate_speech_categories_count = JSON.parse(
            JSON.stringify(this.hate_speech_categories_count)
          );
          this.hate_speech_categories_count.push({
            name: hsc.category,
            count: data,
          });
        }
      });

      if (index == this.hate_speech_categories.length - 1)
        this.show_categories_count = true;
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