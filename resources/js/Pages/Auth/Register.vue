<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200 px-6 py-10">
    <!-- Card -->
    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8">
      <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Register</h1>
      
      <form @submit.prevent="submit" class="space-y-6 w-full">
        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            v-model="form.name"
            type="text"
            id="name"
            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          />
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="form.email"
            type="email"
            id="email"
            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          />
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            v-model="form.password"
            type="password"
            id="password"
            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          />
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            id="password_confirmation"
            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          />
        </div>

        <!-- Submit -->
        <button
          type="submit"
          class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-indigo-700 transition"
        >
          Register
        </button>

        <!-- Login Link -->
        <p class="text-center text-sm text-gray-600 mt-4">
          Sudah punya akun?
          <a href="/login" class="text-indigo-600 hover:text-indigo-700 font-medium">Masuk</a>
        </p>
        <p class="mt-1 text-center text-sm text-gray-600">
            <Link href="/" class="text-indigo-700 hover:underline">
            Kembali
            </Link>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { onMounted } from 'vue'

onMounted(() => {
  document.title = "Register"
})

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post('/register')
}
</script>
