<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">My Profile</h1>
    
    <div class="card">
      <div v-if="user" class="space-y-6">
        <div class="space-y-4">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold">Personal Information</h2>
            <button @click="editing = !editing" class="btn btn-secondary mt-2 md:mt-0">
              {{ editing ? 'Cancel' : 'Edit Profile' }}
            </button>
          </div>
          
          <form v-if="editing" @submit.prevent="updateProfile" class="space-y-4">
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
              <label for="phone" class="form-label">Phone</label>
              <input 
                id="phone"
                v-model="form.phone"
                type="text"
                class="form-input"
              />
            </div>
            
            <div>
              <label for="address" class="form-label">Address</label>
              <input 
                id="address"
                v-model="form.address"
                type="text"
                class="form-input"
              />
            </div>
            
            <div>
              <label for="password" class="form-label">New Password (leave blank to keep current)</label>
              <input 
                id="password"
                v-model="form.password"
                type="password"
                class="form-input"
              />
            </div>
            
            <div>
              <label for="password_confirmation" class="form-label">Confirm New Password</label>
              <input 
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                class="form-input"
              />
            </div>
            
            <div class="flex space-x-3">
              <button 
                type="submit" 
                class="btn btn-primary" 
                :disabled="loading"
              >
                {{ loading ? 'Updating...' : 'Update Profile' }}
              </button>
              <button 
                type="button" 
                @click="editing = false" 
                class="btn btn-secondary"
              >
                Cancel
              </button>
            </div>
          </form>
          
          <div v-else class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <div class="text-sm text-gray-500">Name</div>
                <div>{{ user.name }}</div>
              </div>
              
              <div>
                <div class="text-sm text-gray-500">Email</div>
                <div>{{ user.email }}</div>
              </div>
              
              <div>
                <div class="text-sm text-gray-500">Role</div>
                <div class="capitalize">{{ user.role }}</div>
              </div>
              
              <div>
                <div class="text-sm text-gray-500">Phone</div>
                <div>{{ user.phone || 'Not provided' }}</div>
              </div>
              
              <div>
                <div class="text-sm text-gray-500">Address</div>
                <div>{{ user.address || 'Not provided' }}</div>
              </div>
              
              <div>
                <div class="text-sm text-gray-500">Account Status</div>
                <div>
                  <span 
                    :class="user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    class="px-2 py-1 rounded-full text-xs font-medium"
                  >
                    {{ user.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="flex justify-center">
        <div class="w-8 h-8 border-t-2 border-b-2 border-blue-500 rounded-full animate-spin"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import api from '../services/api'
import Swal from 'sweetalert2'

const authStore = useAuthStore()
const user = computed(() => authStore.user)
const loading = ref(false)
const editing = ref(false)
const error = ref('')

const form = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  password: '',
  password_confirmation: ''
})

onMounted(async () => {
  if (!user.value) {
    await authStore.fetchCurrentUser()
  }
  
  if (user.value) {
    form.value.name = user.value.name
    form.value.email = user.value.email
    form.value.phone = user.value.phone || ''
    form.value.address = user.value.address || ''
  }
})

const updateProfile = async () => {
  if (form.value.password && form.value.password !== form.value.password_confirmation) {
    Swal.fire({
      title: 'Error',
      text: 'Passwords do not match',
      icon: 'error'
    })
    return
  }
  
  // Remove password fields if empty
  const userData = { ...form.value }
  if (!userData.password) {
    delete userData.password
    delete userData.password_confirmation
  }
  
  loading.value = true
  
  try {
    const response = await api.put(`/users/${user.value.id}`, userData)
    
    // Update local user data
    await authStore.fetchCurrentUser()
    
    editing.value = false
    loading.value = false
    
    Swal.fire({
      title: 'Success',
      text: 'Profile updated successfully',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    })
  } catch (err) {
    loading.value = false
    Swal.fire({
      title: 'Update Failed',
      text: err.response?.data?.message || 'Failed to update profile',
      icon: 'error'
    })
  }
}
</script>