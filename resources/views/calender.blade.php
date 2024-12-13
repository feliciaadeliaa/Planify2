<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>
<body>
    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: true,
                droppable: true,
                events: '/events',
                selectable: true,
                select: function (info) {
                    let title = prompt('Enter Event Title:');
                    if (title) {
                        axios.post('/events', {
                            title: title,
                            start: info.startStr,
                            end: info.endStr,
                        }).then(() => calendar.refetchEvents());
                    }
                },
                eventDrop: function (info) {
                    axios.put(`/events/${info.event.id}`, {
                        start: info.event.start.toISOString(),
                        end: info.event.end ? info.event.end.toISOString() : null,
                    }).then(() => calendar.refetchEvents());
                },
                eventClick: function (info) {
                    if (confirm('Do you want to delete this event?')) {
                        axios.delete(`/events/${info.event.id}`).then(() => {
                            info.event.remove();
                        });
                    }
                },
            });

            calendar.render();
        });
    </script>
</body>
</html>
