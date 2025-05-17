<template>
  <div class="flex min-h-[80vh] items-center justify-center">
    <div class="card w-full max-w-md">
      <h1 class="text-2xl font-bold text-center mb-6">Login</h1>
      
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label for="email" class="form-label">Email</label>
          <input 
            id="email"
            v-model="form.email"
            type="email"
            class="form-input"
            required
          />
        </div>
        
        <div>
          <label for="password" class="form-label">Password</label>
          <input 
            id="password"
            v-model="form.password"
            type="password"
            class="form-input"
            required
          />
        </div>
        
        <div v-if="error" class="p-3 bg-red-100 text-red-700 rounded">
          {{ error }}
        </div>
        
        <div>
          <button 
            type="submit" 
            class="btn btn-primary w-full" 
            :disabled="loading"
          >
            {{ loading ? 'Logging in...' : 'Login' }}
          </button>
        </div>
      </form>
      
      <div class="mt-4 text-center">
        <p>Don't have an account? 
          <router-link to="/register" class="text-blue-500 hover:underline">
            Register
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '../stores/auth'
import Swal from 'sweetalert2'

const authStore = useAuthStore()
const loading = computed(() => authStore.loading)
const error = ref('')

const form = ref({
  email: '',
  password: ''
})

const handleLogin = async () => {
  try {
    error.value = ''
    await authStore.login(form.value)
    Swal.fire({
      title: 'Login Successful!',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    })
  } catch (err) {
    error.value = err
  }
}
</script>