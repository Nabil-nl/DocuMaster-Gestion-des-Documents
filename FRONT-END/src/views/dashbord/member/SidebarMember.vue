<template>
  <aside :class="[
    'bg-[#181c2f] text-white flex flex-col items-center py-4 transition-all duration-300 shadow-lg',
    isOpen ? 'w-64' : 'w-16'
  ]">
    <div class="flex flex-col items-center w-full">
      <h1 :class="['text-2xl text-[#5ae4a8] mb-4', !isOpen && 'sr-only']">{{ member.name || 'test' }}</h1>
      <div :class="['flex justify-center items-center rounded-full mb-4', isOpen ? 'w-[100px] h-[100px]' : 'w-[40px] h-[40px]']">
        <img :src="`http://127.0.0.1:8000/storage/${member.image}`" alt="Profile Image" class="w-full h-full object-cover rounded-full shadow-md border-2 border-[#5ae4a8]">
      </div>
    </div>

    <nav class="w-full mt-8">
      <router-link
        v-for="item in menuItems"
        :key="item.name"
        :to="item.href"
        :class="[
          'block py-3 px-4 rounded transition duration-200 hover:bg-indigo-600 hover:text-[#0d0b08] mb-2',
          !isOpen && 'flex justify-center'
        ]"
      >
        <component :is="item.icon" class="inline-block w-5 h-5" :class="{ 'mr-2': isOpen }" />
        <span :class="{ 'sr-only': !isOpen }">{{ item.name }}</span>
      </router-link>
    </nav>

    <div class="mt-auto mb-4 w-full px-4">
      <button @click="logout" class="logout-button group w-full overflow-hidden rounded-lg bg-gradient-to-r from-[#5ae4a8] to-[#3cb1a2] p-0.5 transition-all duration-300 ease-in-out hover:bg-gradient-to-l">
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
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from '@/services/api'; // Ensure the path is correct
import { Home, ChartNoAxesGantt, Files, Settings } from 'lucide-vue-next';

const router = useRouter();
const isOpen = ref(true);
const member = ref({}); // Initialize member data

async function fetchMemberProfile() {
  try {
    const response = await axios.get('/members'); // Adjust the endpoint as needed
    member.value = response.data.data.members[0]; // Assuming you're fetching a list
  } catch (error) {
    console.error('Failed to fetch member profile:', error.response.data);
  }
}

function toggleSidebar() {
  isOpen.value = !isOpen.value; // Toggle the sidebar state
}

function logout() {
  // Handle logout logic (e.g., API call, clear session)
  router.push('/logout');
}

// Menu items for the sidebar
const menuItems = [
  { name: 'Accueil', href: '/Members/member', icon: Home },
  { name: 'Categories', href: '/Members/categoriesMember', icon: ChartNoAxesGantt },
  { name: 'Documents', href: '/Members/documentsMember', icon: Files },
  { name: 'Settings', href: '/Members/settingsMember', icon: Settings },
];

// Fetch member profile when the component is mounted
onMounted(fetchMemberProfile);
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
