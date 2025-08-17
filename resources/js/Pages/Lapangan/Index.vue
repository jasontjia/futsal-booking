<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200">
    <!-- Navbar -->
    <header class="bg-white shadow-sm mb-6 sticky top-0 z-50">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-4 items-center font-medium text-black">
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
          <span
            @click="showLogoutModal = true"
            class="cursor-pointer text-red-600 hover:underline transition mt-2"
          >
            Logout
          </span>
        </nav>
      </div>
    </header>

    <!-- Daftar Lapangan -->
    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-lg p-6 relative">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Daftar Lapangan</h1>
        <Link :href="route('lapangan.create')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
          + Tambah Lapangan
        </Link>
      </div>
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
            <tr v-for="lapangan in lapangans" :key="lapangan.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 border">{{ lapangan.nama }}</td>
              <td class="px-4 py-2 border">Rp {{ lapangan.harga_per_jam }}</td>
              <td class="px-4 py-2 border">{{ lapangan.jam_buka }}</td>
              <td class="px-4 py-2 border">{{ lapangan.jam_tutup }}</td>
              <td class="px-4 py-2 border text-center space-x-2">
                <Link :href="route('lapangan.edit', lapangan.id)" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                  Edit
                </Link>
                <button @click="hapus(lapangan.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                  Hapus
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div
      v-if="showLogoutModal"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
      <div class="bg-white rounded-lg shadow-lg p-6 w-80 transform transition-transform duration-200 scale-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin logout?</p>
        <div class="flex justify-end space-x-2">
          <button
            @click="showLogoutModal = false"
            class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition"
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

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

defineProps({ lapangans: Array })

const menuOpen = ref(false)
const showLogoutModal = ref(false)

function hapus(id) {
  if (confirm('Yakin ingin menghapus lapangan?')) {
    router.delete(route('lapangan.destroy', id))
  }
}

function logout() {
  showLogoutModal.value = false
  router.post('/logout')
}
</script>
