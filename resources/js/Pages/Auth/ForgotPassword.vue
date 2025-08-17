<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200 px-6 py-10 flex items-center justify-center">
    <Head title="Forgot Password" />

    <div class="w-full max-w-md p-8 bg-white shadow-lg rounded-xl">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Lupa Password</h2>

      <div class="mb-4 text-sm text-gray-600 text-center">
        Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.
      </div>

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

        <!-- Submit Button -->
        <div class="pt-4">
          <PrimaryButton
            class="w-full justify-center bg-indigo-600 hover:bg-indigo-700"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Kirim Link Reset Password
          </PrimaryButton>
        </div>
      </form>
      <!-- Link ke login -->
      <p class="mt-6 text-center text-sm text-gray-600">
        Ingat password Anda? 
        <Link href="/login" class="text-indigo-700 hover:underline">
          Masuk di sini
        </Link>
      </p>
    </div>
  </div>
</template>
