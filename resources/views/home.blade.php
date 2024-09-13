<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form View</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <form id="position-form">
            <input type="hidden" id="position-id" name="position-id">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="reports_to">Reports to:</label>
                <select class="form-control" id="reports_to" name="reports_to">
                    <!--  -->
                    <!--  -->
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <div class="container mt-5">
            <h3>Table View</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Reports To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="position-table-body">
                    <!--  -->
                    <!--  -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchPositions();
            fetchPositionOptions();

            // Handle form submission
            document.getElementById('position-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const id = document.getElementById('position-id').value;
                const name = document.getElementById('name').value;
                const reportsTo = document.getElementById('reports_to').value;

                const data = {
                    name: name,
                    reports_to: reportsTo || null
                };

                if (id) {
                    // update position
                    axios.put(`/api/positions/${id}`, data)
                        .then(response => {
                            alert('position has been updated');
                            resetForm();
                            fetchPositions();
                        })
                        .catch(error => console.error(error));
                } else {
                    // create new position
                    axios.post('/api/positions', data)
                        .then(response => {
                            alert('position has been added');
                            resetForm();
                            fetchPositions();
                        })
                        .catch(error => console.error(error));
                }
            });
        });

        function fetchPositions() {
            axios.get('/api/positions')
                .then(response => {
                    const positions = response.data;
                    const tableBody = document.getElementById('position-table-body');
                    tableBody.innerHTML = '';
                    positions.forEach(position => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>${position.name}</td>
                                <td>${position.reports_to_name || ''}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="editPosition(${position.id}, '${position.name}', '${position.reports_to}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deletePosition(${position.id})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>`;
                    });
                })
                .catch(error => console.error(error));
        }

        function fetchPositionOptions() {
            axios.get('/api/positions')
                .then(response => {
                    const positions = response.data;
                    const reportsToSelect = document.getElementById('reports_to');
                    reportsToSelect.innerHTML = '<option value="">None</option>';
                    positions.forEach(position => {
                        reportsToSelect.innerHTML += `<option value="${position.id}">${position.name}</option>`;
                    });
                })
                .catch(error => console.error(error));
        }

        function editPosition(id, name, reportsTo) {
            document.getElementById('position-id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('reports_to').value = reportsTo;
        }

        function deletePosition(id) {
            if (confirm('are you sure you want to delete this position?')) {
                axios.delete(`/api/positions/${id}`)
                    .then(response => {
                        alert('Position ahs deleted');
                        fetchPositions();
                    })
                    .catch(error => console.error(error));
            }
        }

        function resetForm() {
            document.getElementById('position-id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('reports_to').value = '';
        }
    </script>
</body>

</html>