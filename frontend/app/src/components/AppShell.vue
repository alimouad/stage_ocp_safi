<script setup>
import { computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { BarChart3, CloudUpload, LogOut, MapPin, PlusCircle, ShieldAlert } from 'lucide-vue-next'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const navItems = computed(() => [
  { name: 'Dashboard', path: '/dashboard', icon: BarChart3, show: true },
  { name: 'Emission Points', path: '/emission-points', icon: MapPin, show: true },
  { name: 'New Measurement', path: '/measurements', icon: PlusCircle, show: !authStore.isAuditor },
  { name: 'Alerts', path: '/alerts', icon: ShieldAlert, show: true },
  { name: 'Import CSV', path: '/measurements/import', icon: CloudUpload, show: !authStore.isAuditor },
])

const visibleNavItems = computed(() => navItems.value.filter((item) => item.show))
const userLabel = computed(() => authStore.user?.name || 'Operator')

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen bg-[#F1F0EA] p-4 text-[#1A1A1A] antialiased md:p-6">
    <div class="mx-auto flex min-h-[calc(100vh-2rem)] w-full max-w-7xl gap-4 md:min-h-[calc(100vh-3rem)] md:gap-6">
      <aside class="hidden w-64 shrink-0 rounded-3xl bg-white p-4 shadow-[0_10px_30px_rgba(0,0,0,0.03)] md:flex md:flex-col">
        <div class="mb-8 flex items-center gap-3 px-2">
          <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#1A1A1A] text-sm font-black text-white">
            OCP
          </div>
          <div>
            <p class="text-sm font-black">Air Quality</p>
            <p class="text-xs font-semibold text-gray-400">Supervision</p>
          </div>
        </div>

        <nav class="flex flex-1 flex-col gap-2">
          <RouterLink
            v-for="item in visibleNavItems"
            :key="item.path"
            :to="item.path"
            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-gray-500 transition hover:bg-[#F1F0EA] hover:text-[#1A1A1A]"
            active-class="bg-[#1A1A1A] text-white hover:bg-[#1A1A1A] hover:text-white"
          >
            <component :is="item.icon" class="h-4 w-4" />
            <span>{{ item.name }}</span>
          </RouterLink>
        </nav>

        <div class="rounded-2xl bg-[#F6F5F0] p-3">
          <p class="truncate text-xs font-bold text-gray-500">{{ userLabel }}</p>
          <button
            type="button"
            class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-white px-3 py-2 text-xs font-bold transition hover:bg-[#1A1A1A] hover:text-white"
            @click="handleLogout"
          >
            <LogOut class="h-4 w-4" />
            Logout
          </button>
        </div>
      </aside>

      <main class="min-w-0 flex-1">
        <div class="mb-4 flex gap-2 overflow-x-auto rounded-2xl bg-white p-2 md:hidden">
          <RouterLink
            v-for="item in visibleNavItems"
            :key="item.path"
            :to="item.path"
            class="flex shrink-0 items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold text-gray-500"
            active-class="bg-[#1A1A1A] text-white"
          >
            <component :is="item.icon" class="h-4 w-4" />
            <span>{{ item.name }}</span>
          </RouterLink>
        </div>

        <slot />
      </main>
    </div>
  </div>
</template>
