<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Main from "./Layout/Main.vue";
import { ScheduleXCalendar } from "@schedule-x/vue";
import { createCalendar, createViewMonthGrid, createViewDay } from "@schedule-x/calendar";
import "@schedule-x/theme-default/dist/index.css";
import { createEventsServicePlugin } from '@schedule-x/events-service';
import { createEventModalPlugin } from '@schedule-x/event-modal';
import { createDragAndDropPlugin } from '@schedule-x/drag-and-drop'

const eventsServicePlugin = createEventsServicePlugin();
const eventModal = createEventModalPlugin();
const eventDragAndDrop = createDragAndDropPlugin();

const title = ref("");
const start = ref("");
const end = ref("");
const description = ref("");

// Calendar instance
const calendarApp = createCalendar(
  {
    views: [createViewMonthGrid(), createViewDay()],

  },
  [eventsServicePlugin, eventModal, eventDragAndDrop]
);


// Fetch events
const fetchEvents = async () => {
  try {
    const response = await axios.get('/api/events'); // Replace with your endpoint
    response.data.forEach(event => {
      eventsServicePlugin.add(event); // Dynamically add events
    });
  } catch (error) {
    console.error('Error fetching events:', error);
  }
};

const addEvent = async () => {
  try {
    const newEvent = {
      title: title.value,
      description: description.value,
      start: start.value, 
      end: end.value 
    };

    const response = await axios.post('/api/events/store', newEvent);

    // Add the event dynamically
    eventsServicePlugin.add(response.data);

    // Manually refresh the calendar to reflect new events in all views
    calendarApp.render();

    document.getElementById("my_modal_3").open = false;
  } catch (error) {
    if (error.response) {
      console.log("Error data:", error.response.data);
      console.log("Error status:", error.response.status);
      console.log("Error headers:", error.response.headers);
    }
    console.log(error);
  }
};

onMounted(fetchEvents);
</script>


 
<template>
  <Main>
    <h1 class="text-2xl font-bold mb-5">Weekly Planner</h1>
    
    <div class="flex gap-3">
      <button class="btn btn-primary mb-5" onclick="my_modal_3.showModal()">
        Add Event
      </button>
    </div>
    <dialog id="my_modal_3" class="modal">
      <div class="modal-box">
        <form method="dialog">
          <button
            class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
          >
            ✕
          </button>
        </form>
        <h3 class="text-lg font-bold">Create new Event</h3>
        <form @submit.prevent="addEvent" class="mt-4">
          <div class="space-y-4">
            <div>
              <input
                v-model="title"
                type="text"
                placeholder="Event Title"
                required
                class="w-full p-2 border rounded"
              />
            </div>
            <div>
              <input
                v-model="start"
                type="datetime-local"
                required
                class="w-full p-2 border rounded"
              />
            </div>
            <div>
              <input
                v-model="end"
                type="datetime-local"
                required
                class="w-full p-2 border rounded"
              />
            </div>
            <div>
              <textarea
                v-model="description"
                placeholder="Event Description"
                class="w-full p-2 border rounded"
              ></textarea>
            </div>
            <button
              type="submit"
              class="w-full p-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
              Add Event
            </button>
          </div>
        </form>
      </div>
    </dialog>

    <div>
      <ScheduleXCalendar class="h-screen" :calendar-app="calendarApp" />
    </div>

  </Main>
</template>

<style>
:root {
  /* Dark Mode Overrides */
  --sx-color-primary: #bb86fc;
  --sx-color-on-primary: #121212;
  --sx-color-primary-container: #3700b3;
  --sx-color-on-primary-container: #eaddff;
  --sx-color-secondary: #03dac6;
  --sx-color-on-secondary: #121212;
  --sx-color-secondary-container: #004d40;
  --sx-color-on-secondary-container: #e8def8;
  --sx-color-tertiary: #03a9f4;
  --sx-color-on-tertiary: #121212;
  --sx-color-tertiary-container: #01579b;
  --sx-color-on-tertiary-container: #ffd8e4;
  --sx-color-surface: #121212; /* Dark surface */
  --sx-color-surface-dim: #1e1e1e;
  --sx-color-surface-bright: #252525;
  --sx-color-on-surface: #e0e0e0; /* Light text for dark mode */
  --sx-color-surface-container: #1f1f1f;
  --sx-color-surface-container-low: #242424;
  --sx-color-surface-container-high: #2a2a2a;
  --sx-color-background: oklch(var(--b1)); /* Dark background */
  --sx-color-on-background: #e0e0e0; /* Light text for dark background */
  --sx-color-outline: #49454f;
  --sx-color-outline-variant: #878787;
  --sx-color-shadow: #000;
  --sx-color-surface-tint: #6750a4;
  --sx-color-neutral: var(--sx-color-outline);
  --sx-color-neutral-variant: var(--sx-color-outline-variant);
  --sx-internal-color-gray-ripple-background: #1f1f1f;
  --sx-internal-color-light-gray: #242424;
  --sx-internal-color-text: #ffffff;
}
</style>