<template>
    <div class="flex h-screen bg-gray-100">
      <!-- Sidebar -->
      <SidebarMember ref="sidebar" :class="{ 'hidden': !isSidebarOpen, 'md:block': true }" />
  
      <!-- Main Content -->
      <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <HeaderMember :title="'Dashboard'" @toggle-sidebar="toggleSidebar" />
  
        <!-- Main content area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
          <div class="container mx-auto px-6 py-8">
            <router-view />
          </div>
        </main>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import SidebarMember from '@/views/dashbord/member/SidebarMember.vue';
  import HeaderMember from '@/views/dashbord/member/HeaderMember.vue';
  
  const sidebar = ref(null);
  const isSidebarOpen = ref(true);
  
  function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value; 
}
  </script>
  
  <style scoped>
  /* Add any additional styles here */
  </style>
  