<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { 
  LayoutGrid, MapPin, Target, Route, Globe, 
  Search, Plus, Layout, Bell, Sliders, ChevronRight 
} from 'lucide-vue-next'
import VueApexCharts from 'vue3-apexcharts'

const authStore = useAuthStore()

// Mock data reflecting your Statistique model structure
const stats = ref({
  total_measurements: '154K',
  normal_measurements: '148K',
  ongoing_alerts: '12',
  resolved_alerts: '84',
  alerts_by_substance: [35, 45, 20] // SO2, NOx, PM10 percentages
})

// ApexCharts Configuration matching the design theme (Black & Volt Green)
const chartOptions = ref({
  chart: { type: 'bar', toolbar: { show: false } },
  colors: ['#1A1A1A', '#E2FF54'],
  plotOptions: { bar: { borderRadius: 6, columnWidth: '55%' } },
  dataLabels: { enabled: false },
  xaxis: { categories: ['Dec', 'Jan', 'Feb', 'Mar'], axisBorder: { show: false } },
  grid: { borderColor: '#EBEBE6', strokeDashArray: 4 }
})

const chartSeries = ref([
  { name: 'Normal', data: [40, 55, 45, 60] },
  { name: 'Alerts', data: [15, 20, 10, 25] }
])
</script>

