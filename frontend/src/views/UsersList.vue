<template>
  <div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
      <h1 class="text-2xl font-bold">Users Management</h1>
      <router-link :to="{ name: 'users-create' }" class="btn btn-primary mt-2 md:mt-0">
        Create New User
      </router-link>
    </div>
    
    <div class="card mb-6">
      <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
        <div class="flex-grow">
          <input 
            v-model="search"
            type="text"
            placeholder="Search users..."
            class="form-input"
            @input="handleSearch"
          />
        </div>
        
        <div>
          <select v-model="filters.role" class="form-input" @change="fetchData()">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
          </select>
        </div>
        
        <div>
          <select v-model="filters.is_active" class="form-input" @change="fetchData()">
            <option value="">All Status</option>
            <option :value="true">Active</option>
            <option :value="false">Inactive</option>
          </select>
        </div>
      </div>
    </div>
    
    <div class="card overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="loading">
            <td colspan="5" class="px-6 py-4 text-center">
              <div class="flex justify-center">
                <div class="w-6 h-6 border-t-2 border-b-2 border-blue-500 rounded-full animate-spin"></div>
              </div>
            </td>
          </tr>
          
          <tr v-else-if="users.length === 0">
            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
              No users found
            </td>
          </tr>
          
          <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">{{ user.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap capitalize">{{ user.role }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span 
                :class="user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="px-2 py-1 rounded-full text-xs font-medium"
              >
                {{ user.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex justify-end space-x-2">
                <router-link :to="{ name: 'users-edit', params: { id: user.id } }" class="text-blue-600 hover:text-blue-900">
                  Edit
                </router-link>
                <button @click="confirmDelete(user)" class="text-red-600 hover:text-red-900">
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      
      <!-- Pagination -->
      <div class="px-6 py-3 flex items-center justify-between border-t border-gray-200">
        <div class="text-sm text-gray-500">
          Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }} of {{ pagination.total || 0 }} results
        </div>
        
        <div class="flex space-x-2">
          <button 
            @click="changePage(pagination.currentPage - 1)" 
            class="btn btn-secondary"
            :disabled="pagination.currentPage <= 1"
          >
            Previous
          </button>
          <button 
            @click="changePage(pagination.currentPage + 1)" 
            class="btn btn-secondary"
            :disabled="pagination.currentPage >= pagination.lastPage"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useUsersStore } from '../stores/users'
import { useAuthStore } from '../stores/auth'
import Swal from 'sweetalert2'
import { useRouter } from 'vue-router'

const router = useRouter()
const usersStore = useUsersStore()
const authStore = useAuthStore()

const users = computed(() => usersStore.users)
const loading = computed(() => usersStore.loading)
const pagination = computed(() => ({
  ...usersStore.pagination,
  from: (usersStore.pagination.currentPage - 1) * usersStore.pagination.perPage + 1,
  to: Math.min(usersStore.pagination.currentPage * usersStore.pagination.perPage, usersStore.pagination.total)
}))

const search = ref('')
const filters = ref({
  role: '',
  is_active: ''
})

// Debounce search
let searchTimeout = null
const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchData()
  }, 300)
}

const fetchData = async () => {
  try {
    await usersStore.fetchUsers(
      pagination.value.currentPage,
      search.value,
      filters.value
    )
  } catch (error) {
    Swal.fire({
      title: 'Error',
      text: error,
      icon: 'error'
    })
  }
}

const changePage = (page) => {
  if (page < 1 || page > pagination.value.lastPage) return
  usersStore.fetchUsers(page, search.value, filters.value)
}

const confirmDelete = (user) => {
  // Prevent deleting your own account
  if (user.id === authStore.user.id) {
    Swal.fire({
      title: 'Cannot Delete',
      text: 'You cannot delete your own account',
      icon: 'warning'
    })
    return
  }
  
  Swal.fire({
    title: 'Are you sure?',
    text: `Do you want to delete user "${user.name}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#EF4444',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteUser(user.id)
    }
  })
}

const deleteUser = async (id) => {
  try {
    await usersStore.deleteUser(id)
    
    Swal.fire({
      title: 'Deleted!',
      text: 'User has been deleted.',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    })
    
    // Refresh the list
    fetchData()
  } catch (error) {
    Swal.fire({
      title: 'Error',
      text: error,
      icon: 'error'
    })
  }
}

onMounted(() => {
  fetchData()
})
</script>