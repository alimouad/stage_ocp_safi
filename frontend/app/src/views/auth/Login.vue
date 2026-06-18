<script setup>
import { ref, reactive } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { LogIn, Mail, Lock, Loader2 } from 'lucide-vue-next'

const authStore = useAuthStore()
const router = useRouter()

const credentials = reactive({
  email: '',
  password: ''
})

const errorMessage = ref('')

const handleLogin = async () => {
  if (!credentials.email || !credentials.password) {
    errorMessage.value = 'Please fill in all fields.'
    return
  }

  const success = await authStore.login(credentials)
  if (success) {
    router.push('/')
  } else {
    errorMessage.value = authStore.error
  }
}
</script>

<template>
  <div class="min-h-screen bg-[#F1F0EA] flex items-center justify-center p-4 font-sans antialiased text-[#1A1A1A]">
    
    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] p-10 flex flex-col relative overflow-hidden border border-white/40">
      
      <div class="absolute top-0 right-0 w-24 h-24 bg-[#E2FF54] rounded-full blur-2xl opacity-40 -mr-6 -mt-6"></div>

      <div class="mb-10 text-center z-10">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-[#1A1A1A] text-white rounded-2xl mb-4 shadow-lg shadow-black/10">
          <span class="font-black text-xl tracking-tighter">OCP</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight">Welcome Back</h1>
        <p class="text-sm text-gray-400 mt-1">Air Quality Supervision Platform</p>
      </div>

      <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-xs text-red-600 font-medium">
        {{ errorMessage }}
      </div>

      <form @submit.prevent="handleLogin" class="space-y-5 z-10">
        
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 ml-1">Email Address</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
              <Mail class="w-5 h-5 stroke-[1.5]" />
            </div>
            <input 
              v-model="credentials.email"
              type="email" 
              placeholder="name@ocpgroup.ma"
              class="w-full pl-11 pr-4 py-4 bg-[#F6F5F0] border-0 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#1A1A1A] transition-all placeholder-gray-400"
              required
            />
          </div>
        </div>

        <div>
          <div class="flex justify-between items-center mb-2 ml-1">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Password</label>
            <a href="#" class="text-xs text-gray-400 hover:text-[#1A1A1A] transition-colors">Forgot?</a>
          </div>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
              <Lock class="w-5 h-5 stroke-[1.5]" />
            </div>
            <input 
              v-model="credentials.password"
              type="password" 
              placeholder="••••••••"
              class="w-full pl-11 pr-4 py-4 bg-[#F6F5F0] border-0 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#1A1A1A] transition-all placeholder-gray-400"
              required
            />
          </div>
        </div>

        <button 
          type="submit" 
          :disabled="authStore.loading"
          class="w-full py-4 bg-[#1A1A1A] text-white font-bold rounded-2xl text-sm transition-all shadow-md active:scale-[0.98] disabled:opacity-50 hover:bg-black flex items-center justify-center gap-2 mt-2 group"
        >
          <Loader2 v-if="authStore.loading" class="w-4 h-4 animate-spin" />
          <template v-else>
            <span>Sign In</span>
            <LogIn class="w-4 h-4 transition-transform group-hover:translate-x-1" />
          </template>
        </button>

      </form>

      <div class="mt-8 text-center text-xs text-gray-400 z-10">
        Secured by OCP IT Division. Authorized personnel only.
      </div>

    </div>
  </div>
</template>