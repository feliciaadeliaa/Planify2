<script setup>
import { onMounted, ref, watch, defineProps } from "vue";
import Main from "./Layout/Main.vue";
import axios from "axios";
import { route } from "ziggy-js";
import { usePage } from "@inertiajs/vue3";

import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const title = ref("");
const dialog = ref(null);
const tasks = ref([]);
const task_id = ref(null);
const tasks_name = ref("");
const due_date = ref("");
const progress = ref("");
const loading = ref(true);
const subtask = ref(null);
const user_id = ref("");

const props = defineProps({
  userId: {
    type: Number,
    required: true,
  },
});

const openDialog = () => {
  dialog.value.showModal();
};

const closeDialog = () => {
  dialog.value.close();
};

const openSubTaskDialog = (id) => {
  task_id.value = id;
  subtask.value.showModal();
};

const newNotes = async () => {
  const response = await axios.post("/api/create-notes", {
    title: title.value,
    due_date: due_date.value,
    user_id: props.userId,
  });
  toast("Success", {
    theme: "dark",
    type: "success",
    pauseOnFocusLoss: false,
    autoClose: 1000,
    hideProgressBar: true,
    dangerouslyHTMLString: true,
  }); 
  title.value = "";
  fetchNotes();
};

const newSubTask = async () => {
  try {
    const response = await axios.post("/api/create-sub-task", {
      task_id: task_id.value,
      tasks_name: tasks_name.value,
      user_id: props.userId,
    });
    fetchNotes();
    toast("Success", {
      theme: "dark",
      type: "success",
      pauseOnFocusLoss: false,
      autoClose: 1000,
      hideProgressBar: true,
      dangerouslyHTMLString: true,
    });
    tasks_name.value = "";
  } catch (error) {
    if (error.response) {
      console.log("Error data:", error.response.data);
      console.log("Error status:", error.response.status);
      console.log("Error headers:", error.response.headers);
    }
  }
};

const toggleSubTaskStatus = async (subtask) => {
  try {
    const newStatus = subtask.status === 1 ? 0 : 1;

    await axios.put(`/api/update-sub-task/${subtask.id}`, {
      status: newStatus,
    });

    subtask.status = newStatus;
    await fetchNotes();
  } catch (error) {
    console.error(`Failed to update subtask ${subtask.id}:`, error);
  }
};

const deleteTask = async (id) => {
  try {
    const response = await axios.delete(`/api/delete-task/${id}`);
    await fetchNotes();
    toast("Notes Deleted", {
      theme: "dark",
      type: "success",
      pauseOnFocusLoss: false,
      autoClose: 1000,
      hideProgressBar: true,
      dangerouslyHTMLString: true,
    });
  } catch (error) {
    console.error(
      "Error deleting sub-task:",
      error.response?.data || error.message
    );
  }
};

const deleteSubTask = async (subtask_id) => {
  try {
    const response = await axios.delete(`/api/delete-sub-tasks/${subtask_id}`);
    await fetchNotes();
  } catch (error) {
    console.error(
      "Error deleting sub-task:",
      error.response?.data || error.message
    );
  }
};

const setMinimumLoadingTime = () => {
  setTimeout(() => {
    loading.value = false;
  }, 500);
};

const calculateProgress = (subtasks) => {
  if (!subtasks.length) return 0;
  const completed = subtasks.filter((st) => st.status === 1).length;
  return Math.round((completed / subtasks.length) * 100);
};

const fetchNotes = async () => {
  try {
    const user_id = usePage().props.auth.id;
    const response = await axios.get(route("notes.fetch", user_id));
    tasks.value = response.data;
  } catch (error) {
    if (error.response) {
      console.log("Error data:", error.response.data);
      console.log("Error status:", error.response.status);
      console.log("Error headers:", error.response.headers);
    }
    console.log(error);
  } finally {
    setMinimumLoadingTime();
  }
};

watch(progress, (newProgress) => {
  if (newProgress === 100) {
    // Show toast when progress reaches 100%
    toast("Success", {
      theme: "dark",
      type: "success",
      pauseOnFocusLoss: false,
      autoClose: 1000,
      hideProgressBar: true,
      dangerouslyHTMLString: true,
    });
  }
});

onMounted(() => {
  fetchNotes();
});
</script>


