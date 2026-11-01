<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bookings - Community Vehicle Rental</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">Community Vehicle Rental</a>
            <div class="navbar-nav">
                <a class="nav-link" href="/vehicles">Vehicles</a>
                <a class="nav-link active" href="/bookings">Bookings</a>
            </div>
        </div>
    </nav>

    <div id="app" class="container mt-4">
        <h1 class="mb-4">Bookings</h1>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle</th>
                        <th>User ID</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="booking in bookings" :key="booking.id">
                        <td>@{{ booking.id }}</td>
                        <td>@{{ booking.vehicle?.name }}</td>
                        <td>@{{ booking.user_id }}</td>
                        <td>@{{ booking.start_date }}</td>
                        <td>@{{ booking.end_date }}</td>
                        <td>$@{{ booking.total_price }}</td>
                        <td>
                            <span class="badge" :class="getStatusClass(booking.status)">
                                @{{ booking.status }}
                            </span>
                        </td>
                        <td>
                            <select class="form-select form-select-sm" @change="updateStatus(booking, $event.target.value)">
                                <option value="pending" :selected="booking.status === 'pending'">Pending</option>
                                <option value="confirmed" :selected="booking.status === 'confirmed'">Confirmed</option>
                                <option value="cancelled" :selected="booking.status === 'cancelled'">Cancelled</option>
                                <option value="completed" :selected="booking.status === 'completed'">Completed</option>
                            </select>
                            <button class="btn btn-sm btn-danger mt-1" @click="deleteBooking(booking.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const { createApp } = Vue;
        
        createApp({
            data() {
                return {
                    bookings: []
                }
            },
            mounted() {
                this.fetchBookings();
            },
            methods: {
                fetchBookings() {
                    axios.get('/api/bookings')
                        .then(response => {
                            this.bookings = response.data;
                        });
                },
                getStatusClass(status) {
                    const classes = {
                        'pending': 'bg-warning',
                        'confirmed': 'bg-success',
                        'cancelled': 'bg-danger',
                        'completed': 'bg-info'
                    };
                    return classes[status] || 'bg-secondary';
                },
                updateStatus(booking, status) {
                    axios.put('/api/bookings/' + booking.id, { status: status })
                        .then(response => {
                            booking.status = response.data.status;
                        });
                },
                deleteBooking(id) {
                    if (confirm('Are you sure?')) {
                        axios.delete('/api/bookings/' + id)
                            .then(() => {
                                this.bookings = this.bookings.filter(b => b.id !== id);
                            });
                    }
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
