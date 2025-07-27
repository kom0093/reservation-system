<script setup>
import {VueFinalModal} from "vue-final-modal";

const emit = defineEmits(['confirm', 'cancel']);
const props = defineProps({
  title: {type: String, required: true},
  text: {type: String, required: true},
});

</script>

<template>
  <VueFinalModal
      class="flex justify-center items-center"
      content-class="flex flex-col max-w-xl mx-4 bg-white border dark:border-gray-700 rounded-lg space-y-2 modal-content"
      overlay-class="fixed inset-0 bg-black bg-opacity-50 modal-overlay"
      role="dialog"
      aria-modal="true"
      :esc-to-close="true"
      :click-to-close="false"
  >
    <div class="modal-header p-6 border-b dark:border-neutral-200">
      <div class="flex">
        <div class="grow">
          <h1 class="text-xl">
            {{ title }}
          </h1>
        </div>
      </div>
    </div>

    <div class="modal-body pb-6 pt-4 px-6">
      <p class="mb-8">{{ text }}</p>

      <div class="flex justify-end">
        <button class="underline mr-4" @click="emit('cancel')">Zrušit</button>
        <button class="btn btn-danger max-w-50" @click="emit('confirm')">Potvrdit</button>
      </div>
    </div>
  </VueFinalModal>
</template>
