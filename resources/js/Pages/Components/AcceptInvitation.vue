<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">Project Invitations</h2>

    <div v-for="invitation in invitations" :key="invitation.id" class="mb-3">
      <div class="invitation-card p-4 border rounded-md">
        <p>Email: {{ invitation.email }}</p>
        <p>Status: {{ invitation.status }}</p>

        <!-- Button to accept invitation -->
        <button
          v-if="invitation.status === 'pending'"
          @click="acceptInvitation(invitation.project_id, invitation.id)"
          class="btn btn-primary"
        >
          Accept Invitation
        </button>
      </div>
    </div>
  </div>
</template>
  
  <script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const props = defineProps({
  projectId: Number, // Receive projectId as a prop from the parent component
});

const invitations = ref([]); // Empty array to hold the invitations

// Fetch invitations when the component mounts
onMounted(async () => {
  try {
    const response = await axios.get(
      `/api/project/${props.projectId}/invitations`
    );
    invitations.value = response.data; // Set the invitations data from API
  } catch (error) {
    console.error("Error fetching invitations:", error);
  }
});

// Function to accept the invitation
const acceptInvitation = async (projectId, invitationId) => {
  try {
    const response = await axios.post(
      `/project/${projectId}/invitation/${invitationId}/accept`
    );
    alert(response.data.message); // Display success message
    // Update the invitation status locally
    const invitation = invitations.value.find(
      (invite) => invite.id === invitationId
    );
    if (invitation) {
      invitation.status = "accepted"; // Update the status in the frontend
    }
  } catch (error) {
    console.error("Error accepting invitation:", error);
    alert("There was an error accepting the invitation.");
  }
};
</script>
  
  <style scoped>
/* Add some styles if needed */
</style>
  