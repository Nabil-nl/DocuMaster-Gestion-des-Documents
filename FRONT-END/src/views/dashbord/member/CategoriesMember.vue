<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <SidebarMember ref="sidebar" :class="{ 'hidden': !isSidebarOpen, 'md:block': true }" />
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <HeaderMember :title="'Categories'" @toggle-sidebar="toggleSidebar" />

      <!-- Main content area -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <!-- Create Category Button -->
          <button @click="openCreateModal" class="mb-6 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 ease-in-out">
            Create New Category
          </button>

          <!-- Categories list -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="category in categories" :key="category.id" class="bg-white p-6 rounded-lg shadow-md">
              <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ category.name }}</h3>
              <p class="text-gray-600 mb-4">{{ category.description }}</p>
              <div class="flex justify-end space-x-2">
                <button @click="openEditModal(category)" class="text-indigo-600 hover:text-indigo-800">
                  <PencilIcon class="h-5 w-5" />
                </button>
                <button @click="deleteCategory(category.id)" class="text-red-600 hover:text-red-800">
                  <TrashIcon class="h-5 w-5" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Create Category Modal -->
    <div v-if="isCreateModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Create New Category</h2>
        <form @submit.prevent="createCategory">
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input v-model="newCategory.name" id="name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
          <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea v-model="newCategory.description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
          </div>
          <div class="flex justify-end space-x-2">
            <button type="button" @click="closeCreateModal" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Cancel
            </button>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Create
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Category Modal -->
    <div v-if="isEditModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Edit Category</h2>
        <form @submit.prevent="updateCategory">
          <div class="mb-4">
            <label for="edit-name" class="block text-sm font-medium text-gray-700">Name</label>
            <input v-model="editingCategory.name" id="edit-name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
          <div class="mb-4">
            <label for="edit-description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea v-model="editingCategory.description" id="edit-description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
          </div>
          <div class="flex justify-end space-x-2">
            <button type="button" @click="closeEditModal" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Cancel
            </button>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Update
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from '@/services/api';
import SidebarMember from '@/views/dashbord/member/SidebarMember.vue';
import HeaderMember from '@/views/dashbord/member/HeaderMember.vue';
import { PencilIcon, TrashIcon } from '@heroicons/vue/outline';

const sidebar = ref(null);
const isSidebarOpen = ref(true);
const categories = ref([]);
const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);

const newCategory = ref({ name: '', description: '' });
const editingCategory = ref({ id: null, name: '', description: '' });

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value; // Toggle the state for the sidebar
}

async function fetchCategories() {
  try {
    const response = await axios.get('/categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Failed to fetch categories:', error);
  }
}

function openCreateModal() {
  isCreateModalOpen.value = true;
}

function closeCreateModal() {
  isCreateModalOpen.value = false;
  newCategory.value = { name: '', description: '' };
}

async function createCategory() {
  try {
    await axios.post('/categories', newCategory.value);
    await fetchCategories();
    closeCreateModal();
  } catch (error) {
    console.error('Failed to create category:', error);
  }
}

function openEditModal(category) {
  editingCategory.value = { ...category };
  isEditModalOpen.value = true;
}

function closeEditModal() {
  isEditModalOpen.value = false;
  editingCategory.value = { id: null, name: '', description: '' };
}

async function updateCategory() {
  try {
    await axios.put(`/categories/${editingCategory.value.id}`, editingCategory.value);
    await fetchCategories();
    closeEditModal();
  } catch (error) {
    console.error('Failed to update category:', error);
  }
}

async function deleteCategory(id) {
  if (confirm('Are you sure you want to delete this category?')) {
    try {
      await axios.delete(`/categories/${id}`);
      await fetchCategories();
    } catch (error) {
      console.error('Failed to delete category:', error);
    }
  }
}

onMounted(fetchCategories);
</script>