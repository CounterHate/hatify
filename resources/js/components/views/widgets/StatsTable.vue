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
        <td>{{ key.key }}</td>
        <td>{{ key.doc_count }}</td>
      </tr>
    </tbody>
  </table>
</template>
  <script>
export default {
  props: {
    data: Array,
    stats_category: String,
  },
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