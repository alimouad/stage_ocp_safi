<script setup>
import { ref, onMounted } from 'vue'
import Sidebar from '@/components/SideBar.vue' 
import Navbar from '@/components/NavBar.vue'    
import api from '@/services/api' 
import { FileSpreadsheet, Activity, UploadCloud, Plus, Calendar, Disc, AlertTriangle, X } from 'lucide-vue-next'

// Reactive state array structure
const measurements = ref([])
const emissionPoints = ref([])
const substances = ref([])
const loading = ref(true)
const errorMessage = ref('')
const isModalOpen = ref(false)
const isImportOpen = ref(false)
const csvFile = ref(null)

// Form states definitions
const newMeasurement = ref({
  emission_point_id: '',
  substance_id: '',
  value: '',
  measured_at: new Date().toISOString().slice(0, 16) // Current timestamp localized format
})

const formatMeasurement = (measurement) => {
  const substance = measurement.substance || {}
  const emissionPoint = measurement.emission_point || {}
  const threshold = substance.threshold
  const value = Number(measurement.value)
  const isCritical = measurement.alert || (threshold && value > Number(threshold.max_value))

  return {
    id: measurement.id,
    stack: emissionPoint.code || 'N/A',
    stackName: emissionPoint.name || 'Unknown checkpoint',
    gas: substance.code || 'N/A',
    value: Number.isNaN(value) ? measurement.value : value.toFixed(2),
    unit: substance.unit || 'mg/m3',
    time: new Date(measurement.measured_at).toLocaleString(),
    status: isCritical ? 'Critical' : 'Normal'
  }
}

const resetMeasurementForm = () => {
  newMeasurement.value = {
    emission_point_id: '',
    substance_id: '',
    value: '',
    measured_at: new Date().toISOString().slice(0, 16)
  }
}

// --- Jib l-Data completely mn database melli la page kat-lancia ---
const fetchData = async () => {
  try {
    loading.value = true
    errorMessage.value = ''

    const [pointsRes, substancesRes, measurementsRes] = await Promise.all([
      api.get('/emission-points'),
      api.get('/substances'),
      api.get('/measurements')
    ])

    emissionPoints.value = pointsRes.data
    substances.value = substancesRes.data
    measurements.value = measurementsRes.data.map(formatMeasurement)
  } catch (error) {
    console.error('Failed syncing analytics layer:', error)
    errorMessage.value = error.response?.data?.message || 'Unable to load measurement data.'
  } finally {
    loading.value = false
  }
}

onMounted(() => { fetchData() })

// --- Logique 1: Submit single realtime measurement endpoint ---
const handleSingleSubmit = async () => {
  if (!newMeasurement.value.emission_point_id || !newMeasurement.value.substance_id || !newMeasurement.value.value) {
    alert('Please complete all metrics inputs.')
    return
  }
  try {
    await api.post('/measurements', {
      emission_point_id: newMeasurement.value.emission_point_id,
      substance_id: newMeasurement.value.substance_id,
      value: parseFloat(newMeasurement.value.value),
      measured_at: newMeasurement.value.measured_at
    })
    alert('Measurement stream processed into database successfully!')
    isModalOpen.value = false
    resetMeasurementForm()
    await fetchData()
  } catch (err) {
    alert(err.response?.data?.message || 'Error processing data stream block.')
  }
}

// --- Logique 2: Upload CSV bulk file module transformation ---
const handleFileChange = (e) => {
  csvFile.value = e.target.files[0]
}

