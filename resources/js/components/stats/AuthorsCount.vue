<template>
  <h1>{{ this.hate_speech_category }}</h1>
  <div class="row">
    <div class="col-auto">
      <select class="form-select" v-model="this.chart_type">
        <option value="pie">Kołowy</option>
        <option value="bar">Słupkowy</option>
      </select>
    </div>
  </div>

  <!-- charts -->
  <pie-chart
    :data="this.data.slice(0, 20)"
    :tooltip_config="this.tooltip_config"
    v-if="chart_type == 'pie'"
  ></pie-chart>
  <bar-chart
    :data="this.data.slice(0, 20)"
    :tooltip_config="this.tooltip_config"
    :direction="this.direction"
    v-else
  ></bar-chart>

  <!-- tables -->
  <vue-excel-xlsx
    :data="this.data"
    :columns="this.columns_authors"
    :file-name="'Autorzy - ' + this.hate_speech_category"
    :file-type="'xlsx'"
    :sheet-name="'Autorzy'"
  >
    Pobierz kategorie
  </vue-excel-xlsx>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Autor</th>
        <th scope="col">Liczba wyników</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(author, index) in this.data" :key="index">
        <th scope="row">{{ index + 1 }}</th>
        <td>
          <a :href="'/stats/author=' + author.name" target="_blank">{{
            author.name
          }}</a>
        </td>
        <td>{{ author.count }}</td>
        <td>
          <a :href="'https://twitter.com/' + author.author" target="_blank"
            >Zobacz profil</a
          >
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
import BarChart from "./BarChart.vue";
import PieChart from "./PieChart.vue";

export default {
  props: { data: Array, hate_speech_category: String },
  components: { PieChart, BarChart },
  data() {
    return {
      chart_type: "pie",
      direction: "vertical",
      tooltip_config: {
        author: { label: "autor" },
        count: { label: "liczba wpisów" },
      },
      columns_authors: [
        {
          label: "Autor",
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