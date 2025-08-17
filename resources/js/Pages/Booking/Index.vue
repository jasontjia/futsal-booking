<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200">
    <!-- Navbar -->
    <header class="fixed top-0 w-full bg-gray-200 backdrop-blur shadow z-50">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-6 items-center font-medium text-gray-700">
          <Link :href="route('booking.index')" class="hover:text-green-600 transition">Riwayat</Link>
          <Link :href="route('booking.create')" class="hover:text-green-600 transition">Booking</Link>
          <span 
            class="cursor-pointer text-red-600 hover:text-red-700 transition"
            @click="showLogoutModal = true"
          >
            Logout
          </span>
        </nav>

        <!-- Mobile Menu Button -->
        <button
          @click="menuOpen = !menuOpen"
          class="md:hidden text-gray-600 hover:text-gray-800 focus:outline-none"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div v-if="menuOpen" class="md:hidden bg-white border-t shadow-sm animate-fadeIn">
        <nav class="flex flex-col space-y-3 p-4 text-gray-700 font-medium">
          <Link :href="route('booking.index')" class="hover:text-green-600">Riwayat</Link>
          <Link :href="route('booking.create')" class="hover:text-green-600">Booking</Link>
          <span 
            class="cursor-pointer text-red-600 hover:text-red-700 mt-2"
            @click="showLogoutModal = true"
          >
            Logout
          </span>
        </nav>
      </div>
    </header>

    <!-- Konten -->
    <main class="flex-1 container mx-auto pt-28 pb-10 px-4">
      <div class="bg-white shadow-xl rounded-xl p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Booking Saya</h1>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wide">
              <tr>
                <th class="px-6 py-3 text-left">Lapangan</th>
                <th class="px-6 py-3 text-left">Tanggal</th>
                <th class="px-6 py-3 text-left">Jam</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Bukti Pembayaran</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="booking in bookings" :key="booking.id" class="border-t hover:bg-gray-50 transition">
                <td class="px-6 py-4">{{ booking.lapangan.nama }}</td>
                <td class="px-6 py-4">{{ booking.tanggal }}</td>
                <td class="px-6 py-4">{{ booking.jam_mulai }} - {{ booking.jam_selesai }}</td>
                <td class="px-6 py-4 font-semibold">
                  <span
                    class="px-3 py-1 rounded-full text-xs capitalize"
                    :class="{
                      'bg-yellow-100 text-yellow-800': booking.status === 'menunggu_pembayaran',
                      'bg-green-100 text-green-800': booking.status === 'menunggu_verifikasi' || booking.status === 'terverifikasi',
                      'bg-red-100 text-red-800': booking.status === 'ditolak'
                    }"
                  >
                    {{ booking.status.replace('_', ' ') }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div v-if="booking.status === 'menunggu_pembayaran' && !booking.bukti_pembayaran">
                    <form @submit.prevent="uploadBukti(booking)" class="flex flex-col gap-2">
                      <input 
                        type="file" 
                        @change="onFileChange($event, booking)" 
                        required 
                        class="block text-sm text-gray-700" 
                      />
                      <button
                        v-if="files[booking.id]"
                        type="submit"
                        class="bg-green-600 text-white py-1 px-4 rounded-lg hover:bg-green-700 text-sm font-medium"
                      >
                        Upload
                      </button>
                    </form>
                  </div>
                  <div v-else-if="booking.bukti_pembayaran">
                    <a :href="`/storage/${booking.bukti_pembayaran}`" target="_blank">
                      <img
                        :src="`/storage/${booking.bukti_pembayaran}`"
                        alt="Bukti Pembayaran"
                        class="w-24 h-auto rounded-lg shadow border hover:scale-105 transition"
                      />
                    </a>
                  </div>
                  <div v-else class="text-gray-500 italic">Belum tersedia</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <!-- Modal Logout -->
    <div
      v-if="showLogoutModal"
      class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-xl shadow-xl p-6 w-80">
        <h2 class="text-lg font-semibold mb-4">Konfirmasi Logout</h2>
        <p class="mb-6 text-gray-600">Apakah Anda yakin ingin logout?</p>
        <div class="flex justify-end space-x-2">
          <button
            @click="showLogoutModal = false"
            class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400"
          >
            Batal
          </button>
          <button
            @click="logout"
            class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600"
          >
            Logout
          </button>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-200 shadow-inner py-3">
      <div class="container mx-auto text-center text-black before:text-md">
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
import { Link, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
  bookings: Array
})

const files = ref({})
const form = useForm({ bukti_pembayaran: null })

function onFileChange(e, booking) {
  files.value[booking.id] = e.target.files[0]
}

function uploadBukti(booking) {
  if (!files.value[booking.id]) return
  form.bukti_pembayaran = files.value[booking.id]

  form.post(`/booking/${booking.id}/upload-bukti`, {
    onSuccess: () => {
      booking.bukti_pembayaran = files.value[booking.id].name
      booking.status = 'menunggu_verifikasi'
      files.value[booking.id] = null
      form.bukti_pembayaran = null
    },
    preserveScroll: true
  })
}

const showLogoutModal = ref(false)
function logout() {
  router.post('/logout', {
    onSuccess: () => router.visit('/login')
  })
}

const menuOpen = ref(false)
</script>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
  animation: fadeIn 0.2s ease-out;
}
</style>
