<template>
  <div class="bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200 min-h-screen">
    <!-- Navbar -->
    <header class="bg-gray-200 shadow-sm mb-6 sticky top-0 z-50">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>

        <nav class="hidden md:flex space-x-4 items-center font-medium text-black">
          <Link :href="route('admin.dashboard')" class="hover:text-green-600 transition">Dashboard</Link>
          <Link :href="route('lapangan.index')" class="hover:text-green-600 transition">Lapangan</Link>
          <span @click="showLogoutModal = true" class="cursor-pointer text-red-600 hover:underline transition">
            Logout
          </span>
        </nav>

        <button @click="menuOpen = !menuOpen" class="md:hidden text-gray-600 hover:text-gray-800 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>
      </div>

      <div v-if="menuOpen" class="md:hidden bg-white border-t shadow-sm">
        <nav class="flex flex-col space-y-2 p-4 text-gray-600 font-medium">
          <Link :href="route('admin.dashboard')" class="hover:text-green-600">Dashboard</Link>
          <Link :href="route('lapangan.index')" class="hover:text-green-600 transition">Lapangan</Link>
          <span @click="showLogoutModal = true" class="cursor-pointer text-red-600 hover:underline transition mt-2">
            Logout
          </span>
        </nav>
      </div>
    </header>

    <!-- Edit Lapangan Card -->
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg">
      <h1 class="text-2xl font-bold mb-6 text-center">Edit Lapangan</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <!-- Nama Lapangan -->
        <div>
          <label class="block font-semibold mb-1">Nama Lapangan</label>
          <input v-model="form.nama" type="text"
                 class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"/>
          <div v-if="form.errors.nama" class="text-red-600 text-sm mt-1">{{ form.errors.nama }}</div>
        </div>

        <!-- Harga per Jam -->
        <div>
          <label class="block font-semibold mb-1">Harga per Jam</label>
          <input v-model="form.harga_per_jam" type="number"
                 class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"/>
          <div v-if="form.errors.harga_per_jam" class="text-red-600 text-sm mt-1">{{ form.errors.harga_per_jam }}</div>
        </div>

        <!-- Jam Buka -->
        <div>
          <label class="block font-semibold mb-1">Jam Buka</label>
          <input v-model="form.jam_buka" type="time"
                 class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"/>
          <div v-if="form.errors.jam_buka" class="text-red-600 text-sm mt-1">{{ form.errors.jam_buka }}</div>
        </div>

        <!-- Jam Tutup -->
        <div>
          <label class="block font-semibold mb-1">Jam Tutup</label>
          <input v-model="form.jam_tutup" type="time"
                 class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"/>
          <div v-if="form.errors.jam_tutup" class="text-red-600 text-sm mt-1">{{ form.errors.jam_tutup }}</div>
        </div>

                <!-- Foto Lapangan -->
        <div>
          <label class="block font-semibold mb-1">Foto Lapangan</label>
          
          <!-- Preview -->
          <div v-if="previewImage" class="mb-2">
            <p class="text-sm text-gray-600 mb-1">Preview:</p>
            <img :src="previewImage" class="w-40 h-40 object-cover rounded-lg border"/>
          </div>

          <input type="file" @change="handleFileUpload"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"/>
          <div v-if="form.errors.foto" class="text-red-600 text-sm mt-1">{{ form.errors.foto }}</div>

          <!-- Tombol hapus foto -->
          <button type="button" @click="removePhoto" 
                  class="mt-2 px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">
            Hapus Foto
          </button>
        </div>

        <!-- Tombol -->
        <div class="flex justify-between items-center pt-4">
          <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition duration-200">
            Ubah
          </button>
          <Link :href="route('lapangan.index')"
                class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded transition duration-200">
            Batal
          </Link>
        </div>
      </form>
    </div>

    <!-- Logout Modal -->
    <div v-if="showLogoutModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-80 transform transition-transform duration-200 scale-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin logout?</p>
        <div class="flex justify-end space-x-2">
          <button @click="showLogoutModal = false"
                  class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Batal</button>
          <button @click="logout"
                  class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition">Logout</button>
        </div>
      </div>
    </div>

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
import { useForm, router, Link } from '@inertiajs/vue3'
import { onMounted } from 'vue'

onMounted(() => {
  document.title = "Edit Lapangan - Admin"
})
// Props
const props = defineProps({ lapangan: Object })

// Form Inertia
const form = useForm({
  nama: props.lapangan.nama,
  harga_per_jam: props.lapangan.harga_per_jam,
  jam_buka: props.lapangan.jam_buka,
  jam_tutup: props.lapangan.jam_tutup,
  foto: null,        // hanya file baru
  remove_foto: false // flag untuk hapus foto
})

const previewImage = ref(props.lapangan.foto ? `/storage/${props.lapangan.foto}` : null)

// Handle file upload
function handleFileUpload(event) {
  const file = event.target.files[0]
  if (file) {
    form.foto = file
    form.remove_foto = false // kalau upload baru, batalkan hapus
    previewImage.value = URL.createObjectURL(file)
  }
}

// Hapus foto lama
function removePhoto() {
  form.foto = null
  form.remove_foto = true
  previewImage.value = null
}

// Submit form
function submit() {
  const payload = {
    nama: form.nama,
    harga_per_jam: form.harga_per_jam,
    jam_buka: form.jam_buka,
    jam_tutup: form.jam_tutup,
  }
  if (form.foto) payload.foto = form.foto
  payload.remove_foto = form.remove_foto

  form.put(route('lapangan.update', props.lapangan.id), {
    preserveScroll: true,
    data: payload,
    onSuccess: () => router.visit(route('lapangan.index'))
  })
}

</script>
