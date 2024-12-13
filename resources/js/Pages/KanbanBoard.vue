<script setup>
import { usePage } from "@inertiajs/vue3";
import Main from "./Layout/Main.vue";
import axios from "axios";
import { ref, onMounted, defineProps } from "vue";
import { route } from "ziggy-js";

const projects = ref([""]);
const project_title = ref("");
const due_date = ref("");
const user_id = ref("");
const invite_email = ref("");
const invites = ref([]);

const props = defineProps({
  userId: {
    type: Number,
    required: true,
  },
  projects: {
    type: Array,
    required: true,
  },
});

const fetchProject = async () => {
  try {
    const authUser = usePage().props.auth.id;
    const response = await axios.get(route("project.fetch", authUser));
    projects.value = response.data;
  } catch (error) {
    console.log(error);
  }
};

const addInvite = () => {
  if (invite_email.value && !invites.value.includes(invite_email.value)) {
    invites.value.push(invite_email.value);
    invite_email.value = ""; // Clear input after adding
  }
};

const removeInvite = (index) => {
  invites.value.splice(index, 1);
};

const newProject = async () => {
  try {
    const response = await axios.post("/api/project/store", {
      project_title: project_title.value,
      due_date: due_date.value,
      user_id: usePage().props.auth.id,
    });
    fetchProject();
    toast("Success", {
      theme: "dark",
      type: "success",
      pauseOnFocusLoss: false,
      autoClose: 1000,
      hideProgressBar: true,
      dangerouslyHTMLString: true,
    });
  } catch (error) {
    if (error.response) {
      console.log("Error data:", error.response.data);
      console.log("Error status:", error.response.status);
      console.log("Error headers:", error.response.headers);
    }
  }
};

const deleteProject = async (projectID) => {
  try {
    // Make sure the project ID is sent correctly in the URL
    const response = await axios.delete(route("api.project.delete", projectID));
    if (response.status === 200) {
      fetchProject();
    }
  } catch (error) {
    console.log("Error deleting project:", error);
  }
};

onMounted(fetchProject);
</script>

<template>
<Main>
  <div class="mb-8 mt-8 flex items-center justify-between w-full">
    <h2 class="text-3xl font-bold text-white">All Projects</h2>
    <button
      class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition-all"
      onclick="my_modal_3.showModal()"
    >
      Create Project
    </button>
  </div>

  <!-- All Projects -->
  <div class="grid grid-cols-12 gap-6 h-auto">
    <div class="col-span-8">
      <div v-if="projects && projects.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="(item, id) in projects"
          :key="id"
          class="relative bg-gradient-to-r from-gray-800 to-gray-900 dark:from-gray-900 dark:to-gray-800 shadow-2xl rounded-lg p-6 transition-transform transform hover:scale-105 hover:shadow-xl"
        >
          <!-- Link to project detail page -->
          <a :href="`/project/detail/${item.id}`">
            <div class="flex items-center justify-between mb-3">
              <h2 class="text-xl font-semibold text-white truncate">{{ item.project_title }}</h2>
            </div>
            <span class="text-sm text-gray-400">{{ item.due_date }}</span>
          </a>

          <!-- Button to delete the project -->
          <button
            @click="deleteProject(item.id, $event)"
            class="absolute top-3 right-3 text-white hover:text-red-500 focus:outline-none"
          >
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <p class="text-center text-gray-500 col-span-3">No Projects Yet</p>
      </div>
    </div>

    <!-- Stats -->
    <div class="col-span-4 h-full">
      <div class="card bg-gray-800 p-6 rounded-lg shadow-lg h-full">
        <p class="text-xl font-semibold text-white mb-4">Invitation</p>
        <ul v-for="(item, id) in projects" :key="id" class="space-y-3">
          <li class="text-gray-300">Email: {{ item.email }}</li>
          <li class="text-gray-300">Status: {{ item.status }}</li>
        </ul>
      </div>
    </div>
  </div>
</Main>



  <dialog id="my_modal_3" class="modal">
    <div class="modal-box">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">
          ✕
        </button>
      </form>
      <h3 class="text-lg font-bold">Hello!</h3>
      <form @submit.prevent="newProject">
        <input
          type="text"
          name="project_title"
          placeholder="enter project title"
          v-model="project_title"
          class="input w-full my-2"
        />
        <label class="form-control w-full my-2">
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
        <button type="submit" class="btn w-full btn-success btn-sm">
          Create New Project
        </button>
      </form>
    </div>
  </dialog>
</template>