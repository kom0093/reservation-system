<script lang="ts" setup>

export type RegisterForm = { first_name: string, last_name: string, email: string, password: string, password_confirmation: string };

import SubmitButton from '@/components/forms/SubmitButton.vue';
import { ref } from 'vue';
import { authService } from '../../services';

const form = ref<RegisterForm>({
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  password_confirmation: ''
});
const error = ref<string | null>(null);
const loading = ref<boolean>(false);

const register = () => {
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Hesla se neshodují.';
    return;
  }

  loading.value = true;
  error.value = null;

  authService.register(form.value)
      .finally(() => {
        loading.value = false;
      });
};
</script>

<template>
  <div class="public-page">
    <div class="public-page-logo">
      <img src="/resources/images/logo.png" alt="Calendar" class="mx-auto h-20 w-auto"/>
      <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Registrace</h2>
    </div>

    <div class="public-page-content">
      <form @submit.prevent="register" class="space-y-6">
        <div class="form-group">
          <label for="first_name" class="form-group-label">Jméno</label>
          <div class="mt-2">
            <input id="first_name" type="text" name="first_name" v-model="form.first_name" required class="form-group-control"/>
          </div>
        </div>

        <div class="form-group">
          <label for="last_name" class="form-group-label">Příjmení</label>
          <div class="mt-2">
            <input id="last_name" type="text" name="last_name" v-model="form.last_name" required class="form-group-control"/>
          </div>
        </div>

        <div class="form-group">
          <label for="email" class="form-group-label">Email</label>
          <div class="mt-2">
            <input id="email" type="email" name="email" v-model="form.email" required class="form-group-control"/>
          </div>
        </div>

        <div class="form-group">
          <label for="password" class="form-group-label">Heslo</label>
          <div class="mt-2">
            <input id="password" type="password" name="password" v-model="form.password" required minlength="6" class="form-group-control"/>
          </div>
        </div>

        <div class="form-group">
          <label for="password_confirmation" class="form-group-label">Heslo znovu</label>
          <div class="mt-2">
            <input id="password_confirmation" type="password" name="password_confirmation" v-model="form.password_confirmation" required minlength="6" class="form-group-control"/>
          </div>
        </div>

        <SubmitButton :title="'Registrovat se'" :loading="loading"></SubmitButton>
      </form>

      <div v-if="error" class="alert-danger mt-[20px]">{{ error }}</div>

      <p class="mt-10 text-center text-sm/6 text-gray-500">
        Již máte účet?
        <router-link to="/auth/login" class="anchor-tag">Přihlášení</router-link>
      </p>
    </div>
  </div>
</template>

