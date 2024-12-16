<template>
  <div>
    <h1>Group Chat</h1>

    <!-- Display messages -->
    <div id="chat-box" class="chat-box">
      <div v-for="message in chatMessages" :key="message.id" class="message">
        <strong>{{ message.username }}:</strong> {{ message.message }}
      </div>
    </div>

    <!-- Chat input form -->
    <form @submit.prevent="sendMessage" class="chat-form">
      <input v-model="username" type="text" placeholder="Your name" required />
      <input
        v-model="newMessage"
        type="text"
        placeholder="Type your message..."
        required
      />
      <button type="submit">Send</button>
    </form>
  </div>
</template>
  
  <script setup>
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import { ref, computed, onMounted } from "vue";

// Access the props from Inertia
const { props } = usePage();

// Use computed to safely access props
const projectID = computed(() => props.projectID || null);
const messages = computed(() => props.messages || []);

// Reactive state
const chatMessages = ref([...messages.value]);
const newMessage = ref("");
const username = ref("");

// Fetch new messages from the server
const fetchMessages = async () => {
  try {
    const response = await axios.get(`/chat/fetch-messages/${projectID.value}`);
    chatMessages.value = response.data; // Update messages with the latest
  } catch (error) {
    console.error("Failed to fetch messages:", error);
  }
};

// Send a new message
const sendMessage = async () => {
  if (!newMessage.value || !username.value) return;

  try {
    const response = await axios.post("/chat/send", {
      project_id: projectID.value,
      username: username.value,
      message: newMessage.value,
    });
    chatMessages.value.push(response.data); // Add the new message to the chat
    newMessage.value = ""; // Clear input
  } catch (error) {
    if (error.response) {
      console.log("Error data:", error.response.data);
      console.log("Error status:", error.response.status);
      console.log("Error headers:", error.response.headers);
    }
  }
};

// Periodically fetch new messages
onMounted(() => {
  // Initial fetch for messages
  fetchMessages();

  // Set interval to fetch new messages every 5 seconds
  setInterval(fetchMessages, 5000); // Fetch messages every 5 seconds
});
</script>
  
  <style scoped>
/* Add your styles here */
.chat-box {
  max-height: 400px;
  overflow-y: scroll;
  padding: 10px;
  border: 1px solid #ccc;
}
.message {
  margin-bottom: 10px;
}
.chat-form input {
  margin-right: 10px;
}
</style>
  