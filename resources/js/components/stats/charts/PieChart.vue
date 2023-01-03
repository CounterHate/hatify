<template >
  <div class="row">
    <div class="col-auto">
      <div class="row" v-for="(result, index) in this.data" :key="index" style="padding: 4px;">
        <span
          class="badge"
          :style="{'background-color': this.colors[index]}"
          >{{ result.key }}</span
        >
      </div>
    </div>
    <div class="col-auto">
      <Responsive>
        <template #main="{}">
          <Chart
            direction="circular"
            :size="{ width: 600, height: 500 }"
            :data="this.data"
            :axis="this.axis"
            :config="{ controlHover: false }"
            id="chart"
          >
            <template #layers>
              <Pie
                :dataKeys="this.data_keys"
                :pie-style="{
                  innerRadius: 50,
                  padAngle: 0.05,
                  colors: this.colors,
                }"
              />
            </template>
            <template #widgets>
              <Tooltip :config="this.tooltip_config" hideLine />
            </template>
          </Chart>
        </template>
      </Responsive>
    </div>
  </div>
</template>
<script>
import { Chart, Responsive, Pie, Tooltip } from "vue3-charts";
export default {
  props: {
    data: Array,
    tooltip_config: Object,
    data_keys: { default: ["author", "count"], type: Array },
    tooltip_config: Object,
  },
  components: {
    Chart,
    Responsive,
    Pie,
    Tooltip,
  },
  data() {
    return {
      colors: [
        "#696969",
        "#2e8b57",
        "#8b0000",
        "#808000",
        "#000080",
        "#ff0000",
        "#ffa500",
        "#7fff00",
        "#ba55d3",
        "#e9967a",
        "#00ffff",
        "#0000ff",
        "#ff00ff",
        "#1e90ff",
        "#eee8aa",
        "#ffff54",
        "#dda0dd",
        "#ff1493",
        "#98fb98",
        "#87cefa",
      ],
      axis: {
        primary: {
          domain: ["dataMin", "dataMax"],
          type: "band",
        },
        secondary: {
          domain: ["dataMin", "dataMax"],
          type: "linear",
        },
      },
    };
  },
  mounted() {
    document.getElementsByClassName("axis")[0].style.visibility = "hidden";
  },
};
</script>
<style >
</style>