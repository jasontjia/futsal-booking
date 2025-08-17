<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Props dari Laravel
defineProps({
  canResetPassword: Boolean,
  status: String,
});

// Form login
const form = useForm({
  email: '',
  password: '',
  remember: false,
});

// Submit handler
const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200 px-6 py-10 flex items-center justify-center">
    <Head title="Login" />

    <div class="w-full max-w-md p-8 bg-white shadow-lg rounded-xl">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Masuk ke Akun Anda</h2>

      <!-- Status pesan sukses -->
      <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
        {{ status }}
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Email -->
        <div>
          <InputLabel for="email" value="Email" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <!-- Password -->
        <div>
          <InputLabel for="password" value="Password" />
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
            autocomplete="current-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <!-- Remember Me & Lupa Password -->
        <div class="flex items-center justify-between">
          <label class="flex items-center space-x-2">
            <Checkbox name="remember" v-model:checked="form.remember" />
            <span class="text-sm text-gray-600">Ingat saya</span>
          </label>

          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="text-sm text-indigo-700 hover:underline"
          >
            Lupa password?
          </Link>
        </div>

        <!-- Tombol Login -->
        <div class="pt-4">
          <PrimaryButton
            class="w-full justify-center py-3 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 transition"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Masuk
          </PrimaryButton>
        </div>
      </form>

      <!-- Link ke Register -->
      <p class="mt-6 text-center text-sm text-gray-600">
        Belum punya akun?
        <Link href="/register" class="text-indigo-700 hover:underline">
          Daftar di sini
        </Link>
      </p>
      <!-- Link ke Beranda -->
      <p class="mt-2 text-center text-sm text-gray-600">
        <Link href="/" class="text-indigo-700 hover:underline">
          Beranda
        </Link>
      </p>
    </div>
  </div>
</template>
