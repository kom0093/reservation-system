<script lang="ts" setup>

export type LoginForm = { email: string, password: string };

import SubmitButton from '@/components/forms/SubmitButton.vue';
import { ref } from 'vue';
import { authService } from '../../services';

const loading = ref<boolean>(false);

const form = ref<LoginForm>({
  email: '',
  password: '',
});

const login = () => {
  loading.value = true;
  authService.login(form.value)
      .finally(() => {
        loading.value = false;
      });
};
</script>

<template>
  <div class="public-page">
    <div class="public-page-logo">
      <img src="/resources/images/logo.png" alt="Calendar" class="mx-auto h-20 w-auto"/>
      <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Přihlásit se</h2>
    </div>

    <div class="public-page-content">
      <form @submit.prevent="login" class="space-y-6">
        <div class="form-group">
          <label for="email" class="form-group-label">Email</label>
          <div class="mt-2">
            <input id="email" type="email" name="email" v-model="form.email" required class="form-group-control"/>
          </div>
        </div>

        <div class="form-group">
          <label for="password" class="form-group-label">Heslo</label>
          <div class="mt-2">
            <input id="password" type="password" name="password" v-model="form.password" required class="form-group-control"/>
          </div>
        </div>

        <SubmitButton :title="'Přihlásit se'" :loading="loading"></SubmitButton>
      </form>

      <p class="mt-10 text-center text-sm/6 text-gray-500">
        Ještě nemáte účet?
        <router-link to="/auth/register" class="anchor-tag">Registrujte se</router-link>
      </p>
    </div>
  </div>
</template>

