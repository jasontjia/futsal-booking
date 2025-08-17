<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200 flex flex-col">
    <!-- Navbar -->
    <header class="bg-gray-200 shadow-sm sticky top-0 z-50">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-6 items-center font-medium text-gray-700">
          <Link :href="route('admin.dashboard')" class="hover:text-green-600 transition">Dashboard</Link>
          <Link :href="route('lapangan.index')" class="hover:text-green-600 transition">Lapangan</Link>
          <span
            @click="showLogoutModal = true"
            class="cursor-pointer text-red-600 hover:underline transition"
          >
            Logout
          </span>
        </nav>

        <!-- Mobile Menu Button -->
        <button
          @click="menuOpen = !menuOpen"
          class="md:hidden text-gray-600 hover:text-gray-800 focus:outline-none"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div v-if="menuOpen" class="md:hidden bg-white border-t shadow-sm">
        <nav class="flex flex-col space-y-2 p-4 text-gray-600 font-medium">
          <Link :href="route('admin.dashboard')" class="hover:text-green-600">Dashboard</Link>
          <Link :href="route('lapangan.index')" class="hover:text-green-600">Lapangan</Link>
          <span
            @click="showLogoutModal = true"
            class="cursor-pointer text-red-600 hover:underline transition"
          >
            Logout
          </span>
        </nav>
      </div>
    </header>

    <!-- Content -->
    <main class="flex-1 container mx-auto px-4 md:px-6 py-6">
      <div class="bg-white shadow-lg rounded-lg p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold">Daftar Lapangan</h2>
          <Link
            :href="route('lapangan.create')"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
          >
            + Tambah Lapangan
          </Link>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-200 text-gray-700">
                <th class="px-4 py-2 border">Nama</th>
                <th class="px-4 py-2 border">Harga</th>
                <th class="px-4 py-2 border">Jam Buka</th>
                <th class="px-4 py-2 border">Jam Tutup</th>
                <th class="px-4 py-2 border text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="lapangan in lapangans"
                :key="lapangan.id"
                class="hover:bg-gray-50"
              >
                <td class="px-4 py-2 border">{{ lapangan.nama }}</td>
                <td class="px-4 py-2 border">Rp {{ lapangan.harga_per_jam }}</td>
                <td class="px-4 py-2 border">{{ lapangan.jam_buka }}</td>
                <td class="px-4 py-2 border">{{ lapangan.jam_tutup }}</td>
                <td class="px-4 py-2 border text-center space-x-2">
                  <Link
                    :href="route('lapangan.edit', lapangan.id)"
                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded"
                  >
                    Edit
                  </Link>
                  <button
                    @click="confirmDelete(lapangan.id)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                  >
                    Hapus
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <!-- Modals -->
    <!-- Logout Confirmation -->
    <div
      v-if="showLogoutModal"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
      <div class="bg-white rounded-lg shadow-lg p-6 w-80">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin logout?</p>
        <div class="flex justify-end space-x-2">
          <button
            @click="showLogoutModal = false"
            class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition"
          >
            Batal
          </button>
          <button
            @click="logout"
            class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition"
          >
            Logout
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
      <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Hapus</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus lapangan ini?</p>
        <div class="flex justify-end space-x-2">
          <button
            @click="showDeleteModal = false"
            class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition"
          >
            Batal
          </button>
          <button
            @click="hapus"
            class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition"
          >
            Hapus
          </button>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-200 shadow-inner py-3 mt-6">
      <div class="container mx-auto text-center text-gray-700 text-md">
        &copy; {{ new Date().getFullYear() }} JC Developer. All rights reserved.
        <div class="mt-2 space-x-4">
          <a href="#" class="hover:text-green-600 transition">Facebook</a>
          <a href="#" class="hover:text-green-600 transition">Instagram</a>
          <a href="#" class="hover:text-green-600 transition">Twitter</a>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

defineProps({ lapangans: Array })

const menuOpen = ref(false)
const showLogoutModal = ref(false)
const showDeleteModal = ref(false)
const deleteId = ref(null)

function confirmDelete(id) {
  deleteId.value = id
  showDeleteModal.value = true
}

function hapus() {
  if (deleteId.value) {
    router.delete(route('lapangan.destroy', deleteId.value))
    showDeleteModal.value = false
  }
}

function logout() {
  showLogoutModal.value = false
  router.post('/logout')
}
</script>
