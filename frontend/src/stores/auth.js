import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../services/api'
import router from '../router'

export const useAuthStore = defineStore('auth', () => {
  // State
  const token = ref(localStorage.getItem('token') || null)
  const user = ref(JSON.parse(localStorage.getItem('user') || 'null'))
  const loading = ref(false)
  const error = ref(null)

  // Getters
  const isLoggedIn = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

  // Actions
  const register = async (userData) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/register', userData)
      token.value = response.data.token
      user.value = response.data.user
      localStorage.setItem('token', token.value)
      localStorage.setItem('user', JSON.stringify(user.value))
      router.push({ name: 'dashboard' })
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Registration failed'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  const login = async (credentials) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/login', credentials)
      token.value = response.data.token
      user.value = response.data.user
      localStorage.setItem('token', token.value)
      localStorage.setItem('user', JSON.stringify(user.value))
      router.push({ name: 'dashboard' })
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    loading.value = true
    try {
      await api.post('/logout')
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      // Always clear local storage
      token.value = null
      user.value = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      router.push({ name: 'login' })
      loading.value = false
    }
  }

  const fetchCurrentUser = async () => {
    if (!token.value) return null
    
    loading.value = true
    try {
      const response = await api.get('/user')
      user.value = response.data.user
      localStorage.setItem('user', JSON.stringify(user.value))
      return user.value
    } catch (err) {
      error.value = 'Failed to fetch user information'
      token.value = null
      user.value = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      router.push({ name: 'login' })
      throw error.value
    } finally {
      loading.value = false
    }
  }

  return {
    user,
    token,
    loading,
    error,
    isLoggedIn,
    isAdmin,
    register,
    login,
    logout,
    fetchCurrentUser
  }
})