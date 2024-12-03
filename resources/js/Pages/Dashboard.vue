<script setup>
import Main from "./Layout/Main.vue";
import { defineProps, ref, onMounted } from "vue";
import { Chart } from "chart.js";
import {
  CategoryScale,
  LinearScale,
  BarElement,
  BarController,
  PieController,
  ArcElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";

Chart.register(
  CategoryScale,
  LinearScale,
  BarElement,
  BarController,
  PieController,
  ArcElement,
  Title,
  Tooltip,
  Legend
);

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
    const response = await fetch("/api/task-statistics");
    const data = await response.json();
    statusCounts.value = data.statusCounts;
    renderChart();
  } catch (error) {
    console.error("Error fetching task statistics:", error);
  }
};

const calculateProgress = (subtasks) => {
  if (!subtasks || !subtasks.length) return 0;
  const completed = subtasks.filter((st) => st.status === 1).length;
  return Math.round((completed / subtasks.length) * 100);
};

onMounted(() => {
  fetchStatistics();
});
</script>


<template>
  <Main>
    <h1 class="text-2xl font-bold">Dashboard</h1>

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
        <!-- <canvas id="statusChart"></canvas> -->

        <!-- <canvas id="statusPieChart"></canvas> -->
        <canvas id="projectProgressChart"></canvas>
      </div>
    </div>
  </Main>
</template>