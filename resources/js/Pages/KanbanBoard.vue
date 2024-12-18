<script setup>
import { usePage } from "@inertiajs/vue3";
import Main from "./Layout/Main.vue";
import axios from "axios";
import { ref, onMounted, defineProps } from "vue";
import { route } from "ziggy-js";

import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

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

const collabProject = ref([]);
const fetch_collab = async () => {
  try {
    const response = await axios.get(route('project.fetch_collab', { userID: usePage().props.auth.id }));
    collabProject.value = response.data;
  } catch (error) {
    console.log('error : ', error);
  }
}

const pendingInvite = ref([]);
const fetchPendingInvite = async () => {
  try {
    const response = await axios.get(
      `/api/project/${usePage().props.auth.id}/invitations`
    );
    pendingInvite.value = response.data;
  } catch (error) {
    console.log(error);
  }
};

const AcceptInvite = async (invite_id) => {
  try {
    const response = await axios.get(
      route("project.accept", { invite_id: invite_id })
    );

    const modal = document.getElementById("my_modal_7");
    modal.checked = false;

    toast("Success", {
      theme: "dark",
      type: "success",
      pauseOnFocusLoss: false,
      autoClose: 1000,
      hideProgressBar: true,
      dangerouslyHTMLString: true,
    });

    fetchPendingInvite();

  } catch (error) {
    console.log("error : ", error);
  }
};
const DeclineInvite = async (invite_id) => {
  try {
    const response = await axios.post(
      route("project.decline", { invite_id: invite_id })
    );
  } catch (error) {
    console.log("error : ", error);
  }
};

const newProject = async () => {
  try {
    const response = await axios.post("/api/project/store", {
      project_title: project_title.value,
      due_date: due_date.value,
      user_id: usePage().props.auth.id,
    });

    const modal = document.getElementById("my_modal_3");
    modal.checked = false;

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
    toast("Something unexpected happened", {
      theme: "dark",
      type: "error",
      pauseOnFocusLoss: false,
      autoClose: 2000,
      hideProgressBar: true,
      dangerouslyHTMLString: true,
    });

    // if (error.response) {
    //   console.log("Error data:", error.response.data);
    //   console.log("Error status:", error.response.status);
    //   console.log("Error headers:", error.response.headers);
    // }
  }
};

const deleteProject = async (projectID) => {
  try {
    // Make sure the project ID is sent correctly in the URL
    const response = await axios.delete(route("api.project.delete", projectID));
    if (response.status === 200) {

      fetchProject();
      toast("Project Deleted Successfully", {
        theme: "dark",
        type: "success",
        pauseOnFocusLoss: false,
        autoClose: 1000,
        hideProgressBar: true,
        dangerouslyHTMLString: true,
      });

    }
  } catch (error) {
    console.log("Error deleting project:", error);
  }
};

onMounted(() => {
  fetchProject(), fetchPendingInvite(), fetch_collab();
});
</script>

<template>
  <Main>
    <div class="mb-8 mt-8 flex items-center justify-between w-full">
      <h2 class="text-3xl font-bold text-white">Personal Projects</h2>
      <label for="my_modal_3"
        class="btn btn-outline text-white px-6 py-2 rounded-lg shadow-lg"
      >
        Create Project
      </label>
    </div>

    <!-- All Projects -->
    <div class="grid grid-cols-12 gap-6 h-auto">
      <div class="col-span-12">
        <div
          v-if="projects && projects.length"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
        >
          <div
            v-for="(item, id) in projects"
            :key="id"
            class="relative bg-gradient-to-r from-gray-800 to-gray-900 dark:from-gray-900 dark:to-gray-800 shadow-2xl rounded-lg p-6 transition-transform transform hover:scale-105 hover:shadow-xl"
          >
            <!-- Link to project detail page -->
            <a :href="`/project/detail/${item.id}`">
              <div class="flex items-center justify-between mb-3">
                <h2 class="text-xl font-semibold text-white truncate">
                  {{ item.project_title }}
                </h2>
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

        <div
          v-else
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
        >
          <p class="text-center text-gray-500 col-span-3">No Projects Yet</p>
        </div>
      </div>
    </div>

    <div class="mb-8 mt-8 flex items-center justify-between w-full">
      <h2 class="text-3xl font-bold text-white">Shared Project</h2>
      <label for="my_modal_7" class="btn btn-outline text-white"
        >Pending Invite</label
      >
    </div>

    <div class="grid grid-cols-12 gap-6 h-auto">
      <div class="col-span-12">
        <div
          v-if="collabProject && collabProject.length"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
        >
          <div
            v-for="(item) in collabProject"
            :key="item.id"
            class="relative bg-gradient-to-r from-gray-800 to-gray-900 dark:from-gray-900 dark:to-gray-800 shadow-2xl rounded-lg p-6 transition-transform transform hover:scale-105 hover:shadow-xl"
          >
            <!-- Link to project detail page -->
            <a :href="`/project/detail/${item.id}`">
              <div class="flex items-center justify-between mb-3">
                <h2 class="text-xl font-semibold text-white truncate">
                  {{ item.project_title }}
                </h2>
              </div>
              <span class="text-sm text-gray-400">{{ item.due_date }}</span>
            </a>

          </div>
        </div>

        <div
          v-else
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
        >
          <p class="text-center text-gray-500 col-span-3">No Projects Yet</p>
        </div>
      </div>
    </div>

  </Main>

  <input type="checkbox" id="my_modal_7" class="modal-toggle" />
  <div class="modal" role="dialog">
    <div class="modal-box">
      <h3 class="text-lg font-bold">Pending Invite</h3>
      <ul
        v-for="pending in pendingInvite"
        :key="pending.id"
        class="p-6 bg-base-200 rounded-box w-full my-3"
      >
        <li>
          <div class="flex flex-row items-center justify-between gap-5">
            <div class="flex flex-col">
              <a>From : {{ pending.name }}</a>
              <a>Project : {{ pending.project_title }}</a>
            </div>
            <div class="flex">
              <a
                @click.prevent="AcceptInvite(pending.id)"
                class="btn btn-sm btn-outline btn-success"
                >Accept</a
              >
              <a
                @click.prevent="DeclineInvite(pending.id)"
                class="btn btn-sm text-red-600"
                >Decline</a
              >
            </div>
          </div>
        </li>
      </ul>
    </div>
    <label class="modal-backdrop" for="my_modal_7">Close</label>
  </div>

  <input type="checkbox" id="my_modal_3" class="modal-toggle" />
  <div class="modal" role="dialog">
    <div class="modal-box">
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
      <div class="modal-action">
      <label for="my_modal_3" class="btn">Close!</label>
      </div>
    </div>
  </div>

</template>