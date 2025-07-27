<script setup lang="ts">

import CheckboxToggle from '@/components/forms/CheckboxToggle.vue';
import { Reservation } from '@/models/Reservation';
import { reservationService } from '@/services';
import CreateReservationModal from '@/views/user/components/modals/CreateReservationModal.vue';
import { onMounted, ref } from 'vue';
import { useModal } from 'vue-final-modal';
import { useConfirmModal } from '../../../../helpers/confirmModalHelper';

const deleteConfirmModal = useConfirmModal(deleteReservation);

const createUserModal = useModal({
  component: CreateReservationModal,
  attrs: {
    onConfirm: () => onReservationModalClose(),
  }
});

const rows = ref<Reservation[]>([]);
const page = ref<number>(1);
const totalPages = ref<number>(1);
const loading = ref<boolean>(true);
const reservationToBeDeleted = ref<Reservation | null>(null);
const includePastReservations = ref<boolean>(false);

onMounted(() => {
  refreshData();
});

function onReservationModalClose(): void {
  refreshData();
}

function openReservationModal(): void {
  createUserModal.open();
}

function handleDelete(row: Reservation) {
  reservationToBeDeleted.value = row;
  deleteConfirmModal.open();
}

function deleteReservation(): void {
  if (reservationToBeDeleted.value) {
    reservationService.delete(reservationToBeDeleted.value.id)
        .then(() => {
          refreshData();
        })
  }
}

function onToggleChange(value: boolean): void {
  includePastReservations.value = value;
  refreshData();
}

function goToPage(newPage: number) {
  if (newPage < 1 || newPage > totalPages.value) return;
  page.value = newPage;
  refreshData();
}

function refreshData(): void {
  loading.value = true;
  reservationService.getAll(page.value, includePastReservations.value)
      .then(response => {
        rows.value = response.data;
        totalPages.value = response.meta.last_page;
        if (page.value > response.meta.last_page) {
          page.value = response.meta.last_page;
        }
      })
      .finally(() => {
        loading.value = false;
      });
}

</script>

<template>
  <div class="grid grid-cols-2 space-be items-center">
    <h2>Moje rezervace</h2>
    <div class="flex justify-end">
      <button @click="openReservationModal" class="btn-primary btn-icon"><i class="mdi mdi-plus"></i></button>
    </div>
  </div>
  <div class="my-4">
    <div class="ml-3 font-normal text-gray-500 italic">
      Vážený zákazníku, zde si můžete rezervovat stůl na konkrétní datum a čas. K dispozici je celkem 10 stolů s následující kapacitou: 3 stoly pro 2 osoby, 3 stoly pro 4 osoby, 3 stoly pro 8 osob a 1 stůl pro 10 osob. Délka rezervace je 1 hodina.
    </div>
  </div>
  <div class="my-4">
    <div class="grid grid-cols-2 space-be">
      <div>
        Zobrazit i minulé rezervace
      </div>
      <div class="text-end">
        <CheckboxToggle :model="includePastReservations" @update:modelValue="(value: boolean) => onToggleChange(value)"/>
      </div>
    </div>
  </div>
  <div class="relative flex flex-col w-full h-full overflow-auto text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
    <table class="w-full text-left table-auto min-w-max text-slate-800">
      <thead>
        <tr class="text-slate-500 border-b border-slate-300 bg-slate-50">
          <th class="p-4">
            <p class="text-sm leading-none font-normal">
              Datum a čas rezervace
            </p>
          </th>
          <th class="p-4">
            <p class="text-sm leading-none font-normal">
              Číslo stolu
            </p>
          </th>
          <th class="p-4">
            <p class="text-sm leading-none font-normal">
              Kapacita stolu
            </p>
          </th>
          <th class="p-4">
            <p class="text-sm leading-none font-normal">
              Vytvořena
            </p>
          </th>
          <th class="p-4">
            <p></p>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="!loading && rows?.length === 0">
          <td colspan="5">
            <div class="flex justify-center items-center p-10">
              <div class="border-gray-900"> Aktuálně nemáte žádné rezervace</div>
            </div>
          </td>
        </tr>
        <tr v-if="loading">
          <td colspan="5">
            <div class="flex justify-center items-center p-10">
              <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-900"></div>
            </div>
          </td>
        </tr>
        <tr v-if="!loading" v-for="row in rows" :key="row.id" class="hover:bg-slate-50">
          <td class="p-4">
            <p class="text-sm font-bold">
              {{ row.datetime }}
            </p>
          </td>
          <td class="p-4">
            <p class="text-sm">
              {{ row.table_id }}
            </p>
          </td>
          <td class="p-4">
            <p class="text-sm">
              {{ row.table_capacity }}
            </p>
          </td>
          <td class="p-4">
            <p class="text-sm">
              {{ row.created_at }}
            </p>
          </td>
          <td class="p-4 text-right">
            <button @click="handleDelete(row)" class="text-red-700">
              <i class="mdi mdi-trash-can"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="pagination-controls flex justify-end items-center p-4">
      <button :disabled="page <= 1 || loading" @click="goToPage(page - 1)" class="btn-primary btn-icon mr-2"><i class="mdi mdi-menu-left"></i></button>

      <span>Stránka {{ page }} z {{ totalPages }}</span>

      <button :disabled="page >= totalPages || loading" @click="goToPage(page + 1)" class="btn-primary btn-icon ml-2"><i class="mdi mdi-menu-right"></i></button>
    </div>
  </div>
</template>
