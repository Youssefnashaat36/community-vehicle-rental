<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vehicles - Community Vehicle Rental</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">Community Vehicle Rental</a>
            <div class="navbar-nav">
                <a class="nav-link active" href="/vehicles">Vehicles</a>
                <a class="nav-link" href="/bookings">Bookings</a>
            </div>
        </div>
    </nav>

    <div id="app" class="container mt-4">
        <h1 class="mb-4">Available Vehicles</h1>
        
        <button class="btn btn-primary mb-3" @click="showAddForm = !showAddForm">Add New Vehicle</button>
        
        <div v-if="showAddForm" class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Add Vehicle</h5>
                <form @submit.prevent="addVehicle">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" v-model="newVehicle.name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-control" v-model="newVehicle.type">
                            <option value="car">Car</option>
                            <option value="bike">Bike</option>
                            <option value="scooter">Scooter</option>
                            <option value="van">Van</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Daily Rate ($)</label>
                        <input type="number" step="0.01" class="form-control" v-model="newVehicle.daily_rate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" v-model="newVehicle.description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" @click="showAddForm = false">Cancel</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4" v-for="vehicle in vehicles" :key="vehicle.id">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">@{{ vehicle.name }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">@{{ vehicle.type }}</h6>
                        <p class="card-text">@{{ vehicle.description }}</p>
                        <p class="card-text"><strong>$@{{ vehicle.daily_rate }}/day</strong></p>
                        <span class="badge" :class="vehicle.available ? 'bg-success' : 'bg-danger'">
                            @{{ vehicle.available ? 'Available' : 'Not Available' }}
                        </span>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-primary" @click="bookVehicle(vehicle)">Book Now</button>
                            <button class="btn btn-sm btn-danger" @click="deleteVehicle(vehicle.id)">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const { createApp } = Vue;
        
        createApp({
            data() {
                return {
                    vehicles: [],
                    showAddForm: false,
                    newVehicle: {
                        name: '',
                        type: 'car',
                        description: '',
                        daily_rate: '',
                        available: true
                    }
                }
            },
            mounted() {
                this.fetchVehicles();
            },
            methods: {
                fetchVehicles() {
                    axios.get('/api/vehicles')
                        .then(response => {
                            this.vehicles = response.data;
                        });
                },
                addVehicle() {
                    axios.post('/api/vehicles', this.newVehicle)
                        .then(response => {
                            this.vehicles.push(response.data);
                            this.showAddForm = false;
                            this.newVehicle = {
                                name: '',
                                type: 'car',
                                description: '',
                                daily_rate: '',
                                available: true
                            };
                        });
                },
                deleteVehicle(id) {
                    if (confirm('Are you sure?')) {
                        axios.delete('/api/vehicles/' + id)
                            .then(() => {
                                this.vehicles = this.vehicles.filter(v => v.id !== id);
                            });
                    }
                },
                bookVehicle(vehicle) {
                    const userId = prompt('Enter User ID:');
                    const startDate = prompt('Start Date (YYYY-MM-DD):');
                    const endDate = prompt('End Date (YYYY-MM-DD):');
                    
                    if (userId && startDate && endDate) {
                        axios.post('/api/bookings', {
                            user_id: userId,
                            vehicle_id: vehicle.id,
                            start_date: startDate,
                            end_date: endDate
                        }).then(response => {
                            alert('Booking created successfully!');
                            window.location.href = '/bookings';
                        }).catch(error => {
                            alert('Error: ' + (error.response?.data?.message || 'Something went wrong'));
                        });
                    }
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
