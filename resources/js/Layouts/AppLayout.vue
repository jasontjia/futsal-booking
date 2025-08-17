<template>
  <div class="min-h-screen flex flex-col bg-gray-200">
    <!-- Navbar -->
    <header class="bg-gray-200 shadow-sm">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <!-- Logo -->
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>

        <!-- Navigation (Desktop) -->
        <nav class="hidden md:flex space-x-4 items-center font-medium text-black">
          <Link href="/" class="hover:text-green-600 transition">Home</Link>
          <Link v-if="isLoggedIn" href="/booking" class="hover:text-green-600 transition">Booking</Link>
          <Link v-if="isAdmin" href="/admin.dashboard" class="hover:text-green-600 transition">Admin</Link>

          <template v-if="!isLoggedIn">
            <Link href="/login" class="ml text-black hover:text-green-600">Login</Link>
          </template>

          <Link
            v-if="isLoggedIn"
            href="/logout"
            method="post"
            as="button"
            class="ml-4 bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg shadow transition"
          >
            Logout
          </Link>
        </nav>

        <!-- Mobile Menu Button -->
        <button
          @click="toggleMenu"
          class="md:hidden text-gray-600 hover:text-gray-800 focus:outline-none"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>
      </div>

      <!-- Mobile Dropdown -->
      <div v-if="menuOpen" class="md:hidden bg-white border-t shadow-sm">
        <nav class="flex flex-col space-y-2 p-4 text-gray-600 font-medium">
          <Link href="/" class="hover:text-green-600">Home</Link>
          <Link v-if="isLoggedIn" href="/booking" class="hover:text-green-600">Booking</Link>
          <Link v-if="isAdmin" href="/admin.index" class="hover:text-green-600">Admin</Link>

          <template v-if="!isLoggedIn">
            <Link
              href="/login"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition mt-2"
            >
              Login
            </Link>
          </template>

          <Link
            v-if="isLoggedIn"
            href="/logout"
            method="post"
            as="button"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow transition mt-2"
          >
            Logout
          </Link>
        </nav>
      </div>
    </header>

    <!-- Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-200 shadow-inner mt-auto">
      <div class="container mx-auto text-center py-3 text-black text-md">
        &copy; {{ new Date().getFullYear() }} JC Developer. All rights reserved.
        <div class="mt-2 space-x-3">
          <a href="#" class="hover:text-green-600 transition">Facebook</a>
          <a href="#" class="hover:text-green-600 transition">Instagram</a>
          <a href="#" class="hover:text-green-600 transition">Twitter</a>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const page = usePage()
const user = page.props.auth?.user

const isLoggedIn = computed(() => !!user)
const isAdmin = computed(() => user?.role === 'admin')

const menuOpen = ref(false)
const toggleMenu = () => {
  menuOpen.value = !menuOpen.value
}
</script>
