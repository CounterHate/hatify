<template>

  <!-- select row -->
  <div class="row">

    <!-- form to pick data order -->
    <div class="col">
      <div class="form-check" style="padding-left: 0px">
        <label>Sortuj</label>
        <select class="form-select" v-model="media_chosen">
          <option v-for="option in this.mediaOptions" :value="option.value"> {{ option.text }}</option>
        </select>
      </div>
    </div>

    <!-- form to pick data order -->
    <div class="col">
      <div class="form-check" style="padding-left: 0px">
        <label>Sortuj</label>
        <select class="form-select" v-model="sortOrder" @change="sortData">
          <option v-for="option in this.sortOptions" :value="option.value"> {{ option.text }}</option>
        </select>
      </div>
    </div>

    <!-- form to pick data order -->
    <div class="col">
      <div class="form-check" style="padding-left: 0px">
        <label>Ile wpisów zwrócić</label>
        <select class="form-select" v-model="size">
          <option v-for="option in this.sizeOptions" :value="option.value"> {{ option.text }}</option>
        </select>
      </div>
    </div>

  </div>

  <!-- text input row -->
  <div class="row" style="margin-top: 8px">
    <div class="col">
      <!-- form for text to search -->
      <div class="mb-3">
        <label for="contentInput" class="form-label">Treść do wyszukiwania</label>
        <input type="text" class="form-control" id="contentInput" placeholder="Treść do wyszukania" v-model="content"
          v-on:keyup.enter="getData" />
      </div>
    </div>
    <div class="col">
      <!-- form for account to search -->
      <div class="mb-3" v-if="this.media_chosen == 'twitter'">
        <label for="authorInput" class="form-label">Konto do wyszukiwania</label>
        <input type="text" class="form-control" id="authorInput" placeholder="konto do wyszukiwania"
          v-model="author_username" v-on:keyup.enter="getData" />

      </div>
      <div class="mb-3" v-if="this.media_chosen == 'facebook'">
        <label for="authorInput" class="form-label">Id konta do wyszukiwania</label>
        <input type="text" class="form-control" id="authorInput" placeholder="Id konta do wyszukiwania"
          v-model="author_username" v-on:keyup.enter="getData" />

      </div>
    </div>

  </div>
  <div class="row">
    <div class="col">
      <label>Od</label>
      <Datepicker v-model="this.from_date"></Datepicker>
    </div>
    <div class="col">
      <label>Do</label>
      <Datepicker v-model="this.to_date"></Datepicker>
    </div>
    <div class="col">
      <label class="form-label">Dokładność {{ this.min_score }}</label>
      <input type="range" class="form-range" v-model="this.min_score" min="0" max="100" step="0.01">
    </div>
  </div>
  <br />
  <button type="submit" class="btn btn-success mb-3" @click="getData">Szukaj</button>

  <!-- results -->
  <div class="d-flex justify-content-center" v-if="is_loading">
    <div class="spinner-border" role="status">
      <span class="sr-only"></span>
    </div>
  </div>
  <div v-if="this.no_results == true">
    Brak wyników dla treści "{{ this.content }}"
  </div>

  <!-- render tweets -->
  <div v-if="this.media_chosen == 'twitter'">
    <p>Znaleziono {{ this.tweets.length }} wpisów</p>
    <vue-excel-xlsx :data="this.tweets" :columns="columns" :file-name="'tweets'" :file-type="'xlsx'"
      :sheet-name="'wyniki'">
      Download
    </vue-excel-xlsx>


    <tweet v-for="(t, index) in this.tweets" :key="index" :data="t"></tweet>
  </div>
  <div v-else>

    <!-- render fb posts and comments -->
    <div v-if="this.fb_posts.length > 0">
      <fb-post v-for="p in this.fb_posts" :key="p" :data="p" :anotation_view="false" :verification_view="false">
      </fb-post>
    </div>
    <div v-if="this.fb_comments.length > 0">
      <fb-comment v-for="c in this.fb_comments" :key="c" :data="c" :anotation_view="false" :verification_view="false">
      </fb-comment>
    </div>
  </div>
</template>
<script>

import Tweet from "./Tweet.vue";
import FbPost from "./FbPost.vue";
import FbComment from "./FbComment.vue";
import { getTweets, getTweet, getFbData, getFbRecord } from "../es.js";

