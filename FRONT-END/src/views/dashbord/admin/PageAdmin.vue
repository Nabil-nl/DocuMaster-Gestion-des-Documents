<template>
  <div class="all flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <Aside ref="sidebar" :class="{ 'hidden': !isSidebarOpen, 'md:block': true }" />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <Header :title="'Dashboard'" @toggle-sidebar="toggleSidebar" />

      <!-- News Ticker Notification -->
      <NewsTickerNotification 
        v-if="showNotification" 
        :message="notificationMessage" 
        @close="showNotification = false"
      />

      <!-- Main content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
          <transition-group name="fade" tag="div" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="stat in statistics" :key="stat.name" class="bg-white overflow-hidden shadow rounded-lg">
              <div class="p-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0 bg-[#3949AB] rounded-md p-3">
                    <component :is="stat.icon" class="h-6 w-6 text-white" />
                  </div>
                  <div class="ml-5 w-0 flex-1">
                    <dl>
                      <dt class="text-sm font-medium text-gray-500 truncate">
                        {{ stat.name }}
                      </dt>
                      <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900">
                          {{ stat.value }}
                        </div>
                      </dd>
                    </dl>
                  </div>
                </div>
              </div>
            </div>
          </transition-group>

          <!-- Loading indicator -->
          <div v-if="loading" class="text-center mt-4">
            <p class="text-gray-500">Loading dashboard data...</p>
          </div>

          <!-- Error message -->
          <div v-if="error" class="text-center mt-4">
            <p class="text-red-500">{{ error }}</p>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import axios from "@/services/api"; 
import Aside from '@/views/dashbord/admin/Aside.vue';
import Header from '@/views/dashbord/admin/Header.vue';
import { ref, onMounted, onUnmounted } from 'vue';
import { Users, Files, BookOpen } from 'lucide-vue-next'; 

const statistics = ref([]);
const loading = ref(true);
const error = ref(null);
const showNotification = ref(false);
const notificationMessage = ref('');

// Function to fetch statistics
const fetchDashboardData = async () => {
  try {
    const [membersResponse, documentsResponse, categoriesResponse] = await Promise.all([
      axios.get('/members'),
      axios.get('/documents'),
      axios.get('/categories')
    ]);

    // Log the responses to see what data you're working with
    console.log('Members Response:', membersResponse);
    console.log('Documents Response:', documentsResponse);
    console.log('Categories Response:', categoriesResponse);

    statistics.value = [
      {
        name: 'Total Members',
        value: membersResponse.data.data.members.length,  
        icon: Users
      },
      {
        name: 'Total Documents',
        value: documentsResponse.data.length, 
        icon: Files
      },
      {
        name: 'Total Categories',
        value: categoriesResponse.data.length, 
        icon: BookOpen
      }
    ];

    loading.value = false;
  } catch (err) {
    console.error('Error fetching dashboard data:', err);
    error.value = 'Failed to load dashboard data. Please try again later.';
    loading.value = false;
  }
};

// Fetch data initially
onMounted(fetchDashboardData);

// Set up polling to check for updates every 30 seconds
const pollInterval = setInterval(fetchDashboardData, 30000);

// Clean up interval on component unmount
onUnmounted(() => {
  clearInterval(pollInterval);
});
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
</style>
