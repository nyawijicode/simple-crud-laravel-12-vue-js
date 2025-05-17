import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../services/api'

export const useUsersStore = defineStore('users', () => {
  // State
  const users = ref([])
  const user = ref(null)
  const loading = ref(false)
  const error = ref(null)
  const pagination = ref({
    total: 0,
    perPage: 10,
    currentPage: 1,
    lastPage: 1
  })

  // Actions
  const fetchUsers = async (page = 1, search = '', filters = {}) => {
    loading.value = true
    error.value = null
    try {
      const params = {
        page,
        per_page: pagination.value.perPage,
        search,
        ...filters
      }
      
      const response = await api.get('/users', { params })
      users.value = response.data.data
      
      // Update pagination
      if (response.data.meta) {
        pagination.value = {
          total: response.data.meta.total,
          perPage: parseInt(response.data.meta.per_page || 10),
          currentPage: parseInt(response.data.meta.current_page || 1),
          lastPage: parseInt(response.data.meta.last_page || 1)
        }
      }
      
      return users.value
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch users'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  const fetchUser = async (id) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get(`/users/${id}`)
      user.value = response.data.user
      return user.value
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch user'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  const createUser = async (userData) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/users', userData)
      return response.data.user
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to create user'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  const updateUser = async (id, userData) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(`/users/${id}`, userData)
      return response.data.user
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update user'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  const deleteUser = async (id) => {
    loading.value = true
    error.value = null
    try {
      await api.delete(`/users/${id}`)
      return true
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to delete user'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  return {
    users,
    user,
    loading,
    error,
    pagination,
    fetchUsers,
    fetchUser,
    createUser,
    updateUser,
    deleteUser
  }
})