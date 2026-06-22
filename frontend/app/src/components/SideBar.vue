<script setup>
import { useAuthStore } from '@/stores/auth'
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { LayoutGrid, MapPin, ShieldAlert, FileSpreadsheet, LogOut } from 'lucide-vue-next'

const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()

const navItems = computed(() => [
  { path: '/emission-points', title: 'Map View', icon: MapPin, show: authStore.isHseAdmin || authStore.isOperator || authStore.isAuditor },
  { path: '/measurements', title: 'Measurements', icon: FileSpreadsheet, show: authStore.isHseAdmin || authStore.isOperator },
  { path: '/alerts', title: 'Alert Settings', icon: ShieldAlert, show: authStore.isHseAdmin },
])

const isActive = (path) => {
  if (path === '/measurements') {
    return route.path === '/measurements'
  }

  return route.path === path
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<template>
  <aside class="w-24 bg-white rounded-[2.5rem] flex flex-col items-center justify-between py-8 shadow-[0_10px_30px_rgba(0,0,0,0.02)] h-[calc(100vh-3rem)] sticky top-6">
    <div class="flex flex-col gap-10 items-center">
      <button
        type="button"
        class="w-12 h-12 rounded-2xl flex items-center justify-center cursor-pointer transition-all"
        :class="route.path === '/dashboard' ? 'bg-[#1A1A1A] text-white shadow-md' : 'bg-[#F1F0EA] text-gray-400 hover:bg-[#1A1A1A] hover:text-white'"
        title="Dashboard"
        @click="router.push('/dashboard')"
      >
        <LayoutGrid class="w-5 h-5" />
      </button>
      
      <nav class="flex flex-col gap-6">
        <button
          v-for="item in navItems.filter((navItem) => navItem.show)"
          :key="item.path"
          type="button"
          class="w-12 h-12 rounded-xl flex items-center justify-center transition-all"
          :class="isActive(item.path) ? 'bg-[#1A1A1A] text-white shadow-md' : 'text-gray-400 hover:bg-[#F1F0EA] hover:text-[#1A1A1A]'"
          :title="item.title"
          @click="router.push(item.path)"
        >
          <component :is="item.icon" class="w-5 h-5" />
        </button>
      </nav>
    </div>

    <div class="flex flex-col items-center gap-4">
      <button @click="handleLogout" class="w-10 h-10 rounded-full flex items-center justify-center text-red-400 hover:bg-red-50 transition-colors" title="Logout">
        <LogOut class="w-5 h-5" />
      </button>
      
      <div class="flex flex-col items-center gap-1">
        <div class="w-12 h-12 bg-[#E2FF54] rounded-full overflow-hidden border-2 border-white shadow-md">
          <img :src="`https://api.dicebear.com/7.x/bottts/svg?seed=${authStore.user?.name || 'Auditor'}`" alt="Avatar" />
        </div>
        <span class="text-[9px] font-black text-gray-400 text-center max-w-[65px] truncate uppercase">
          {{ authStore.role }}
        </span>
      </div>
    </div>
  </aside>
</template>
