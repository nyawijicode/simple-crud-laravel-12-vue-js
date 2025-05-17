<template>
  <div class="min-h-screen bg-gray-100">
    <Navbar v-if="isLoggedIn" />
    <div class="container mx-auto px-4 py-6">
      <router-view />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from './stores/auth'
import Navbar from './components/Navbar.vue'

const authStore = useAuthStore()
const isLoggedIn = computed(() => authStore.isLoggedIn)
</script>