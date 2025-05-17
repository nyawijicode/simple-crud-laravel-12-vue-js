<template>
  <div class="flex min-h-[80vh] items-center justify-center">
    <div class="card w-full max-w-md">
      <h1 class="text-2xl font-bold text-center mb-6">Register</h1>
      
      <form @submit.prevent="handleRegister" class="space-y-4">
        <div>
          <label for="name" class="form-label">Name</label>
          <input 
            id="name"
            v-model="form.name"
            type="text"
            class="form-input"
            required
          />
        </div>
        
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
          <label for="phone" class="form-label">Phone (optional)</label>
          <input 
            id="phone"
            v-model="form.phone"
            type="text"
            class="form-input"
          />
        </div>
        
        <div>
          <label for="address" class="form-label">Address (optional)</label>
          <input 
            id="address"
            v-model="form.address"
            type="text"
            class="form-input"
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
        
        <div>
          <label for="password_confirmation" class="form-label">Confirm Password</label>
          <input 
            id="password_confirmation"
            v-model="form.password_confirmation"
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
            {{ loading ? 'Registering...' : 'Register' }}
          </button>
        </div>
      </form>
      
      <div class="mt-4 text-center">
        <p>Already have an account? 
          <router-link to="/login" class="text-blue-500 hover:underline">
            Login
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
  name: '',
  email: '',
  phone: '',
  address: '',
  password: '',
  password_confirmation: ''
})

const handleRegister = async () => {
  try {
    error.value = ''
    
    if (form.value.password !== form.value.password_confirmation) {
      error.value = 'Passwords do not match'
      return
    }
    
    await authStore.register(form.value)
    Swal.fire({
      title: 'Registration Successful!',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    })
  } catch (err) {
    error.value = err
  }
}
</script>