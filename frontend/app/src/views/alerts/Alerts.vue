<script setup>
import { computed, onMounted, ref } from 'vue'
import Sidebar from '@/components/SideBar.vue'
import Navbar from '@/components/NavBar.vue'
import api from '@/services/api'
import { useAuthStore } from '@/stores/auth'
import {
  AlertTriangle,
  CheckCircle2,
  Clock3,
  Filter,
  Loader2,
  MapPin,
  RadioTower,
  RotateCw,
  ShieldAlert,
} from 'lucide-vue-next'



const authStore = useAuthStore()

const alerts = ref([])
const loading = ref(true)
const updatingId = ref(null)
const error = ref('')
const activeStatus = ref('ALL')

const statusOptions = ['ALL', 'PENDING', 'ONGOING', 'RESOLVED']

const normalizeAlert = (alert) => {
  const measurement = alert.measurement || {}
  const emissionPoint = measurement.emission_point || {}
  const substance = measurement.substance || {}
  const threshold = substance.threshold || {}
  const value = Number(measurement.value)
  const maxValue = Number(threshold.max_value)
  const overLimit = Number.isFinite(value) && Number.isFinite(maxValue) ? value - maxValue : null

  return {
    id: alert.id,
    message: alert.message,
    status: alert.status,
    stack: emissionPoint.code || 'N/A',
    stackName: emissionPoint.name || 'Unknown emission point',
    zone: emissionPoint.factory_zone || 'Unassigned zone',
    gas: substance.code || 'N/A',
    gasName: substance.name || 'Unknown substance',
    value: Number.isFinite(value) ? value.toFixed(2) : measurement.value || 'N/A',
    threshold: Number.isFinite(maxValue) ? maxValue.toFixed(2) : 'N/A',
    unit: substance.unit || 'mg/m3',
    exceededBy: overLimit !== null ? Math.max(overLimit, 0).toFixed(2) : 'N/A',
    measuredAt: measurement.measured_at ? new Date(measurement.measured_at).toLocaleString() : 'N/A',
    createdAt: alert.created_at ? new Date(alert.created_at).toLocaleString() : 'N/A',
  }
}

const fetchAlerts = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get('/alerts')
    alerts.value = response.data.map(normalizeAlert)
  } catch (err) {
    error.value = err.response?.data?.message || 'Unable to load alerts.'
  } finally {
    loading.value = false
  }
}

const filteredAlerts = computed(() => {
  if (activeStatus.value === 'ALL') {
    return alerts.value
  }
  return alerts.value.filter((alert) => alert.status === activeStatus.value)
})

const alertStats = computed(() => ({
  total: alerts.value.length,
  pending: alerts.value.filter((alert) => alert.status === 'PENDING').length,
  ongoing: alerts.value.filter((alert) => alert.status === 'ONGOING').length,
  resolved: alerts.value.filter((alert) => alert.status === 'RESOLVED').length,
}))

const canManageAlerts = computed(() => authStore.isHseAdmin)

const statusClass = (status) => {
  if (status === 'RESOLVED') return 'bg-emerald-50 text-emerald-700 border-emerald-100'
  if (status === 'ONGOING') return 'bg-amber-50 text-amber-700 border-amber-100'
  return 'bg-red-50 text-red-700 border-red-100'
}

const updateAlertStatus = async (alert, status) => {
  updatingId.value = alert.id
  error.value = ''

  try {
    await api.patch(`/alerts/${alert.id}/status`, { status })
    alert.status = status
  } catch (err) {
    error.value = err.response?.data?.message || 'Unable to update alert status.'
  } finally {
    updatingId.value = null
  }
}

onMounted(fetchAlerts)
</script>

