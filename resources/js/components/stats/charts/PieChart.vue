<template >
  <Responsive>
    <template #main="{  }">
      <Chart
        direction="circular"
        :size="{ width: 800, height: 500 }"
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
              colors: [
                '#fcba03',
                '#2f578f',
                '#8d2f8f',
                '#32a852',
                '#db4f1d',
                '#1dd2db',
              ],
            }"
          />
        </template>
        <template #widgets>
          <Tooltip :config="this.tooltip_config" hideLine />
        </template>
      </Chart>
    </template>
  </Responsive>
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
    console.log(this.tooltip_config)
    document.getElementsByClassName("axis")[0].style.visibility = "hidden";
  },
};
</script>
<style >
</style>