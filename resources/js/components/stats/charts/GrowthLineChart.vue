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
      <Tooltip
        borderColor="#48CAE4"
        :config="{
          date: { color: '#0077b6' },
          count: { label: 'averange', color: 'red' },
          growth: { hide: true },
        }"
      />
    </template>
  </Chart>
</template>
  <script>
import { Chart, Grid, Line } from "vue3-charts";
export default {
  props: { data: Array },
  components: { Chart, Grid, Line },
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