import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
  props: { data_id: String, media: String, author: String },
  components: { Tweet, FbPost, FbComment, Datepicker },
  data() {
    return {
      columns: [
        {
          label: "is_hate_speech",
          field: "is_hate_speech",
        },
        {
          label: "is_retweet",
          field: "is_retweet",
        },
        {
          label: "author_username",
          field: "author_username",
        },
        {
          label: "author_name",
          field: "author_name",
        },
        {
          label: "content",
          field: "content",
        },
        {
          label: "posted_utime",
          field: "posted_utime",
          dataFormat: this.dateFormat
        },
        {
          label: "keywords",
          field: "keywords",
          dataFormat: this.keywordsFormat
        },
        {
          label: "url",
          field: "url",
        }
      ],
      url: process.env.MIX_ES,
      tweets_index: process.env.MIX_TWEETS_INDEX,
      fb_posts_index: process.env.MIX_FBPOSTS_INDEX,
      fb_comments_index: process.env.MIX_FBCOMMENTS_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      min_score: 0,
      from_date: null,
      to_date: null,
      tweets: [],
      fb_posts: [],
      fb_comments: [],
      content: null,
      author_username: null,
      is_loading: false,
      no_results: false,
      sortOrder: null,
      sortOptions: [
        { value: 'ascending', text: 'Od najstarszych' },
        { value: 'descending', text: 'Od najnowszych' },
        { value: 'ascending_score', text: 'Od najmniejszej dokładności' },
        { value: 'descending_score', text: 'Od największej dokładności' }
      ],
      size: 10,
      sizeOptions: [
        { value: '10', text: '10' },
        { value: '50', text: '50' },
        { value: '100', text: '100' },
        { value: '500', text: '500' },
        { value: '1000', text: '1000' },
        { value: '5000', text: '5000' },
        { value: '10000', text: '10000' },
      ],
      media_chosen: 'twitter',
      mediaOptions: [
        { value: 'twitter', text: 'twitter' },
        { value: 'facebook', text: 'facebook' },
      ]

    };
  },
  methods: {
    getTweets,
    getFbData,
    sortData() {
      if (this.media_chosen == 'facebook') {
        if (this.sortOrder == 'ascending') {
          this.fb_posts.sort((a, b) => a.date > b.date ? 1 : -1)
          this.fb_comments.sort((a, b) => a.date > b.date ? 1 : -1)
        }
        if (this.sortOrder == 'descending') {
          this.fb_posts.sort((a, b) => a.date < b.date ? 1 : -1)
          this.fb_comments.sort((a, b) => a.date < b.date ? 1 : -1)
        }
      }

      if (this.media_chosen == 'twitter') {
        if (this.sortOrder == 'ascending') this.tweets.sort((a, b) => a.posted_utime > b.posted_utime ? 1 : -1)
        if (this.sortOrder == 'descending') this.tweets.sort((a, b) => a.posted_utime < b.posted_utime ? 1 : -1);
        if (this.sortOrder == 'ascending_score') this.tweets.sort((a, b) => a.score > b.score ? 1 : -1)
        if (this.sortOrder == 'descending_score') this.tweets.sort((a, b) => a.score < b.score ? 1 : -1);
      }
    },
    async getData() {
      if (this.media_chosen == 'twitter') {
        this.is_loading = true;
        await getTweets(
          this.url + "/" + this.tweets_index,
          this.auth, this.size,
          this.content,
          this.author_username,
          this.from_date,
          this.to_date,
          this.min_score).then((data) => {
            this.tweets = data;
            this.sortData(data, this.sortOrder, this.media_chosen);
            if (this.tweets.length == 0) {
              this.no_results = true;
            } else {
              this.no_results = false;
            }
            this.is_loading = false;
          }).catch((error) => {
            console.log(error);
          })
      }

      if (this.media_chosen == 'facebook') {
        console.log("getting fb posts");
        await getFbData(
          this.url + "/" + this.fb_posts_index,
          this.auth,
          this.size,
          this.content,
          this.author_username
        ).then((data) => {
          this.fb_posts = data;



        });

        console.log("getting fb comments");
        await getFbData(
          this.url + "/" + this.fb_comments_index,
          this.auth,
          this.size,
          this.content,
          this.author_username
        ).then((data) => {
          this.fb_comments = data;

        });

        if (this.fb_posts.length == 0 && this.fb_comments.length == 0) {
          this.no_results = true;
        } else {
          this.no_results = false;
        }

      }
    },
    dateFormat(value) {
      return new Date(+value)
    },
    keywordsFormat(value) {
      if (value == null) return value;
      console.log(typeof value)
      return value.toString()
    }
  },

  async mounted() {
    this.media_chosen = this.media;
    this.author_username = this.author;
    if (this.data_id) {
      if (this.media_chosen == 'twitter') {
        await getTweet(
          this.url + "/" + this.tweets_index,
          this.auth,
          this.data_id)
          .then((data) => {
            this.content = data.content;
          })
      }

      if (this.media_chosen == 'facebook') {
        await getFbRecord(
          this.url + "/" + this.fb_posts_index,
          'fb_post',
          this.auth,
          this.data_id
        ).then((data) => {
          if (data) this.content = data.content
        })

        if (this.content == null) {
          await getFbRecord(
            this.url + "/" + this.fb_comments_index,
            'fb_comment',
            this.auth,
            this.data_id
          ).then((data) => {
            if (data) this.content = data.content
          })
        }
      }



    }
    await this.getData()
  },
};
</script>
<style>
</style>