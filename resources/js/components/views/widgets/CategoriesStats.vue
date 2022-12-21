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
          data-target="#modalCategories"
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
          <td>
            <a :href="'/search/twitter/category=' + key.key" target="_blank">{{
              key.key
            }}</a>
          </td>
          <td>{{ key.doc_count }}</td>
        </tr>
      </tbody>
    </table>
  
    <!-- Modal categories -->
    <div
      class="modal fade"
      id="modalCategories"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Wykres</h5>
          </div>
          <div class="modal-body">
            <bar-chart
              :data="this.data ? this.data.slice(0, this.size) : []"
              :tooltip_config="this.categories_tooltip_config"
              :data_keys="['key', 'doc_count']"
              direction="vertical"
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
  import BarChart from "../../stats/charts/BarChart.vue";
  
  export default {
    props: {
      data: Array,
    },
    components: { BarChart },
    data() {
      return {
        size: 10,
        columns: [null, "liczba wyników"],
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
        categories_tooltip_config: {
          key: { label: "kategoria" },
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