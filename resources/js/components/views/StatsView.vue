<template>
  <div class="row">
    <div class="col">
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Od</label>
        <div class="col-sm-10">
          <Datepicker v-model="this.from_date"></Datepicker>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Do</label>
        <div class="col-sm-10">
          <Datepicker v-model="this.to_date"></Datepicker>
        </div>
      </div>
    </div>
    <div class="col">
      <button class="btn btn-primary" @click="getData">Filtruj</button>
    </div>
  </div>
  <br />

  <div class="text-center" v-if="this.counting">
    <div class="spinner-border" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
  <div v-else>
    <categories-count
      :data="this.hate_speech_categories_count"
      :words="this.words"
      :hate_speech_categories_count="this.hate_speech_categories_count"
      v-if="this.author == '' && this.hate_category == ''"
    >
    </categories-count>
    <authors-count
      :data="this.hate_speech_category_authors_count"
      :data_growth="this.category_growth"
      :hate_speech_category="this.hate_category"
      v-if="this.author == '' && this.hate_category"
    >
    </authors-count>
    <author-stats
      :data="this.author_category_count"
      :author="this.author"
      :words="this.words"
      v-if="this.author && this.hate_category == ''"
    >
    </author-stats>
  </div>
</template>
<script>
import CategoriesCount from "../stats/CategoriesCount.vue";
import AuthorsCount from "../stats/AuthorsCount.vue";
import AuthorStats from "../stats/AuthorStats.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import {
  getHateCategories,
  countTweetsFromCategory,
  countTweetsWithWord,
  getAuthorsForCategory,
  countAuthorTweetsForCategory,
  countAuthorTweetsForWord,
  getCategoryGrowth,
} from "../../es.js";

export default {
  props: { author: String, hate_category: String },
  data() {
    return {
      url: process.env.MIX_ES,
      tweets_index: process.env.MIX_TWEETS_INDEX,
      hate_categories_index: process.env.MIX_HATE_CATEGORIES_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      stats_index: process.env.MIX_HATE_STATS_INDEX,
      hate_speech_categories: [],
      hate_speech_categories_count: [],
      hate_speech_category_authors: [],
      hate_speech_category_authors_count: [],
      author_category_count: [],
      words: [],
      from_date: null,
      to_date: null,
      counting: true,
      category_growth: null,
    };
  },
  components: { CategoriesCount, AuthorsCount, AuthorStats, Datepicker },
  methods: {
    getHateCategories,
    countTweetsFromCategory,
    countTweetsWithWord,
    getAuthorsForCategory,
    countAuthorTweetsForCategory,
    countAuthorTweetsForWord,
    getCategoryGrowth,
    async getData() {
      this.counting = true;
      if (this.hate_category) {
        this.hate_speech_categories.forEach((hsc, index) => {
          if (hsc.category == this.hate_category) {
            getAuthorsForCategory(
              this.url + "/" + this.tweets_index,
              this.auth,
              hsc,
              this.from_date,
              this.to_date
            ).then((data) => {
              this.hate_speech_category_authors = JSON.parse(
                JSON.stringify(data)
              );
              this.hate_speech_category_authors.forEach((hsca) => {
                this.hate_speech_category_authors_count = [];

                countAuthorTweetsForCategory(
                  this.url + "/" + this.tweets_index,
                  this.auth,
                  hsca,
                  hsc,
                  this.from_date,
                  this.to_date
                ).then((data) => {
                  this.hate_speech_category_authors_count = JSON.parse(
                    JSON.stringify(this.hate_speech_category_authors_count)
                  );
                  this.hate_speech_category_authors_count.push({
                    name: hsca,
                    count: data,
                  });
                  this.hate_speech_category_authors_count.sort(
                    (a, b) => b.count - a.count
                  );
                });
              });
            });
          }
          if (index == this.hate_speech_categories.length - 1)
            this.counting = false;
        });
        getCategoryGrowth(
          this.url + "/" + this.stats_index,
          this.auth,
          this.hate_category,
          this.from_date,
          this.to_date
        ).then((data) => {
          this.category_growth = data;
        });
      } else if (this.author) {
        this.hate_speech_categories.forEach((hsc) => {
          countAuthorTweetsForCategory(
            this.url + "/" + this.tweets_index,
            this.auth,
            this.author,
            hsc,
            this.from_date,
            this.to_date
          ).then((data) => {
            this.author_category_count = JSON.parse(
              JSON.stringify(this.author_category_count)
            );
            if (data > 0) {
              this.author_category_count.push({
                name: hsc.category,
                count: data,
              });
              this.author_category_count.sort((a, b) => b.count - a.count);
            }
          });
        });

        this.words.forEach((word, index) => {
          countAuthorTweetsForWord(
            this.url + "/" + this.tweets_index,
            this.auth,
            this.author,
            word.word,
            this.from_date,
            this.to_date
          ).then((data) => {
            word.count = data;
          });
          if (index == this.words.length - 1) this.counting = false;
        });
      } else {
        this.hate_speech_categories.forEach((hsc) => {
          this.hate_speech_categories_count = [];

          countTweetsFromCategory(
            this.url + "/" + this.tweets_index,
            this.auth,
            hsc,
            this.from_date,
            this.to_date
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
        });

        this.words.forEach((word, index) => {
          countTweetsWithWord(
            this.url + "/" + this.tweets_index,
            this.auth,
            word.word,
            this.from_date,
            this.to_date
          ).then((data) => {
            word.count = data;
          });
          if (index == this.words.length - 1) this.counting = false;
        });
      }
    },
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
    await this.getData();
  },
};
</script>
<style>
</style>