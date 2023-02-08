<template>
  <div class="card card-frame" style="margin-bottom: 16px">
    <div class="card-body">
      <!-- select row -->
      <div class="row">
        <!-- form to pick data order -->
        <search-select-input
          :options="this.media_options"
          v-model="media_chosen"
          label="Wybierz medium"
        ></search-select-input>

        <!-- form to pick data order -->
        <search-select-input
          :options="this.sort_options"
          v-model="sortOrder"
          label="Wybierz kolejność"
          @change="sortData"
        ></search-select-input>

        <!-- form to pick data order -->
        <search-select-input
          :options="this.size_options"
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
        <!-- <div class="col">
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
    </div> -->
      </div>
      <br />
      <div class="row" style="margin-top: 8px">
        <div class="col">
          <label>Kategorie</label>
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

        <div class="col">
          <label>Słowa kluczowe</label>
          <VueMultiselect
            v-model="keywords_selected"
            :options="keywords"
            :multiple="true"
            :searchable="true"
            placeholder="Słowa kluczowe"
            :clear-on-select="false"
            :close-on-select="false"
            style="margin-bottom: 8px"
          >
          </VueMultiselect>
        </div>
      </div>
      <button
        type="submit"
        class="btn btn-success mb-3"
        @click="getDataWithStats"
      >
        Filtruj
      </button>
    </div>
  </div>

  <!-- results -->
  <div class="d-flex justify-content-center" v-if="is_loading">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
  <div v-if="this.no_results == true">
    Brak wyników dla wybranych opcji. Spróbuj zmienić parametry szukania i
    wyszukać ponownie
  </div>

  <!-- render tweets -->
  <div v-if="this.stats_mode">
    <button
      v-if="!this.is_loading"
      class="btn btn-primary"
      @click="this.stats_mode = false"
      style="margin-bottom: 8px"
    >
      Pokaż wpisy
    </button>
    <div class="row" v-if="this.stats != 0">
      <div class="col">
        <words-stats
          :data="this.wordsStats"
          :declination_mode="this.declinations_stats_mode"
          @count-declinations="calcDeclinations"
          v-if="!this.declinations_stats_mode"
        >
        </words-stats>
        <words-stats
          :data="this.wordsStats"
          :declination_mode="this.declinations_stats_mode"
          @detailed-count="this.declinations_stats_mode = false"
          v-else
        >
        </words-stats>
      </div>
      <div class="col">
        <days-stats
          :data="this.stats.dates.buckets"
          :data_sorted="this.dates_sorted"
        ></days-stats>
      </div>
    </div>
    <div class="row" v-if="this.stats.length != 0">
      <div class="col">
        <categories-stats
          :data="this.stats.categories.buckets"
        ></categories-stats>
      </div>
      <div class="col">
        <authors-stats :data="this.stats.authors.buckets"></authors-stats>
      </div>
    </div>
  </div>
  <div v-else>
    <div v-if="this.media_chosen == 'twitter'">
      <p>
        Znaleziono {{ this.total_count }} wpisów. Wyświetlono
        {{ this.size < this.total_count ? this.size : this.total_count }}
      </p>

      <div class="row">
        <div class="col-auto">
          <vue-excel-xlsx
            :data="this.tweets"
            :columns="columns"
            :file-name="'tweets'"
            :file-type="'xlsx'"
            :sheet-name="'wyniki'"
            class="btn btn-success"
            style="margin: 0px"
          >
            Pobierz
          </vue-excel-xlsx>
        </div>

        <!-- pagination -->
        <div class="col-auto">
          <button
            class="btn btn-primary"
            style="margin: 0px"
            @click="this.previousPage"
          >
            <i class="bi bi-arrow-left"></i>
          </button>
        </div>
        <div class="col-auto">
          <span style="vertical-align: middle">
            {{ this.size * this.result_page_number }} -
            {{
              this.size * (this.result_page_number + 1) < this.tweets.length
                ? this.size * (this.result_page_number + 1)
                : this.tweets.length
            }}
          </span>
        </div>
        <div class="col-auto">
          <button
            class="btn btn-primary"
            style="margin: 0px"
            @click="this.nextPage"
          >
            <i class="bi bi-arrow-right"></i>
          </button>
        </div>
        <!-- end of pagination -->
        <div class="col-auto">
          <button
            class="btn btn-primary"
            style="margin: 0px"
            @click="this.stats_mode = true"
          >
            Pokaż statystyki
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
import { getDataForQuery, getDeclinations } from "../../es.js";
import {
  hco,
  size_options,
  media_options,
  sort_options,
  columns,
} from "../../const.js";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import VueMultiselect from "vue-multiselect";
import SearchSelectInput from "./widgets/SearchSelectInput";
import SearchTextInput from "./widgets/SearchTextInput";
import WordsStats from "./widgets/WordsStats.vue";
import DaysStats from "./widgets/DaysStats.vue";
import CategoriesStats from "./widgets/CategoriesStats.vue";
import AuthorsStats from "./widgets/AuthorsStats.vue";
export default {
  props: {
    data_id: String,
    media: String,
    author: String,
    category: String,
    content: String,
    declination: String,
  },
  components: {
    Tweet,
    FbPost,
    FbComment,
    Datepicker,
    VueMultiselect,
    SearchSelectInput,
    SearchTextInput,
    WordsStats,
    DaysStats,
    CategoriesStats,
    AuthorsStats,
  },
  data() {
    return {
      columns: columns,
      tooltip_content_text:
        "Wyszukuje treści podobnej do podanej. Przykładowo 'nienawidzę żydów' jest treścią podobną do 'nienawidzę ukrów i żydów'",
      tooltip_min_score_text:
        "Jest to abstrakcyjna wartość, która Pokażuje jak zbliżony jest dany wpis do podanych parametrów wyszukiwania. Im wyższa wartość tym bardziej odpowiada parametrom.",
      url: process.env.MIX_ES,
      tweets_index: process.env.MIX_TWEETS_INDEX,
      fb_posts_index: process.env.MIX_FBPOSTS_INDEX,
      fb_comments_index: process.env.MIX_FBCOMMENTS_INDEX,
      declinations_index: process.env.MIX_DECLINATIONS_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      params: {
        author_username: "",
        gte: null,
        lte: null,
        hate_categories: [],
        content: "",
        min_score: 0,
        declinations: "",
      },
      declinations_stats: [],
      declinations_count_dict: {},
      declinations_stats_mode: false,
      stats_mode: true,
      result_page_number: 0,
      total_count: 0,
      declinations: {},
      keywords: [],
      keywords_selected: [],
      tweets: [],
      fb_posts: [],
      fb_comments: [],
      stats: [],
      dates_sorted: [],
      is_loading: false,
      no_results: false,
      sortOrder: null,
      sort_options: sort_options,
      size: 10,
      size_options: size_options,
      media_chosen: "twitter",
      media_options: media_options,
      hate_categories_options: hco,
    };
  },
  computed: {
    wordsStats() {
      return this.declinations_stats_mode
        ? this.declinations_stats
        : this.stats.words.buckets;
    },
  },
  methods: {
    getDataForQuery,
    getDeclinations,
    sortDates() {
      this.dates_sorted = [...this.stats.dates.buckets];
      this.dates_sorted.sort((a, b) =>
        this.getDateFromString(a.key) > this.getDateFromString(b.key) ? 1 : -1
      );
    },
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
    async getDeclinationsFromEs() {
      this.getDeclinations(
        this.url + "/" + this.declinations_index + "/_search",
        this.auth
      )
        .then((data) => {
          this.declinations = data.declinations;
          this.keywords = data.keywords;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    removeRussiaFromWords() {
      this.stats.words.buckets.forEach((el) => {
        if (this.declinations["rusek"].includes(el.key)) {
          this.stats.words.buckets.splice(
            this.stats.words.buckets.indexOf(el),
            1
          );
        }
      });
      delete this.declinations["rusek"];
    },
    async getDataWithStats() {
      this.is_loading = true;
      if (this.keywords_selected.length > 0) {
        this.params.declinations = this.keywords_selected.toString();
      }
      this.getDataForQuery(
        this.url + "/" + this.tweets_index + "/_search",
        this.auth,
        this.params
      ).then((data) => {
        this.tweets = data.tweets;
        this.total_count = data.total;
        this.stats = data.stats;
        // this.removeRussiaFromWords();
        this.sortData(data, this.sortOrder, this.media_chosen);
        this.tweets.length == 0
          ? (this.no_results = true)
          : (this.no_results = false);
        this.is_loading = false;
        this.stats.words.buckets.forEach(
          (b) => (this.declinations_count_dict[b.key] = b.doc_count)
        );
        this.sortDates();
      });
    },
    nextPage() {
      if (this.result_page_number * (this.size + 1) < this.tweets.length)
        this.result_page_number++;
    },
    previousPage() {
      if (this.result_page_number > 0) this.result_page_number--;
    },
    calcDeclinations() {
      this.declinations_stats = [];
      this.keywords.forEach((kw) => {
        // print("kw:",kw)
        if (Object.keys(this.declinations).includes(kw)) {
          let decl_count = 0;
          let checked_words = [];
          let words = this.declinations[kw].split(", ");
          words.forEach((w) => {
            if (!checked_words.includes(w)) {
              checked_words.push(w);
              if (w in this.declinations_count_dict)
                decl_count += this.declinations_count_dict[w];
            }
          });
          if (decl_count > 0)
            this.declinations_stats.push({ key: kw, doc_count: decl_count });
        }
      });
      this.declinations_stats.sort((a, b) =>
        a.doc_count < b.doc_count ? 1 : b.doc_count < a.doc_count ? -1 : 0
      );
      this.declinations_stats_mode = true;
    },
    getDateFromString(date) {
      var parts = date.split("-");
      let year = parts[2];
      let month = parts[1] < 10 ? "0" + parts[1] : parts[1];
      let day = parts[0] < 10 ? "0" + parts[0] : parts[0];
      let date_string = year + "-" + month + "-" + day;
      return new Date(date_string).getTime();
    },
  },

  async mounted() {
    this.media_chosen = this.media;
    this.params.author_username = this.author;
    if (this.category) this.params.hate_categories = [this.category];
    if (this.declination) this.keywords_selected = [this.declination];
    this.params.content = this.content;
    await this.getDeclinationsFromEs();
    if (this.declination) await new Promise((r) => setTimeout(r, 1000));
    await this.getDataWithStats();
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>

