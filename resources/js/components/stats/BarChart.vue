<template>
  <Chart
    :size="{ width: this.windowWidth * 0.8, height: 420 }"
    :data="data"
    :margin="margin"
    :direction="direction"
    :axis="axis"
  >
    <template #layers>
      <Grid strokeDasharray="2,2" />
      <Bar :dataKeys="['name', 'count']" :barStyle="{ fill: '#90e0ef' }" />
    </template>

    <template #widgets>
      <Tooltip :config="this.tooltip_config" />
    </template>
  </Chart>
</template>
<script>
import { Chart, Tooltip, Grid, Line, Bar } from "vue3-charts";

export default {
  props: {
    data: Array,
    direction: String,
    tooltip_config: Object,
  },
  components: { Chart, Tooltip, Grid, Line, Bar, data_keys: Array },
  data() {
    return {
      windowWidth: window.innerWidth,
      axis: {
        primary: {
          type: "band",
        },
        secondary: {
          domain: ["dataMin", "dataMax + 10"],
          type: "linear",
          ticks: 8,
        },
      },
      margin: {
        left: 0,
        top: 20,
        right: 20,
        bottom: 0,
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