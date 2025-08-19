<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200">
    <!-- Navbar -->
    <header class="fixed top-0 w-full bg-gray-200 backdrop-blur shadow z-50">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>
        <nav class="hidden md:flex space-x-6 items-center font-medium text-gray-700">
          <Link :href="route('booking.index')" class="hover:text-green-600 transition">Riwayat</Link>
          <Link :href="route('booking.create')" class="hover:text-green-600 transition">Booking</Link>
          <span class="cursor-pointer text-red-600 hover:text-red-700 transition" @click="showLogoutModal=true">Logout</span>
        </nav>
        <button @click="menuOpen=!menuOpen" class="md:hidden text-gray-600 hover:text-gray-800 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
          </svg>
        </button>
      </div>
      <div v-if="menuOpen" class="md:hidden bg-white border-t shadow-sm animate-fadeIn">
        <nav class="flex flex-col space-y-3 p-4 text-gray-700 font-medium">
          <Link :href="route('booking.index')" class="hover:text-green-600">Riwayat</Link>
          <Link :href="route('booking.create')" class="hover:text-green-600">Booking</Link>
          <span class="cursor-pointer text-red-600 hover:text-red-700 mt-2" @click="showLogoutModal=true">Logout</span>
        </nav>
      </div>
    </header>

    <main class="flex-1 container mx-auto pt-28 pb-10 px-4">
      <div class="bg-white shadow-xl rounded-xl p-6 max-w-lg mx-auto">
        <h1 class="text-2xl font-bold mb-4">Edit Booking</h1>
        <form @submit.prevent="submit" class="flex flex-col gap-4">

          <!-- Pilih Lapangan -->
          <label class="flex flex-col">
            Lapangan
            <select v-model="form.lapangan_id" @change="updateLapangan" class="w-full border px-3 py-2 rounded">
              <option v-for="lap in lapangans" :key="lap.id" :value="lap.id">{{ lap.nama }}</option>
            </select>
          </label>

          <div v-if="selectedLapangan.foto" class="mt-2">
            <img :src="`/storage/${selectedLapangan.foto}`" alt="Foto Lapangan" class="w-64 h-40 object-cover rounded shadow"/>
          </div>
          <div v-else class="text-gray-500 italic mt-2">Tidak ada gambar lapangan</div>

          <!-- Tanggal & Jam -->
          <label class="flex flex-col">Tanggal<input type="date" v-model="form.tanggal" class="w-full border px-3 py-2 rounded"/></label>
          <label class="flex flex-col">Jam Mulai<input type="time" v-model="form.jam_mulai" class="w-full border px-3 py-2 rounded"/></label>
          <label class="flex flex-col">Jam Selesai<input type="time" v-model="form.jam_selesai" class="w-full border px-3 py-2 rounded"/></label>

          <!-- Upload Bukti Pembayaran -->
          <label class="flex flex-col">
            Bukti Pembayaran
            <input type="file" @change="onFileChange" class="w-full border px-3 py-2 rounded"/>
            <div v-if="form.bukti_pembayaran_preview" class="mt-2">
              <img :src="form.bukti_pembayaran_preview" class="w-32 h-auto rounded shadow"/>
            </div>
            <div v-else-if="props.booking.bukti_pembayaran" class="mt-2">
              <a :href="`/storage/${props.booking.bukti_pembayaran}`" target="_blank">
                <img :src="`/storage/${props.booking.bukti_pembayaran}`" class="w-24 h-auto rounded shadow"/>
              </a>
            </div>
          </label>

          <!-- Aksi -->
          <div class="flex justify-between items-center pt-4">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
            <Link :href="route('booking.index')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</Link>
          </div>
        </form>
      </div>
    </main>
    <!-- Footer -->
    <footer class="bg-gray-200 shadow-inner py-3 mt-6">
      <div class="container mx-auto text-center text-gray-700 text-md">
        &copy; {{ new Date().getFullYear() }} JC Developer. All rights reserved.
        <div class="mt-2 space-x-4">
          <a href="https://www.instagram.com/jasonn_christopher?igsh=OXAzenJwa3g5azcx" target="_blank"class="hover:text-green-600 transition">Instagram</a>
          <a href="https://mail.google.com/mail/?view=cm&to=christopher.ciayadi2511@gmail.com" target="_blank" class="hover:text-green-600 transition">Gmail</a>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, Link, router } from '@inertiajs/vue3'

const props = defineProps({ booking: Object, lapangans: Array })
const selectedLapangan = ref(props.lapangans.find(l => l.id === props.booking.lapangan.id) || {})

const form = useForm({
  lapangan_id: props.booking.lapangan.id,
  tanggal: props.booking.tanggal,
  jam_mulai: props.booking.jam_mulai,
  jam_selesai: props.booking.jam_selesai,
  bukti_pembayaran: null,
  bukti_pembayaran_preview: null
})

function updateLapangan() {
  selectedLapangan.value = props.lapangans.find(l => l.id === form.lapangan_id) || {}
}

function onFileChange(e){
  const file = e.target.files[0]
  form.bukti_pembayaran = file
  form.bukti_pembayaran_preview = file ? URL.createObjectURL(file) : null
}

function submit(){
  const data = new FormData()
  data.append('_method', 'PUT')  // fix Method Not Allowed
  data.append('lapangan_id', form.lapangan_id)
  data.append('tanggal', form.tanggal)
  data.append('jam_mulai', form.jam_mulai)
  data.append('jam_selesai', form.jam_selesai)
  if(form.bukti_pembayaran) data.append('bukti_pembayaran', form.bukti_pembayaran)

  router.post(`/booking/${props.booking.id}`, data, {
    onSuccess: () => router.visit(route('booking.index'))
  })
}
</script>
