<script setup>
import axios from "axios";
import Main from "./Layout/Main.vue";
import { defineProps, onMounted, ref, watch, computed, nextTick } from "vue";

import Draggable from "vuedraggable";
import { usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
});

const isFormVisible = ref(false);
const toggleForm = () => {
  isFormVisible.value = !isFormVisible.value;
  if (!isFormVisible.value) {
    cardTitle.value = "";
  }
};

const isAddTaskFormVisible = ref(false);
const toggleAddTaskForm = () => {
  isAddTaskFormVisible.value = !isAddTaskFormVisible.value;
  if (!isAddTaskFormVisible.value) {
    cardTitle.value = "";
  }
};

const visibleForms = ref({});
const toggleColumnFormVisibility = (columnId) => {
  visibleForms.value[columnId] = !visibleForms.value[columnId];
};

const isColumnFormVisible = (columnId) => {
  return visibleForms.value[columnId] || false;
};

const project_id = ref("");
const column_title = ref("");

const addColumn = async () => {
  try {
    const response = await axios.post("/api/column/store", {
      project_id: project_id.value,
      column_title: column_title.value,
    });
    toast("Success", {
      theme: "dark",
      type: "success",
      pauseOnFocusLoss: false,
      autoClose: 1000,
      hideProgressBar: true,
      dangerouslyHTMLString: true,
    });
    toggleForm()
    window.location.reload();
  } catch (error) {
    console.log("the error = ", error);
  }
};

const column_id = ref(null);
const card_name = ref("");
const addCard = async (columnId) => {
  try {
    const response = axios.post("/api/card/store", {
      column_id: columnId,
      card_name: card_name.value,
    });
    window.location.reload();
    console.log("success");
  } catch (error) {
    console.log("error = ", error);
  }
};

const deleteCard = async (CardId) => {
  try {
    const response = await axios.delete(`/api/card/delete/${CardId}`);
    toast("Success", {
      theme: "dark",
      type: "success",
      pauseOnFocusLoss: false,
      autoClose: 1000,
      hideProgressBar: true,
      dangerouslyHTMLString: true,
    });
  } catch (error) {
    console.log("error = ", error);
  }
};

const deleteColumn = async (id) => {
  try {
    const response = await axios.delete(`/api/column/delete/${id}`);
    toast("Column Deleted !", {
      theme: "dark",
      type: "success",
      pauseOnFocusLoss: false,
      autoClose: 1000,
      hideProgressBar: true,
      dangerouslyHTMLString: true,
    });
  } catch (error) {
    console.log("error = ", error);
  }
};

// group chat
const projectID = props.data.id;
const messages = computed(() => props.messages || []);

const chatMessages = ref([...messages.value]);
const newMessage = ref("");
const username = ref("");

const fetchMessages = async () => {
  try {
    const response = await axios.get(`/chat/fetch-messages/${projectID}`);
    chatMessages.value = response.data; // Update messages with the latest
  } catch (error) {
    console.error("Failed to fetch messages:", error);
  }
};

const sendMessage = async () => {
  try {
    const response = await axios.post("/chat/send", {
      project_id: projectID,
      username: usePage().props.auth.name,
      message: newMessage.value,
    });
    chatMessages.value.push(response.data); // Add the new message to the chat
    newMessage.value = ""; // Clear input
    scrollToBottom();
  } catch (error) {
    if (error.response) {
      console.log("Error data:", error.response.data);
      console.log("Error status:", error.response.status);
      console.log("Error headers:", error.response.headers);
    }
  }
};

function scrollToBottom() {
  nextTick(() => {
    const container = document.querySelector(".flex-1.overflow-y-auto");
    if (container) {
      container.scrollTop = container.scrollHeight;
    }
  });
}

const users = ref([]);
const fetchUsers = async () => {
  try {
    const response = await axios.get(route('fetchUsers'));
    users.value = response.data;
  } catch (error) {
    console.log(error);
  }
};

