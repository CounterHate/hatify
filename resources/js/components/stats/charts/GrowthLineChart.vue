<template >
  <Chart
    :size="{ width: this.windowWidth * 0.8, height: 420 }"
    :data="this.data"
    :margin="this.margin"
    :direction="this.direction"
    :axis="axis"
  >
    <template #layers>
      <Grid strokeDasharray="2,2" />
      <Line :dataKeys="['date', 'count']" />
    </template>

    <template #widgets>
      <Tooltip borderColor="#48CAE4" :config="this.tooltip_config" />
    </template>
  </Chart>
</template>
  <script>
import { Chart, Grid, Line, Tooltip } from "vue3-charts";
export default {
  props: { data: Array },
  components: { Chart, Grid, Line, Tooltip },
  data() {
    return {
      direction: "horizontal",
      windowWidth: window.innerWidth,
      margin: {
        left: 0,
        top: 20,
        right: 20,
        bottom: 0,
      },
      axis: {
        primary: {
          type: "band",
        },
        secondary: {
          domain: ["dataMin", "dataMax + 100"],
          type: "linear",
          ticks: 8,
        },
      },
      tooltip_config: {
        date: { label: "Data" },
        count: { label: "Liczba wpisów" },
        growth: { label: "Przyrost" },
        entity: { hide: true },
        entity_value: { hide: true },
      },
    };
  },
  mounted() {
    this.$nextTick(() => {
      window.addEventListener("resize", this.onResize);
    });
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.onResize);
  },
  methods: {
    onResize() {
      this.windowWidth = window.innerWidth;
    },
  },
};
</script>
  <style >
</style>