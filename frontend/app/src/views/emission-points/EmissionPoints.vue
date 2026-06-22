<script setup>
import { ref, onMounted } from 'vue'
import Sidebar from '@/components/SideBar.vue' 
import Navbar from '@/components/NavBar.vue'    
import api from '@/services/api' 
import { MapPin, Sliders, Plus, Target, Compass, X } from 'lucide-vue-next'

const emissionPoints = ref([]) 
const selectedPoint = ref(null)
const isModalOpen = ref(false)
const loading = ref(true)

const newPoint = ref({
  code: '',
  name: '',
  description: '',
  factory_zone: 'ZONE_A_SULFURIC',
  latitude: '',
  longitude: '',
  status: 'Active'
})

const fetchEmissionPoints = async () => {
  try {
    loading.value = true
    const response = await api.get('/emission-points')
    emissionPoints.value = response.data
  } catch (error) {
    console.error('Error Syncing PostGIS:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => { fetchEmissionPoints() })

const selectPoint = (point) => { selectedPoint.value = point }

const handleSubmit = async () => {
  if (!newPoint.value.code || !newPoint.value.name || !newPoint.value.latitude || !newPoint.value.longitude) {
    alert('Missing spatial fields required.')
    return
  }
  try {
    await api.post('/emission-points', {
      code: newPoint.value.code.toUpperCase(),
      name: newPoint.value.name,
      description: newPoint.value.description,
      factory_zone: newPoint.value.factory_zone,
      latitude: parseFloat(newPoint.value.latitude),
      longitude: parseFloat(newPoint.value.longitude)
    })
    alert('Saved successfully to the db!')
    await fetchEmissionPoints()
    isModalOpen.value = false
    newPoint.value = { code: '', name: '', description: '', factory_zone: 'ZONE_A_SULFURIC', latitude: '', longitude: '', status: 'Active' }
  } catch (error) {
    alert('Insertion rejected by backend rules.')
  }
}
</script>

<template>
  <div class="min-h-screen bg-[#F4F3EF] p-8 flex gap-8 font-sans antialiased text-[#1A1A1A]">
    <Sidebar class="shrink-0" />
    <div class="flex-1 flex flex-col min-w-0">
      <Navbar />

      <div class="flex justify-between items-end mb-8 mt-2">
        <div>
          <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full border border-emerald-200/60">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> GIS Spatial Tracking
          </div>
          <h2 class="text-3xl font-black tracking-tight mt-2.5">Emission Points Mapping</h2>
        </div>
        <button @click="isModalOpen = true" class="flex items-center gap-2 px-5 py-3 bg-[#1A1A1A] text-white text-xs font-bold rounded-full hover:bg-black transition-all duration-300 shadow-sm">
          <Plus class="w-4 h-4" /> <span>Add New Point</span>
        </button>
      </div>

      <div class="grid grid-cols-3 gap-8 flex-1 items-stretch">
        <div class="col-span-2 bg-white rounded-[2.5rem] border border-white/80 p-4 flex flex-col relative overflow-hidden min-h-[500px]">
          <div v-if="loading" class="absolute inset-0 bg-white/70 backdrop-blur-sm z-30 flex items-center justify-center text-xs font-bold tracking-widest uppercase text-gray-400">Syncing database maps...</div>
          <div class="absolute top-8 left-8 z-10 bg-[#1A1A1A]/95 text-white backdrop-blur-md px-4 py-3 rounded-2xl shadow-xl flex items-center gap-4">
            <Compass class="w-4 h-4 text-[#E2FF54]" />
            <div class="text-xs"><p class="font-bold">Safi Complex Area</p><p class="text-[10px] text-gray-400">WGS84 EPSG:4326</p></div>
          </div>
          <div class="flex-1 w-full bg-[#EBEBE6] rounded-[2rem] relative flex items-center justify-center border overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#1a1a1a_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div v-for="point in emissionPoints" :key="point.id" :style="{ top: `${35 + (point.id * 8)}%`, left: `${25 + (point.id * 12)}%` }" class="absolute cursor-pointer group" @click="selectPoint(point)">
              <div class="w-5 h-5 rounded-full bg-[#1A1A1A] border-2 border-white shadow-md flex items-center justify-center"><Target class="w-2.5 h-2.5 text-white" /></div>
            </div>
            <span class="text-xs font-bold text-gray-400 tracking-widest uppercase">PostGIS Active Engine Mapping</span>
          </div>
        </div>

        <div class="flex flex-col gap-6">
          <div class="bg-white p-6 rounded-[2.5rem] border border-white/80"><p class="text-xs font-black uppercase text-gray-400 tracking-widest">Inventory Registry</p><p class="text-2xl font-black mt-1">{{ emissionPoints.length }} <span class="text-sm font-medium text-gray-400">Stacks Active</span></p></div>
          <div class="flex-1 bg-white rounded-[2.5rem] border border-white/80 p-6 flex flex-col justify-between">
            <div class="space-y-4 max-h-[350px] overflow-y-auto pr-1">
              <div v-for="point in emissionPoints" :key="point.id" @click="selectPoint(point)" :class="selectedPoint?.id === point.id ? 'border-black bg-[#F4F3EF]' : 'border-gray-100 bg-white'" class="p-4 rounded-2xl flex items-center justify-between border transition-all duration-300 cursor-pointer">
                <div class="flex items-center gap-4">
                  <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center border"><MapPin class="w-4 h-4" /></div>
                  <div><h5 class="text-sm font-bold">{{ point.code }}</h5><p class="text-[11px] text-gray-400 truncate max-w-[140px]">{{ point.name }}</p></div>
                </div>
                <div class="text-right"><span class="text-[9px] font-mono font-bold uppercase bg-emerald-50 text-emerald-700 border px-2 py-0.5 rounded-md">Active</span><p class="text-[10px] text-gray-400 font-mono mt-1">{{ parseFloat(point.latitude).toFixed(3) }}, {{ parseFloat(point.longitude).toFixed(3) }}</p></div>
              </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
              <div v-if="selectedPoint" class="text-xs bg-[#F4F3EF] p-4 rounded-2xl border"><p class="font-black text-sm text-gray-800">{{ selectedPoint.name }}</p><p class="text-[10px] text-gray-400 font-mono mt-1">Zone code parameter: {{ selectedPoint.factory_zone }}</p></div>
              <div v-else class="text-center py-4 text-xs text-gray-400 italic">Select a checkpoint stack.</div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="isModalOpen" class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border">
          <button @click="isModalOpen = false" class="absolute top-6 right-6 text-gray-400 hover:text-black"><X class="w-5 h-5" /></button>
          <div class="mb-6"><h3 class="text-2xl font-black tracking-tight">Register New Stack</h3><p class="text-xs text-gray-400 mt-1">Insert exact geographic data attributes.</p></div>
          <form @submit.prevent="handleSubmit" class="space-y-4">
            <div class="grid grid-cols-3 gap-4">
              <div class="col-span-1"><label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Code</label><input v-model="newPoint.code" type="text" placeholder="CHEM_04" class="w-full px-4 py-3 bg-[#F4F3EF] rounded-xl text-xs font-bold uppercase focus:ring-1 focus:ring-black border-0" /></div>
              <div class="col-span-2"><label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Stack Descriptor Name</label><input v-model="newPoint.name" type="text" placeholder="Filtering unit plant" class="w-full px-4 py-3 bg-[#F4F3EF] rounded-xl text-xs font-medium focus:ring-1 focus:ring-black border-0" /></div>
            </div>
            <div><label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Operational Area</label><select v-model="newPoint.factory_zone" class="w-full px-4 py-3 bg-[#F4F3EF] rounded-xl text-xs font-bold border-0"><option value="ZONE_A_SULFURIC">Zone A - Sulfuric</option><option value="ZONE_B_PHOSPHORIC">Zone B - Phosphoric</option></select></div>
            <div class="grid grid-cols-2 gap-4">
              <div><label class="block text-[10px] font-bold text-gray-400 tracking-wider mb-1">Y Coords (Latitude)</label><input v-model="newPoint.latitude" type="number" step="0.00001" placeholder="32.225" class="w-full px-4 py-3 bg-[#F4F3EF] rounded-xl text-xs font-mono focus:ring-1 focus:ring-black border-0" /></div>
              <div><label class="block text-[10px] font-bold text-gray-400 tracking-wider mb-1">X Coords (Longitude)</label><input v-model="newPoint.longitude" type="number" step="0.00001" placeholder="-9.24" class="w-full px-4 py-3 bg-[#F4F3EF] rounded-xl text-xs font-mono focus:ring-1 focus:ring-black border-0" /></div>
            </div>
            <div><label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Description</label><textarea v-model="newPoint.description" class="w-full px-4 py-3 bg-[#F4F3EF] rounded-xl text-xs h-16 resize-none border-0" placeholder="Meta logs..."></textarea></div>
            <div class="pt-4 flex gap-3 justify-end"><button type="button" @click="isModalOpen = false" class="px-5 py-3 border text-xs font-bold rounded-full">Cancel</button><button type="submit" class="px-6 py-3 bg-[#1A1A1A] text-white text-xs font-bold rounded-full">Save Location</button></div>
          </form>
        </div>
      </div>

    </div>
  </div>
</template>