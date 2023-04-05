<template>
  <div class="row">
    <div class="col-auto" v-if="this.can_download == 'true'">
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
        data-target="#modalAuthors"
      >
        Pokaż wykres
      </button>
    </div>
  </div>
  <div class="table-responsive scrollable">
    <table class="table table-striped align-items-center mb-0">
      <thead>
        <tr>
          <th scope="col" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
          <th scope="col" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" v-for="column in this.columns" :key="column">
            {{ column }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(key, index) in this.data.slice(0, this.size)" :key="index">
          <th scope="row" class="mb-0 text-xs">{{ index + 1 }}</th>
          <td>
            <a
              :href="'/search/twitter/author_username=' + key.key"
              target="_blank"
              ><span class="mb-0 text-xs">{{ key.key }}</span></a
            >
          </td>
          <td class="mb-0 text-xs">{{ key.doc_count }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Modal authors -->
  <div
    class="modal fade"
    id="modalAuthors"
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
                <option value="vertical" selected>Paskowy</option>
                <option value="horizontal">Kolumnowy</option>
                <option value="circular">Kołowy</option>
              </select>
            </div>
            <div class="col">Liczba wyników</div>
            <div class="col-auto">
              <select class="form-select" v-model="this.chart_size">
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="50">50</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-body" id="authorsGraphToImage">
          <pie-chart
            v-if="this.chart_direction == 'circular'"
            :data="this.data ? this.data.slice(0, this.chart_size) : []"
            :tooltip_config="this.authors_tooltip_config"
            :data_keys="['key', 'doc_count']"
          ></pie-chart>
          <bar-chart
            v-else
            :data="this.data ? this.data.slice(0, this.chart_size) : []"
            :tooltip_config="this.authors_tooltip_config"
            :data_keys="['key', 'doc_count']"
            :direction="this.chart_direction"
          ></bar-chart>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" @click="downloadGraph">
            Pobierz
          </button>
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
import html2canvas from 'html2canvas';

export default {
  props: {
    data: Array,
    can_download: String
  },
  components: { BarChart, PieChart },
  data() {
    return {
      size: 10,
      chart_size: 10,
      columns: [null, "liczba wyników"],
      chart_direction: "vertical",
      stats_category: "autorzy",
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
      authors_tooltip_config: {
        key: { label: "autor" },
        doc_count: { label: "liczba wpisów" },
      },
    };
  },
  methods: {
    downloadGraph() {
      html2canvas(document.getElementById("authorsGraphToImage")).then((canvas) => {
        var anchorTag = document.createElement("a");
        document.body.appendChild(anchorTag);
        anchorTag.download = "autorzy.jpg";
        anchorTag.href = canvas.toDataURL();
        anchorTag.target = "_blank";
        anchorTag.click();
      });
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
