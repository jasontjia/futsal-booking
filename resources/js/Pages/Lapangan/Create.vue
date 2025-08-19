<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200">
    <!-- Navbar -->
    <header class="bg-gray-200 shadow-sm sticky top-0 z-50">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-6 items-center font-medium text-gray-700">
          <Link :href="route('admin.dashboard')" class="hover:text-green-600 transition">Dashboard</Link>
          <Link :href="route('lapangan.index')" class="hover:text-green-600 transition">Lapangan</Link>
          <span @click="showLogoutModal = true" class="cursor-pointer text-red-600 hover:underline transition">
            Logout
          </span>
        </nav>

        <!-- Mobile Menu Button -->
        <button @click="menuOpen = !menuOpen" class="md:hidden text-gray-600 hover:text-gray-800 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div v-if="menuOpen" class="md:hidden bg-white border-t shadow-sm">
        <nav class="flex flex-col space-y-2 p-4 text-gray-600 font-medium">
          <Link :href="route('admin.dashboard')" class="hover:text-green-600">Dashboard</Link>
          <Link :href="route('lapangan.index')" class="hover:text-green-600">Lapangan</Link>
          <span @click="showLogoutModal = true" class="cursor-pointer text-red-600 hover:underline transition">
            Logout
          </span>
        </nav>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center p-6">
      <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Tambah Lapangan</h1>
        <form @submit.prevent="submit" class="space-y-4" enctype="multipart/form-data">
          <!-- Nama Lapangan -->
          <div>
            <label class="block font-semibold mb-1">Nama Lapangan</label>
            <input v-model="form.nama" type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
            <p v-if="form.errors.nama" class="text-red-600 text-sm mt-1">{{ form.errors.nama }}</p>
          </div>

          <!-- Harga per Jam -->
          <div>
            <label class="block font-semibold mb-1">Harga per Jam</label>
            <input v-model="form.harga_per_jam" type="number" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
            <p v-if="form.errors.harga_per_jam" class="text-red-600 text-sm mt-1">{{ form.errors.harga_per_jam }}</p>
          </div>

          <!-- Jam Buka -->
          <div>
            <label class="block font-semibold mb-1">Jam Buka</label>
            <input v-model="form.jam_buka" type="time" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
            <p v-if="form.errors.jam_buka" class="text-red-600 text-sm mt-1">{{ form.errors.jam_buka }}</p>
          </div>

          <!-- Jam Tutup -->
          <div>
            <label class="block font-semibold mb-1">Jam Tutup</label>
            <input v-model="form.jam_tutup" type="time" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
            <p v-if="form.errors.jam_tutup" class="text-red-600 text-sm mt-1">{{ form.errors.jam_tutup }}</p>
          </div>

          <!-- Upload Gambar -->
          <div>
            <label class="block font-semibold mb-1">Foto Lapangan</label>
            <input type="file" @change="handleFileChange" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
            <p v-if="form.errors.foto" class="text-red-600 text-sm mt-1">{{ form.errors.foto }}</p>

            <!-- Preview Gambar -->
            <div v-if="preview" class="mt-4">
              <p class="text-gray-600 text-sm mb-1">Preview:</p>
              <img :src="preview" alt="Preview" class="w-full h-48 object-cover rounded-lg" />
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-between items-center pt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
            <Link :href="route('lapangan.index')" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Batal</Link>
          </div>
        </form>
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-200 shadow-inner py-3">
      <div class="container mx-auto text-center text-black text-md">
        &copy; {{ new Date().getFullYear() }} JC Developer. All rights reserved.
        <div class="mt-2 space-x-4">
          <a href="https://www.instagram.com/jasonn_christopher?igsh=OXAzenJwa3g5azcx" target="_blank"class="hover:text-green-600 transition">Instagram</a>
          <a href="https://mail.google.com/mail/?view=cm&to=christopher.ciayadi2511@gmail.com" target="_blank" class="hover:text-green-600 transition">Gmail</a>
        </div>
      </div>
    </footer>

    <!-- Logout Modal -->
    <div v-if="showLogoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
        <h2 class="text-lg font-bold mb-4">Konfirmasi Logout</h2>
        <p class="mb-6 text-gray-600">Apakah Anda yakin ingin logout?</p>
        <div class="flex justify-end space-x-3">
          <button @click="showLogoutModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
          <form :action="route('logout')" method="post">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { onMounted } from 'vue'

onMounted(() => {
  document.title = "Tambah Lapangan - Admin"
})

const menuOpen = ref(false)
const showLogoutModal = ref(false)

const form = useForm({
  nama: '',
  harga_per_jam: '',
  jam_buka: '',
  jam_tutup: '',
  foto: null,
})

const preview = ref(null)

function handleFileChange(e) {
  const file = e.target.files[0]
  form.foto = file

  if (file) {
    const reader = new FileReader()
    reader.onload = (event) => {
      preview.value = event.target.result
    }
    reader.readAsDataURL(file)
  } else {
    preview.value = null
  }
}

function submit() {
  form.post(route('lapangan.store'), {
    forceFormData: true,
  })
}
</script>
