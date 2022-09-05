<template>
  <Chart
    :size="{ width: this.windowWidth * 0.8, height: 420 }"
    :data="data"
    :margin="margin"
    :direction="this.direction"
    :axis="axis"
  >
    <template #layers>
      <Grid strokeDasharray="2,2" />
      <Area
        :dataKeys="['date', 'count', 'growth']"
        type="monotone"
        :areaStyle="{ fill: 'url(#grad)' }"
      />
      <Line
        :dataKeys="['date', 'count', 'growth']"
        type="monotone"
        :lineStyle="{
          stroke: '#9f7aea',
        }"
      />
      <defs>
        <linearGradient id="grad" gradientTransform="rotate(90)">
          <stop offset="0%" stop-color="#be90ff" stop-opacity="1" />
          <stop offset="100%" stop-color="white" stop-opacity="0.4" />
        </linearGradient>
      </defs>
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
      windowWidth: window.innerWidth,
      direction: "horizontal",
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
  <style>
</style>