<script setup>
import Main from "./Layout/Main.vue";
import { defineProps, ref, onMounted } from "vue";
import { Chart } from "chart.js";

import { PieController, ArcElement, Title, Tooltip, Legend } from "chart.js";
import axios from "axios";
Chart.register(PieController, ArcElement, Title, Tooltip, Legend);

const props = defineProps({
  totalTask: {
    type: Number,
    required: true,
  },
  totalSubTask: {
    type: Number,
    required: true,
  },
  project: {
    type: Object,
    required: true,
  },
  task: {
    type: Object,
    required: true,
  },
});

// fetch chart
const statusCounts = ref([]);
const fetchStatistics = async () => {
  try {
    const response = await axios.get("/api/task-statistics"); // Adjust the route if necessary
    renderChart(response.data);
  } catch (error) {
    console.error("Error fetching project statistics:", error);
  }
};

const renderChart = (data) => {
  const ctx = document.getElementById("projectDistributionChart").getContext("2d");

  new Chart(ctx, {
    type: "pie",
    data: {
      labels: Object.keys(data), // ['personal', 'colaboration']
      datasets: [
        {
          data: Object.values(data), // [count of personal, count of colaboration]
          backgroundColor: ["#36A2EB", "#FF6384"], // Colors for each status
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "top",
        },
        title: {
          display: true,
          text: "Project Status Distribution",
        },
      },
    },
  });
};

const calculateProgress = (subtasks) => {
  if (!subtasks || !subtasks.length) return 0;
  const completed = subtasks.filter((st) => st.status === 1).length;
  return Math.round((completed / subtasks.length) * 100);
};

// Fungsi logout
const handleLogout = async () => {
  try {
    const response = await fetch("/logout", {
      method: "GET",
      credentials: "include", // Jika menggunakan cookie untuk autentikasi
    });
    if (response.ok) {
      console.log("Logged out successfully.");
      window.location.href = "/logout"; // Arahkan ke halaman login
    } else {
      console.error("Logout failed:", response.statusText);
    }
  } catch (error) {
    console.error("Error during logout:", error);
  }
};

onMounted(() => {
  fetchStatistics();
});
</script>

<template>
  <Main>
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Dashboard</h1>
      <button
        class="btn btn-error btn-sm"
        @click="handleLogout"
      >
        Logout
      </button>
    </div>

    <!-- Card Stats -->
    <div class="grid grid-cols-4 gap-3 mt-3">
      <div class="card">
        <div class="stats shadow bg-base-200 rounded-lg flex flex-col">
          <div class="stat flex justify-between items-center">
            <div class="stat-title text-lg font-semibold">
              Total Tasks Assigned
            </div>
            <div class="stat-value">{{ totalTask }}</div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="stats shadow bg-base-200 rounded-lg">
          <div class="stat flex items-center justify-between">
            <div class="stat-title">Total Sub Task</div>
            <div class="stat-value">{{ totalSubTask }}</div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="stats shadow bg-base-200 rounded-lg">
          <div class="stat flex items-center justify-between">
            <div class="stat-title">Project Assigned</div>
            <div class="stat-value">12</div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="stats shadow bg-base-200 rounded-lg">
          <div class="stat flex items-center justify-between">
            <div class="stat-title">Project Task Assigned</div>
            <div class="stat-value">32</div>
          </div>
        </div>
      </div>
    </div>

    <div class="divider"></div>

    <!-- Latest Notes -->
    <h1 class="text-2xl font-bold my-3">All Project</h1>

    <div class="grid grid-cols-12 gap-3">
      <!-- Left -->
      <div class="col-span-8">
        <div class="overflow-x-auto">
          <table class="table">
            <!-- head -->
            <thead>
              <tr>
                <th></th>
                <th>Project Name</th>
                <th>Due Date</th>
                <th>Creator</th>
                <th>Progress</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, id) in props.task" :key="id" class="hover">
                <th>{{ item.id }}</th>
                <td>{{ item.title }}</td>
                <td>{{ item.due_date }}</td>
                <td>{{ item.user_id }}</td>
                <td>
                  <progress
                    class="progress progress-success w-56"
                    :value="calculateProgress(item.sub_task)"
                    max="100"
                  ></progress>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Right -->
      <div class="col-span-4">
        <h1 class="text-2xl font-bold my-3">Goal Tracking</h1>
        <canvas id="projectDistributionChart"></canvas>
      </div>
    </div>
  </Main>
</template>
