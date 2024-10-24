<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <div class="text-center">
        <transition name="fade" mode="out-in">
          <div v-if="!logoutComplete" key="logging-out" class="text-2xl font-semibold text-gray-700">
            Logging out...
          </div>
          <div v-else key="logout-complete" class="text-2xl font-semibold text-[#5ae4a8]">
            Logout Successful!
          </div>
        </transition>
        <transition name="scale">
          <svg v-if="logoutComplete" class="h-16 w-16 text-[#5ae4a8] mx-auto mt-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </transition>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRouter } from 'vue-router'
  import axios from '../../services/api'
  
  const router = useRouter()
  const logoutComplete = ref(false)
  
  const logout = async () => {
    try {
      await axios.post('/logout')
      localStorage.removeItem('authToken')
      logoutComplete.value = true
      setTimeout(() => {
        router.push('/login')
      }, 1500) // Redirect after  seconds
    } catch (error) {
      console.error('Logout failed:', error)
      router.push('/login')
    }
  }
  
  onMounted(logout)
  </script>
  
  <style scoped>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s ease;
  }
  
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  
  .scale-enter-active {
    transition: all 0.5s ease;
  }
  
  .scale-enter-from {
    opacity: 0;
    transform: scale(0.5);
  }
  </style>