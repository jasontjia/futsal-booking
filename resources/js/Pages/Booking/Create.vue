<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200">
    <!-- Navbar -->
    <header class="bg-gray-200 shadow-sm fixed top-0 w-full z-50">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-4 items-center font-medium text-black">
          <Link :href="route('booking.index')" class="hover:text-green-600 transition">Riwayat</Link>
          <Link :href="route('booking.create')" class="hover:text-green-600 transition">Booking</Link>
          <span class="cursor-pointer text-red-600 hover:underline transition" @click="showLogoutModal = true">
            Logout
          </span>
        </nav>

        <!-- Mobile Menu Button -->
        <button @click="menuOpen = !menuOpen" class="md:hidden text-gray-600 hover:text-gray-800 focus:outline-none">
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
          <Link :href="route('booking.index')" class="hover:text-green-600">Riwayat</Link>
          <Link :href="route('booking.create')" class="hover:text-green-600">Booking</Link>
          <span class="cursor-pointer text-red-600 hover:underline mt-2" @click="showLogoutModal = true">
            Logout
          </span>
        </nav>
      </div>
    </header>

    <!-- Content -->
    <main class="flex-grow container mx-auto px-6 pt-28 pb-10">
      <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Buat Booking</h1>
        
        <form @submit.prevent="submit" class="space-y-4">
          <!-- Lapangan Custom Dropdown -->
          <div>
            <label class="block font-semibold mb-1">Lapangan</label>
            <div 
              class="border rounded p-2 flex items-center justify-between cursor-pointer"
              @click="dropdownOpen = !dropdownOpen"
            >
              <div class="flex items-center space-x-2">
                <img v-if="selected?.foto" :src="`/storage/${selected.foto}`" class="w-12 h-8 object-cover rounded" />
                <span>{{ selected?.nama || '-- Pilih Lapangan --' }}</span>
              </div>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            <div v-if="dropdownOpen" class="border rounded mt-1 bg-white max-h-60 overflow-auto shadow-lg">
              <div 
                v-for="lapangan in lapangans" 
                :key="lapangan.id"
                @click="selectLapangan(lapangan)"
                class="flex items-center space-x-2 p-2 hover:bg-gray-100 cursor-pointer"
              >
                <img v-if="lapangan.foto" :src="`/storage/${lapangan.foto}`" class="w-12 h-8 object-cover rounded" />
                <span>{{ lapangan.nama }}</span>
              </div>
            </div>
            <p v-if="form.errors.lapangan_id" class="text-red-600 text-sm mt-1">{{ form.errors.lapangan_id }}</p>
          </div>

          <!-- Tanggal -->
          <div>
            <label for="tanggal" class="block font-semibold mb-1">Tanggal</label>
            <input id="tanggal" type="date" v-model="form.tanggal" class="w-full border rounded-lg p-2" />
            <p v-if="form.errors.tanggal" class="text-red-600 text-sm mt-1">{{ form.errors.tanggal }}</p>
          </div>

          <!-- Jam Mulai -->
          <div>
            <label for="jam_mulai" class="block font-semibold mb-1">Jam Mulai</label>
            <input id="jam_mulai" type="time" v-model="form.jam_mulai" class="w-full border rounded-lg p-2" />
            <p v-if="form.errors.jam_mulai" class="text-red-600 text-sm mt-1">{{ form.errors.jam_mulai }}</p>
          </div>

          <!-- Jam Selesai -->
          <div>
            <label for="jam_selesai" class="block font-semibold mb-1">Jam Selesai</label>
            <input id="jam_selesai" type="time" v-model="form.jam_selesai" class="w-full border rounded-lg p-2" />
            <p v-if="form.errors.jam_selesai" class="text-red-600 text-sm mt-1">{{ form.errors.jam_selesai }}</p>
          </div>

          <!-- Buttons -->
          <div class="flex justify-between mt-6">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
              Konfirmasi
            </button>
            <Link href="/booking" class="px-4 py-2 bg-gray-300 text-black rounded-lg hover:bg-gray-400 transition">
              Batal
            </Link>
          </div>
        </form>
      </div>
    </main>

    <!-- Logout Modal -->
    <div v-if="showLogoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-lg p-6 w-96">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Logout</h2>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar?</p>
        <div class="flex justify-end space-x-3">
          <button @click="showLogoutModal = false" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">
            Batal
          </button>
          <form method="POST" :action="route('logout')">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
              Logout
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-200 shadow-inner py-3 mt-auto">
      <div class="container mx-auto text-center text-black text-md">
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
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({ lapangans: Array })

const form = useForm({
  lapangan_id: '',
  tanggal: '',
  jam_mulai: '',
  jam_selesai: '',
})

const dropdownOpen = ref(false)
const selected = ref(null)
const showLogoutModal = ref(false)
const menuOpen = ref(false)

function selectLapangan(lapangan) {
  form.lapangan_id = lapangan.id
  selected.value = lapangan
  dropdownOpen.value = false
}

function submit() {
  form.post('/booking')
}
</script>
