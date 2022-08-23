<template>
  <div>
    <!-- visualization test -->
    <Responsive>
      <template #main="{ width }">
        <Chart direction="circular" :size="{ width, height: 500 }" :data="this.data" :margin="{
          left: Math.round((width - 360) / 2),
          top: 40,
          right: 0,
          bottom: 40,
        }"  :config="{ controlHover: false }">
          <template #layers>
            <Pie :dataKeys="['name', 'count']" :pie-style="{ innerRadius: 50, padAngle: 0.05, colors: ['#fcba03', '#2f578f', '#8d2f8f', '#32a852', '#db4f1d', '#1dd2db'] }" />
          </template>
          <template #widgets>
            <Tooltip :config="{
              name: {},
              count: { label: 'value' },
              words: { hide: true },
            }" hideLine />
          </template>
        </Chart>
      </template>
    </Responsive>
    <!-- end of visualization -->
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Kategoria</th>
        <th scope="col">Liczba wyników</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(category, index) in this.hate_speech_categories_count.sort((a, b) => b.count - a.count)" :key="index">
        <th scope="row">{{ index + 1 }}</th>
        <td><a :href="'/stats/hate_category=' + category.name" target="_blank">{{ category.name }}</a></td>
        <td>{{ category.count }}</td>
      </tr>
    </tbody>
  </table>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Słowo</th>
        <th scope="col">Kategoria</th>
        <th scope="col">Liczba wyników</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(word, index) in this.words.sort((a, b) => b.count - a.count)" :key="index">
        <th scope="row">{{ index + 1 }}</th>
        <td>{{ word.word }}</td>
        <td><a :href="'/stats/hate_category=' + word.category">{{ word.category }}</a></td>
        <td>{{ word.count }}</td>
      </tr>
    </tbody>
  </table>
</template>
<script>
import { Chart, Responsive, Pie, Tooltip } from "vue3-charts";

export default {
  props: { data: Array, words: Array, hate_speech_categories_count: Array },
  components: { Chart, Responsive, Pie, Tooltip },
};
</script>
<style>
</style>