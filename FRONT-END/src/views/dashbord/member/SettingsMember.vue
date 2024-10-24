<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <SidebarMember ref="sidebar" :class="{ 'hidden': !isSidebarOpen, 'md:block': true }" />
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <HeaderMember :title="'Settings'" @toggle-sidebar="toggleSidebar" />

      <!-- Settings Form -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="container mx-auto px-6 py-8">
          <h3 class="text-gray-700 text-3xl font-medium">Member Settings</h3>
          
          <!-- Alert Section -->
          <div v-if="alertMessage" :class="`mt-4 p-4 rounded-md text-white ${alertType === 'success' ? 'bg-green-500' : 'bg-red-500'}`">
            {{ alertMessage }}
          </div>

          <div v-if="members.length > 0" class="mt-6">
            <div v-for="member in members" :key="member.id" class="mb-8 bg-white shadow overflow-hidden sm:rounded-lg">
              <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ member.name }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                  Member details and settings
                </p>
              </div>
              <div class="border-t border-gray-200">
                <dl>
                  <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                      <input v-model="member.name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </dd>
                  </div>
                  <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Email address</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                      <input v-model="member.email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </dd>
                  </div>
                  <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">New Password</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                      <input v-model="member.password" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </dd>
                  </div>
                  <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Confirm New Password</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                      <input v-model="member.password_confirmation" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </dd>
                  </div>
                </dl>
              </div>
              <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button @click="updateMember(member)" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  Save Changes
                </button>
              </div>
            </div>
          </div>
          <div v-else class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <p class="text-center text-gray-500">No members found.</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from '@/services/api';
import SidebarMember from '@/views/dashbord/member/SidebarMember.vue';
import HeaderMember from '@/views/dashbord/member/HeaderMember.vue';

const sidebar = ref(null);
const isSidebarOpen = ref(true);
const members = ref([]);
const alertMessage = ref(''); // Reactive variable for alert message
const alertType = ref(''); // Reactive variable for alert type ('success' or 'error')

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value; // Toggle the state for the sidebar
}

async function fetchMembers() {
  try {
    const response = await axios.get('/members');
    members.value = response.data.data.members.map(member => ({
      ...member,
      password: '',
      password_confirmation: ''
    }));
  } catch (error) {
    console.error('Error fetching members:', error);
  }
}

async function updateMember(member) {
  try {
    const data = {
      name: member.name,
      email: member.email
    };

    if (member.password) {
      data.password = member.password;
      data.password_confirmation = member.password_confirmation;
    }

    const response = await axios.put(`/members/${member.id}`, data);
    console.log('Member updated successfully:', response.data);

    // Reset password fields
    member.password = '';
    member.password_confirmation = '';

    // Set success alert message
    alertMessage.value = 'Member updated successfully!';
    alertType.value = 'success'; // Set the alert type to success
  } catch (error) {
    console.error('Error updating member:', error);
    // Set error alert message
    alertMessage.value = 'Error updating member. Please try again.';
    alertType.value = 'error'; // Set the alert type to error
  }
}

onMounted(() => {
  fetchMembers();
});
</script>
