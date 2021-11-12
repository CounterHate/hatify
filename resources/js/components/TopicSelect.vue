<template>
  <div
    class="modal fade"
    id="topicModal"
    tabindex="-1"
    aria-labelledby="topicModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="topicModalLabel">
            Kogo atakuje ten wpis
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="form-check" v-for="(topic, index) in topics" :key="index">
            <input
              class="form-check-input"
              type="checkbox"
              @change="toggleTopic(topic.name, index)"
            />
            <label class="form-check-label" for="flexCheckDefault">
              {{ topic.name }}
            </label>
          </div>
          <input
            class="form-control"
            v-if="show_input"
            v-model="input_topic"
            type="text"
            placeholder="Inna ofiara"
          />
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Cofnij
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="processTweet"
            data-bs-dismiss="modal"
          >
            Zapisz
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    topics: Array,
  },
  emits: ["process_tweet_hate_speech"],
  data() {
    return {
      selected_topics: [],
      input_topic: null,
      show_input: false,
    };
  },

  methods: {
    resetCheckboxes() {
      document
        .querySelectorAll("input[type=checkbox]")
        .forEach((el) => (el.checked = false));
    },
    processTweet() {
      var data = {
        is_hate_speech: true,
        selected_topics: this.selected_topics,
        input_topic: this.input_topic,
      };
      this.$emit("process_tweet_hate_speech", data);
      this.selected_topics = [];
      this.resetCheckboxes();
    },
    toggleTopic(topic, index) {
      if (index == 9) {
        this.show_input = !this.show_input;
      }
      if (this.selected_topics.includes(topic)) {
        var index = this.selected_topics.indexOf(topic);
        this.selected_topics.splice(index, 1);
      } else {
        this.selected_topics.push(topic);
      }
    },
  },
};
</script>

<style></style>
