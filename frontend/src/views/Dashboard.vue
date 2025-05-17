<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
    
    <div class="card">
      <div v-if="user" class="space-y-4">
        <h2 class="text-xl font-semibold">Welcome, {{ user.name }}!</h2>
        <p>You are logged in as: <span class="font-medium">{{ user.role }}</span></p>
        
        <div v-if="isAdmin" class="bg-blue-50 p-4 rounded border border-blue-200">
          <p class="font-medium text-blue-800">You have admin privileges</p>
          <p class="text-sm text-blue-600 mt-1">You can manage users through the Users menu in the navigation bar.</p>
        </div>
      </div>
      
      <div v-else class="flex justify-center">
        <div class="w-8 h-8 border-t-2 border-b-2 border-blue-500 rounded-full animate-spin"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
const user = computed(() => authStore.user)
const isAdmin = computed(() => authStore.isAdmin)

onMounted(async () => {
  if (!user.value) {
    await authStore.fetchCurrentUser()
  }
})
</script>