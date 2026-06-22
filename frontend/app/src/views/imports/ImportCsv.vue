<script setup>
import { ref } from 'vue'
import { CloudUpload, FileText, Loader2 } from 'lucide-vue-next'
import AppShell from '@/components/AppShell.vue'
import api from '@/services/api'

const file = ref(null)
const uploading = ref(false)
const message = ref('')
const error = ref('')

const handleFile = (event) => {
  file.value = event.target.files?.[0] || null
  message.value = ''
  error.value = ''
}

const uploadCsv = async () => {
  if (!file.value) {
    error.value = 'Choose a CSV file first.'
    return
  }

  uploading.value = true
  message.value = ''
  error.value = ''

  try {
    const formData = new FormData()
    formData.append('file', file.value)
    const response = await api.post('/measurements/import-csv', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    message.value = response.data?.message || 'CSV imported successfully.'
    file.value = null
  } catch (err) {
    error.value = err.response?.data?.message || 'CSV import failed.'
  } finally {
    uploading.value = false
  }
}
</script>

<template>
  <AppShell>
    <section class="mx-auto max-w-3xl space-y-6">
      <header>
        <h1 class="text-3xl font-black tracking-tight">Import CSV</h1>
        <p class="mt-1 text-sm font-medium text-gray-500">Upload measurements with columns: emission_point_id, substance_id, value, measured_at.</p>
      </header>

      <p v-if="message" class="rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">{{ message }}</p>
      <p v-if="error" class="rounded-2xl bg-red-50 px-4 py-3 text-sm font-bold text-red-700">{{ error }}</p>

      <form class="rounded-3xl bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.03)]" @submit.prevent="uploadCsv">
        <label class="flex min-h-64 cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-gray-200 bg-[#F6F5F0] px-6 text-center transition hover:border-[#1A1A1A]">
          <CloudUpload class="mb-4 h-10 w-10 text-gray-400" />
          <span class="text-lg font-black">{{ file?.name || 'Choose CSV file' }}</span>
          <span class="mt-2 text-sm font-semibold text-gray-400">Accepted: .csv, .txt</span>
          <input class="hidden" type="file" accept=".csv,.txt" @change="handleFile" />
        </label>

        <div class="mt-5 rounded-2xl bg-[#F6F5F0] p-4">
          <div class="flex items-center gap-3 text-sm font-bold">
            <FileText class="h-4 w-4" />
            <span>Example row</span>
          </div>
          <p class="mt-2 font-mono text-xs text-gray-500">1,1,420.2500,2026-06-19 08:00:00</p>
        </div>

        <button
          type="submit"
          :disabled="uploading"
          class="mt-5 flex w-full items-center justify-center gap-2 rounded-2xl bg-[#1A1A1A] px-4 py-4 text-sm font-black text-white transition hover:bg-black disabled:opacity-50"
        >
          <Loader2 v-if="uploading" class="h-4 w-4 animate-spin" />
          <CloudUpload v-else class="h-4 w-4" />
          Upload CSV
        </button>
      </form>
    </section>
  </AppShell>
</template>