// kanban board
const onDragChange = async () => {
  const updates = [];

  // Loop through each column and gather updates
  props.data.columns.forEach((column) => {
    column.cards.forEach((card, index) => {
      updates.push({
        id: card.id,
        column_id: column.id,
        position: index + 1,
      });
    });
  });

  // Send the updated positions to the backend
  try {
    await axios.post("/api/update-card-positions", { updates });
    console.log("Positions updated successfully!");
  } catch (error) {
    console.error("Failed to update positions!", error);
  }
};

watch(
  () => props.data.id,
  (newId) => {
    project_id.value = newId;
  },
  { immediate: true }
);

onMounted(() => {
  fetchMessages();
  fetchUsers();
  setInterval(fetchMessages, 5000); // Fetch messages every 5 seconds
});
</script>

<template>
  <Main>
    <!-- Back Button and Title -->
    <div class="flex items-center space-x-4 mb-8">
      <a href="/project/all" class="btn btn-neutral btn-sm">
        <i class="fa-solid fa-chevron-left"></i>
      </a>
      <div class="flex flex-row w-full items-center justify-between">
        <h1 class="text-3xl font-bold text-white">
          {{ props.data.project_title }}
        </h1>
        <label for="my_modal_6" class="btn btn-sm btn-primary"
          >Invite Users</label
        >
      </div>
    </div>

    <!-- Modal for invite users -->
    <input type="checkbox" id="my_modal_6" class="modal-toggle" />
    <div class="modal" role="dialog">
      <div class="modal-box max-w-5xl">
        <div class="current-users">
          <h3 class="text-lg font-bold mb-3">Current Users</h3>
          <div class="grid grid-cols-4 gap-1">
            <p class="p-3 bg-base-200">Name</p>
            <p class="p-3 bg-base-200">Name</p>
            <p class="p-3 bg-base-200">Name</p>
            <p class="p-3 bg-base-200">Name</p>
            <p class="p-3 bg-base-200">Name</p>
          </div>
        </div>
        <div class="invite-users">
          <h3 class="text-lg font-bold mt-8 mb-5">Invite Users</h3>
          <div class="overflow-x-auto">
            <table class="table">
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user) in users" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>
                    <a :href="route('project.invite', { id:props.data.id, from:usePage().props.auth.id, to: user.id })" class="btn btn-sm btn-outline btn-primary">Invite</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-action">
          <label for="my_modal_6" class="btn">Close!</label>
        </div>
      </div>
    </div>

    <!-- Kanban Columns and Group Chat (Side by Side) -->
    <div class="flex gap-4" style="height: 45rem">
      <!-- Kanban Board (Left Side) -->
      <div
        class="flex-1 lg:max-w-screen-xl md:max-w-screen-md overflow-x-auto horizontal-scroll-container bg-base-200 px-5 py-5 rounded-xl"
      >
        <div class="flex flex-row gap-4">
          <!-- Loop through each column -->
          <div
            v-for="column in props.data.columns"
            :key="column.id"
            class="card bg-gray-800 shadow-xl rounded-2xl h-fit p-4 w-80 flex-none dark:bg-base-100"
          >
            <!-- Column Header -->
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold text-gray-200 truncate">
                {{ column.column_name }}
              </h2>
              <button
                @click="deleteColumn(column.id)"
                class="btn btn-sm btn-neutral bg-transparent text-gray-400 dark:text-gray-300"
              >
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>

            <!-- Draggable Task List -->
            <Draggable
              v-model="column.cards"
              group="cards"
              item-key="id"
              animation="200"
              @change="onDragChange"
            >
              <template #item="{ element }">
                <li
                  class="drag-list bg-gray-700 p-4 mb-3 rounded-lg flex justify-between items-center hover:bg-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
                >
                  <a class="text-lg text-gray-300">{{ element.card_name }}</a>
                  <button
                    @click="deleteCard(element.id)"
                    class="text-gray-500 hover:text-red-500 focus:outline-none"
                  >
                    <i class="fa-solid fa-x fa-sm"></i>
                  </button>
                </li>
              </template>
            </Draggable>

            <!-- Add Task Form -->
            <form
              v-if="isColumnFormVisible(column.id)"
              @submit.prevent="addCard(column.id)"
              method="post"
              class="mt-5"
            >
              <input
                type="text"
                name="column_id"
                :value="column.id"
                class="hidden"
                disabled
              />
              <input
                type="text"
                name="card_name"
                v-model="card_name"
                class="input input-bordered w-full my-3 text-gray-300 placeholder-gray-500 dark:bg-gray-700 dark:text-gray-200"
                placeholder="Add task"
              />
              <button type="submit" class="btn btn-success w-full my-2">
                Create Task
              </button>
            </form>

            <!-- New Task Button -->
            <button
              @click="toggleColumnFormVisibility(column.id)"
              class="btn btn-neutral w-full mt-4 dark:text-gray-300"
            >
              New Task
            </button>
          </div>

          <!-- New Column Card -->
          <div
            class="card w-80 h-fit flex-none shadow-xl rounded-2xl p-6 bg-gray-800 dark:bg-base-100"
          >
            <button
              class="btn btn-neutral btn-outline w-full mb-4"
              @click="toggleForm"
            >
              {{ isFormVisible ? "Cancel" : "New Column" }}
            </button>

            <form @submit.prevent="addColumn" method="post">
              <input type="hidden" name="project_id" v-model="project_id" />
              <input
                type="text"
                placeholder="Column Title"
                v-show="isFormVisible"
                name="column_title"
                v-model="column_title"
                class="input input-bordered w-full mb-4 text-gray-300 placeholder-gray-500 dark:bg-gray-700 dark:text-gray-200"
              />
              <button
                type="submit"
                v-show="isFormVisible"
                class="btn btn-success w-full"
              >
                Create Column
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Group Chat (Right Side) -->
      <div class="w-1/3 bg-base-200 shadow-md p-4 flex flex-col h-full">
        <!-- Chat Header -->
        <div class="flex items-center mb-4">
          <div>
            <h2 class="text-xl font-bold">
              Project {{ props.data.project_title }}
            </h2>
            <p class="text-sm">8 members</p>
          </div>
        </div>

        <!-- Chat Body -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
          <!-- Loop through messages -->
          <div
            v-for="message in chatMessages"
            :key="message.id"
            class="flex"
            :class="
              message.username === username ? 'justify-end' : 'justify-start'
            "
          >
            <div
              :class="
                message.username === username
                  ? 'bg-blue-500 text-white'
                  : 'bg-gray-300 text-gray-800'
              "
              class="max-w-xs p-3 rounded-lg shadow-lg"
            >
              <p class="flex flex-col text-sm">
                <strong>{{ message.username }}:</strong> {{ message.message }}
              </p>
            </div>
          </div>
        </div>

        <!-- Form stays at the bottom -->
        <form @submit.prevent="sendMessage" class="flex space-x-2 mt-4">
          <input
            v-model="newMessage"
            type="text"
            placeholder="Type your message..."
            required
            class="input input-bordered w-full"
          />
          <button type="submit" class="btn btn-primary">Send</button>
        </form>
      </div>
    </div>
  </Main>
