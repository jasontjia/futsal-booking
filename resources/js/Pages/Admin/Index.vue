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

    <!-- Table Card -->
    <div class="overflow-x-auto shadow-lg rounded-lg bg-white border border-gray-200 mx-6">
      <!-- Table Header with Title -->
      <div class="flex justify-between items-center bg-gray-100 px-6 py-4 rounded-t-lg border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Data Booking Masuk</h2>
      </div>

      <!-- Table -->
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">User</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Lapangan</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jam</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Bukti</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr
            v-for="booking in bookings"
            :key="booking.id"
            class="hover:bg-gray-50 transition"
          >
            <td class="px-4 py-2 text-gray-800">{{ booking.user.name }}</td>
            <td class="px-4 py-2 text-gray-800">{{ booking.lapangan.nama }}</td>
            <td class="px-4 py-2 text-gray-800">{{ booking.tanggal }}</td>
            <td class="px-4 py-2 text-gray-800">{{ booking.jam_mulai }} - {{ booking.jam_selesai }}</td>
            <td class="px-4 py-2">
              <template v-if="booking.bukti_pembayaran">
                <a
                  :href="`/storage/${booking.bukti_pembayaran}`"
                  target="_blank"
                  class="text-blue-600 hover:underline font-medium"
                >
                  Lihat Bukti
                </a>
              </template>
              <span v-else class="text-gray-400">-</span>
            </td>
            <td class="px-4 py-2">
              <span
                :class="{
                  'bg-yellow-100 text-yellow-800': booking.status === 'menunggu_verifikasi',
                  'bg-green-100 text-green-800': booking.status === 'diterima',
                  'bg-red-100 text-red-800': booking.status === 'ditolak',
                }"
                class="px-2 py-1 rounded-full text-xs font-semibold"
              >
                {{ booking.status.replace('_', ' ') }}
              </span>
            </td>
            <td class="px-4 py-2 flex justify-center space-x-2">
              <template v-if="booking.status === 'menunggu_verifikasi'">
                <button
                  @click="verifikasi(booking.id, 'diterima')"
                  :disabled="loading"
                  class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm shadow-sm transition"
                >
                  Terima
                </button>
                <button
                  @click="verifikasi(booking.id, 'ditolak')"
                  :disabled="loading"
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm shadow-sm transition"
                >
                  Tolak
                </button>
              </template>
              <span v-else class="text-gray-400">-</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Logout Confirmation Modal -->
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
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
  bookings: Array,
});

const loading = ref(false);
const menuOpen = ref(false);
const showLogoutModal = ref(false);

async function verifikasi(id, status) {
  loading.value = true;
  try {
    await router.post(`/admin/bookings/${id}/verifikasi`, { status });
  } catch (error) {
    console.error('Error verifying booking:', error);
  } finally {
    loading.value = false;
  }
}

function logout() {
  showLogoutModal.value = false;
  router.post('/logout');
}
</script>
