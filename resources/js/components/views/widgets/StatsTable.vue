<template>
  <div class="row">
    <div class="col-auto">
      <vue-excel-xlsx
        :data="this.data"
        :columns="this.export_columns"
        :file-name="this.stats_category"
        :file-type="'xlsx'"
      >
        Pobierz
      </vue-excel-xlsx>
    </div>
    <div class="col-auto">
      <select class="form-select" v-model="this.size">
        <option value="10">10</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
    </div>
    <div class="col-auto">
      <button
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#modalWords"
        v-if="this.stats_category == 'słowa'"
      >
        Pokaz wykres
      </button>
      <button
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#modalDays"
        v-if="this.stats_category == 'dni'"
      >
        Pokaz wykres
      </button>
      <button
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#modalCategories"
        v-if="this.stats_category == 'kategorie'"
      >
        Pokaz wykres
      </button>
      <button
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#modalAuthors"
        v-if="this.stats_category == 'autorzy'"
      >
        Pokaz wykres
      </button>
    </div>
    <div class="col-auto" v-if="this.stats_category == 'słowa'">
      <button
        class="btn btn-primary"
        @click="countDeclination"
        v-if="this.declination_mode == false"
      >
        Deklinacja
      </button>
      <button class="btn btn-primary" @click="detailedCount" v-else>
        Szczegółowo
      </button>
    </div>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col" v-for="column in this.columns" :key="column">
          {{ column }}
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(key, index) in this.data.slice(0, this.size)" :key="index">
        <th scope="row">{{ index + 1 }}</th>
        <td v-if="this.stats_category == 'autorzy'">
          <a
            :href="'/search/twitter/author_username=' + key.key"
            target="_blank"
            >{{ key.key }}</a
          >
        </td>
        <td
          v-else-if="this.stats_category == 'słowa' && !this.declination_mode"
        >
          <a :href="'/search/twitter/content=' + key.key" target="_blank">{{
            key.key
          }}</a>
        </td>
        <td v-else-if="this.stats_category == 'słowa' && this.declination_mode">
          <a :href="'/search/twitter/declination=' + key.key" target="_blank">{{
            key.key
          }}</a>
        </td>
        <td v-else-if="this.stats_category == 'kategorie'">
          <a :href="'/search/twitter/category=' + key.key" target="_blank">{{
            key.key
          }}</a>
        </td>
        <td v-else>{{ key.key }}</td>
        <td>{{ key.doc_count }}</td>
      </tr>
    </tbody>
  </table>

  <!-- Modal words -->
  <div
    class="modal fade"
    id="modalWords"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Wykres</h5>
        </div>
        <div class="modal-body">
          <pie-chart
            :data="this.words_data ? this.words_data.slice(0, this.size) : []"
            :tooltip_config="this.words_tooltip_config"
            :data_keys="['key', 'doc_count']"
          ></pie-chart>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Zamknij
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal days -->
  <div
    class="modal fade"
    id="modalDays"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Wykres</h5>
        </div>
        <div class="modal-body">
          <bar-chart
            :data="this.days_data ? this.days_data.slice(0, this.size) : []"
            :tooltip_config="this.words_tooltip_config"
            :data_keys="['key', 'doc_count']"
            direction="vertical"
          ></bar-chart>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Zamknij
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal categories -->
  <div
    class="modal fade"
    id="modalCategories"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Wykres</h5>
        </div>
        <div class="modal-body">
          <bar-chart
            :data="this.categories_data ? this.categories_data.slice(0, this.size) : []"
            :tooltip_config="this.categories_tooltip_config"
            :data_keys="['key', 'doc_count']"
            direction="vertical"
          ></bar-chart>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Zamknij
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal authors -->
  <div
    class="modal fade"
    id="modalAuthors"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Wykres</h5>
        </div>
        <div class="modal-body">
          <bar-chart
            :data="this.data.slice(0, this.size)"
            :tooltip_config="this.authors_tooltip_config"
            :data_keys="['key', 'doc_count']"
            direction="vertical"
          ></bar-chart>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Zamknij
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
  <script>
import PieChart from "../../stats/charts/PieChart.vue";
import BarChart from "../../stats/charts/BarChart.vue";

export default {
  props: {
    data: Array,
    words_data: Array,
    days_data: Array,
    categories_data: Array,
    authors_data: Array,
    stats_category: String,
    declination_mode: {
      type: Boolean,
      default: false,
    },
  },
  components: { PieChart, BarChart },
  emits: ["count-declinations", "detailed-count"],
  data() {
    return {
      size: 10,
      columns: [null, "liczba wyników"],
      export_columns: [
        {
          label: null,
          field: "key",
        },
        {
          label: "Liczba wpisów",
          field: "doc_count",
        },
      ],
      words_tooltip_config: {
        key: { label: "słowo" },
        doc_count: { label: "liczba wpisów" },
      },
      days_tooltip_config: {
        key: { label: "dzień" },
        doc_count: { label: "liczba wpisów" },
      },
      categories_tooltip_config: {
        key: { label: "kategoria" },
        doc_count: { label: "liczba wpisów" },
      },
      authors_tooltip_config: {
        key: { label: "autor" },
        doc_count: { label: "liczba wpisów" },
      },
    };
  },
  methods: {
    detailedCount() {
      this.$emit("detailed-count");
    },
    countDeclination() {
      this.$emit("count-declinations");
    },
  },
  mounted() {
    console.log('kategoria: ', this.stats_category);
    console.log('words: ', this.words_data)
    console.log('days: ', this.days_data)
    console.log('categories: ', this.categories_data)
    console.log('authors: ', this.authors_data)
    this.columns[0] = this.stats_category;
    this.export_columns[0].label = this.stats_category;
  },
};
</script>
  <style>
</style>