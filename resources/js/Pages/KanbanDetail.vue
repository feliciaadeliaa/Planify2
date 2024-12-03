<script setup>
import axios from "axios";
import Main from "./Layout/Main.vue";
import { defineProps, ref, watch } from "vue";
import { toast } from "vue3-toastify";
import Draggable from "vuedraggable";

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
    <a href="/project/all" class="btn btn-neutral btn-sm"
      ><i class="fa-solid fa-chevron-left"></i
    ></a>
    <h1 class="text-2xl font-bold my-5">{{ props.data.project_title }}</h1>

    <div class="flex flex-row gap-4 my-5 w-full">
      <div
        v-for="column in props.data.columns"
        :key="column.id"
        class="card bg-base-100 w-80 h-fit shadow-xl p-4 flex-none"
      >
        <div class="flex justify-between">
          <h2 class="card-title font-bold mb-3 text-gray-300">
            {{ column.column_name }}
          </h2>
          <button
            @click="deleteColumn(column.id)"
            class="btn btn-sm btn-neutral bg-transparent"
          >
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>

        <Draggable
          v-model="column.cards"
          group="cards"
          item-key="id"
          animation="200"
          @change="onDragChange"
        >
          <template #item="{ element }">
            <li
              class="drag-list bg-base-200 p-4 my-2 rounded-md flex flex-row justify-between hover:bg-base-300"
            >
              <a>{{ element.card_name }}</a>
              <button @click="deleteCard(element.id)" class="text-gray-500">
                <i class="fa-solid fa-x fa-sm"></i>
              </button>
            </li>
          </template>
        </Draggable>

        <form
          v-if="isColumnFormVisible(column.id)"
          @submit.prevent="addCard(column.id)"
          method="post"
        >
          <input
            type="text"
            name="column_id"
            :value="column.id"
            class="w-full"
            disabled
          />
          <input
            type="text"
            name="card_name"
            v-model="card_name"
            class="input w-full my-3"
            placeholder="Add task"
          />
          <button type="submit" class="btn btn-success w-full my-1">
            Create New Task
          </button>
        </form>
        <button
          @click="toggleColumnFormVisibility(column.id)"
          class="btn btn-neutral"
        >
          New Task
        </button>
      </div>

      <div class="card w-80 flex-none">
        <button class="btn btn-neutral btn-outline mb-3" @click="toggleForm">
          {{ isFormVisible ? "Cancel" : "New Card" }}
        </button>
        <form @submit.prevent="addColumn" method="post">
          <input type="hidden" name="project_id" v-model="project_id" />
          <input
            type="text"
            placeholder="Card Title"
            v-show="isFormVisible"
            name="column_title"
            v-model="column_title"
            class="input input-bordered w-full mb-3"
          />
          <button
            type="submit"
            v-show="isFormVisible"
            class="btn btn-success w-full"
          >
            Create Card
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
</style>