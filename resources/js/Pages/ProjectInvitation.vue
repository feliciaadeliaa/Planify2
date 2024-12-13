<template>
    <div>
      <h1>Manage Invitations for {{ projectTitle }}</h1>
  
      <!-- Add Invitation Section -->
      <form @submit.prevent="sendInvite">
        <input v-model="newUserId" type="text" placeholder="Enter User ID" required />
        <button type="submit">Send Invitation</button>
      </form>
  
      <!-- Review Invitations Section -->
      <h3>Invitations</h3>
      <ul>
        <li v-for="invitation in invitations" :key="invitation.id">
          User ID: {{ invitation.user_id }} - Status: {{ invitation.status }}
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive, onMounted } from "vue";
  import { usePage } from "@inertiajs/vue3";
  import axios from "axios";
  
  // Props from Inertia
  const { projectId, projectTitle } = usePage().props;
  
  // State variables
  const newUserId = ref("");
  const invitations = ref([]);
  
  // Fetch Invitations
  const fetchInvitations = async () => {
    try {
      const response = await axios.get(`/api/project/${projectId}/invitations`);
      invitations.value = response.data;
    } catch (error) {
      console.error("Error fetching invitations:", error);
    }
  };
  
  // Send Invitation
  const sendInvite = async () => {
    try {
      await axios.post(`/api/project/${projectId}/invite`, { user_id: usePage().props.auth.id });
      newUserId.value = ""; // Clear input
      fetchInvitations(); // Refresh invitations
      alert("Invitation sent!");
    } catch (error) {
      console.error("Error sending invitation:", error);
      alert("Failed to send invitation.");
    }
  };
  
  // Load invitations on mount
  onMounted(fetchInvitations);
  </script>
  