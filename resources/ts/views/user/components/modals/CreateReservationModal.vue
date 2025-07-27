<script lang="ts" setup>

export type ReservationForm = {
  person_count?: number,
  date?: Date | string,
  time?: string,
}

import Select from '@/components/forms/Select.vue';
import { reservationService } from '@/services';
import { AvailableTimesResponse } from '@/services/ReservationService';
import { useVfm, VueFinalModal } from 'vue-final-modal';
import { cs } from 'date-fns/locale';
import { ref } from 'vue';
import { formatDate } from '../../../../../helpers';

const modal = useVfm();
const emit = defineEmits(['confirm', 'cancel']);
const form = ref<ReservationForm>({
  person_count: undefined,
  date: undefined,
  time: undefined,
});
const availableTimes = ref<string[]>([]);
const availableTimesLoading = ref<boolean>(true);
const minDate = new Date();

let timeoutId: ReturnType<typeof setTimeout> | undefined;

function onInputsChange(): void {
  clearTimeout(timeoutId);

  if (form.value.person_count) {
    if (form.value.person_count > 10) {
      form.value.person_count = 10;
    }
    if (form.value.person_count < 1) {
      form.value.person_count = 1;
    }
  }

  timeoutId = setTimeout(() => {
    if (form.value.date && form.value.person_count && form.value.person_count > 0) {
      availableTimesLoading.value = true;
      const date = formatDate(form.value.date as Date, 'Y-m-d');
      reservationService.getAvailableTimesByPersonCountAndDate({person_count: form.value.person_count, date})
          .then((response: AvailableTimesResponse) => {
            availableTimes.value = response.available_times;
            form.value.time = undefined;
          })
          .finally(() => {
            availableTimesLoading.value = false;
          });
    }
  }, 300);
}

function onSubmit(): void {
  if (!form.value.date || !form.value.person_count || !form.value.time) return;
  const body = {
    ...form.value,
    date: formatDate(form.value.date as Date, 'Y-m-d')
  };

  reservationService.save(body)
      .then(() => {
        modal.closeAll();
        emit('confirm');
      });
}
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
            Vytvořit rezervaci
          </h1>
        </div>
        <div class="flex-none">
          <button @click="modal.closeAll()" class="max-w-4 cursor-pointer">
            <i class="mdi mdi-24px mdi-close"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="modal-body pb-6 pt-4 px-6">
      <form @submit.prevent="onSubmit">
        <div class="form-group">
          <label for="person_count" class="form-group-label required-label">Počet osob</label>
          <div class="mt-2">
            <input @input="onInputsChange"
                   id="person_count"
                   type="number"
                   name="person_count"
                   v-model="form.person_count"
                   required
                   max="10"
                   class="form-group-control"/>
          </div>
        </div>


        <div class="form-group">
          <label for="date" class="form-group-label required-label">Datum</label>
          <div class="mt-2">
            <DatePicker :enable-time-picker="false"
                        :format-locale="cs"
                        :format="'dd.MM.yyyy'"
                        :min-date="minDate"
                        @update:model-value="onInputsChange"
                        v-model="form.date"
                        required
                        cancel-text="Zrušit"
                        :id="'date'"
                        select-text="Potvrdit"/>
          </div>
        </div>

        <div class="form-group">
          <label for="time" class="form-group-label required-label">Čas</label>
          <div class="mt-2">
            <Select :loading="availableTimesLoading && form.date !== undefined && form.person_count !== undefined" :disabled="availableTimesLoading || availableTimes.length === 0" :id="'time'" :required="true" v-model="form.time" :options="availableTimes"/>
          </div>
        </div>

        <div v-if="availableTimes.length === 0 && !availableTimesLoading" class="alert-danger">Pro toto datum a počet osob již nemáme žádný volný stůl.</div>

        <div class="text-right mt-4">
          <button :disabled="!form.date || !form.person_count || !form.time" type="submit" class="btn btn-primary">
            Vytvořit rezervaci
          </button>
        </div>
      </form>
    </div>
  </VueFinalModal>
</template>
