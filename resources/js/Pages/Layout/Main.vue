<script setup>
import Sidebar from "./Sidebar.vue";
</script>

<template>
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <Sidebar class="bg-base-200 w-64 p-4 fixed left-0 top-0 h-full"></Sidebar>
    <!-- Sidebar -->

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-base-100 ml-64"> <!-- Ensure margin-left to prevent overlap -->
      <slot></slot>
    </main>
  </div>
</template>

<style scoped>
/* Optional: Apply some margin and padding tweaks */
main {
  overflow-x: hidden; /* Prevent horizontal scroll */
}
</style>
