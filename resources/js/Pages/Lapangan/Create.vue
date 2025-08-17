<template>
  <div class="min-h-screen bg-gradient-to-b from-green-200 to-blue-200 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Tambah Lapangan</h1>

      <form @submit.prevent="submit" class="space-y-4">

        <div>
          <label class="block font-semibold mb-1">Nama Lapangan</label>
          <input v-model="form.nama" type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
          <p v-if="form.errors.nama" class="text-red-600 text-sm mt-1">{{ form.errors.nama }}</p>
        </div>

        <div>
          <label class="block font-semibold mb-1">Harga per Jam</label>
          <input v-model="form.harga_per_jam" type="number" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
          <p v-if="form.errors.harga_per_jam" class="text-red-600 text-sm mt-1">{{ form.errors.harga_per_jam }}</p>
        </div>

        <div>
          <label class="block font-semibold mb-1">Jam Buka</label>
          <input v-model="form.jam_buka" type="time" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
          <p v-if="form.errors.jam_buka" class="text-red-600 text-sm mt-1">{{ form.errors.jam_buka }}</p>
        </div>

        <div>
          <label class="block font-semibold mb-1">Jam Tutup</label>
          <input v-model="form.jam_tutup" type="time" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
          <p v-if="form.errors.jam_tutup" class="text-red-600 text-sm mt-1">{{ form.errors.jam_tutup }}</p>
        </div>

        <div class="flex justify-between items-center pt-4">
          <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Simpan
          </button>
          <Link :href="route('lapangan.index')" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
            Batal
          </Link>
        </div>

      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  nama: '',
  harga_per_jam: '',
  jam_buka: '',
  jam_tutup: '',
})

function submit() {
  form.post(route('lapangan.store'))
}
</script>
