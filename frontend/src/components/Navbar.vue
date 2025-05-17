<template>
  <nav class="bg-gray-800 text-white">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
          <div class="text-xl font-bold">Laravel CRUD</div>
          <div class="ml-10 flex items-baseline space-x-4">
            <router-link to="/dashboard" class="px-3 py-2 rounded-md hover:bg-gray-700" active-class="bg-gray-700">
              Dashboard
            </router-link>
            <router-link v-if="isAdmin" to="/users" class="px-3 py-2 rounded-md hover:bg-gray-700" active-class="bg-gray-700">
              Users
            </router-link>
            <router-link to="/profile" class="px-3 py-2 rounded-md hover:bg-gray-700" active-class="bg-gray-700">
              Profile
            </router-link>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <span v-if="user" class="text-sm">Welcome, {{ user.name }}</span>
          <button @click="handleLogout" class="px-3 py-2 rounded-md bg-red-600 hover:bg-red-700">
            Logout
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../stores/auth'
import Swal from 'sweetalert2'

const authStore = useAuthStore()
const user = computed(() => authStore.user)
const isAdmin = computed(() => authStore.isAdmin)

const handleLogout = async () => {
  try {
    await authStore.logout()
    Swal.fire({
      title: 'Logged out successfully!',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    })
  } catch (error) {
    Swal.fire({
      title: 'Logout Failed',
      text: error,
      icon: 'error'
    })
  }
}
</script>