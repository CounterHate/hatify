<template>
  <!-- select row -->
  <div class="row">
    <!-- form to pick data order -->
    <search-select-input
      :options="this.mediaOptions"
      v-model="media_chosen"
      label="Wybierz medium"
    ></search-select-input>

    <!-- form to pick data order -->
    <search-select-input
      :options="this.sortOptions"
      v-model="sortOrder"
      label="Wybierz kolejność"
      @change="sortData"
    ></search-select-input>

    <!-- form to pick data order -->
    <search-select-input
      :options="this.sizeOptions"
      v-model="size"
      label="Wybierz liczbę wyświetlanych wyników"
    ></search-select-input>
  </div>

  <!-- text input row -->
  <div class="row" style="margin-top: 8px">
    <search-text-input
      label="Treść"
      v-model="params.content"
      :tooltip_text="this.tooltip_content_text"
      placeholder="Treść do wyszukania"
      v-on:keyup.enter="getDataWithStats"
    ></search-text-input>

    <search-text-input
      label="Konto do wyszukiwania"
      v-model="params.author_username"
      :tooltip_text="null"
      placeholder="konto do wyszukiwania"
      v-on:keyup.enter="getDataWithStats"
    ></search-text-input>
  </div>
  <div class="row">
    <div class="col">
      <label>Od</label>
      <Datepicker v-model="this.params.gte"></Datepicker>
    </div>
    <div class="col">
      <label>Do</label>
      <Datepicker v-model="this.params.lte"></Datepicker>
    </div>
    <div class="col">
      <label class="form-label"
        >Dokładność {{ this.params.min_score }}
        <i
          data-bs-toggle="tooltip"
          data-bs-placement="top"
          :title="this.tooltip_min_score_text"
          class="bi bi-info-circle"
        ></i
      ></label>
      <input
        type="range"
        class="form-range"
        v-model="this.params.min_score"
        min="0"
        max="100"
        step="0.01"
      />
    </div>
  </div>
  <br />
  <div class="row" style="margin-top: 8px">
    <VueMultiselect
      v-model="params.hate_categories"
      :options="hate_categories_options"
      :multiple="true"
      :searchable="true"
      placeholder="Kategorie"
      :clear-on-select="false"
      :close-on-select="false"
      style="margin-bottom: 8px"
    >
    </VueMultiselect>
  </div>
  <button type="submit" class="btn btn-success mb-3" @click="getDataWithStats">
    Filtruj
  </button>

  <!-- results -->
  <div class="d-flex justify-content-center" v-if="is_loading">
    <div class="spinner-border" role="status">
      <span class="sr-only"></span>
    </div>
  </div>
  <div v-if="this.no_results == true">
    Brak wyników dla wybranych opcji. Spróbuj zmienić parametry szukania i
    wyszukać ponownie
  </div>

  <!-- render tweets -->
  <div v-if="this.stats_mode">
    <button
      class="btn btn-primary"
      @click="this.stats_mode = false"
      style="margin-bottom: 8px"
    >
      Pokaz wpisy
    </button>
    <div class="row">
      <div class="col">
        <stats-table-vue
          :data="this.stats.words.buckets"
          stats_category="słowa"
        ></stats-table-vue>
      </div>
      <div class="col">
        <stats-table-vue
          :data="this.stats.dates.buckets"
          stats_category="dni"
        ></stats-table-vue>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <stats-table-vue
          :data="this.stats.categories.buckets"
          stats_category="kategorie"
        ></stats-table-vue>
      </div>
      <div class="col">
        <stats-table-vue
          :data="this.stats.authors.buckets"
          stats_category="autorzy"
        ></stats-table-vue>
      </div>
    </div>
  </div>
  <div v-else>
    <div v-if="this.media_chosen == 'twitter'">
      <p>
        Znaleziono {{ this.total_count }} wpisów. Wyświetlono
        {{ this.size < this.total_count ? this.size : this.total_count }}
      </p>

      <div class="row" style="margin-top: 8px">
        <div class="col-auto">
          <vue-excel-xlsx
            :data="this.tweets"
            :columns="columns"
            :file-name="'tweets'"
            :file-type="'xlsx'"
            :sheet-name="'wyniki'"
            class="btn btn-success"
          >
            Pobierz
          </vue-excel-xlsx>
        </div>

        <!-- pagination -->
        <div class="col-auto">
          <button class="btn btn-primary" @click="this.previousPage">
            <i class="bi bi-arrow-left"></i>
          </button>
        </div>
        <div class="col-auto">
          {{ this.size * this.result_page_number }} -
          {{
            this.size * (this.result_page_number + 1) < this.tweets.length
              ? this.size * (this.result_page_number + 1)
              : this.tweets.length
          }}
        </div>
        <div class="col-auto">
          <button class="btn btn-primary" @click="this.nextPage">
            <i class="bi bi-arrow-right"></i>
          </button>
        </div>
        <!-- end of pagination -->
        <div class="col-auto">
          <button class="btn btn-primary" @click="this.stats_mode = true">
            Pokaz statystyki
          </button>
        </div>
      </div>

      <tweet
        v-for="(t, index) in this.tweets.slice(
          this.size * this.result_page_number,
          this.size * (this.result_page_number + 1) < this.tweets.length
            ? this.size * (this.result_page_number + 1)
            : this.tweets.length
        )"
        :key="index"
        :data="t"
      ></tweet>
    </div>
    <div v-else>
      <!-- render fb posts and comments -->
      <div v-if="this.fb_posts.length > 0">
        <fb-post
          v-for="p in this.fb_posts"
          :key="p"
          :data="p"
          :anotation_view="false"
          :verification_view="false"
        >
        </fb-post>
      </div>
      <div v-if="this.fb_comments.length > 0">
        <fb-comment
          v-for="c in this.fb_comments"
          :key="c"
          :data="c"
          :anotation_view="false"
          :verification_view="false"
        >
        </fb-comment>
      </div>
    </div>
  </div>
