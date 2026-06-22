<script setup>
import { ref } from 'vue'
import Sidebar from '@/components/SideBar.vue' 
import Navbar from '@/components/NavBar.vue'    
import { ShieldCheck, FileText, AlertTriangle, Activity, Filter, Calendar, ArrowUpRight } from 'lucide-vue-next'
import VueApexCharts from 'vue3-apexcharts'

const auditStats = ref({
  compliance_rate: '98.4%',
  total_checked: '1.2M',
  critical_violations: '3',
  pending_reviews: '14'
})

const chartOptions = ref({
  chart: { type: 'area', toolbar: { show: false }, fontFamily: 'Satoshi, Inter, sans-serif' },
  colors: ['#1A1A1A'],
  stroke: { curve: 'smooth', width: 2 },
  fill: {
    type: 'gradient',
    gradient: { shadeIntensity: 1, opacityFrom: 0.12, opacityTo: 0.01, stops: [0, 90, 100] }
  },
  xaxis: { 
    categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], 
    axisBorder: { show: false },
    axisTicks: { show: false },
    labels: { style: { colors: '#7A7A7A', fontSize: '11px', fontWeight: 500 } }
  },
  yaxis: { labels: { style: { colors: '#7A7A7A', fontSize: '11px' } } },
  grid: { borderColor: '#E5E5E0', strokeDashArray: 5 },
  annotations: {
    yaxis: [{
      y: 75,
      borderColor: '#EF4444',
      strokeDashArray: 4,
      label: { 
        borderColor: '#EF4444', 
        style: { color: '#fff', background: '#EF4444', fontSize: '10px', fontWeight: 'bold', padding: { x: 6, y: 4 } }, 
        text: 'OMS Limit (75 mg/m³)' 
      }
    }]
  }
})

const chartSeries = ref([
  { name: 'Average SO2 Levels', data: [42, 50, 78, 61, 35, 40, 44] }
])
</script>

