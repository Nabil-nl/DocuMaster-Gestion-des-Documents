<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <Aside ref="sidebar" :class="{ 'hidden': !isSidebarOpen, 'md:block': true }" />
  
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <Header :title="'Documents'" @toggle-sidebar="toggleSidebar" />
  
      <!-- Main content area -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="container mx-auto px-6 py-8">
          <!-- Document List -->
          <div class="mb-4">
            <h2 class="text-2xl font-bold mb-4">Documents</h2>
            <div v-if="documents.length === 0" class="text-gray-500">No documents available.</div>
            <ul>
              <li v-for="document in documents" :key="document.id" class="bg-white shadow rounded-lg p-4 mb-4 flex justify-between items-center">
                <div>
                  <h3 class="text-lg font-semibold">{{ document.title }}</h3>
                  <p class="text-sm text-gray-600">Category: {{ document.category.name }}</p>
                </div>
                <button @click="deleteDocument(document.id)" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded">
                  Delete
                </button>
              </li>
            </ul>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Aside from '@/views/dashbord/admin/Aside.vue';
import Header from '@/views/dashbord/admin/Header.vue';
import axios from '@/services/api';

const sidebar = ref(null);
const isSidebarOpen = ref(true);
const documents = ref([]);

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
  if (sidebar.value) {
    sidebar.value.toggleSidebar();
  }
}

async function fetchDocuments() {
  try {
    const response = await axios.get('/documents');
    documents.value = response.data;
  } catch (error) {
    console.error('Error fetching documents:', error);
  }
}

async function deleteDocument(id) {
  if (confirm('Are you sure you want to delete this document?')) {
    try {
      await axios.delete(`/documents/${id}`);
      await fetchDocuments(); // Refresh the document list
      alert('Document deleted successfully!');
    } catch (error) {
      console.error('Error deleting document:', error);
    }
  }
}

onMounted(() => {
  fetchDocuments();
});
</script>

<style scoped>
/* Add any additional styles here */
</style>