const handleCsvSubmit = async () => {
  if (!csvFile.value) {
    alert('Please drop a valid .csv template matrix.')
    return
  }
  const formData = new FormData()
  formData.append('file', csvFile.value)

  try {
    await api.post('/measurements/import-csv', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    alert('CSV Bulk ingestion parsed completely into database!')
    isImportOpen.value = false
    csvFile.value = null
    await fetchData()
  } catch (err) {
    alert(err.response?.data?.message || 'CSV Ingestion aborted. Validation mismatch constraints.')
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
          <div class="inline-flex items-center gap-2 bg-[#1A1A1A] text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">
            <span class="w-1.5 h-1.5 rounded-full bg-[#E2FF54]"></span> Data Ingestion Flow
          </div>
          <h2 class="text-4xl font-black tracking-tight mt-2.5">Air Quality Measurements</h2>
        </div>

        <div class="flex items-center gap-3">
          <button @click="isImportOpen = true" class="flex items-center gap-2 px-5 py-3 border border-gray-200 bg-white text-[#1A1A1A] text-xs font-bold rounded-full hover:bg-gray-50 transition-all shadow-sm">
            <FileSpreadsheet class="w-4 h-4" /> <span>Bulk Import CSV</span>
          </button>
          <button @click="isModalOpen = true" class="flex items-center gap-2 px-5 py-3 bg-[#1A1A1A] text-white text-xs font-bold rounded-full hover:bg-black transition-all shadow-sm">
            <Plus class="w-4 h-4" /> <span>Insert Log Entry</span>
          </button>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-8 flex-1 items-stretch">
        
        <div class="col-span-2 flex flex-col bg-transparent">
          <div class="flex justify-between items-center mb-4">
            <h4 class="text-xl font-black tracking-tight">Chronological Telemetry Stream</h4>
            <span class="text-xs text-gray-400 font-mono font-bold uppercase">Real-time DB Influx logs</span>
          </div>

          <div class="space-y-4 max-h-[520px] overflow-y-auto pr-2">
            <div v-if="loading" class="bg-white p-8 rounded-[1.8rem] border border-white/80 text-sm font-bold text-gray-400">
              Loading telemetry stream...
            </div>

            <div v-else-if="errorMessage" class="bg-red-50 p-8 rounded-[1.8rem] border border-red-100 text-sm font-bold text-red-600">
              {{ errorMessage }}
            </div>

            <div v-else-if="!measurements.length" class="bg-white p-8 rounded-[1.8rem] border border-white/80 text-sm font-bold text-gray-400">
              No measurements recorded yet.
            </div>

            <div v-for="log in measurements" :key="log.id" 
                 class="group bg-white p-5 rounded-[1.8rem] flex items-center justify-between border border-white/80 transition-all duration-300 hover:shadow-[0_10px_30px_rgba(0,0,0,0.03)] hover:border-gray-200">
              <div class="flex items-center gap-5">
                <div class="w-11 h-11 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center transition-colors group-hover:bg-black group-hover:text-white">
                  <Activity class="w-4 h-4" />
                </div>
                <div>
                  <h5 class="text-base font-black text-gray-900 tracking-tight">{{ log.stack }} — <span class="text-gray-400">{{ log.gas }}</span></h5>
                  <p class="text-[11px] text-gray-400 font-medium mt-0.5">{{ log.stackName }}</p>
                  <p class="text-xs text-gray-400 font-medium mt-0.5 flex items-center gap-1"><Calendar class="w-3.5 h-3.5" /> {{ log.time }}</p>
                </div>
              </div>

              <div class="flex items-center gap-6">
                <div class="text-right">
                  <p class="text-lg font-mono font-black text-gray-900">{{ log.value }} <span class="text-xs text-gray-400">{{ log.unit }}</span></p>
                </div>
                <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-xl flex items-center gap-1.5"
                      :class="log.status === 'Normal' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100 animate-pulse'">
                  <Disc class="w-3 h-3" :class="log.status === 'Critical' ? 'fill-red-600' : 'fill-emerald-700'" />
                  {{ log.status }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="flex flex-col gap-6">
          <div class="bg-white p-6 rounded-[2.5rem] border border-white/80 shadow-[0_10px_40px_rgba(0,0,0,0.01)] flex flex-col justify-between h-full min-h-[300px]">
            <div>
              <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Ingestion Guideline Rules</h4>
              <p class="text-xs text-gray-500 font-medium leading-relaxed">
                Every measurement submitted directly flags an automated verification protocol. If parameters bypass localized Moroccan environmental legislation constraints, system creates immediate system alerts routing straight to HSE supervisors.
              </p>
            </div>
            <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-200/50 flex gap-3 text-xs text-amber-900 font-medium mt-4">
              <AlertTriangle class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
              <p>Ensure laboratory files exactly mimic the required CSV layout model before structural ingestion block processing execution.</p>
            </div>
          </div>
        </div>

      </div>

      <div v-if="isModalOpen" class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 relative shadow-2xl border text-[#1A1A1A]">
          <button @click="isModalOpen = false" class="absolute top-6 right-6 text-gray-400 hover:text-black"><X class="w-5 h-5" /></button>
          <div class="mb-6"><h3 class="text-2xl font-black tracking-tight">Log Telemetry Entry</h3><p class="text-xs text-gray-400 mt-1">Inject real-time single stack monitoring variables.</p></div>
          
          <form @submit.prevent="handleSingleSubmit" class="space-y-4">
            <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Select Active Stack Checkpoint *</label>
              <select v-model="newMeasurement.emission_point_id" class="w-full px-4 py-3 bg-[#F4F3EF] rounded-xl text-xs font-bold border-0 focus:ring-1 focus:ring-black">
                <option value="" disabled>Choose target checkpoint</option>
                <option v-for="p in emissionPoints" :key="p.id" :value="p.id">{{ p.code }} — {{ p.name }}</option>
              </select>
            </div>

            <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Target Chemical Compound Substance *</label>
              <select v-model="newMeasurement.substance_id" class="w-full px-4 py-3 bg-[#F4F3EF] rounded-xl text-xs font-bold border-0 focus:ring-1 focus:ring-black">
                <option value="" disabled>Choose chemical parameter</option>
                <option v-for="s in substances" :key="s.id" :value="s.id">{{ s.code }} ({{ s.name }})</option>
              </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Value Concentration *</label>
                <input v-model="newMeasurement.value" type="number" step="0.01" placeholder="42.5" class="w-full px-4 py-3 bg-[#F4F3EF] rounded-xl text-xs font-mono font-bold border-0 focus:ring-1 focus:ring-black" />
              </div>
              <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Timestamp Logged *</label>
                <input v-model="newMeasurement.measured_at" type="datetime-local" class="w-full px-4 py-3 bg-[#F4F3EF] rounded-xl text-xs font-medium border-0 focus:ring-1 focus:ring-black" />
              </div>
            </div>

            <div class="pt-4 flex gap-3 justify-end"><button type="button" @click="isModalOpen = false" class="px-5 py-3 border text-xs font-bold rounded-full">Cancel</button><button type="submit" class="px-6 py-3 bg-[#1A1A1A] text-white text-xs font-bold rounded-full">Process entry</button></div>
          </form>
        </div>
      </div>

      <div v-if="isImportOpen" class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 relative shadow-2xl border text-[#1A1A1A]">
          <button @click="isImportOpen = false" class="absolute top-6 right-6 text-gray-400 hover:text-black"><X class="w-5 h-5" /></button>
          <div class="mb-6"><h3 class="text-2xl font-black tracking-tight">Bulk Laboratory Ingestion</h3><p class="text-xs text-gray-400 mt-1">Upload verified structured text CSV files for parameter batch entry.</p></div>
          
          <form @submit.prevent="handleCsvSubmit" class="space-y-6">
            <div class="border-2 border-dashed border-gray-200 hover:border-black rounded-[1.8rem] p-8 text-center cursor-pointer transition-colors relative group">
              <input type="file" accept=".csv" @change="handleFileChange" class="absolute inset-0 opacity-0 cursor-pointer" />
              <UploadCloud class="w-10 h-10 text-gray-300 mx-auto group-hover:text-black transition-colors mb-2" />
              <p class="text-xs font-bold text-gray-900 select-none">{{ csvFile ? csvFile.name : 'Click or drop .csv laboratory log file structure here' }}</p>
              <p class="text-[10px] text-gray-400 mt-1 select-none font-medium">Standard structural matrix sequencing format only.</p>
            </div>

            <div class="flex gap-3 justify-end"><button type="button" @click="isImportOpen = false" class="px-5 py-3 border text-xs font-bold rounded-full">Cancel</button><button type="submit" class="px-6 py-3 bg-[#1A1A1A] text-white text-xs font-bold rounded-full">Inject File Registry</button></div>
          </form>
        </div>
      </div>

    </div>
  </div>
</template>