<template>
  <div class="min-h-screen bg-[#F4F3EF] p-8 flex gap-8 font-sans antialiased text-[#1A1A1A]">
    
    <Sidebar class="shrink-0" />

    <div class="flex-1 flex flex-col min-w-0">
      <Navbar />

      <section class="space-y-8 mt-2 flex-1 flex flex-col justify-stretch">
        
        <header class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
          <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-red-100 bg-red-50 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-red-700">
              <span class="h-1.5 w-1.5 rounded-full bg-red-500 animate-pulse"></span>
              Alert Control Center
            </div>
            <h1 class="mt-3 text-4xl font-black tracking-tight">Environmental Alerts</h1>
            <p class="mt-1 text-sm font-semibold text-gray-400">Track real-time regulatory threshold breaches and compliance states.</p>
          </div>

          <button
            type="button"
            class="flex items-center justify-center gap-2 rounded-full bg-white px-5 py-3 text-xs font-black shadow-[0_10px_30px_rgba(0,0,0,0.02)] border border-gray-100 transition duration-300 hover:bg-[#1A1A1A] hover:text-white"
            @click="fetchAlerts"
          >
            <RotateCw class="h-4 w-4" :class="{ 'animate-spin': loading }" />
            <span>Refresh Stream</span>
          </button>
        </header>

        <div class="grid gap-5 grid-cols-4">
          <div class="group rounded-[2.2rem] bg-[#1A1A1A] p-6 text-white shadow-md transition-all duration-300 hover:-translate-y-1">
            <ShieldAlert class="h-5 w-5 text-[#E2FF54]" />
            <p class="mt-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Total Alerts</p>
            <p class="mt-1 text-3xl font-black tracking-tight">{{ alertStats.total }}</p>
          </div>
          <div class="group rounded-[2.2rem] bg-white p-6 border border-white shadow-[0_10px_40px_rgba(0,0,0,0.01)] transition-all duration-300 hover:-translate-y-1">
            <AlertTriangle class="h-5 w-5 text-red-500" />
            <p class="mt-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Pending</p>
            <p class="mt-1 text-3xl font-black tracking-tight text-red-500">{{ alertStats.pending }}</p>
          </div>
          <div class="group rounded-[2.2rem] bg-white p-6 border border-white shadow-[0_10px_40px_rgba(0,0,0,0.01)] transition-all duration-300 hover:-translate-y-1">
            <Clock3 class="h-5 w-5 text-amber-500" />
            <p class="mt-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Ongoing</p>
            <p class="mt-1 text-3xl font-black tracking-tight text-amber-500">{{ alertStats.ongoing }}</p>
          </div>
          <div class="group rounded-[2.2rem] bg-white p-6 border border-white shadow-[0_10px_40px_rgba(0,0,0,0.01)] transition-all duration-300 hover:-translate-y-1">
            <CheckCircle2 class="h-5 w-5 text-emerald-500" />
            <p class="mt-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Resolved</p>
            <p class="mt-1 text-3xl font-black tracking-tight text-emerald-500">{{ alertStats.resolved }}</p>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 rounded-3xl bg-white p-2 shadow-[0_4px_30px_rgba(0,0,0,0.01)] border border-white/60">
          <div class="flex items-center gap-2 px-3 text-xs font-black uppercase tracking-widest text-gray-400">
            <Filter class="h-4 w-4" />
            Status Layer
          </div>
          <button
            v-for="status in statusOptions"
            :key="status"
            type="button"
            class="rounded-2xl px-4 py-2 text-xs font-black transition-colors duration-200"
            :class="activeStatus === status ? 'bg-[#1A1A1A] text-white shadow-sm' : 'text-gray-500 hover:bg-[#F4F3EF] hover:text-black'"
            @click="activeStatus = status"
          >
            {{ status }}
          </button>
        </div>

        <p v-if="error" class="rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-sm font-bold text-red-700">{{ error }}</p>

        <div class="flex-1 flex flex-col justify-stretch">
          <div v-if="loading" class="rounded-[2.5rem] bg-white p-12 text-sm font-black text-gray-400 border border-white text-center shadow-sm">
            <Loader2 class="mr-2 inline h-4 w-4 animate-spin text-black" />
            Loading real-time postgis alert registry...
          </div>

          <div v-else-if="!filteredAlerts.length" class="rounded-[2.5rem] bg-white p-12 text-sm font-black text-gray-400 border border-white text-center shadow-sm italic">
            No environmental breaches registered under this scope parameters.
          </div>

          <div v-else class="space-y-4 max-h-[420px] overflow-y-auto pr-1">
            <article
              v-for="alert in filteredAlerts"
              :key="alert.id"
              class="group rounded-[1.8rem] bg-white p-5 shadow-[0_4px_30px_rgba(0,0,0,0.01)] border border-white/80 transition-all duration-300 hover:shadow-[0_10px_30px_rgba(0,0,0,0.02)] hover:border-gray-200"
            >
              <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div class="min-w-0 flex-1">
                  <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-xl border px-3 py-1 text-[9px] font-black uppercase tracking-widest" :class="statusClass(alert.status)">
                      {{ alert.status }}
                    </span>
                    <span class="rounded-xl bg-[#F4F3EF] border border-gray-200/40 px-3 py-1 font-mono text-[10px] font-bold text-gray-600">
                      {{ alert.gas }} Level: {{ alert.value }} / Max Permissible: {{ alert.threshold }} {{ alert.unit }}
                    </span>
                  </div>

                  <h2 class="mt-3 text-lg font-black tracking-tight text-gray-900">{{ alert.stack }} — {{ alert.gasName }}</h2>
                  <p class="mt-1 text-sm font-semibold text-gray-500 leading-relaxed">{{ alert.message }}</p>

                  <div class="mt-4 flex flex-wrap gap-5 text-xs font-bold text-gray-400">
                    <span class="flex items-center gap-1.5"><MapPin class="h-3.5 w-3.5 text-gray-400" /> {{ alert.stackName }} · <b class="text-gray-500 uppercase text-[10px]">{{ alert.zone }}</b></span>
                    <span class="flex items-center gap-1.5"><RadioTower class="h-3.5 w-3.5 text-gray-400" /> Logs Transmitted {{ alert.measuredAt }}</span>
                    <span class="text-red-500/80 font-bold">Exceeded limit constraints by {{ alert.exceededBy }} {{ alert.unit }}</span>
                  </div>
                </div>

                <div v-if="canManageAlerts" class="flex shrink-0 flex-wrap gap-2 items-center xl:justify-end">
                  <button
                    type="button"
                    :disabled="updatingId === alert.id || alert.status === 'ONGOING'"
                    class="rounded-full border border-amber-200 bg-amber-50/50 px-4 py-2 text-xs font-black text-amber-700 transition duration-200 hover:bg-amber-100 disabled:cursor-not-allowed disabled:opacity-40 shadow-sm"
                    @click="updateAlertStatus(alert, 'ONGOING')"
                  >
                    Mark Ongoing
                  </button>
                  <button
                    type="button"
                    :disabled="updatingId === alert.id || alert.status === 'RESOLVED'"
                    class="rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-xs font-black text-emerald-700 transition duration-200 hover:bg-emerald-100 disabled:cursor-not-allowed disabled:opacity-40 shadow-sm"
                    @click="updateAlertStatus(alert, 'RESOLVED')"
                  >
                    Resolve Breach
                  </button>
                </div>
              </div>
            </article>
          </div>
        </div>

      </section>
    </div>
  </div>
</template>