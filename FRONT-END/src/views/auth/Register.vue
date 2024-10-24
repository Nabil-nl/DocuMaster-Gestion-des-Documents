<template>
  <section>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <div class="w-full bg-slate-100 rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1 class="text-xl font-bold leading-tight tracking-tight text-[#171819] md:text-2xl" id="font">
            Create an account
          </h1>
          <form @submit.prevent="register" class="space-y-4 md:space-y-6">
            <div>
              <label for="name" class="block mb-2 text-sm text-gray-900" id="font">Name</label>
              <input v-model="name" type="text" name="name" id="name" class="bg-[#E7ECEF] border border-gray-300 text-[#171819] text-sm rounded-lg font-mono focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Name" required />
            </div>
            <div>
              <label for="email" class="block mb-2 text-sm text-[#171819]" id="font">Email</label>
              <input v-model="email" type="email" name="email" id="email" class="bg-[#E7ECEF] border border-gray-300 font-mono text-[#171819] text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="name@mail.com" required />
            </div>
            <div>
              <label for="password" class="block mb-2 text-sm text-[#171819]" id="font">Password</label>
              <input v-model="password" type="password" name="password" id="password" placeholder="Password" class="bg-[#E7ECEF] border font-mono border-gray-300 text-[#171819] text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required />
            </div>
            <div>
              <label for="confirm-password" class="block mb-2 text-sm text-[#171819]" id="font">Confirm Password</label>
              <input v-model="password_confirmation" type="password" name="confirm-password" id="confirm-password" placeholder="••••••••" class="bg-[#E7ECEF] border font-mono border-gray-300 text-[#171819] text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required />
            </div>
            <div>
              <label for="image" class="block mb-2 text-sm text-[#171819]" id="font">Profile Image (optional)</label>
              <input ref="image" type="file" name="image" id="image" accept="image/*" class="bg-[#E7ECEF] border font-mono border-gray-300 text-[#171819] text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" />
            </div>
            <button type="submit" class="w-full text-white bg-[#5ae4a8] focus:ring-4 rounded-lg text-sm px-5 py-2.5 text-center" id="font">Create an Account</button>
            <p class="text-sm font-light text-black">Already have an account? <router-link to="/login" class="text-primary-600 hover:underline text-[#f05d5e]">Sign in here</router-link></p>
          </form>
        </div>
      </div>
    </div>
    <Particles />
  </section>
</template>

<script>
import axios from '../../services/api';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
    };
  },
  methods: {
    async register() {
      try {
        const formData = new FormData();
        formData.append('name', this.name);
        formData.append('email', this.email);
        formData.append('password', this.password);
        formData.append('password_confirmation', this.password_confirmation);
        if (this.$refs.image.files[0]) {
          formData.append('image', this.$refs.image.files[0]);
        }

        await axios.post('/members', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        this.$router.push('/login');
      } catch (error) {
        console.error('Registration failed:', error.response.data);
      }
    },
  },
};
</script>

<style scoped>
/* Your styles */
</style>
