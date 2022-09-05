<template>
  <!-- tables -->

  <div class="row">
    <div class="col-auto">
      <vue-excel-xlsx
        :data="this.data"
        :columns="this.columns_authors"
        :file-name="'Autorzy - ' + this.hate_speech_category"
        :file-type="'xlsx'"
        :sheet-name="'Autorzy'"
      >
        Pobierz kategorie
      </vue-excel-xlsx>
    </div>
    <div class="col-auto">
      <select class="form-select" v-model="this.how_many_results">
        <option value="10">10</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="all">Wszystkie</option>
      </select>
    </div>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Autor</th>
        <th scope="col">Liczba wyników</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="(author, index) in this.data.slice(0, this.how_many_results)"
        :key="index"
      >
        <th scope="row">{{ index + 1 }}</th>
        <td>
          <a :href="'/stats/author=' + author.name" target="_blank">{{
            author.name
          }}</a>
        </td>
        <td>{{ author.count }}</td>
        <td>
          <a :href="'https://twitter.com/' + author.name" target="_blank"
            >Zobacz profil</a
          >
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
export default {
  props: { data: Array, hate_speech_category: String },
  data() {
    return {
      how_many_results: 10,
      columns_authors: [
        {
          label: "Autor",
          field: "name",
        },
        {
          label: "Liczba wpisów",
          field: "count",
        },
      ],
      columns_words: [
        {
          label: "Słowo",
          field: "word",
        },
        {
          label: "Liczba wpisów",
          field: "count",
        },
      ],
    };
  },
};
</script>
<style>
</style>