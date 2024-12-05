<script setup>
import Main from "./Layout/Main.vue";
import axios from "axios";
import { ref, onMounted, defineProps } from "vue";


const projects = ref([""]);
const project_title = ref("");
const due_date = ref("");
const user_id = ref("");

// fetch projects
const fetchProject = async () => {
  try {
    const response = await axios.get("/api/project");
    projects.value = response.data;
  } catch (error) {
    console.log("error" + error);
  }
};

const props = defineProps({
  userId: {
    type: Number,
    required: true,
  },
});

const newProject = async () => {
  try {
    const response = await axios.post("/api/project/store", {
      project_title: project_title.value,
      due_date: due_date.value,
      user_id: props.userId
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
    if (error.response) {
      console.log("Error data:", error.response.data);
      console.log("Error status:", error.response.status);
      console.log("Error headers:", error.response.headers);
    }
  }
};

onMounted(fetchProject);
</script>

<template>
  <Main>
    <div class="mb-5 mt-5 flex items-center justify-between w-full">
      <h2 class="text-2xl font-bold">All Projects</h2>
      <button class="btn" onclick="my_modal_3.showModal()">
        Create Project
      </button>
    </div>

    <!-- All Project -->
    <div class="grid grid-cols-12 gap-4 h-80">
      <div class="col-span-8">
        <div class="grid grid-cols-3 gap-2">
          <a
            :href="`/project/detail/${item.id}`"
            v-for="(item, id) in projects"
            :key="id"
          >
            <div class="card bg-base-200 shadow-xl p-4 flex-none">
              <h2 class="card-title font-bold mb-3 text-gray-300">
                {{ item.project_title }}
              </h2>
              <span class="text-sm text-gray-400">{{ item.due_date }}</span>
            </div>
          </a>
        </div>
      </div>

      <!-- Stats -->
      <div class="col-span-4 h-full">
        <div class="card bg-base-200 shadow-xl h-full p-4 flex-none">
          <h2 class="font-bold mb-3 text-gray-300 text-center w-full">
            Priority List
          </h2>

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
          class="input w-full"
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
        <button type="submit" class="btn w-full btn-success btn-sm">
          Create New Project
        </button>
      </form>
    </div>
  </dialog>
</template>