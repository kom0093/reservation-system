<script lang="ts" setup>

import { User } from '@/models/User';
import { ref } from 'vue';
import { authService } from '../services';

const menuOpen = ref<boolean>(false);
const loggedUser = ref<User | null>(null);

authService.getLoggedUser()
    .then(user => {
      loggedUser.value = user;
    })

const logout = () => {
  authService.logout();
};

</script>

<template>
  <nav class="bg-gray-900 relative">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
      <div class="relative flex h-16 items-center justify-between">
        <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
          <!-- Mobile menu button-->
          <button @click="menuOpen = !menuOpen" type="button" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:none">
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only"></span>

            <svg v-if="!menuOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="block size-6">
              <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <svg v-if="menuOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="block size-6">
              <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </div>
        <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
          <div class="flex shrink-0 items-center">
            <img src="/resources/images/logo.png" alt="Calendar" class="h-8 w-auto" />
          </div>
          <div class="hidden sm:ml-6 sm:block">
            <div class="flex space-x-4">
              <router-link to="/home" class="rounded-md px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Rezervace</router-link>
            </div>
          </div>
        </div>
        <div class="flex gap-4">

          <div class="flex items-center gap-4">
            <span v-if="loggedUser?.full_name" class="cursor-pointer text-white" title="Přihlášený uživatel">{{loggedUser.full_name}}
            </span>
          </div>
          <div class="dropdown inline-block relative">
            <span class="mdi mdi-account-circle text-3xl text-white"></span>
            <ul class="dropdown-menu min-w-max absolute right-0 hidden text-white pt-3">
              <li class="bg-gray-900 hover:bg-gray-700 py-2 px-4 block whitespace-no-wrap cursor-pointer"
                  @click="logout">
                Odhlásit se
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div id="mobile-menu" class="sm:hidden bg-gray-800" :class="menuOpen ? 'block' : 'hidden'">
      <div class="space-y-1 px-2 pt-2 pb-3">
        <router-link to="/home" class="block rounded-md   bg-gray-900 px-3 py-2 text-base font-medium text-white">Rezervace</router-link>
      </div>
    </div>
  </nav>

</template>

