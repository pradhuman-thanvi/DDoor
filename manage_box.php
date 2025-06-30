<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Time Slots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .time-slot-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .time-slot-item .delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Manage Time Slots</h1>
    <form id="add-slot-form">
        <div class="mb-3">
            <label for="company_id" class="form-label">Company ID</label>
            <input type="number" class="form-control" id="company_id" name="company_id" required>
        </div>
        <div class="mb-3">
            <label for="period" class="form-label">Period</label>
            <select class="form-control" id="period" name="period" required>
                <option value="morning">Morning</option>
                <option value="afternoon">Afternoon</option>
                <option value="evening">Evening</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="slot_time" class="form-label">Time Slot</label>
            <input type="text" class="form-control" id="slot_time" name="slot_time" placeholder="e.g., 05-06 AM" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Time Slot</button>
    </form>

    <h2>Existing Time Slots</h2>
    <div id="time-slots-list"></div>

    <script>
        document.getElementById('add-slot-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('add_time_slot.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Time slot added successfully!');
                    loadTimeSlots();
                } else {
                    alert('Error adding time slot: ' + data.message);
                }
            });
        });

        function loadTimeSlots() {
            fetch('fetch_time_slots.php')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('time-slots-list');
                    list.innerHTML = '';
                    data.forEach(slot => {
                        const item = document.createElement('div');
                        item.className = 'time-slot-item';
                        item.innerHTML = `
                            <span>${slot.company_id} - ${slot.period} - ${slot.slot_time}</span>
                            <button class="delete-btn" data-id="${slot.id}">Delete</button>
                        `;
                        item.querySelector('.delete-btn').addEventListener('click', function() {
                            deleteTimeSlot(slot.id);
                        });
                        list.appendChild(item);
                    });
                });
        }

        function deleteTimeSlot(id) {
            fetch('delete_time_slot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Time slot deleted successfully!');
                    loadTimeSlots();
                } else {
                    alert('Error deleting time slot: ' + data.message);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadTimeSlots();
        });
    </script>
</body>
</html>
