<template >
  <Responsive>
    <template #main="{ width }">
      <Chart
        direction="circular"
        :size="{ width, height: 500 }"
        :data="this.data"
        :margin="{
          left: Math.round((width - 360) / 2),
          top: 40,
          right: 0,
          bottom: 40,
        }"
        :config="{ controlHover: false }"
      >
        <template #layers>
          <Pie
            :dataKeys="['author', 'count']"
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
  props: { data: Array, tooltip_config: Object },
  components: {
    Chart,
    Responsive,
    Pie,
    Tooltip,
  },
};
</script>
<style >
</style>