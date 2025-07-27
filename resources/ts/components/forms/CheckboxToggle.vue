<script setup>
const model = defineModel();
</script>

<template>
  <label class="flex items-center cursor-pointer justify-end">
    <input class="sr-only peer" v-model="model" type="checkbox"/>
    <div class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-200 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all  peer-checked:bg-primary"></div>
    <span class="text-sm font-medium text-gray-900 dark:text-gray-300"></span>
  </label>
</template>
