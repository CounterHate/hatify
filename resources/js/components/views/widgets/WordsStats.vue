<template>
  <div class="row">
    <div class="col-auto">
      <vue-excel-xlsx
        :data="this.data"
        :columns="this.export_columns"
        :file-name="this.stats_category"
        :file-type="'xlsx'"
        class="btn btn-success"
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
        data-target="#modalWords"
      >
        Pokaz wykres
      </button>
    </div>
    <div class="col-auto">
      <button
        class="btn btn-primary"
        @click="countDeclination"
        v-if="this.declination_mode == false"
      >
        Deklinacja
      </button>
      <button class="btn btn-primary" @click="detailedCount" v-else>
        Szczegółowo
      </button>
    </div>
  </div>
  <div class="table-responsive scrollable" style="margin-bottom: 16px;">
    <table class="table table-striped align-items-center mb-0">
      <thead>
        <tr>
          <th
            scope="col"
            class="
              text-uppercase text-secondary text-xxs
              font-weight-bolder
              opacity-7
            "
          >
            #
          </th>
          <th
            scope="col"
            class="
              text-uppercase text-secondary text-xxs
              font-weight-bolder
              opacity-7
            "
            v-for="column in this.columns"
            :key="column"
          >
            {{ column }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(key, index) in this.data.slice(0, this.size)" :key="index">
          <th scope="row" class="mb-0 text-xs">{{ index + 1 }}</th>
          <td v-if="!this.declination_mode">
            <a :href="'/search/twitter/content=' + key.key" target="_blank"
              ><span class="mb-0 text-xs">{{ key.key }}</span></a
            >
          </td>
          <td v-else>
            <a :href="'/search/twitter/declination=' + key.key" target="_blank"
              ><span class="mb-0 text-xs">{{ key.key }}</span></a
            >
          </td>

          <td class="mb-0 text-xs">{{ key.doc_count }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Modal words -->
  <div
    class="modal fade"
    id="modalWords"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="row">
            <div class="col">Wykres</div>
            <div class="col-auto">
              <select class="form-select" v-model="this.chart_direction">
                <option value="circular" selected>Kołowy</option>
                <option value="vertical">Paskowy</option>
                <option value="horizontal">Kolumnowy</option>
              </select>
            </div>
            <div class="col">Liczba wyników</div>
            <div class="col-auto">
              <select class="form-select" v-model="this.chart_size">
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="50" v-if="this.chart_direction != 'circular'">
                  50
                </option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-body">
          <pie-chart
            v-if="this.chart_direction == 'circular'"
            :data="this.data ? this.data.slice(0, this.chart_size) : []"
            :tooltip_config="this.words_tooltip_config"
            :data_keys="['key', 'doc_count']"
          ></pie-chart>
          <bar-chart
            v-else
            :data="this.data ? this.data.slice(0, this.chart_size) : []"
            :tooltip_config="this.words_tooltip_config"
            :data_keys="['key', 'doc_count']"
            :direction="this.chart_direction"
          ></bar-chart>
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
import PieChart from "../../stats/charts/PieChart.vue";
import BarChart from "../../stats/charts/BarChart.vue";

export default {
  props: {
    data: Array,
    declination_mode: {
      type: Boolean,
      default: false,
    },
  },
  components: { PieChart, BarChart },
  emits: ["count-declinations", "detailed-count"],
  watch: {
    chart_direction(newVal) {
      if (newVal == "circular") this.chart_size = 10;
    },
  },
  data() {
    return {
      size: 10,
      chart_size: 10,
      columns: [null, "liczba wyników"],
      chart_direction: "circular",
      stats_category: "Słowa",
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
      words_tooltip_config: {
        key: { label: "słowo" },
        doc_count: { label: "liczba wpisów" },
      },
    };
  },
  methods: {
    detailedCount() {
      this.$emit("detailed-count");
    },
    countDeclination() {
      this.$emit("count-declinations");
    },
  },
  mounted() {
    this.columns[0] = this.stats_category;
    this.export_columns[0].label = this.stats_category;
  },
};
</script>
    <style>
    .scrollable {
  max-height: 457px;
  overflow: auto;
}
</style>
