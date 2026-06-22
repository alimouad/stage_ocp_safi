<script setup>
import { useAuthStore } from '@/stores/auth'
import { Search, Plus, Layout, Bell } from 'lucide-vue-next'

const authStore = useAuthStore()
</script>

<template>
  <header class="flex items-center justify-between w-full mb-6">
    <div class="relative w-96">
      <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
      <input 
        type="text" 
        placeholder="Search emission points, gas levels, compliance logs..." 
        class="w-full pl-12 pr-4 py-3 bg-[#EAE9E2]/70 border-0 rounded-full text-sm focus:ring-2 focus:ring-black placeholder-gray-500 font-medium"
      />
    </div>

    <div class="flex items-center gap-3">
      <span class="text-xs font-bold text-gray-500 mr-2">Hello, <b class="text-black">{{ authStore.user?.name || 'Auditor' }}</b></span>
      <button v-if="!authStore.isAuditor" class="p-3 bg-[#EAE9E2]/70 rounded-full hover:bg-[#1A1A1A] hover:text-white transition-colors">
        <Plus class="w-4 h-4" />
      </button>
      <button class="p-3 bg-[#EAE9E2]/70 rounded-full hover:bg-[#1A1A1A] hover:text-white transition-colors">
        <Layout class="w-4 h-4" />
      </button>
      <button class="p-3 bg-[#EAE9E2]/70 rounded-full relative hover:bg-[#1A1A1A] hover:text-white transition-colors">
        <Bell class="w-4 h-4" />
        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
      </button>
    </div>
  </header>
</template>