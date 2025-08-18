<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200 flex flex-col">
    <!-- Navbar -->
    <header class="bg-gray-200 shadow-sm sticky top-0 z-50">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>
        <nav class="hidden md:flex space-x-6 items-center font-medium text-gray-700">
          <Link :href="route('admin.dashboard')" class="hover:text-green-600 transition">Dashboard</Link>
          <Link :href="route('lapangan.index')" class="hover:text-green-600 transition">Lapangan</Link>
          <span @click="showLogoutModal = true" class="cursor-pointer text-red-600 hover:underline transition">Logout</span>
        </nav>
        <button @click="menuOpen = !menuOpen" class="md:hidden text-gray-600 hover:text-gray-800 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>
      </div>
      <div v-if="menuOpen" class="md:hidden bg-white border-t shadow-sm">
        <nav class="flex flex-col space-y-2 p-4 text-gray-600 font-medium">
          <Link :href="route('admin.dashboard')" class="hover:text-green-600">Dashboard</Link>
          <Link :href="route('lapangan.index')" class="hover:text-green-600">Lapangan</Link>
          <span @click="showLogoutModal = true" class="cursor-pointer text-red-600 hover:underline transition">Logout</span>
        </nav>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 md:px-6 py-6">
      <div class="bg-white shadow-lg rounded-lg p-6">
        <!-- Judul + Search + Tambah -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
          <h2 class="text-2xl font-bold mb-3 md:mb-0">Daftar Lapangan</h2>
          <div class="flex flex-col md:flex-row md:items-center md:space-x-2 w-full md:w-auto">
            <input type="text" v-model="globalSearch" placeholder="Cari.."
                   class="w-full md:w-64 px-3 py-2 border rounded mb-2 md:mb-0 focus:outline-none focus:ring"/>
            <Link :href="route('lapangan.create')"
                  class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
              + Tambah Lapangan
            </Link>
          </div>
        </div>

        <!-- Total Lapangan -->
        <div class="text-gray-700 font-medium">Total Lapangan: <span class="font-bold">{{ filteredLapangan.length }}</span></div>
        <br />

        <!-- Tabel -->
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-200 text-gray-700">
                <th class="px-4 py-2 border cursor-pointer" @click="sortBy('nama')">
                  Nama <span v-if="sortColumn === 'nama'">{{ sortDir === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="px-4 py-2 border cursor-pointer" @click="sortBy('harga_per_jam')">
                  Harga <span v-if="sortColumn === 'harga_per_jam'">{{ sortDir === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="px-4 py-2 border cursor-pointer" @click="sortBy('jam_buka')">
                  Jam Buka <span v-if="sortColumn === 'jam_buka'">{{ sortDir === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="px-4 py-2 border cursor-pointer" @click="sortBy('jam_tutup')">
                  Jam Tutup <span v-if="sortColumn === 'jam_tutup'">{{ sortDir === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="px-4 py-2 border text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="lapangan in paginatedLapangan" :key="lapangan.id" class="hover:bg-gray-50">
                <td class="px-4 py-2 border cursor-pointer text-blue-600 hover:underline"
                    @click="openImageModal(lapangan)">{{ lapangan.nama }}</td>
                <td class="px-4 py-2 border">Rp {{ lapangan.harga_per_jam }}</td>
                <td class="px-4 py-2 border">{{ lapangan.jam_buka }}</td>
                <td class="px-4 py-2 border">{{ lapangan.jam_tutup }}</td>
                <td class="px-4 py-2 border text-center space-x-2">
                  <Link :href="route('lapangan.edit', lapangan.id)" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">Edit</Link>
                  <button @click="confirmDelete(lapangan.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                </td>
              </tr>
              <tr v-if="filteredLapangan.length === 0">
                <td colspan="5" class="text-center py-4 text-gray-500">Data tidak ditemukan</td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination -->
          <div class="flex justify-between items-center mt-4">
            <button @click="currentPage--" :disabled="currentPage === 1"
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50">Prev</button>
            <div class="space-x-2">
              <button v-for="page in totalPages" :key="page"
                      @click="currentPage = page"
                      :class="{'bg-blue-500 text-white': currentPage === page, 'bg-gray-200': currentPage !== page}"
                      class="px-3 py-1 rounded hover:bg-blue-400 transition">
                {{ page }}
              </button>
            </div>
            <button @click="currentPage++" :disabled="currentPage === totalPages"
                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50">Next</button>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal Gambar -->
    <div v-if="showImageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-4 relative">
        <button @click="showImageModal = false" class="absolute top-2 right-2 text-gray-700 hover:text-gray-900 text-xl font-bold">&times;</button>
        <h3 class="text-lg font-bold mb-2">{{ selectedLapangan.nama }}</h3>
        <img :src="`/storage/${selectedLapangan.foto}`" alt="Lapangan" class="w-full rounded"/>
      </div>
    </div>

    <!-- Modals Konfirmasi Logout / Hapus -->
    <div v-if="showLogoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-4">
        <h3 class="text-lg font-bold mb-4">Konfirmasi Logout</h3>
        <p class="mb-4">Apakah Anda yakin ingin logout?</p>
        <div class="flex justify-end space-x-2">
          <button @click="showLogoutModal=false" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
          <button @click="logout" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Logout</button>
        </div>
      </div>
    </div>

    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-4">
        <h3 class="text-lg font-bold mb-4">Konfirmasi Hapus</h3>
        <p class="mb-4">Apakah Anda yakin ingin menghapus lapangan ini?</p>
        <div class="flex justify-end space-x-2">
          <button @click="showDeleteModal=false" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
          <button @click="hapus" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
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
import { ref, computed, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({ lapangans: Array })

// Navbar & modals
const menuOpen = ref(false)
const showLogoutModal = ref(false)
const showDeleteModal = ref(false)
const deleteId = ref(null)
const showImageModal = ref(false)
const selectedLapangan = ref({ foto: '', nama: '' })

function openImageModal(lapangan) { selectedLapangan.value = lapangan; showImageModal.value = true }
function confirmDelete(id) { deleteId.value = id; showDeleteModal.value = true }
function hapus() { if(deleteId.value){ router.delete(route('lapangan.destroy', deleteId.value)); showDeleteModal.value = false } }
function logout() { showLogoutModal.value = false; router.post('/logout') }

// Global search & sorting
const globalSearch = ref('')
const sortColumn = ref('')
const sortDir = ref('asc')

function sortBy(column) {
  if(sortColumn.value === column) sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
  else { sortColumn.value = column; sortDir.value = 'asc' }
}

const filteredLapangan = computed(() => {
  let data = props.lapangans.filter(l => {
    const term = globalSearch.value.toLowerCase()
    return l.nama.toLowerCase().includes(term) ||
           l.harga_per_jam.toString().includes(term) ||
           l.jam_buka.toLowerCase().includes(term) ||
           l.jam_tutup.toLowerCase().includes(term)
  })

  if(sortColumn.value){
    data.sort((a,b)=>{
      let valA = a[sortColumn.value], valB = b[sortColumn.value]
      if(typeof valA==='string') valA = valA.toLowerCase()
      if(typeof valB==='string') valB = valB.toLowerCase()
      return valA < valB ? (sortDir.value==='asc'?-1:1) : valA > valB ? (sortDir.value==='asc'?1:-1) : 0
    })
  }
  return data
})

// Pagination
const currentPage = ref(1)
const perPage = ref(5)
const totalPages = computed(()=>Math.ceil(filteredLapangan.value.length/perPage.value))
const paginatedLapangan = computed(()=>{
  const start = (currentPage.value-1)*perPage.value
  return filteredLapangan.value.slice(start, start+perPage.value)
})
watch(globalSearch,()=>{ currentPage.value=1 })
</script>
