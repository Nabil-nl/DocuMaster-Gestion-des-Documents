<template>
  <header class="headerr bg-white shadow-md">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
      <button @click="toggleSidebar" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 md:hidden">
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
        {{ title }}
      </h2>
    </div>
  </header>
</template>

<script>
export default {
  name: 'Header',
  props: {
    title: String
  },
  methods: {
    toggleSidebar() {
      this.$emit('toggle-sidebar');
    }
  }
}
</script>

<style scoped>
/* Add any additional styles here */
</style>