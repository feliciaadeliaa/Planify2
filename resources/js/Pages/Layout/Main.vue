<script setup>
import Sidebar from "./Sidebar.vue";
</script>


<template>
  <div class="container-body flex">
    <!-- Sidebar -->
    <Sidebar></Sidebar>
    <!-- Sidebar -->

    <!-- Content -->
     <div class="container-body px-14 pt-8 w-full">
         <slot></slot>
     </div>
  </div>
</template>