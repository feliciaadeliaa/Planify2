<script setup>
import Sidebar from "./Sidebar.vue";
</script>

<template>
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <Sidebar class="w-64 bg-gray-800 p-4"></Sidebar>
    <!-- Sidebar -->

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-100 dark:bg-gray-900">
      <slot></slot>
    </main>
  </div>
</template>
