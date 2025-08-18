<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-200 via-indigo-200 to-purple-200">
    <!-- Navbar -->
    <header class="bg-white shadow-sm mb-6 sticky top-0 z-50">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-2xl font-bold text-green-600">Futsal Booking</h1>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-4 items-center font-medium text-black">
          <Link :href="route('admin.dashboard')" class="hover:text-green-600 transition">Dashboard</Link>
          <Link :href="route('lapangan.index')" class="hover:text-green-600 transition">Lapangan</Link>
          <span @click="showLogoutModal = true" class="cursor-pointer text-red-600 hover:underline transition">Logout</span>
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
          <Link :href="route('admin.dashboard')" class="hover:text-green-600">Dashboard</Link>
          <span @click="showLogoutModal = true" class="cursor-pointer text-red-600 hover:underline transition mt-2">Logout</span>
        </nav>
      </div>
    </header>

    <!-- Content -->
    <main class="flex-1">
      <div class="overflow-x-auto shadow-lg rounded-lg bg-white border border-gray-200 mx-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between bg-gray-100 px-6 py-4 rounded-t-lg border-b border-gray-200 mb-2 space-y-2 md:space-y-0">
          <h2 class="text-2xl font-bold text-gray-800">Data Booking Masuk</h2>
          <div class="flex flex-col md:flex-row md:space-x-2 w-full md:w-auto space-y-2 md:space-y-0">
            <input type="text" v-model="globalSearch" placeholder="Cari semua kolom..."
                   class="px-3 py-2 border rounded focus:outline-none focus:ring w-full md:w-auto" />
            <input type="date" v-model="dateFrom" class="px-3 py-2 border rounded focus:outline-none focus:ring" placeholder="Dari tanggal" />
            <input type="date" v-model="dateTo" class="px-3 py-2 border rounded focus:outline-none focus:ring" placeholder="Sampai tanggal" />
          </div>
        </div>

        <!-- Total Data -->
        <div class="px-6 py-2 text-gray-700 font-medium">
          Total Booking: <span class="font-bold">{{ filteredBookings.length }}</span>
        </div>

        <!-- Table -->
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th @click="sortBy('user')" class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer">
                User <span v-if="sortColumn==='user'">{{ sortDir==='asc'?'↑':'↓' }}</span>
              </th>
              <th @click="sortBy('lapangan')" class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer">
                Lapangan <span v-if="sortColumn==='lapangan'">{{ sortDir==='asc'?'↑':'↓' }}</span>
              </th>
              <th @click="sortBy('tanggal')" class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer">
                Tanggal <span v-if="sortColumn==='tanggal'">{{ sortDir==='asc'?'↑':'↓' }}</span>
              </th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jam</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Bukti</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="booking in paginatedBookings" :key="booking.id" class="hover:bg-gray-50 transition">
              <td class="px-4 py-2 text-gray-800">{{ booking.user.name }}</td>
              <td class="px-4 py-2 text-gray-800">{{ booking.lapangan.nama }}</td>
              <td class="px-4 py-2 text-gray-800">{{ booking.tanggal }}</td>
              <td class="px-4 py-2 text-gray-800">{{ booking.jam_mulai }} - {{ booking.jam_selesai }}</td>
              <td class="px-4 py-2">
                <template v-if="booking.bukti_pembayaran">
                  <button @click="openBuktiModal(booking)" class="text-blue-600 hover:underline font-medium">Lihat Bukti</button>
                </template>
                <span v-else class="text-gray-400">-</span>
              </td>
              <td class="px-4 py-2">
                <span
                  :class="{
                    'bg-yellow-100 text-yellow-800': booking.status==='menunggu_verifikasi',
                    'bg-green-100 text-green-800': booking.status==='diterima',
                    'bg-red-100 text-red-800': booking.status==='ditolak'
                  }"
                  class="px-2 py-1 rounded-full text-xs font-semibold"
                >
                  {{ booking.status.replace('_',' ') }}
                </span>
              </td>
              <td class="px-4 py-2 flex justify-center space-x-2">
                <template v-if="booking.status==='menunggu_verifikasi'">
                  <button @click="verifikasi(booking.id,'diterima')" :disabled="loading" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm shadow-sm transition">Terima</button>
                  <button @click="verifikasi(booking.id,'ditolak')" :disabled="loading" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm shadow-sm transition">Tolak</button>
                </template>
                <span v-else class="text-gray-400">-</span>
              </td>
            </tr>
            <tr v-if="filteredBookings.length===0">
              <td colspan="7" class="text-center py-4 text-gray-500">Data tidak ditemukan</td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4 px-6 py-2">
          <button @click="currentPage--" :disabled="currentPage===1" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50">Prev</button>
          <div class="space-x-2">
            <button v-for="page in totalPages" :key="page" @click="currentPage=page"
              :class="{'bg-blue-500 text-white': currentPage===page,'bg-gray-200': currentPage!==page}"
              class="px-3 py-1 rounded hover:bg-blue-400 transition">
              {{ page }}
            </button>
          </div>
          <button @click="currentPage++" :disabled="currentPage===totalPages" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50">Next</button>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-200 shadow-inner py-3 mt-6">
      <div class="container mx-auto text-center text-black text-md">
        &copy; {{ new Date().getFullYear() }} JC Developer. All rights reserved.
      </div>
    </footer>

    <!-- Logout Modal -->
    <div v-if="showLogoutModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-80">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin logout?</p>
        <div class="flex justify-end space-x-2">
          <button @click="showLogoutModal=false" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Batal</button>
          <button @click="logout" class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition">Logout</button>
        </div>
      </div>
    </div>

    <!-- Bukti Modal -->
    <div v-if="showBuktiModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white rounded-lg shadow-lg p-4 max-w-lg w-full relative">
        <button @click="showBuktiModal=false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">&times;</button>
        <img :src="`/storage/${selectedBooking.bukti_pembayaran}`" alt="Bukti Pembayaran" class="w-full h-auto rounded"/>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({ bookings: Array })

