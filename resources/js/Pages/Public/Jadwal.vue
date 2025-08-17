<template>
  <AppLayout>
    <div class="min-h-screen bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200 py-12 px-4">
      <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-2xl p-8">
        <!-- Title -->
        <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-8">
          Jadwal <span class="text-blue-700">Ketersediaan</span>
        </h1>

        <!-- Filter Tanggal -->
        <form
          @submit.prevent="submitTanggal"
          class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-10"
        >
          <input
            type="date"
            v-model="tanggal"
            class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:w-auto focus:ring-2 focus:ring-blue-500"
          />
          <button
            type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Lihat
          </button>
        </form>

        <!-- Jadwal per Lapangan -->
        <div
          v-for="lap in lapangans"
          :key="lap.id"
          class="mb-10 bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100"
        >
          <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ lap.nama }}</h3>
          <p class="text-gray-600 mb-4">
            Jam buka:
            <span class="font-medium text-gray-800">
              {{ lap.jam_buka }} - {{ lap.jam_tutup }}
            </span>
          </p>

          <div v-if="lap.bookings.length">
            <h4 class="font-medium text-gray-800 mb-3">Sudah Dipesan:</h4>
            <ul class="space-y-2">
              <li
                v-for="b in lap.bookings"
                :key="b.id"
                class="flex items-center justify-between bg-white rounded-lg shadow px-4 py-2 border"
              >
                <span class="text-gray-700">
                  {{ b.jam_mulai }} - {{ b.jam_selesai }}
                </span>
                <span
                  class="px-3 py-1 rounded-full text-sm font-medium"
                  :class="{
                    'bg-green-100 text-green-700': b.status === 'confirmed',
                    'bg-yellow-100 text-yellow-700': b.status === 'pending',
                    'bg-red-100 text-red-700': b.status === 'cancelled'
                  }"
                >
                  {{ b.status }}
                </span>
              </li>
            </ul>
          </div>
          <div v-else>
            <p class="text-gray-500 italic">Tidak ada booking hari ini</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { router, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
  lapangans: Array,
  tanggal: String,
});

let tanggal = props.tanggal;

function submitTanggal() {
  router.get("/jadwal", { tanggal });
}
</script>
