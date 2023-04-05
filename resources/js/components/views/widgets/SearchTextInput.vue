<template>
  <div class="col">
    <!-- form for text to search -->
    <div class="mb-3">
      <label for="contentInput" class="form-label"
        >{{ this.label }}
        <i
        v-if="this.tooltip_text"
          data-bs-toggle="tooltip"
          data-bs-placement="top"
          :title="this.tooltip_text"
          class="bi bi-info-circle"
        ></i
      ></label>
      <input
        type="text"
        class="form-control"
        id="contentInput"
        :placeholder="placeholder"
        v-model="this.modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
      />
    </div>
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    placeholder: String,
    tooltip_text: String,
    modelValue: String,
  },
};
</script>
<style>
</style>