<template>
  <dialog class="modal" ref="subtask">
    <div class="modal-box">
      <form method="dialog">
        <button
          @click="closeDialog"
          class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
        >
          ✕
        </button>
      </form>
      <h3 class="text-lg font-bold mb-3">Add Subtask</h3>
      <form @submit.prevent="newSubTask" method="post">
        <input type="hidden" name="task_id" v-model="task_id" />
        <input
          type="text"
          v-model="tasks_name"
          name="tasks_name"
          placeholder="Type here"
          class="input input-bordered w-full"
        />
        <button type="submit" class="btn btn-success w-full mt-3">
          Create !
        </button>
      </form>
    </div>
  </dialog>

  <Main>
    <h1 class="text-2xl font-bold">Notes</h1>

    <div class="flex items-center justify-between gap-3 my-5">
      <a onclick="my_modal_3.showModal()" class="btn btn-sm btn-neutral"
        >Create Task</a
      >
    </div>

    <dialog id="my_modal_3" class="modal" ref="dialog">
      <div class="modal-box">
        <form method="dialog">
          <button
            @click="closeDialog"
            class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
          >
            ✕
          </button>
        </form>
        <h3 class="text-lg font-bold mb-3">Add Task</h3>
        <form @submit.prevent="newNotes" method="post">
          <input
            type="text"
            v-model="title"
            name="title"
            placeholder="task name"
            class="input input-bordered w-full"
          />
          <label class="form-control w-full">
            <div class="label">
              <span class="label-text">Due Date</span>
            </div>
            <input
              type="date"
              placeholder="Type here"
              class="input input-bordered w-full"
              name="due_date"
              v-model="due_date"
            />
          </label>
          <button type="submit" class="btn btn-success w-full mt-3">
            Create !
          </button>
        </form>
      </div>
    </dialog>

    <div v-if="loading" class="grid grid-cols-4 gap-3">
      <div class="skeleton h-72"></div>
      <div class="skeleton h-72"></div>
      <div class="skeleton h-72"></div>
      <div class="skeleton h-72"></div>
    </div>

    <div v-else class="grid grid-cols-3 gap-5">
      <div
        v-for="item in tasks"
        :key="item.id"
        class="card bg-gradient-to-r w-full from-gray-800 to-gray-900 shadow-lg p-6 flex-none rounded-lg transition-transform transform hover:scale-105"
      >
        <div class="card-body text-white">
          <button
            @click.prevent="deleteTask(item.id)"
            class="btn btn-sm btn-circle btn-ghost absolute left-2 top-2 text-gray-300 hover:text-white"
          >
            ✕
          </button>
          <div
            class="flex flex-row gap-2 items-center justify-between my-2 w-fit"
          >
            <h2 class="card-title text-xl font-semibold">{{ item.title }}</h2>
            <button
              @click="openSubTaskDialog(item.id)"
              class="btn btn-sm btn-neutral hover:bg-neutral-600 transition-colors"
            >
              Add
            </button>
          </div>

          <form v-for="subtask in item.sub_task" :key="subtask.id">
            <div
              class="flex justify-between items-center py-2 border-b border-gray-700"
            >
              <button
                @click.prevent="deleteSubTask(subtask.id)"
                class="text-gray-300 hover:text-white"
              >
                <i class="fa-solid fa-minus"></i>
              </button>
              <div class="form-control">
                <label class="label cursor-pointer text-start">
                  <span class="label-text w-full px-5 text-gray-200">{{
                    subtask.tasks_name
                  }}</span>
                  <input
                    type="checkbox"
                    class="checkbox checkbox-primary"
                    :checked="subtask.status === 1"
                    @change="toggleSubTaskStatus(subtask)"
                  />
                </label>
              </div>
            </div>
          </form>
        </div>
        <div
          v-if="item.sub_task.length > 0"
          class="card-footer px-8 py-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-b-lg"
        >
          <div class="flex items-center justify-between">
            <span class="label-text-alt text-gray-200"
              >{{ calculateProgress(item.sub_task) }}%</span
            >
            <span class="text-sm text-end text-red-600">{{
              item.due_date
            }}</span>
          </div>
          <div class="progress-container w-full mt-2">
            <div
              class="progress-bar h-2 rounded-lg"
              :style="{ width: calculateProgress(item.sub_task) + '%' }"
              :aria-valuenow="calculateProgress(item.sub_task)"
              aria-valuemin="0"
              aria-valuemax="100"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </Main>
</template>

<style scoped>
/* Progress Bar Container */
.progress-container {
  background-color: oklch(var(--b1)); /* Light gray background */
  border-radius: 8px;
  overflow: hidden;
  position: relative;
  height: 8px;
  width: 100%;
}

/* Animated Progress Bar */
.progress-bar {
  background-color: #01f43a; /* Primary color */
  height: 100%;
  width: 0%;
  transition: width 0.5s ease-in-out; /* Smooth animation */
  border-radius: 8px;
}
</style>