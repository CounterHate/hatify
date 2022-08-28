<template>
  <h1>{{ this.author }}</h1>
  <div class="row">
    <div class="col-auto">
      <a
        class="btn btn-primary"
        :href="'/search/twitter/author_username=' + this.author"
        >Zobacz wpisy</a
      >
    </div>
    <div class="col-auto">
      <select class="form-select" v-model="this.chart_type">
        <option value="pie">Kołowy</option>
        <option value="bar">Słupkowy</option>
      </select>
    </div>
  </div>

  <!-- charts -->
  <pie-chart :data="this.data" :tooltip_config="this.tooltip_config" v-if="chart_type == 'pie'"></pie-chart>
  <bar-chart :data="this.data" :tooltip_config="this.tooltip_config" :direction="this.direction" v-else></bar-chart>

  <!-- tables -->
  <vue-excel-xlsx
    :data="this.data"
    :columns="columns_categories"
    :file-name="'Kategorie - ' + this.author"
    :file-type="'xlsx'"
    :sheet-name="'Kategorie - ' + this.author"
  >
    Pobierz kategorie
  </vue-excel-xlsx>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Kategoria</th>
        <th scope="col">Liczba wyników</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(category, index) in this.data" :key="index">
        <th scope="row">{{ index + 1 }}</th>
        <td>
          <a :href="'/stats/hate_category=' + category.name" target="_blank">{{
            category.name
          }}</a>
        </td>
        <td>{{ category.count }}</td>
      </tr>
    </tbody>
  </table>

  <vue-excel-xlsx
    :data="this.words"
    :columns="columns_words"
    :file-name="'Słowa - ' + this.author"
    :file-type="'xlsx'"
    :sheet-name="'Słowa - ' + this.author"
  >
    Pobierz słowa
  </vue-excel-xlsx>
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
      <tr
        v-for="(word, index) in this.words.sort((a, b) => b.count - a.count)"
        :key="index"
        v-show="word.count > 0"
      >
        <th scope="row">{{ index + 1 }}</th>
        <td>{{ word.word }}</td>
        <td>
          <a :href="'/stats/hate_category=' + word.category">{{
            word.category
          }}</a>
        </td>
        <td>{{ word.count }}</td>
      </tr>
    </tbody>
  </table>
</template>
<script>
import BarChart from "./BarChart.vue";
import PieChart from "./PieChart.vue";

export default {
  props: { data: Array, author: String, words: Array },
  components: {
    BarChart,
    PieChart,
  },
  data() {
    return {
      chart_type: "pie",
      direction: "vertical",
      tooltip_config: {
        name: { label: "kategoria" },
        count: { label: "liczba wpisów" },
      },
      columns_categories: [
        {
          label: "Kategoria",
          field: "name",
        },
        {
          label: "Liczba wpisów",
          field: "count",
        },
      ],
      columns_words: [
        {
          label: "Słowo",
          field: "word",
        },
        {
          label: "Liczba wpisów",
          field: "count",
        },
      ],
    };
  },
};
</script>
<style>
</style>