<template>
  <div class="min-h-screen bg-[#F4F3EF] p-8 flex gap-8 font-sans antialiased text-[#1A1A1A]">
    
    <Sidebar class="shrink-0" />

    <div class="flex-1 flex flex-col min-w-0">
      
      <Navbar />

      <div class="flex justify-between items-end mb-12 mt-2">
        <div>
          <div class="inline-flex items-center gap-2 bg-amber-50 text-amber-800 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full border border-amber-200/60 backdrop-blur-md">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
            Auditor View Layer
          </div>
          <h2 class="text-3xl font-black tracking-tight mt-2.5 text-[#1A1A1A] bg-gradient-to-r from-[#1A1A1A] to-[#4A4A4A] bg-clip-text">
            Environmental Compliance Audit
          </h2>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-8 flex-1 items-stretch">
        
        <div class="col-span-2 flex flex-col gap-8">
          
          <div class="grid grid-cols-4 gap-5">
            
            <div class="group bg-[#1A1A1A] text-white p-6 rounded-[2.2rem] flex flex-col justify-between min-h-[140px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,0,0,0.1)] relative overflow-hidden">
              <div class="absolute right-0 top-0 w-24 h-24 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
              <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center transition-transform duration-300 group-hover:scale-110"><ShieldCheck class="w-4 h-4 text-[#E2FF54]" /></div>
              <div class="mt-4">
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Global Compliance</p>
                <h3 class="text-3xl font-black mt-1 text-[#E2FF54] tracking-tight">{{ auditStats.compliance_rate }}</h3>
              </div>
            </div>

            <div class="group bg-[#1A1A1A] text-white p-6 rounded-[2.2rem] flex flex-col justify-between min-h-[140px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,0,0,0.1)] relative overflow-hidden">
              <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center transition-transform duration-300 group-hover:scale-110"><Activity class="w-4 h-4 text-gray-300" /></div>
              <div class="mt-4">
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Inspected Logs</p>
                <h3 class="text-3xl font-black mt-1 tracking-tight">{{ auditStats.total_checked }}</h3>
              </div>
            </div>

            <div class="group bg-[#1A1A1A] text-white p-6 rounded-[2.2rem] flex flex-col justify-between min-h-[140px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] border border-red-500/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(239,68,68,0.1)] relative overflow-hidden">
              <div class="w-9 h-9 rounded-full bg-red-500/10 flex items-center justify-center transition-transform duration-300 group-hover:scale-110"><AlertTriangle class="w-4 h-4 text-red-400" /></div>
              <div class="mt-4">
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Critical Thresholds</p>
                <h3 class="text-3xl font-black mt-1 text-red-400 tracking-tight">{{ auditStats.critical_violations }}</h3>
              </div>
            </div>

            <div class="group bg-[#1A1A1A] text-white p-6 rounded-[2.2rem] flex flex-col justify-between min-h-[140px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,0,0,0.1)] relative overflow-hidden">
              <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center transition-transform duration-300 group-hover:scale-110"><FileText class="w-4 h-4 text-gray-300" /></div>
              <div class="mt-4">
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Pending Reviews</p>
                <h3 class="text-3xl font-black mt-1 tracking-tight">{{ auditStats.pending_reviews }}</h3>
              </div>
            </div>

          </div>

          <div class="flex-1 flex flex-col bg-transparent mt-2">
            <div class="flex justify-between items-center mb-4">
              <h4 class="text-xl font-black tracking-tight text-[#1A1A1A]">Recent Air Quality Incidents & Flagged Violations</h4>
              <button class="text-xs font-bold text-gray-500 hover:text-black flex items-center gap-1.5 bg-white/80 border border-gray-200/50 px-3 py-1.5 rounded-full backdrop-blur-md transition-colors shadow-sm">
                <Filter class="w-3.5 h-3.5" /> Filter by Severity
              </button>
            </div>
            
            <div class="space-y-4 flex-1 overflow-y-auto max-h-[380px] pr-2">
              
              <div class="group bg-white p-5 rounded-[1.8rem] flex items-center justify-between shadow-[0_4px_30px_rgba(0,0,0,0.015)] border border-white/80 transition-all duration-300 hover:shadow-[0_10px_30px_rgba(0,0,0,0.03)] hover:border-gray-200">
                <div class="flex items-center gap-5">
                  <div class="w-2 h-12 bg-red-500 rounded-full shadow-[0_0_10px_rgba(239,68,68,0.4)]"></div>
                  <div>
                    <h5 class="text-base font-bold text-gray-900 tracking-tight">Safi Central Plant — Chimney 02</h5>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Sulfur Dioxide exceeded legal parameters by <span class="text-red-500 font-semibold">14%</span></p>
                  </div>
                </div>
                <div class="flex items-center gap-6">
                  <span class="text-xs font-mono font-bold bg-red-50 text-red-600 px-3 py-1.5 rounded-xl border border-red-100/70">78.2 mg/m³</span>
                  <span class="text-xs text-gray-400 font-medium flex items-center gap-1.5"><Calendar class="w-4 h-4 text-gray-400" /> June 17, 14:32</span>
                  <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center opacity-60 group-hover:opacity-100 group-hover:bg-black group-hover:text-white transition-all duration-300"><ArrowUpRight class="w-4 h-4" /></div>
                </div>
              </div>

              <div class="group bg-white p-5 rounded-[1.8rem] flex items-center justify-between shadow-[0_4px_30px_rgba(0,0,0,0.015)] border border-white/80 transition-all duration-300 hover:shadow-[0_10px_30px_rgba(0,0,0,0.03)] hover:border-gray-200">
                <div class="flex items-center gap-5">
                  <div class="w-2 h-12 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.4)]"></div>
                  <div>
                    <h5 class="text-base font-bold text-gray-900 tracking-tight">Zone A Sulfuric Acid Units</h5>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Nitrogen Oxides emissions verified within regular limits</p>
                  </div>
                </div>
                <div class="flex items-center gap-6">
                  <span class="text-xs font-mono font-bold bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-xl border border-emerald-100/70">32.0 mg/m³</span>
                  <span class="text-xs text-gray-400 font-medium flex items-center gap-1.5"><Calendar class="w-4 h-4 text-gray-400" /> June 17, 11:15</span>
                  <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center opacity-60 group-hover:opacity-100 group-hover:bg-black group-hover:text-white transition-all duration-300"><ArrowUpRight class="w-4 h-4" /></div>
                </div>
              </div>

            </div>
          </div>

        </div>

        <div class="flex flex-col gap-8">
          
          <div class="bg-white p-6 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.01)] border border-white/80 relative overflow-hidden">
            <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Audit Standards (Normes Marocaines)</h4>
            <div class="space-y-3.5 text-xs">
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="font-bold text-gray-600">SO2 24h Threshold</span>
                <span class="font-mono bg-[#F4F3EF] px-2.5 py-1 rounded-lg text-gray-800 font-bold">125 µg/m³</span>
              </div>
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="font-bold text-gray-600">NOx Annual Limit</span>
                <span class="font-mono bg-[#F4F3EF] px-2.5 py-1 rounded-lg text-gray-800 font-bold">40 µg/m³</span>
              </div>
              <div class="flex justify-between items-center py-2">
                <span class="font-bold text-gray-600">PM10 Daily Cap</span>
                <span class="font-mono bg-[#F4F3EF] px-2.5 py-1 rounded-lg text-gray-800 font-bold">50 µg/m³</span>
              </div>
            </div>
          </div>

          <div class="bg-[#E2FF54] p-[4px] rounded-[2.6rem] shadow-[0_20px_40px_rgba(226,255,84,0.08)] flex-1 flex transition-transform duration-300 hover:scale-[1.01]">
            <div class="bg-white w-full h-full rounded-[2.4rem] p-6 flex flex-col justify-between">
              <div>
                <h4 class="text-lg font-black tracking-tight text-[#1A1A1A]">Continuous Threshold Profiling</h4>
                <p class="text-xs text-gray-400 mt-0.5 font-medium">Weekly emission timeline checked against safety limits.</p>
              </div>
              
              <div class="flex-1 flex items-center justify-center mt-6 w-full overflow-hidden">
                <VueApexCharts class="w-full" type="area" height="230" :options="chartOptions" :series="chartSeries" />
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>