<script setup>
import { ref, onMounted } from 'vue';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import axios from 'axios';

const calendar = ref(null);

const initializeCalendar = () => {
  const calendarEl = document.getElementById('calendar');

  calendar.value = new Calendar(calendarEl, {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    editable: true,
    droppable: true,
    events: '/events', // API to fetch events
    selectable: true,
    select: handleEventCreation,
    eventDrop: handleEventUpdate,
    eventClick: handleEventDeletion,
  });

  calendar.value.render();
};

const handleEventCreation = (info) => {
  const title = prompt('Enter Event Title:');
  if (title) {
    axios
      .post('/events', {
        title: title,
        start: info.startStr,
        end: info.endStr,
      })
      .then(() => calendar.value.refetchEvents())
      .catch((error) => {
        console.error('Error creating event:', error);
        alert('Failed to create event.');
      });
  }
};

const handleEventUpdate = (info) => {
  axios
    .put(`/events/${info.event.id}`, {
      start: info.event.start.toISOString(),
      end: info.event.end ? info.event.end.toISOString() : null,
    })
    .then(() => calendar.value.refetchEvents())
    .catch((error) => {
      console.error('Error updating event:', error);
      alert('Failed to update event.');
    });
};

const handleEventDeletion = (info) => {
  if (confirm('Do you want to delete this event?')) {
    axios
      .delete(`/events/${info.event.id}`)
      .then(() => {
        info.event.remove();
      })
      .catch((error) => {
        console.error('Error deleting event:', error);
        alert('Failed to delete event.');
      });
  }
};

onMounted(() => {
  initializeCalendar();
});
</script>

<template>
  <div id="calendar"></div>
</template>

<style>
#calendar {
  max-width: 900px;
  margin: 0 auto;
}
</style>
