<script setup lang="ts">
const props = defineProps({
  title: {
    type: String,
    default: ''
  },
  loading: {
    type: Boolean,
    default: false
  },
})

</script>

<template>
  <button :disabled="loading" type="submit" class="btn-primary">
            <span v-if="loading" class="flex justify-center items-center">
              <span class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-900"></span>
            </span>
    <span v-else>{{title}}</span>
  </button>
</template>