</template>

<style scoped>
.drag-list {
  cursor: move;
}

.card {
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .card {
  background-color: #1e1e1e; /* Dark Card Background */
}

/* Horizontal Scroll Styling */
.horizontal-scroll-container {
  overflow-x: auto;
  scroll-behavior: smooth;
  scrollbar-width: thin; /* For Firefox */
  scrollbar-color: #4b5563 #1f2937; /* Thumb and track colors */
}

.horizontal-scroll-container::-webkit-scrollbar {
  height: 10px; /* Horizontal scrollbar height */
}

.horizontal-scroll-container::-webkit-scrollbar-thumb {
  background-color: #4b5563; /* Thumb color */
  border-radius: 5px; /* Rounded corners */
  border: 2px solid #1f2937; /* Padding around thumb */
}

.horizontal-scroll-container::-webkit-scrollbar-track {
  background-color: #1f2937; /* Track color */
  border-radius: 5px; /* Rounded corners */
}

/* Smooth Hover Effect for Scroll Thumb */
.horizontal-scroll-container::-webkit-scrollbar-thumb:hover {
  background-color: #9ca3af; /* Lighter thumb on hover */
}

/* Optional: Hide vertical scroll for aesthetics */
.horizontal-scroll-container::-webkit-scrollbar:vertical {
  display: none;
}
</style>