<template>
  <div class="min-h-screen bg-white text-gray-800">
    <header class="sticky top-0 z-10 bg-white shadow-md">
      <nav class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="text-3xl font-extrabold text-gray-800">
            Docu<span class="text-[#5ae4a8]">Master</span>
          </div>
          <div class="hidden md:flex items-center space-x-8">
            <a href="#" class="text-gray-600 hover:text-[#5ae4a8] transition duration-300">Home</a>
            <a href="#" class="text-gray-600 hover:text-[#5ae4a8] transition duration-300">Documents</a>
            <a href="#" class="text-gray-600 hover:text-[#5ae4a8] transition duration-300">Categories</a>
            <a href="#" class="text-gray-600 hover:text-[#5ae4a8] transition duration-300">Contact</a>
          </div>
          <div class="hidden md:flex items-center space-x-4">
            <router-link to="/login"
              class="px-4 py-2 text-sm font-medium text-white bg-[#5ae4a8] rounded-md hover:bg-[#4cd399] focus:outline-none focus:ring-2 focus:ring-[#5ae4a8] focus:ring-offset-2 transition duration-300">
              Sign In
            </router-link>
            <router-link to="/register"
              class="px-4 py-2 text-sm font-medium text-[#5ae4a8] bg-transparent border border-[#5ae4a8] rounded-md hover:bg-[#5ae4a8] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#5ae4a8] focus:ring-offset-2 transition duration-300">
              Sign Up
            </router-link>
          </div>
          <div class="md:hidden">
            <button @click="toggleMenu" class="text-gray-800 focus:outline-none">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </nav>
      <!-- Mobile menu -->
      <div v-if="isMenuOpen" class="md:hidden bg-white">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <a href="#"
            class="block px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-100 hover:text-[#5ae4a8]">Home</a>
          <a href="#"
            class="block px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-100 hover:text-[#5ae4a8]">Documents</a>
          <a href="#"
            class="block px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-100 hover:text-[#5ae4a8]">Categories</a>
          <a href="#"
            class="block px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-100 hover:text-[#5ae4a8]">Contact</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
          <div class="flex items-center px-5">
           <router-link to="/login"
              class="flex-shrink-0 w-full px-4 py-2 text-sm font-medium text-white bg-[#5ae4a8] rounded-md hover:bg-[#4cd399] focus:outline-none focus:ring-2 focus:ring-[#5ae4a8] focus:ring-offset-2 transition duration-300">
              Sign In
            </router-link>
          </div>
          <div class="mt-3 px-2">
            <router-link to="/register"
              class="w-full px-4 py-2 text-sm font-medium text-[#5ae4a8] bg-transparent border border-[#5ae4a8] rounded-md hover:bg-[#5ae4a8] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#5ae4a8] focus:ring-offset-2 transition duration-300">
              Sign Up
            </router-link>
          </div>
        </div>
      </div>
    </header>

    <main class="relative z-0 overflow-hidden">
      <div class="container mx-auto px-6 flex relative py-16">
        <div class="sm:w-2/3 lg:w-2/5 flex flex-col relative z-20">
          <span class="w-20 h-2 bg-[#5ae4a8] mb-12"></span>
          <h1 class="text-6xl sm:text-7xl font-extrabold text-gray-900 leading-tight mb-6">
            Docu<span class="text-[#5ae4a8]">Master</span>
          </h1>
          <p class="text-sm sm:text-base text-gray-600 mb-8">
            DocuMaster is a web application designed for efficient document
            management. It enables users to easily upload, organize, and share
            documents while facilitating the management of document categories.
            The platform aims to enhance collaboration and ensure secure
            handling of files, streamlining the document workflow for enhanced
            productivity.
          </p>

          <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="#"
              class="inline-block py-3 px-6 rounded-lg bg-[#5ae4a8] text-white font-semibold hover:bg-[#4cd399] transition duration-300 transform hover:scale-105">
              Get started
            </a>
            <a href="#"
              class="inline-block py-3 px-6 rounded-lg bg-transparent border-2 border-[#5ae4a8] text-[#5ae4a8] font-semibold hover:bg-[#5ae4a8] hover:text-white transition duration-300 transform hover:scale-105">
              Read more
            </a>
          </div>
        </div>
        <div class="hidden sm:block sm:w-1/3 lg:w-3/5 relative">
          <img src="@/assets/images/doc.jpg"
            class="max-w-xs md:max-w-sm m-auto rounded-lg shadow-xl transform hover:scale-105 transition duration-300"
            alt="DocuMaster illustration" />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from "vue";

const isMenuOpen = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};
</script>

<style scoped>

</style>
