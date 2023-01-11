<template>
  <div class="row">
    <div class="col-auto">
      <vue-excel-xlsx
        :data="this.data"
        :columns="this.export_columns"
        :file-name="this.stats_category"
        :file-type="'xlsx'"
      >
        Pobierz
      </vue-excel-xlsx>
    </div>
    <div class="col-auto">
      <select class="form-select" v-model="this.size">
        <option value="10">10</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
    </div>
    <div class="col-auto">
      <button
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#modalDays"
      >
        Pokaz wykres
      </button>
    </div>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col" v-for="column in this.columns" :key="column">
          {{ column }}
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(key, index) in this.data.slice(0, this.size)" :key="index">
        <th scope="row">{{ index + 1 }}</th>
        <td v-if="this.stats_category == 'autorzy'">
          <a
            :href="'/search/twitter/author_username=' + key.key"
            target="_blank"
            >{{ key.key }}</a
          >
        </td>
        <td>{{ key.key }}</td>
        <td>{{ key.doc_count }}</td>
      </tr>
    </tbody>
  </table>

  <!-- Modal days -->
  <div
    class="modal fade"
    id="modalDays"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="row">
            <div class="col">Wykres</div>
            <div class="col">Liczba wyników</div>
            <div class="col-auto">
              <select
                class="form-select"
                v-model="this.chart_size"
              >
                <option value="7" selected>7</option>
                <option value="14">14</option>
                <option value="21">21</option>
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="90">90</option>
                <option value="120">120</option>
                <option value="150">150</option>
                <option value="180">180</option>
                <option value="210">210</option>
                <option value="240">240</option>
                <option value="270">270</option>
                <option value="300">300</option>
                <option value="330">330</option>
                <option value="360">360</option>
              </select>
            </div>
          </div>        </div>
        <div class="modal-body">
          <!-- <bar-chart
            :data="this.data ? this.data.slice(0, this.size) : []"
            :tooltip_config="this.words_tooltip_config"
            :data_keys="['key', 'doc_count']"
            direction="horizontal"
          ></bar-chart> -->
          <line-chart
            :data="this.data_sorted ? this.data_sorted.slice(-this.chart_size) : []"
            :tooltip_config="this.days_tooltip_config"
            :data_keys="['key', 'doc_count']"
          >
          </line-chart>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Zamknij
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
    <script>
import BarChart from "../../stats/charts/BarChart.vue";
import LineChart from "../../stats/charts/LineChart.vue"

export default {
  props: {
    data: Array,
    data_sorted: Array
  },
  components: { BarChart, LineChart },
  data() {
    return {
      size: 10,
      chart_size: 7,
      columns: [null, "liczba wyników"],
      stats_category: "dni",
      export_columns: [
        {
          label: null,
          field: "key",
        },
        {
          label: "Liczba wpisów",
          field: "doc_count",
        },
      ],
      days_tooltip_config: {
        key: { label: "dzień" },
        doc_count: { label: "liczba wpisów" },
      },
    };
  },
  mounted() {
    this.columns[0] = this.stats_category;
    this.export_columns[0].label = this.stats_category;
  },
};
</script>
    <style>
</style>
