<template>
  <AppLayout>
    <div class="min-h-screen bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200">
      <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Hero -->
        <div class="text-center mb-12">
          <h1 class="text-4xl font-extrabold text-gray-800 sm:text-5xl">
            Selamat Datang di <span class="text-blue-700">Futsal Booking</span>
          </h1>
          <p class="mt-4 text-lg text-gray-700">
            Pilih lapangan favoritmu dan cek ketersediaan jadwal dengan mudah 🚀
          </p>
        </div>

        <!-- Grid Lapangan -->
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 mb-12">
          <div
            v-for="lap in lapangans"
            :key="lap.id"
            class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transform transition duration-300"
          >
            <!-- Tambahan Gambar -->
            <img
              v-if="lap.foto"
              :src="`/storage/${lap.foto}`"
              :alt="lap.nama"
              class="w-full h-48 object-cover rounded-lg mb-4"
            />
            <img
              v-else
              src="/storage/images/no-image.png"
              alt="Lapangan"
              class="w-full h-48 object-cover rounded-lg mb-4"
            />

            <h3 class="text-xl font-bold text-gray-800 mb-3">
              {{ lap.nama }}
            </h3>
            <p class="text-gray-600 mb-4">
              Harga: 
              <span class="font-semibold text-green-600">
                Rp {{ lap.harga_per_jam }}
              </span> / jam
            </p>
            <Link
              :href="`/jadwal?lapangan=${lap.id}`"
              class="inline-block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
            >
              Lihat Jadwal
            </Link>
          </div>
        </div>

        <!-- CTA -->
        <div class="text-center space-y-4">
          <!-- CTA Register -->
          <p class="text-gray-700">
            Belum punya akun?  
            <Link href="/register" class="text-blue-700 font-semibold hover:underline">
              Daftar Sekarang
            </Link>
          </p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
  lapangans: Array,
});
</script>
