<script setup>
import axios from "axios";
import { ref } from "vue";
import { route } from "ziggy-js";

const email = ref("");
const password = ref("");
const name = ref("");
const errorMessage = ref("");
const isLoading = ref(false);

const createUser = async () => {
  try {
    isLoading.value = true;
    const response = await axios.post(route("register.create"), {
      name: name.value,
      email: email.value,
      password: password.value,
    });
    window.location.href = response.data.redirect;
  } catch (error) {
    console.log("error message : " + error);
  }
};
</script>

<template>
  <section class="bg-white dark:bg-gray-900">
    <div
      class="container flex items-center justify-center min-h-screen px-6 mx-auto"
    >
      <form @submit.prevent="createUser" class="w-full max-w-md">
        <div
          v-if="$page.props.flash.message"
          role="alert"
          class="alert alert-error mb-5"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 shrink-0 stroke-current"
            fill="none"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <span>{{ $page.props.flash.message }}</span>
        </div>

        <img
          class="w-auto h-7 sm:h-8"
          src="https://merakiui.com/images/logo.svg"
          alt=""
        />

        <h1
          class="mt-3 text-2xl font-semibold text-gray-800 capitalize sm:text-3xl dark:text-white"
        >
          Create an Account
        </h1>
        <div
          role="alert"
          class="alert alert-error my-3 text-white"
          v-if="errorMessage"
        >
          <span>{{ errorMessage }}</span>
        </div>

        <div class="relative flex items-center mt-8">
          <span class="absolute">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
              />
            </svg>
          </span>

          <input
            type="text"
            class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
            placeholder="Full Name"
            name="name"
            v-model="name"
          />
        </div>

        <div class="relative flex items-center mt-4">
          <span class="absolute">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
              />
            </svg>
          </span>

          <input
            type="email"
            class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
            placeholder="Email address"
            name="email"
            v-model="email"
          />
        </div>

        <div class="relative flex items-center mt-4">
          <span class="absolute">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
              />
            </svg>
          </span>

          <input
            type="password"
            class="block w-full px-10 py-3 text-gray-700 bg-white border rounded-lg dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
            placeholder="Password"
            name="password"
            v-model="password"
          />
        </div>

        <div class="mt-6">
          <button
            :disabled="isLoading"
            class="w-full px-6 py-3 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50"
          >
            <span v-if="isLoading">Loading...</span>
            <span v-else>Sign Up</span>
          </button>

          <div class="mt-6 text-center">
            <a
              :href="route('login')"
              class="text-sm text-blue-500 hover:underline dark:text-blue-400"
            >
              Already have an account yet? Login
            </a>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>