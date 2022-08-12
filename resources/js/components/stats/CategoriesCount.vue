<template>
  <div>
    <!-- visualization test -->
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
          :axis="axis"
          :config="{ controlHover: false }"
        >
          <template #layers>
            <Pie
              :dataKeys="['name', 'count']"
              :pie-style="{ innerRadius: 50, padAngle: 0.05 }"
            />
          </template>
          <template #widgets>
            <Tooltip
              :config="{
                name: {},
                count: { label: 'value' },
                words: { hide: true },
              }"
              hideLine
            />
          </template>
        </Chart>
      </template>
    </Responsive>
    <!-- end of visualization -->
  </div>
</template>
<script>
import { Chart, Responsive, Pie, Tooltip } from "vue3-charts";

export default {
  props: { data: Array },
  components: { Chart, Responsive, Pie, Tooltip },
  mounted() {},
};
</script>
<style>
</style>