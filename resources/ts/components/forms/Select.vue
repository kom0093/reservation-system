<script setup>
const model = defineModel();

const props = defineProps({
  label: {
    type: String,
    default: ''
  },
  options: {
    type: Array,
    default: () => []
  },
  optionKey: {
    type: String,
    default: 'value'
  },
  modelKey: {
    type: String,
    default: null
  },
  optionLabel: {
    type: String,
    default: 'label'
  },
  id: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  },
  required: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  }
});

function containsOnlyBasicTypes(arr) {
  const basicTypes = ['string', 'number', 'boolean', 'undefined'];
  return arr.every(item => basicTypes.includes(typeof item) || item === null);
}
</script>

<template>
  <div class="form-group-select-wrapper relative">
    <div v-if="loading" class="absolute left-[44%] top-0 flex justify-center items-center p-4">
      <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-900"></div>
    </div>
    <select :id="id" class="form-group-control form-group-select" :required="required" :disabled="disabled" v-model="model">
      <option v-if="!containsOnlyBasicTypes(options)" v-for="option of options" :key="option[optionKey]" :value="option[optionKey]" :selected="option[optionKey] === (modelKey ? model[modelKey] : model)">{{ option[optionLabel] }}</option>
      <option v-if="containsOnlyBasicTypes(options)" v-for="option of options" :key="option" :value="option" :selected="option === model">{{ option }}</option>
    </select>
    <div v-if="label && error" class="absolute -bottom-5 right-0">
      <span class="text-xxs text-error">{{ error }}</span>
    </div>
  </div>
</template>

<style scoped>
</style>
