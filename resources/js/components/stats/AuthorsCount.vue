<template>
  <h1>{{ this.hate_speech_category }}</h1>
  <div class="row">
    <div class="col-auto">
      <select class="form-select" v-model="this.mode">
        <option value="numbers">Zebrane tweety</option>
        <option value="growth">Przyrosty</option>
      </select>
    </div>
    <div class="col-auto" v-if="this.mode == 'numbers'">
      <select class="form-select" v-model="this.chart_type">
        <option value="pie">Kołowy</option>
        <option value="bar">Słupkowy</option>
        <option value="line">Liniowy</option>
        <option value="area">Obszarowy</option>
      </select>
    </div>
    <div class="col-auto" v-if="this.mode == 'growth'">
      <select class="form-select" v-model="this.growth_period">
        <option value="13">Kwartał</option>
        <option value="27">Pół roku</option>
        <option value="54">Rok</option>
      </select>
    </div>
    <div class="col-auto">
      <div class="form-check" style="padding-left: 0px">
        <select class="form-select" v-model="chart_limit">
          <option v-for="option in this.chart_limit_options" :value="option.value" :key="option"> {{ option.text }}</option>
        </select>
      </div>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary" @click="saveChart">Zapisz wykres</button>
    </div>
  </div>

  <!-- charts -->
  <div v-if="this.mode == 'numbers'">
    <pie-chart
      :data="this.data.slice(0, this.chart_limit)"
      :tooltip_config="this.tooltip_config"
      v-if="chart_type == 'pie'"
    >
    </pie-chart>
    <bar-chart
      :data="this.data.slice(0, this.chart_limit)"
      :tooltip_config="this.tooltip_config"
      :direction="this.vertical_direction"
      v-if="chart_type == 'bar'"
    ></bar-chart>
    <area-chart
      :data="this.data.slice(0, this.chart_limit)"
      :direction="this.horizontal_direction"
      v-if="chart_type == 'area'"
    >
    </area-chart>
    <line-chart
      :data="this.data.slice(0, this.chart_limit)"
      :direction="this.horizontal_direction"
      v-if="chart_type == 'line'"
    >
    </line-chart>
  </div>
  <div v-else>
    <growth-line-chart
      :data="this.week_stats.slice(-this.growth_period)"
    ></growth-line-chart>
  </div>

  <authors-table
    :data="this.data"
    :hate_speech_category="this.hate_speech_category"
  ></authors-table>
</template>
<script>
import BarChart from "./charts/BarChart.vue";
import PieChart from "./charts/PieChart.vue";
import AreaChart from "./charts/AreaChart.vue";
import LineChart from "./charts/LineChart.vue";
import GrowthLineChart from "./charts/GrowthLineChart.vue";
import AuthorsTable from "./tables/AuthorsTable.vue";
import * as htmlToImage from "html-to-image";
import { toPng, toJpeg, toBlob, toPixelData, toSvg } from "html-to-image";

export default {
  props: { data: Array, data_growth: Array, hate_speech_category: String },
  components: {
    PieChart,
    BarChart,
    LineChart,
    AreaChart,
    AuthorsTable,
    GrowthLineChart,
  },
  watch: {
    data_growth(oldVal, newVal) {
      var num_of_weeks = Math.floor(this.data_growth.length / 7);
      for (var i = 0; i < num_of_weeks; i++) {
        this.week_stats.push(
          JSON.parse(JSON.stringify(this.data_growth))[i * 7]
        );
      }
    },
  },
  data() {
    return {
      chart_type: "pie",
      week_stats: [],
      vertical_direction: "vertical",
      horizontal_direction: "horizontal",
      mode: "numbers",
      tooltip_config: {
        author: { label: "autor" },
        count: { label: "liczba wpisów" },
      },
      growth_period: 13,
      chart_limit: 10,
      chart_limit_options: [
        { value: '10', text: '10' },
        { value: '20', text: '20' },
        { value: '30', text: '30' },
        { value: '40', text: '40' },
        { value: '50', text: '50' },
        { value: '100', text: '100' },
            ]
    };
  },
  methods: {
    saveChart() {
      console.log("elo");
      htmlToImage
        .toJpeg(document.getElementById("chart"), { quality: 0.95, backgroundColor: '#FFFFFF' })
        .then(function (dataUrl) {
          var link = document.createElement("a");
          link.download = "my-image-name.jpeg";
          link.href = dataUrl;
          link.click();
        });
    },
  }
};
</script>
<style>
</style>