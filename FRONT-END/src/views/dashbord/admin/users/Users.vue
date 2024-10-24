<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <Aside ref="sidebar" :class="{ 'hidden': !isSidebarOpen, 'md:block': true }" />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <Header :title="'Members'" @toggle-sidebar="toggleSidebar" />

      <!-- Main content area -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="container mx-auto px-6 py-8">
          <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
              <h2 class="text-2xl font-semibold mb-4">Member List</h2>
              <div v-if="loading" class="text-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
              </div>
              <div v-else-if="error" class="text-red-600 text-center py-4">
                {{ error }}
              </div>
              <div v-else>
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="member in members" :key="member.id">
                      <td class="px-6 py-4 whitespace-nowrap">{{ member.name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap">{{ member.email }}</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <img v-if="member.image" :src="`http://127.0.0.1:8000/storage/${member.image}`"
                          alt="Member image" class="h-10 w-10 rounded-full" />
                        <span v-else>No image</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button @click="editMember(member)"
                          class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                        <button @click="deleteMember(member.id)" class="text-red-600 hover:text-red-900">Delete</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Edit Member Modal -->
    <div v-if="showEditModal"
      class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
      <div class="bg-white p-8 rounded-lg shadow-xl w-96">
        <h3 class="text-xl font-semibold mb-4">Edit Member</h3>
        <form @submit.prevent="updateMember">
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" v-model="editingMember.name"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" v-model="editingMember.email"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
          <div class="flex justify-end">
            <button type="button" @click="showEditModal = false"
              class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</button>
            <button type="submit"
              class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from '@/services/api';
import Aside from '@/views/dashbord/admin/Aside.vue';
import Header from '@/views/dashbord/admin/Header.vue';

const sidebar = ref(null);
const isSidebarOpen = ref(true);
const members = ref([]);
const loading = ref(true);
const error = ref(null);
const showEditModal = ref(false);
const editingMember = ref({});

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
  if (sidebar.value) {
    sidebar.value.toggleSidebar();
  }
}

const fetchMembers = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/members');
    members.value = response.data.data.members;
  } catch (err) {
    error.value = 'Failed to fetch members';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const editMember = (member) => {
  editingMember.value = { ...member };
  showEditModal.value = true;
};

const updateMember = async () => {
  try {

    await axios.put(`/members/${editingMember.value.id}`, {
      name: editingMember.value.name,
      email: editingMember.value.email
    });

    // Update the members list with the new data
    const index = members.value.findIndex(m => m.id === editingMember.value.id);
    if (index !== -1) {
      members.value[index] = { ...editingMember.value };
    }

    // Close the modal
    showEditModal.value = false;
  } catch (err) {
    console.error('Failed to update member:', err);
    error.value = err.response?.data?.message || 'Failed to update member';
  }
};

const deleteMember = async (id) => {
  if (confirm('Are you sure you want to delete this member?')) {
    try {
      await axios.delete(`/members/${id}`);
      members.value = members.value.filter(m => m.id !== id);
    } catch (err) {
      console.error('Failed to delete member:', err);
      error.value = err.response?.data?.message || 'Failed to delete member';
    }
  }
};

onMounted(fetchMembers);
</script>

<style scoped>
/* Add any specific styles here */
</style>
