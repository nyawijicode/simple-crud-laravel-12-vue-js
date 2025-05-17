<template>
  <div>
    <div class="flex items-center mb-6">
      <button @click="$router.back()" class="mr-3 text-gray-600 hover:text-gray-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
      </button>
      <h1 class="text-2xl font-bold">Edit User</h1>
    </div>
    
    <div class="card">
      <div v-if="loading && !user" class="flex justify-center py-6">
        <div class="w-8 h-8 border-t-2 border-b-2 border-blue-500 rounded-full animate-spin"></div>
      </div>
      
      <form v-else-if="user" @submit.prevent="updateUser" class="space-y-4">
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
          <label for="password" class="form-label">Password (leave blank to keep current)</label>
          <input 
            id="password"
            v-model="form.password"
            type="password"
            class="form-input"
          />
        </div>
        
        <div>
          <label for="role" class="form-label">Role</label>
          <select 
            id="role"
            v-model="form.role"
            class="form-input"
            required
          >
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        
        <div>
          <label for="is_active" class="form-label">Status</label>
          <select 
            id="is_active"
            v-model="form.is_active"
            class="form-input"
            required
          >
            <option :value="true">Active</option>
            <option :value="false">Inactive</option>
          </select>
        </div>
        
        <div v-if="error" class="p-3 bg-red-100 text-red-700 rounded">
          {{ error }}
        </div>
        
        <div class="flex space-x-3">
          <button 
            type="submit" 
            class="btn btn-primary" 
            :disabled="loading"
          >
            {{ loading ? 'Updating...' : 'Update User' }}
          </button>
          <button 
            type="button" 
            @click="$router.back()" 
            class="btn btn-secondary"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUsersStore } from '../stores/users'
import { useAuthStore } from '../stores/auth'
import Swal from 'sweetalert2'

const route = useRoute()
const router = useRouter()
const usersStore = useUsersStore()
const authStore = useAuthStore()

const userId = computed(() => route.params.id)
const user = computed(() => usersStore.user)
const loading = computed(() => usersStore.loading)
const error = ref('')

const form = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  password: '',
  role: 'user',
  is_active: true
})

const loadUser = async () => {
  try {
    await usersStore.fetchUser(userId.value)
    
    if (user.value) {
      form.value.name = user.value.name
      form.value.email = user.value.email
      form.value.phone = user.value.phone || ''
      form.value.address = user.value.address || ''
      form.value.role = user.value.role
      form.value.is_active = user.value.is_active
    }
  } catch (err) {
    Swal.fire({
      title: 'Error',
      text: err,
      icon: 'error'
    })
    router.push({ name: 'users' })
  }
}

const updateUser = async () => {
  try {
    error.value = ''
    
    // Check if updating own account
    const isOwnAccount = parseInt(userId.value) === authStore.user.id
    
    // Remove password if empty
    const userData = { ...form.value }
    if (!userData.password) {
      delete userData.password
    }
    
    await usersStore.updateUser(userId.value, userData)
    
    Swal.fire({
      title: 'Success!',
      text: 'User updated successfully',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    })
    
    // If user updated their own account, refresh auth user data
    if (isOwnAccount) {
      await authStore.fetchCurrentUser()
    }
    
    router.push({ name: 'users' })
  } catch (err) {
    error.value = err
  }
}

onMounted(() => {
  loadUser()
})
</script>