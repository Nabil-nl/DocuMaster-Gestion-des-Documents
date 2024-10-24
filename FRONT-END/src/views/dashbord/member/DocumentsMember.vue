<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <SidebarMember ref="sidebar" :class="{ 'hidden': !isSidebarOpen, 'md:block': true }" />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <HeaderMember :title="'Documents'" @toggle-sidebar="toggleSidebar" />

      <!-- Main content area -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="container mx-auto px-4 py-8">
          <!-- Search and Filter -->
          <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <div class="relative flex-grow">
              <input
                type="text"
                v-model="searchTerm"
                placeholder="Search documents..."
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <div class="relative">
              <select
                v-model="selectedCategory"
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none"
              >
                <option value="">All Categories</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
            </div>
          </div>

          <!-- Add Document Button -->
          <button @click="openCreateForm"
            class="mb-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Add Document
          </button>

          <!-- Document Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="document in filteredDocuments" :key="document.id" class="bg-white rounded-lg shadow-md overflow-hidden">
              <div class="p-4">
                <h3 class="text-lg font-semibold mb-2">{{ document.title }}</h3>
                <p class="text-sm text-gray-600 mb-2">Category: {{ document.category.name }}</p>
                <p class="text-sm text-gray-600 mb-4">Type: {{ document.file_type }}</p>
                <div class="flex justify-between">
                  <button @click="openEditForm(document)"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-sm">
                    Edit
                  </button>
                  <button @click="deleteDocument(document.id)"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm">
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Create/Edit Form Modal -->
          <div v-if="showForm"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
              <h2 class="text-2xl font-bold mb-4">{{ isEditing ? 'Edit' : 'Create' }} Document</h2>
              <form @submit.prevent="submitForm">
                <div class="mb-4">
                  <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                  <input type="text" id="title" v-model="formData.title" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div class="mb-4">
                  <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                  <select id="category" v-model="formData.category_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}
                    </option>
                  </select>
                </div>
                <div class="mb-4">
                  <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                  <input type="file" id="file" @change="handleFileChange" :required="!isEditing"
                    class="mt-1 block w-full">
                </div>
                <div class="flex justify-end space-x-2">
                  <button type="button" @click="closeForm"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                  </button>
                  <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    {{ isEditing ? 'Update' : 'Create' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import axios from '@/services/api';
import SidebarMember from '@/views/dashbord/member/SidebarMember.vue';
import HeaderMember from '@/views/dashbord/member/HeaderMember.vue';

const sidebar = ref(null);
const isSidebarOpen = ref(true);
const documents = ref([]);
const categories = ref([]);
const showForm = ref(false);
const isEditing = ref(false);
const formData = ref({
  title: '',
  category_id: '',
  file: null
});

// Refs for search and filter
const searchTerm = ref('');
const selectedCategory = ref('');

// Computed property for filtered documents
const filteredDocuments = computed(() => {
  return documents.value.filter(doc => 
    (searchTerm.value === '' || doc.title.toLowerCase().includes(searchTerm.value.toLowerCase())) &&
    (selectedCategory.value === '' || doc.category.id === parseInt(selectedCategory.value))
  );
});

// Watch for changes in search term or selected category
watch([searchTerm, selectedCategory], () => {
  console.log('Search term:', searchTerm.value);
  console.log('Selected category:', selectedCategory.value);
  console.log('Filtered documents:', filteredDocuments.value);
});

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value; 
}

async function fetchDocuments() {
  try {
    const response = await axios.get('/documents');
    documents.value = response.data;
    console.log('Fetched documents:', documents.value);
  } catch (error) {
    console.error('Error fetching documents:', error);
  }
}

async function fetchCategories() {
  try {
    const response = await axios.get('/categories');
    categories.value = response.data;
    console.log('Fetched categories:', categories.value);
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
}

function openCreateForm() {
  isEditing.value = false;
  formData.value = { title: '', category_id: '', file: null };
  showForm.value = true;
}

function openEditForm(document) {
  isEditing.value = true;
  formData.value = { ...document, file: null }; // Reset the file for editing
  showForm.value = true;
}

function closeForm() {
  showForm.value = false;
  formData.value = { title: '', category_id: '', file: null };
}

function handleFileChange(event) {
  const file = event.target.files[0];
  if (file) {
    formData.value.file = file;
    console.log('Selected file:', file.name, file.type, file.size);
  } else {
    formData.value.file = null;
    console.error('No file selected');
  }
}

async function submitForm() {
  const formDataToSend = new FormData();
  formDataToSend.append('title', formData.value.title);
  formDataToSend.append('category_id', formData.value.category_id);

  if (formData.value.file instanceof File) {
    formDataToSend.append('file', formData.value.file, formData.value.file.name);
  } else {
    console.error('No file selected or invalid file');
    return;
  }

  console.log('Submitting form data:');
  for (const [key, value] of formDataToSend.entries()) {
    console.log(key, value);
  }

  try {
    let response;
    if (isEditing.value) {
      response = await axios.post(`/documents/${formData.value.id}?_method=PUT`, formDataToSend, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
    } else {
      response = await axios.post('/documents', formDataToSend, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
    }

    console.log('Server response:', response.data);
    await fetchDocuments();
    closeForm();
  } catch (error) {
    console.error('Error submitting form:', error.response ? error.response.data : error.message);
  }
}

async function deleteDocument(id) {
  if (confirm('Are you sure you want to delete this document?')) {
    try {
      await axios.delete(`/documents/${id}`);
      await fetchDocuments();
    } catch (error) {
      console.error('Error deleting document:', error);
    }
  }
}

onMounted(() => {
  fetchDocuments();
  fetchCategories();
});
</script>

<style scoped>
/* Add any additional styles here */
</style>