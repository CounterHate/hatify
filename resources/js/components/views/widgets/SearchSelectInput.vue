<template >
  <div class="col">
    <div class="form-check" style="padding-left: 0px">
      <label>{{ this.label }}</label>
      <select
        class="form-select"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
      >
        <option
          v-for="option in this.options"
          :value="option.value"
          :key="option"
        >
          {{ option.text }}
        </option>
      </select>
    </div>
  </div>
</template>
<script>
export default {
  props: { options: Array, modelValue: String, label: String },
};
</script>
<style>
</style>