const loading = ref(false)
const menuOpen = ref(false)
const showLogoutModal = ref(false)
const showBuktiModal = ref(false)
const selectedBooking = ref(null)

const globalSearch = ref('')
const dateFrom = ref('')
const dateTo = ref('')
const sortColumn = ref('')
const sortDir = ref('asc')

// Sort function
function sortBy(column){
  if(sortColumn.value===column) sortDir.value = sortDir.value==='asc'?'desc':'asc'
  else { sortColumn.value = column; sortDir.value='asc' }
}

// Filtered + sorted
const filteredBookings = computed(()=>{
  let data = props.bookings.filter(b=>{
    const term = globalSearch.value.toLowerCase()
    const matchSearch = b.user.name.toLowerCase().includes(term) ||
                        b.lapangan.nama.toLowerCase().includes(term) ||
                        b.tanggal.toLowerCase().includes(term) ||
                        b.jam_mulai.toLowerCase().includes(term) ||
                        b.jam_selesai.toLowerCase().includes(term) ||
                        b.status.toLowerCase().includes(term)
    const matchDateFrom = dateFrom.value ? b.tanggal >= dateFrom.value : true
    const matchDateTo = dateTo.value ? b.tanggal <= dateTo.value : true
    return matchSearch && matchDateFrom && matchDateTo
  })

  if(sortColumn.value){
    data.sort((a,b)=>{
      let valA = sortColumn.value==='user'? a.user.name :
                 sortColumn.value==='lapangan'? a.lapangan.nama :
                 a[sortColumn.value]
      let valB = sortColumn.value==='user'? b.user.name :
                 sortColumn.value==='lapangan'? b.lapangan.nama :
                 b[sortColumn.value]
      if(typeof valA==='string') valA = valA.toLowerCase()
      if(typeof valB==='string') valB = valB.toLowerCase()
      return valA<valB ? (sortDir.value==='asc'? -1:1) : valA>valB ? (sortDir.value==='asc'?1:-1) :0
    })
  }
  return data
})

// Pagination
const currentPage = ref(1)
const perPage = ref(5)
const totalPages = computed(()=> Math.ceil(filteredBookings.value.length/perPage.value))
const paginatedBookings = computed(()=>{
  const start = (currentPage.value-1)*perPage.value
  return filteredBookings.value.slice(start,start+perPage.value)
})
watch([globalSearch,dateFrom,dateTo],()=> currentPage.value=1)

// Modal functions
function openBuktiModal(booking){
  selectedBooking.value = booking
  showBuktiModal.value = true
}

// Booking verification
async function verifikasi(id,status){
  loading.value=true
  try{
    await router.post(`/admin/bookings/${id}/verifikasi`,{status})
  }catch(err){
    console.error(err)
  }finally{
    loading.value=false
  }
}

// Logout
function logout(){
  showLogoutModal.value=false
  router.post('/logout')
}
</script>
