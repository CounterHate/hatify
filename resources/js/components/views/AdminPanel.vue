<template >
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <span class="text-white">Stream status</span>
  <span
    class="badge badge-pill bg-success"
    style="margin-left: 8px"
    v-if="this.stream_alive == true"
    >Aktywny</span
  >
  <span
    class="badge badge-pill bg-danger"
    style="margin-left: 8px"
    v-if="this.stream_alive == false"
    >Wyłączony</span
  >
  <button
    class="btn btn-primary btn-sm"
    style="margin-bottom: 0px; margin-left: 16px"
    v-if="this.stream_alive == false && this.can_start_stream == 'true'"
    @click="toggleStream"
  >
    Uruchom
  </button>
  <button
    class="btn btn-primary btn-sm"
    style="margin-bottom: 0px; margin-left: 16px"
    v-if="this.stream_alive == true"
    @click="toggleStream"
  >
    Zatrzymaj
  </button>

  <br />
  <br />
  <br />
  <br />

  <div class="table-responsive scrollable-phrases">
    <table class="table" v-show="this.phrases.length > 0">
      <thead>
        <tr>
          <th
            scope="col"
            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
          >
            #
          </th>
          <th
            scope="col"
            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
          >
            Słowo
          </th>
          <th
            scope="col"
            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
          ></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(phrase, index) in this.phrases" :key="index">
          <th scope="row" class="mb-0 text-xs">{{ index + 1 }}</th>
          <td>
            <span class="mb-0 text-xs">{{ phrase.phrase }}</span>
          </td>
          <td>
            <button
              v-if="this.can_remove_keywords == 'true'"
              class="btn btn-danger btn-sm"
              style="margin-bottom: 0px"
              @click="deletePhrase(phrase.id, index)"
            >
              Usuń
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import {
  checkStreamAlive,
  getPhrasesFromIndex,
  updateStreamAlive,
  deletePhraseFromIndex,
} from "../../es.js";
export default {
  props: {
    can_start_stream: String,
    can_remove_keywords: String,
  },
  data() {
    return {
      url: process.env.MIX_ES,
      phrases_index: process.env.MIX_PHRASES_INDEX,
      alive_index: process.env.MIX_ALIVE_INDEX,
      auth: {
        username: process.env.MIX_ES_USER,
        password: process.env.MIX_ES_PASS,
      },
      stream_alive: null,
      phrases: [],
    };
  },
  methods: {
    checkStreamAlive,
    getPhrasesFromIndex,
    updateStreamAlive,
    deletePhraseFromIndex,
    async toggleStream() {
      await updateStreamAlive(
        this.url + "/" + this.alive_index,
        this.auth,
        !this.stream_alive
      );
      await checkStreamAlive(this.url + "/" + this.alive_index, this.auth).then(
        (data) => {
          this.stream_alive = data.is_alive;
        }
      );
    },
    async deletePhrase(id, index) {
      await deletePhraseFromIndex(
        this.url + "/" + this.phrases_index,
        this.auth,
        id
      ).then((response) => {
        this.toggleStream();
        this.phrases.splice(index, 1);
      });
    },
  },
  async mounted() {
    await checkStreamAlive(this.url + "/" + this.alive_index, this.auth).then(
      (data) => {
        this.stream_alive = data.is_alive;
      }
    );

    await getPhrasesFromIndex(
      this.url + "/" + this.phrases_index,
      this.auth
    ).then((data) => {
      this.phrases = data;
    });
  },
};
</script>
<style>
.scrollable-phrases {
  max-height: 800px;
  overflow: auto;
}
</style>