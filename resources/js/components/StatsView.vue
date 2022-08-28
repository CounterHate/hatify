<template>
  <categories-count
    :data="this.hate_speech_categories_count"
    :words="this.words"
    :hate_speech_categories_count="this.hate_speech_categories_count"
    v-if="this.author == '' && this.hate_category == ''"
  >
  </categories-count>
  <authors-count
    :data="this.hate_speech_category_authors_count"
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
</template>
<script>
import CategoriesCount from "./stats/CategoriesCount.vue";
import AuthorsCount from "./stats/AuthorsCount.vue";
import AuthorStats from "./stats/AuthorStats.vue";
import {
  getHateCategories,
  countTweetsFromCategory,
  countTweetsWithWord,
  getAuthorsForCategory,
  countAuthorTweetsForCategory,
  countAuthorTweetsForWord,
} from "../es.js";

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
      hate_speech_categories: [],
      hate_speech_categories_count: [],
      hate_speech_category_authors: [],
      hate_speech_category_authors_count: [],
      author_category_count: [],
      words: [],
    };
  },
  components: { CategoriesCount, AuthorsCount, AuthorStats },
  methods: {
    getHateCategories,
    countTweetsFromCategory,
    countTweetsWithWord,
    getAuthorsForCategory,
    countAuthorTweetsForCategory,
    countAuthorTweetsForWord,
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

    if (this.hate_category) {
      this.hate_speech_categories.forEach((hsc) => {
        if (hsc.category == this.hate_category) {
          getAuthorsForCategory(
            this.url + "/" + this.tweets_index,
            this.auth,
            hsc
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
                hsc
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
      });
    } else if (this.author) {
      this.hate_speech_categories.forEach((hsc) => {
        countAuthorTweetsForCategory(
          this.url + "/" + this.tweets_index,
          this.auth,
          this.author,
          hsc
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

      this.words.forEach((word) => {
        countAuthorTweetsForWord(
          this.url + "/" + this.tweets_index,
          this.auth,
          this.author,
          word.word
        ).then((data) => {
          word.count = data;
        });
      });
    } else {
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
    }
  },
};
</script>
<style>
</style>