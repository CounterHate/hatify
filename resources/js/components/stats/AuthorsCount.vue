<template>
  <h1>{{ this.hate_speech_category }}</h1>
  <div class="row">
    <div class="col-auto">
      <select class="form-select" v-model="this.chart_type">
        <option value="pie">Kołowy</option>
        <option value="bar">Słupkowy</option>
        <option value="line">Liniowy</option>
        <option value="area">Obszarowy</option>
      </select>
    </div>
    <div class="col-auto">
      <select class="form-select" v-model="this.mode">
        <option value="numbers">Zebrane tweety</option>
        <option value="growth">Przyrosty</option>
      </select>
    </div>
  </div>

  <!-- charts -->
  <div v-if="this.mode == 'numbers'">
    <pie-chart
      :data="this.data.slice(0, 20)"
      :tooltip_config="this.tooltip_config"
      v-if="chart_type == 'pie'"
    ></pie-chart>
    <bar-chart
      :data="this.data.slice(0, 20)"
      :tooltip_config="this.tooltip_config"
      :direction="this.vertical_direction"
      v-if="chart_type == 'bar'"
    ></bar-chart>
    <area-chart
      :data="this.data.slice(0, 20)"
      :direction="this.horizontal_direction"
      v-if="chart_type == 'area'"
    >
    </area-chart>
    <line-chart
      :data="this.data.slice(0, 20)"
      :direction="this.horizontal_direction"
      v-if="chart_type == 'line'"
    >
    </line-chart>
  </div>
  <div v-else>
    <growth-area-chart :data="this.data_growth" v-if="this.chart_type == 'area'"></growth-area-chart>
    <growth-line-chart :data="this.data_growth" v-if="this.chart_type == 'line'"></growth-line-chart>
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
import GrowthAreaChart from "./charts/GrowthAreaChart.vue";
import GrowthLineChart from "./charts/GrowthLineChart.vue"
import AuthorsTable from "./tables/AuthorsTable.vue";


export default {
  props: { data: Array, data_growth: Array, hate_speech_category: String },
  components: {
    PieChart,
    BarChart,
    LineChart,
    AreaChart,
    GrowthAreaChart,
    AuthorsTable,
    GrowthLineChart
  },
  data() {
    return {
      chart_type: "pie",
      vertical_direction: "vertical",
      horizontal_direction: "horizontal",
      mode: "numbers",
      tooltip_config: {
        author: { label: "autor" },
        count: { label: "liczba wpisów" },
      },
    };
  },
};
</script>
<style>
</style>