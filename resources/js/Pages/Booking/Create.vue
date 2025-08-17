<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200">
  <!-- Navbar -->
    <header class="bg-white shadow-sm sticky top-0 z-50 mb-6">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-4 items-center font-medium text-black">
          <Link :href="route('booking.index')" class="hover:text-green-600 transition">Riwayat</Link>
          <Link :href="route('booking.create')" class="hover:text-green-600 transition">Booking</Link>
          <span 
            class="cursor-pointer text-red-600 hover:underline transition"
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
          <span 
            class="cursor-pointer text-red-600 hover:underline mt-2"
            @click="showLogoutModal = true"
          >
            Logout
          </span>
        </nav>
      </div>
    </header>
    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg">
      <div style="max-width: 500px; margin: auto; padding: 2rem;">
        <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 1.5rem;">Buat Booking</h1>
        
        <form @submit.prevent="submit" style="display: flex; flex-direction: column; gap: 1rem;">
          
          <div>
            <label for="lapangan" style="display: block; font-weight: 600;">Lapangan</label>
            <select id="lapangan" v-model="form.lapangan_id" style="width: 100%; padding: 0.5rem;">
              <option disabled value="">-- Pilih Lapangan --</option>
              <option v-for="lapangan in lapangans" :key="lapangan.id" :value="lapangan.id">
                {{ lapangan.nama }}
              </option>
            </select>
            <p v-if="form.errors.lapangan_id" style="color: red; font-size: 14px;">
              {{ form.errors.lapangan_id }}
            </p>
          </div>

          <div>
            <label for="tanggal" style="display: block; font-weight: 600;">Tanggal</label>
            <input id="tanggal" type="date" v-model="form.tanggal" style="width: 100%; padding: 0.5rem;" />
            <p v-if="form.errors.tanggal" style="color: red; font-size: 14px;">
              {{ form.errors.tanggal }}
            </p>
          </div>

          <div>
            <label for="jam_mulai" style="display: block; font-weight: 600;">Jam Mulai</label>
            <input id="jam_mulai" type="time" v-model="form.jam_mulai" style="width: 100%; padding: 0.5rem;" />
            <p v-if="form.errors.jam_mulai" style="color: red; font-size: 14px;">
              {{ form.errors.jam_mulai }}
            </p>
          </div>

          <div>
            <label for="jam_selesai" style="display: block; font-weight: 600;">Jam Selesai</label>
            <input id="jam_selesai" type="time" v-model="form.jam_selesai" style="width: 100%; padding: 0.5rem;" />
            <p v-if="form.errors.jam_selesai" style="color: red; font-size: 14px;">
              {{ form.errors.jam_selesai }}
            </p>
          </div>

          <div style="display: flex; justify-content: space-between; margin-top: 1.5rem;">
            <button type="submit" style="padding: 0.5rem 1rem; background-color: #4CAF50; color: white; border: none; border-radius: 4px;">
              Konfirmasi
            </button>
            <Link href="/booking" style="padding: 0.5rem 1rem; background-color: #ccc; color: black; text-decoration: none; border-radius: 4px;">
              Batal
            </Link>
          </div>
        </form>
      </div>
    </div>
</div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
const props = defineProps({ lapangans: Array })

const form = useForm({
  lapangan_id: '',
  tanggal: '',
  jam_mulai: '',
  jam_selesai: '',
})

function submit() {
  form.post('/booking')
}
</script>