<template>
  <div class="min-h-screen bg-[#F1F0EA] p-6 flex gap-6 font-sans antialiased text-[#1A1A1A]">
    
    <aside class="w-24 bg-white rounded-[2.5rem] flex flex-col items-center justify-between py-8 shadow-[0_10px_30px_rgba(0,0,0,0.02)]">
      <div class="flex flex-col gap-10 items-center">
        <div class="w-12 h-12 bg-[#1A1A1A] rounded-2xl flex items-center justify-center text-white">
          <LayoutGrid class="w-5 h-5" />
        </div>
        <nav class="flex flex-col gap-6">
          <button class="w-12 h-12 rounded-xl flex items-center justify-center text-gray-400 hover:bg-[#F1F0EA] hover:text-[#1A1A1A] transition-all">
            <MapPin class="w-5 h-5" />
          </button>
          <button class="w-12 h-12 rounded-xl flex items-center justify-center text-gray-400 hover:bg-[#F1F0EA] hover:text-[#1A1A1A] transition-all">
            <Target class="w-5 h-5" />
          </button>
          <button class="w-12 h-12 rounded-xl flex items-center justify-center text-gray-400 hover:bg-[#F1F0EA] hover:text-[#1A1A1A] transition-all">
            <Route class="w-5 h-5" />
          </button>
          <button class="w-12 h-12 rounded-xl flex items-center justify-center text-gray-400 hover:bg-[#F1F0EA] hover:text-[#1A1A1A] transition-all">
            <Globe class="w-5 h-5" />
          </button>
        </nav>
      </div>

      <div class="flex flex-col items-center gap-2">
        <div class="w-12 h-12 bg-[#E2FF54] rounded-full overflow-hidden border-2 border-white shadow-md">
          <img src="https://api.dicebear.com/7.x/bottts/svg?seed=Edward" alt="Avatar" />
        </div>
        <span class="text-[10px] font-bold text-gray-400 text-center max-w-[60px] truncate">
          {{ authStore.user?.name || 'Operator' }}
        </span>
      </div>
    </aside>

    <main class="flex-1 flex flex-col gap-6">
      
      <header class="flex items-center justify-between w-full">
        <div class="relative w-96">
          <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input 
            type="text" 
            placeholder="Search emission points, gas levels, logs..." 
            class="w-full pl-12 pr-4 py-3 bg-[#EAE9E2] border-0 rounded-full text-sm focus:ring-2 focus:ring-black placeholder-gray-500 font-medium"
          />
        </div>

        <div class="flex items-center gap-3">
          <button class="p-3 bg-[#EAE9E2] rounded-full hover:bg-[#1A1A1A] hover:text-white transition-colors">
            <Plus class="w-4 h-4" />
          </button>
          <button class="p-3 bg-[#EAE9E2] rounded-full hover:bg-[#1A1A1A] hover:text-white transition-colors">
            <Layout class="w-4 h-4" />
          </button>
          <button class="p-3 bg-[#EAE9E2] rounded-full relative hover:bg-[#1A1A1A] hover:text-white transition-colors">
            <Bell class="w-4 h-4" />
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>
        </div>
      </header>

      <div class="grid grid-cols-3 gap-6 flex-1">
        
        <div class="col-span-2 flex flex-col gap-6">
          
          <div>
            <h2 class="text-3xl font-black tracking-tight">Hello, OCP Team!</h2>
            <p class="text-sm text-gray-500 mt-1">Industrial Environment Air Quality Monitoring & Management Portal.</p>
          </div>

          <div class="grid grid-cols-4 gap-4">
            
            <div class="bg-[#1A1A1A] text-white p-5 rounded-[2rem] flex flex-col justify-between shadow-lg min-h-[140px]">
              <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center"><Globe class="w-4 h-4 text-gray-300" /></div>
              <div>
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Total Readings</p>
                <h3 class="text-2xl font-bold mt-1">{{ stats.total_measurements }}</h3>
              </div>
            </div>

            <div class="bg-[#1A1A1A] text-white p-5 rounded-[2rem] flex flex-col justify-between shadow-lg min-h-[140px]">
              <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center"><Target class="w-4 h-4 text-gray-300" /></div>
              <div>
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Normal Status</p>
                <h3 class="text-2xl font-bold mt-1 text-[#E2FF54]">{{ stats.normal_measurements }}</h3>
              </div>
            </div>

            <div class="bg-[#1A1A1A] text-white p-5 rounded-[2rem] flex flex-col justify-between shadow-lg min-h-[140px]">
              <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center"><Bell class="w-4 h-4 text-gray-300" /></div>
              <div>
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Active Alerts</p>
                <h3 class="text-2xl font-bold mt-1 text-red-400">{{ stats.ongoing_alerts }}</h3>
              </div>
            </div>

            <div class="bg-[#1A1A1A] text-white p-5 rounded-[2rem] flex flex-col justify-between shadow-lg min-h-[140px]">
              <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center"><Sliders class="w-4 h-4 text-gray-300" /></div>
              <div>
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Resolved Cases</p>
                <h3 class="text-2xl font-bold mt-1">{{ stats.resolved_alerts }}</h3>
              </div>
            </div>

          </div>

          <div class="flex-1 bg-transparent">
            <h4 class="text-lg font-bold mb-4 tracking-tight">Active Gas Emission Source Nodes</h4>
            <div class="space-y-3">
              
              <div v-for="i in 3" :key="i" class="bg-[#1A1A1A] text-white p-4 rounded-[1.5rem] flex items-center justify-between shadow-md hover:translate-y-[-2px] transition-transform cursor-pointer">
                <div class="flex items-center gap-4">
                  <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-sm font-bold">CH{{ i }}</div>
                  <div>
                    <h5 class="text-sm font-bold">Main Stack Chimney {{ i }}</h5>
                    <p class="text-xs text-gray-400">Safi Factory Zone A</p>
                  </div>
                </div>
                <div class="flex items-center gap-8">
                  <span class="text-xs font-mono bg-emerald-500/10 text-emerald-400 px-3 py-1 rounded-full border border-emerald-500/20">42.5 mg/m³</span>
                  <span class="text-xs text-gray-400 font-medium">SO2 Substance</span>
                  <ChevronRight class="w-4 h-4 text-gray-500" />
                </div>
              </div>

            </div>
          </div>

        </div>

        <div class="flex flex-col gap-6">
          
          <div class="bg-white p-6 rounded-[2.5rem] shadow-[0_15px_40px_rgba(0,0,0,0.02)] flex flex-col gap-4">
            <div class="flex justify-between items-center">
              <h4 class="text-sm font-bold tracking-tight">Compliance Index</h4>
              <span class="text-xs text-gray-400 font-bold">98.2% Optimal</span>
            </div>
            <div class="space-y-2">
              <div class="flex justify-between text-xs font-semibold text-gray-500"><span>Sulfur Dioxide ($SO_2$)</span><span class="text-emerald-500">Safe</span></div>
              <div class="w-full h-3 bg-[#F1F0EA] rounded-full overflow-hidden"><div class="w-[85%] h-full bg-[#1A1A1A] rounded-full"></div></div>
            </div>
            <div class="space-y-2">
              <div class="flex justify-between text-xs font-semibold text-gray-500"><span>Nitrogen Oxides ($NO_x$)</span><span class="text-amber-500">Warning</span></div>
              <div class="w-full h-3 bg-[#F1F0EA] rounded-full overflow-hidden"><div class="w-[60%] h-full bg-[#E2FF54] rounded-full"></div></div>
            </div>
          </div>

          <div class="bg-[#E2FF54] p-[3px] rounded-[2.5rem] shadow-md flex-1 flex">
            <div class="bg-white w-full h-full rounded-[2.4rem] p-6 flex flex-col justify-between">
              <div class="flex justify-between items-center mb-2">
                <h4 class="text-sm font-bold tracking-tight">Emission Insights</h4>
                <div class="flex gap-2 bg-[#F1F0EA] p-1 rounded-full text-[10px] font-bold">
                  <span class="bg-white px-2 py-0.5 rounded-full shadow-sm">Monthly</span>
                  <span class="px-2 py-0.5 text-gray-400">Weekly</span>
                </div>
              </div>
              
              <div class="flex-1 flex items-center justify-center">
                <VueApexCharts class="w-full" type="bar" height="180" :options="chartOptions" :series="chartSeries" />
              </div>
            </div>
          </div>

        </div>

      </div>

    </main>
  </div>
</template>