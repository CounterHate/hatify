<template >
  <Chart
    :size="{ width: 1100, height: 420 }"
    :data="data"
    :margin="margin"
    direction="horizontal"
    :axis="axis"
  >
    <template #layers>
      <Grid strokeDasharray="2,2" />
      <Line :dataKeys="['name', 'count']" />
      <Line :dataKeys="this.data_keys" :lineStyle="{ stroke: 'red' }" />
    </template>

    <template #widgets>
      <Tooltip :config="this.tooltip_config" />
    </template>
  </Chart>
</template>
<script>
import { Chart, Grid, Line, Tooltip } from "vue3-charts";
export default {
  props: {
    data: Array,
    tooltip_config: Object,
    data_keys: { default: ["author", "count"], type: Array },
  },
  components: { Chart, Grid, Line, Tooltip },
  data() {
    return {
      margin: {
        left: 0,
        top: 20,
        right: 20,
        bottom: 0,
      },
      axis: {
        primary: {
          type: "band",
          rotate: true
        },
        secondary: {
          domain: ["dataMin", "dataMax + 100"],
          type: "linear",
          ticks: 8,
        },
      },
    };
  },
};
</script>
<style >
</style>
