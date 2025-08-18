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

    <!-- Konten -->
    <main class="flex-1 container mx-auto pt-28 pb-10 px-4">
      <div class="bg-white shadow-xl rounded-xl p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Riwayat Booking Saya</h1>

        <!-- Filters & Total -->
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <div class="flex-1 flex flex-col md:flex-row md:gap-2 gap-2">
            <input v-model="globalSearch" type="text" placeholder="Cari semua kolom..."
                   class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition"/>
            <select v-model="filterStatus"
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition">
              <option value="">Semua Status</option>
              <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
              <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
              <option value="diterima">Diterima</option>
              <option value="ditolak">Ditolak</option>
            </select>
            <input type="date" v-model="filterTanggal"
                   class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition"/>
          </div>
          <div class="text-gray-700 font-medium">Total Booking: <span class="font-bold">{{ filteredBookings.length }}</span></div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wide">
              <tr>
                <th @click="sortBy('lapangan')" class="px-6 py-3 cursor-pointer">Lapangan <span v-if="sortColumn==='lapangan'">{{ sortDir==='asc'?'↑':'↓' }}</span></th>
                <th @click="sortBy('tanggal')" class="px-6 py-3 cursor-pointer">Tanggal <span v-if="sortColumn==='tanggal'">{{ sortDir==='asc'?'↑':'↓' }}</span></th>
                <th @click="sortBy('jam')" class="px-6 py-3 cursor-pointer">Jam <span v-if="sortColumn==='jam'">{{ sortDir==='asc'?'↑':'↓' }}</span></th>
                <th @click="sortBy('status')" class="px-6 py-3 cursor-pointer">Status <span v-if="sortColumn==='status'">{{ sortDir==='asc'?'↑':'↓' }}</span></th>
                <th class="px-6 py-3">Bukti Pembayaran</th>
                <th class="px-6 py-3 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="booking in paginatedBookings" :key="booking.id" class="border-t hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                  <span class="text-blue-600 cursor-pointer hover:underline" @click="openLapanganModal(booking.lapangan)">
                    {{ booking.lapangan.nama }}
                  </span>
                </td>
                <td class="px-6 py-4">{{ booking.tanggal }}</td>
                <td class="px-6 py-4">{{ booking.jam_mulai }} - {{ booking.jam_selesai }}</td>
                <td class="px-6 py-4 font-semibold">
                  <span class="px-3 py-1 rounded-full text-xs capitalize" :class="statusClass(booking.status)">
                    {{ booking.status.replace('_',' ') }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div v-if="booking.status==='menunggu_pembayaran' && !booking.bukti_pembayaran">
                    <form @submit.prevent="uploadBukti(booking)" class="flex flex-col gap-2">
                      <input type="file" @change="onFileChange($event, booking)" required class="block text-sm text-gray-700"/>
                      <button v-if="files[booking.id]" type="submit"
                              class="bg-green-600 text-white py-1 px-4 rounded-lg hover:bg-green-700 text-sm font-medium">
                        Upload
                      </button>
                    </form>
                  </div>
                  <div v-else-if="booking.bukti_pembayaran">
                    <a :href="`/storage/${booking.bukti_pembayaran}`" target="_blank">
                      <img :src="`/storage/${booking.bukti_pembayaran}`" alt="Bukti Pembayaran"
                           class="w-24 h-auto rounded-lg shadow border hover:scale-105 transition"/>
                    </a>
                  </div>
                  <div v-else class="text-gray-500 italic">Belum tersedia</div>
                </td>
                <td class="px-6 py-4 text-center space-x-2">
                  <button @click="editBooking(booking)" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">Ubah</button>
                  <button @click="confirmDelete(booking)" class="bg-red-500 text-white px-3 py-1 rounded hover:text-red-600 text-sm">Hapus</button>

                </td>
              </tr>
              <tr v-if="filteredBookings.length===0">
                <td colspan="6" class="text-center py-4 text-gray-500">Data tidak ditemukan</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
          <button @click="currentPage--" :disabled="currentPage===1"
                  class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50">Prev</button>
          <div class="space-x-2">
            <button v-for="page in totalPages" :key="page" @click="currentPage=page"
                    :class="{'bg-blue-500 text-white':currentPage===page,'bg-gray-200':currentPage!==page}"
                    class="px-3 py-1 rounded hover:bg-blue-400 transition">{{ page }}</button>
          </div>
          <button @click="currentPage++" :disabled="currentPage===totalPages"
                  class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50">Next</button>
        </div>
      </div>
    </main>

    <!-- Modals -->
    <div v-if="showLapanganModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-xl p-6 w-96">
        <h2 class="text-xl font-semibold mb-4">{{ selectedLapangan.nama }}</h2>
        <div v-if="selectedLapangan.foto">
          <img :src="`/storage/${selectedLapangan.foto}`" alt="Foto Lapangan" class="w-full h-64 object-cover rounded-lg"/>
        </div>
        <div v-else class="text-gray-500 italic">Tidak ada gambar tersedia</div>
        <div class="flex justify-end mt-4">
          <button @click="showLapanganModal=false" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Tutup</button>
        </div>
      </div>
    </div>

    <div v-if="showLogoutModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-xl p-6 w-80">
        <h2 class="text-lg font-semibold mb-4">Konfirmasi Logout</h2>
        <p class="mb-6 text-gray-600">Apakah Anda yakin ingin logout?</p>
        <div class="flex justify-end space-x-2">
          <button @click="showLogoutModal=false" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Batal</button>
          <button @click="logout" class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">Logout</button>
        </div>
      </div>
    </div>

    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-xl p-6 w-80">
        <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
        <p class="mb-6 text-gray-600">Apakah Anda yakin ingin menghapus booking lapangan <strong>{{ bookingToDelete?.lapangan?.nama }}</strong> tanggal <strong>{{ bookingToDelete?.tanggal }}</strong>?</p>
        <div class="flex justify-end space-x-2">
          <button @click="showDeleteModal=false" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Batal</button>
          <button @click="performDelete" class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">Hapus</button>
        </div>
      </div>
    </div>


    <!-- Footer -->
    <footer class="bg-gray-200 shadow-inner py-3 mt-6">
      <div class="container mx-auto text-center text-gray-700 text-md">
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
import { Link, router, useForm } from '@inertiajs/vue3'

const props = defineProps({ bookings: Array })

const files = ref({})
const form = useForm({ bukti_pembayaran: null })
const showLogoutModal = ref(false)
const menuOpen = ref(false)
const showLapanganModal = ref(false)
const selectedLapangan = ref({})
const globalSearch = ref('')
const filterStatus = ref('')
const filterTanggal = ref('')
const sortColumn = ref('')
const sortDir = ref('asc')
const currentPage = ref(1)
const perPage = ref(5)
const showDeleteModal = ref(false)
const bookingToDelete = ref(null)

function confirmDelete(booking) {
  bookingToDelete.value = booking
  showDeleteModal.value = true
}

function performDelete() {
  if (!bookingToDelete.value) return
  router.delete(`/booking/${bookingToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false
      bookingToDelete.value = null
    }
  })
}


function onFileChange(e, booking){ files.value[booking.id]=e.target.files[0] }
function uploadBukti(booking){
  if(!files.value[booking.id]) return
  form.bukti_pembayaran=files.value[booking.id]
  form.post(`/booking/${booking.id}/upload-bukti`,{
    onSuccess:()=> {
      booking.bukti_pembayaran=files.value[booking.id].name
      booking.status='menunggu_verifikasi'
      files.value[booking.id]=null
      form.bukti_pembayaran=null
    },
    preserveScroll:true
  })
}

function logout(){ router.post('/logout',{ onSuccess:()=> router.visit('/login') }) }
function openLapanganModal(lapangan){ selectedLapangan.value=lapangan; showLapanganModal.value=true }
function sortBy(column){ sortColumn.value===column? sortDir.value=sortDir.value==='asc'?'desc':'asc':(sortColumn.value=column,sortDir.value='asc') }

const filteredBookings = computed(()=>{
  let data = props.bookings.filter(b=>{
    const term=globalSearch.value.toLowerCase()
    let matchSearch=b.lapangan.nama.toLowerCase().includes(term)||
                    b.tanggal.toLowerCase().includes(term)||
                    (b.jam_mulai+'-'+b.jam_selesai).toLowerCase().includes(term)||
                    b.status.toLowerCase().includes(term)
    let matchStatus = filterStatus.value? b.status===filterStatus.value : true
    let matchTanggal = filterTanggal.value? b.tanggal===filterTanggal.value : true
    return matchSearch && matchStatus && matchTanggal
  })
  if(sortColumn.value){
    data.sort((a,b)=>{
      let valA,valB
      if(sortColumn.value==='lapangan'){ valA=a.lapangan.nama; valB=b.lapangan.nama }
      else if(sortColumn.value==='jam'){ valA=a.jam_mulai+'-'+a.jam_selesai; valB=b.jam_mulai+'-'+b.jam_selesai }
      else{ valA=a[sortColumn.value]; valB=b[sortColumn.value] }
      if(typeof valA==='string') valA=valA.toLowerCase()
      if(typeof valB==='string') valB=valB.toLowerCase()
      return valA<valB? (sortDir.value==='asc'? -1:1) : valA>valB? (sortDir.value==='asc'?1:-1):0
    })
  }
  return data
})

const totalPages = computed(()=>Math.ceil(filteredBookings.value.length/perPage.value))
const paginatedBookings = computed(()=>{
  const start=(currentPage.value-1)*perPage.value
  return filteredBookings.value.slice(start,start+perPage.value)
})
watch([globalSearch, filterStatus, filterTanggal],()=>{ currentPage.value=1 })

function statusClass(status){
  return {
    'bg-yellow-100 text-yellow-800':status==='menunggu_pembayaran',
    'bg-green-100 text-green-800':status==='menunggu_verifikasi'||status==='diterima',
    'bg-red-100 text-red-800':status==='ditolak'
  }
}

// Aksi kolom
function editBooking(booking){ router.visit(`/booking/${booking.id}/edit`) }

function deleteBooking(id) {
  if (confirm('Apakah kamu yakin ingin menghapus booking ini?')) {
    router.delete(`/booking/${id}`)
  }
}

</script>

<style>
@keyframes fadeIn{ from{opacity:0;transform:translateY(-10px);} to{opacity:1;transform:translateY(0);} }
.animate-fadeIn{ animation:fadeIn 0.2s ease-out; }
</style>