</template>
<script>
import Tweet from "../entities/Tweet.vue";
import FbPost from "../entities/FbPost.vue";
import FbComment from "../entities/FbComment.vue";
import { getDataForQuery } from "../../es.js";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import VueMultiselect from "vue-multiselect";
import SearchSelectInput from "./widgets/SearchSelectInput";
import SearchTextInput from "./widgets/SearchTextInput";
import StatsTableVue from "./widgets/StatsTable.vue";

export default {
  props: { data_id: String, media: String, author: String },
  components: {
    Tweet,
    FbPost,
    FbComment,
    Datepicker,
    VueMultiselect,
    SearchSelectInput,
    SearchTextInput,
    StatsTableVue,
  },
  data() {
    return {
      columns: [
        {
          label: "Czy jest mową nienawiści",
          field: "is_hate_speech",
        },
        {
          label: "Czy to retweet",
          field: "is_retweet",
        },
        {
          label: "Login autora",
          field: "author_username",
        },
        {
          label: "Autor",
          field: "author_name",
        },
        {
          label: "Treść",
          field: "content",
        },
        {
          label: "Data wysłania",
          field: "posted_utime",
          dataFormat: this.dateFormat,
        },
        {
          label: "Słowa kluczowe",
          field: "keywords",
          dataFormat: this.keywordsFormat,
        },
        {
          label: "Link",
          field: "url",
        },
      ],
      tooltip_content_text:
        "Wyszukuje treści podobnej do podanej. Przykładowo 'nienawidzę żydów' jest treścią podobną do 'nienawidzę ukrów i żydów'",
      tooltip_min_score_text:
        "Jest to abstrakcyjna wartość, która pokazuje jak zbliżony jest dany wpis do podanych parametrów wyszukiwania. Im wyższa wartość tym bardziej odpowiada parametrom.",
      url: process.env.MIX_ES,
      tweets_index: process.env.MIX_TWEETS_INDEX,
      fb_posts_index: process.env.MIX_FBPOSTS_INDEX,
      fb_comments_index: process.env.MIX_FBCOMMENTS_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      params: {
        author_username: null,
        gte: null,
        lte: null,
        hate_categories: [],
        content: null,
        min_score: 0,
      },
      stats_mode: false,
      result_page_number: 0,
      total_count: 0,
      tweets: [],
      fb_posts: [],
      fb_comments: [],
      stats: [],
      is_loading: false,
      no_results: false,
      sortOrder: null,
      sortOptions: [
        { value: "ascending", text: "Od najstarszych" },
        { value: "descending", text: "Od najnowszych" },
        { value: "ascending_score", text: "Od najmniejszej dokładności" },
        { value: "descending_score", text: "Od największej dokładności" },
      ],
      size: 10,
      sizeOptions: [
        { value: "10", text: "10" },
        { value: "50", text: "50" },
        { value: "100", text: "100" },
        { value: "500", text: "500" },
        { value: "1000", text: "1000" },
        { value: "5000", text: "5000" },
        { value: "10000", text: "10000" },
      ],
      media_chosen: "twitter",
      mediaOptions: [
        { value: "twitter", text: "twitter" },
        { value: "facebook", text: "facebook" },
      ],
      hate_categories_options: [
        "Osoby należące do mniejszości etnicznych lub narodowych w Polsce",
        "Osoby z doświadczeniem migracji",
        "Osoby o innym kolorze skóry",
        "Osoby LGBTQ+",
        "antysemityzm",
        "Romowie",
        "Osoby należące do mniejszości religijnych w Polsce",
        "Kobiety",
      ],
    };
  },
  methods: {
    getDataForQuery,
    sortData() {
      if (this.media_chosen == "facebook") {
        if (this.sortOrder == "ascending") {
          this.fb_posts.sort((a, b) => (a.date > b.date ? 1 : -1));
          this.fb_comments.sort((a, b) => (a.date > b.date ? 1 : -1));
        }
        if (this.sortOrder == "descending") {
          this.fb_posts.sort((a, b) => (a.date < b.date ? 1 : -1));
          this.fb_comments.sort((a, b) => (a.date < b.date ? 1 : -1));
        }
      }

      if (this.media_chosen == "twitter") {
        if (this.sortOrder == "ascending")
          this.tweets.sort((a, b) =>
            a.posted_utime > b.posted_utime ? 1 : -1
          );
        if (this.sortOrder == "descending")
          this.tweets.sort((a, b) =>
            a.posted_utime < b.posted_utime ? 1 : -1
          );
        if (this.sortOrder == "ascending_score")
          this.tweets.sort((a, b) => (a.score > b.score ? 1 : -1));
        if (this.sortOrder == "descending_score")
          this.tweets.sort((a, b) => (a.score < b.score ? 1 : -1));
      }
    },
    async getDataWithStats() {
      this.getDataForQuery(
        this.url + "/" + this.tweets_index + "/_search",
        this.auth,
        this.params
      ).then((data) => {
        this.tweets = data.tweets;
        this.total_count = data.total;
        this.stats = data.stats;
        this.sortData(data, this.sortOrder, this.media_chosen);
        if (this.tweets.length == 0) {
          this.no_results = true;
        } else {
          this.no_results = false;
        }
        this.is_loading = false;
      });
    },
    dateFormat(value) {
      return new Date(+value);
    },
    keywordsFormat(value) {
      if (value == null) return value;
      return value.toString();
    },
    nextPage() {
      if (this.result_page_number * (this.size + 1) < this.tweets.length)
        this.result_page_number++;
    },
    previousPage() {
      if (this.result_page_number > 0) this.result_page_number--;
    },
  },

  async mounted() {
    this.media_chosen = this.media;
    this.params.author_username = this.author;
    // if (this.data_id) {
    //   if (this.media_chosen == "twitter") {
    //     await getTweet(
    //       this.url + "/" + this.tweets_index,
    //       this.auth,
    //       this.data_id
    //     ).then((data) => {
    //       this.content = data.content;
    //     });
    //   }

    //   if (this.media_chosen == "facebook") {
    //     await getFbRecord(
    //       this.url + "/" + this.fb_posts_index,
    //       "fb_post",
    //       this.auth,
    //       this.data_id
    //     ).then((data) => {
    //       if (data) this.content = data.content;
    //     });

    //     if (this.content == null) {
    //       await getFbRecord(
    //         this.url + "/" + this.fb_comments_index,
    //         "fb_comment",
    //         this.auth,
    //         this.data_id
    //       ).then((data) => {
    //         if (data) this.content = data.content;
    //       });
    //     }
    //   }
    // }
    await this.getDataWithStats();
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
