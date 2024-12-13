<script setup>
import axios from "axios";
import Main from "./Layout/Main.vue";
import { defineProps, ref, watch } from "vue";

import Draggable from "vuedraggable";

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
});

// const fetchProject = () {
//   try {
//     const response = awat axios.get("/api/project")
//   } catch (error) {

//   }
// }

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
</script>

<template>
  <Main>
    <!-- Back Button and Title -->
    <div class="flex items-center space-x-4 mb-8">
      <a href="/project/all" class="btn btn-neutral btn-sm">
        <i class="fa-solid fa-chevron-left"></i>
      </a>
      <h1 class="text-3xl font-bold text-white">
        {{ props.data.project_title }}
      </h1>
    </div>

    <!-- Kanban Columns (Horizontal Scroll) -->
    <div class="flex overflow-x-auto gap-8 py-6 justify-start">
      <!-- Loop through each column -->
      <div
        v-for="column in props.data.columns"
        :key="column.id"
        class="card bg-gray-800 shadow-lg rounded-2xl h-fit p-6 w-96 flex-none hover:shadow-xl transition-all duration-300 dark:bg-gray-900"
      >
        <!-- Column Header -->
        <div class="flex justify-between items-center mb-5">
          <h2 class="text-2xl font-semibold text-gray-200 truncate">
            {{ column.column_name }}
          </h2>
          <button
            @click="deleteColumn(column.id)"
            class="btn btn-sm btn-neutral bg-transparent hover:bg-gray-700 text-gray-400 dark:text-gray-300 dark:hover:bg-gray-700"
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
              class="drag-list bg-gray-700 p-4 mb-3 rounded-lg flex justify-between items-center hover:bg-gray-600 transition-all dark:bg-gray-800 dark:hover:bg-gray-700"
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
        class="card w-96 flex-none shadow-xl rounded-2xl p-6 bg-gray-800 dark:bg-gray-900"
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
</style>