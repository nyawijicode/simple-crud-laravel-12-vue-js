import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

// Public Pages
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'

// Protected Pages
import Dashboard from '../views/Dashboard.vue'
import UsersList from '../views/UsersList.vue'
import UserCreate from '../views/UserCreate.vue'
import UserEdit from '../views/UserEdit.vue'
import Profile from '../views/Profile.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/login'
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: { requiresAuth: false }
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
      meta: { requiresAuth: false }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: Dashboard,
      meta: { requiresAuth: true }
    },
    {
      path: '/profile',
      name: 'profile',
      component: Profile,
      meta: { requiresAuth: true }
    },
    {
      path: '/users',
      name: 'users',
      component: UsersList,
      meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
      path: '/users/create',
      name: 'users-create',
      component: UserCreate,
      meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
      path: '/users/:id/edit',
      name: 'users-edit',
      component: UserEdit,
      meta: { requiresAuth: true, requiresAdmin: true }
    }
  ]
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const isLoggedIn = authStore.isLoggedIn
  const requiresAuth = to.meta.requiresAuth
  const requiresAdmin = to.meta.requiresAdmin
  const user = authStore.user

  // Check for auth requirements
  if (requiresAuth && !isLoggedIn) {
    next({ name: 'login' })
  } else if (requiresAdmin && user && user.role !== 'admin') {
    next({ name: 'dashboard' })
  } else if (!requiresAuth && isLoggedIn) {
    next({ name: 'dashboard' })
  } else {
    next()
  }
})

export default router