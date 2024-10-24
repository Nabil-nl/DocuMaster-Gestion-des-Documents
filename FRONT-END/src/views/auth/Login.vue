<template>
    <section class="">
      <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0  ">
        <div class="login-register w-full rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:border-gray-700 bg-slate-100 ">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-[#171819] md:text-2xl" id="font">
              Sign in to your account
            </h1>
            <form @submit.prevent="login" class="space-y-4 md:space-y-6">
              <div>
                <label for="email" class="block mb-2 text-sm font-medium text-[#171819]" id="font">Your email</label>
                <input v-model="email" type="email" name="email" id="email"
                  class="bg-[#dee2e6] border border-gray-300 text-[#171819] rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:border-gray-600 dark:placeholder-[#afb7bb] dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="name@mail.com" required />
              </div>
              <div>
                <label for="password" class="block mb-2 text-sm font-medium text-[#171819]" id="font">Password</label>
                <input v-model="password" type="password" name="password" id="password" placeholder="••••••••"
                  class="bg-[#dee2e6] border border-gray-300 text-[#171819] rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:border-gray-600 dark:placeholder-[#afb7bb] dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  required />
              </div>
              <div class="flex items-center justify-between">
                <a href="#" class="text-sm font-medium text-[#5ae4a8] hover:underline dark:text-primary-500">Forgot
                  password?</a>
              </div>
              <button type="submit"
                class="w-full text-white bg-[#5ae4a8] hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                id="font">Sign in</button>
              <p class="text-sm font-light text-black">
                Don’t have an account? <router-link to="/register"
                  class="font-medium text-[#5ae4a8] hover:underline dark:text-primary-500">Sign up</router-link>
              </p>
            </form>
          </div>
        </div>
      </div>
    </section>
  

  </template>
  
  <script>
import axios, { setAuthToken } from "../../services/api";

  export default {
    name: 'Login',

    data() {
      return {
        email: '',
        password: '',
      };
    },
    methods: {
    async login() {
  try {
    const response = await axios.post("/login", {
      email: this.email,
      password: this.password,
    });
    const { token, roles, user } = response.data; 
    const role = roles[0];
    const memberId = user.member_id; 

    localStorage.setItem('authToken', token);
    localStorage.setItem('memberID', memberId); 
    setAuthToken(token);

    if (role === 'admin') {
      this.$router.push('/dashboard/pageadmin');
    } 
    else if (role === 'member') {
      this.$router.push('/Members/member');
    }        
    else {
      console.warn('Unknown user role:', role);
    }

  } catch (error) {
    console.error("Login failed:", error.response.data.message || error.message);
    alert("Login failed. Please check your credentials.");
  }
},

  },


  };
  </script>
  
  <style scoped>

  </style>