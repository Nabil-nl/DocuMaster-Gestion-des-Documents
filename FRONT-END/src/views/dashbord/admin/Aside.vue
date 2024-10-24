<template>
    <aside :class="[
      'bg-[#1b2e35] text-white flex flex-col items-center py-4 transition-all duration-300',
      isOpen ? 'w-64' : 'w-16'
    ]">
      <div class="flex flex-col items-center w-full">
        <h1 :class="['text-2xl text-[#5ae4a8] mb-4', !isOpen && 'sr-only']">ADMIN</h1>
        <div :class="['flex justify-center items-center rounded-full mb-4', isOpen ? 'w-[100px] h-[100px]' : 'w-[40px] h-[40px]']">
          <img src="@/assets/images/admin.png" alt="Profile Image" class="w-full h-full object-cover rounded-full">
        </div>
      </div>
      <nav class="w-full mt-8">
        <router-link
          v-for="item in menuItems"
          :key="item.name"
          :to="item.href"
          :class="[
            'block py-3 px-4 rounded transition duration-200 hover:bg-[#5ae4a8] hover:text-[#0d0b08] mb-2',
            !isOpen && 'flex justify-center'
          ]"
        >
          <component :is="item.icon" class="inline-block w-5 h-5" :class="{ 'mr-2': isOpen }" />
          <span :class="{ 'sr-only': !isOpen }">{{ item.name }}</span>
        </router-link>
      </nav>
      <div class="mt-auto mb-4 w-full px-4">
      <button @click="logout" class="logout-button group w-full overflow-hidden rounded-lg bg-gradient-to-r from-[#5ae4a8] to-[#5ae4a8] p-0.5 transition-all duration-300 ease-in-out hover:bg-gradient-to-l">
        <div class="flex items-center justify-center rounded-md bg-[#1b2e35] px-4 py-2.5 transition-all duration-300 group-hover:bg-opacity-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white transition-transform duration-300 ease-in-out group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span :class="['ml-2 font-medium text-white transition-all duration-300', !isOpen && 'hidden']">
            Logout
          </span>
        </div>
      </button>
    </div>
    </aside>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import { Home, ChartNoAxesGantt, Files, Users } from 'lucide-vue-next';
  
  const router = useRouter();
  const isOpen = ref(true);
  
  function logout() {
    router.push('/logout');
  }
  
  const menuItems = [
    { name: 'Accueil', href: '/dashboard/pageadmin', icon: Home },
    { name: 'Categories', href: '/dashboard/categories', icon: ChartNoAxesGantt },
    { name: 'Documents', href: '/dashboard/documents', icon: Files },
    { name: 'Users', href: '/dashboard/users', icon: Users },
  ];
  
  // Function to toggle sidebar
  function toggleSidebar() {
    isOpen.value = !isOpen.value;
  }
  
  defineExpose({ toggleSidebar });
  </script>
  
<style scoped>
.logout-button {
  transition: all 0.3s ease;
}

.logout-button:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(113, 253, 193, 0.42);
}

.logout-button:active {
  transform: translateY(-1px);
  box-shadow: 0 5px 10px rgba(113, 253, 193, 0.42);
}
</style>