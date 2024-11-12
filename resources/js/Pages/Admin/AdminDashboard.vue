<template>
    <AdminLayout>
        <div class="container-fluid mt-4">
            <h2 class="mb-4">Admin Dashboard</h2>
            <div class="row">
                <!-- Cards for User Statistics -->
                <div class="col-lg-4 col-md-4">
                    <div class="card text-white" style="background-color: #f583a9;">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text">{{ totalUsers }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="card text-white" style="background-color: #f583a9;">
                        <div class="card-body">
                            <h5 class="card-title">Free Users</h5>
                            <p class="card-text">{{ freeUsersCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="card text-white" style="background-color: #f583a9;">
                        <div class="card-body">
                            <h5 class="card-title">Premium Users</h5>
                            <p class="card-text">{{ premiumUsersCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <!-- Cards for Matches and Bookings -->
                    <div class="col-lg-4 col-md-4">
                        <div class="card text-white" style="background-color: #f583a9;">
                            <div class="card-body">
                                <h5 class="card-title">Matches</h5>
                                <p class="card-text">{{ totalMatches }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="card text-white" style="background-color: #f583a9;">
                            <div class="card-body">
                                <h5 class="card-title">User Dates</h5>
                                <p class="card-text">{{ datesCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphs Section -->
            <div class="row mt-4">
                <div class="col-lg-6">
                    <h4>Daily New Users</h4>
                    <canvas id="dailyUsersChart"></canvas>
                </div>
                <div class="col-lg-6">
                    <h4>Premium Users</h4>
                    <canvas id="premiumUsersChart"></canvas>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <h4>Daily Dates</h4>
                    <canvas id="dailyDatesChart"></canvas>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '../Layout/AdminLayout.vue';
import { onMounted, ref } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
    totalUsers: Number,
    freeUsersCount: Number,
    premiumUsersCount: Number,
    totalMatches: Number,
    datesCount: Number,
    dailyUserData: Array,
    premiumUserData: Array,
    dailyDateData: Array,
});

// Function to get days from a start date to end date
function getDaysBetween(startDate, endDate) {
    const days = [];
    for (let dt = new Date(startDate); dt <= endDate; dt.setDate(dt.getDate() + 1)) {
        days.push(new Date(dt).toISOString().split('T')[0]); // Format date to YYYY-MM-DD
    }
    return days;
}

const dailyUsersChart = ref(null);
const premiumUsersChart = ref(null);
const dailyDatesChart = ref(null);

onMounted(() => {
    const startDate = new Date('2024-10-01'); // Start from October 1, 2024
    const endDate = new Date(); // Current date
    const daysInRange = getDaysBetween(startDate, endDate);

    // Daily New Users Chart
    dailyUsersChart.value = new Chart(document.getElementById('dailyUsersChart'), {
        type: 'bar', // Changed to bar chart
        data: {
            labels: daysInRange, // Labels from October 1 to current date
            datasets: [{
                label: 'Daily New Users',
                data: daysInRange.map(date => {
                    const userData = props.dailyUserData.find(data => data.date === date);
                    return userData ? userData.count : 0; // Default to 0 if no data for that date
                }),
                backgroundColor: '#f583a9',
            }],
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date',
                    },
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Users',
                    },
                    beginAtZero: true,
                },
            },
        },
    });

    // Premium Users Chart
    premiumUsersChart.value = new Chart(document.getElementById('premiumUsersChart'), {
        type: 'bar',
        data: {
            labels: daysInRange,
            datasets: [{
                label: 'Premium Users',
                data: daysInRange.map(date => {
                    const premiumData = props.premiumUserData.find(data => data.date === date);
                    return premiumData ? premiumData.count : 0; // Default to 0 if no data for that date
                }),
                backgroundColor: '#f583a9',
            }],
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date',
                    },
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Premium Users',
                    },
                    beginAtZero: true,
                },
            },
        },
    });

    // Daily Dates Chart
    dailyDatesChart.value = new Chart(document.getElementById('dailyDatesChart'), {
        type: 'bar',
        data: {
            labels: daysInRange,
            datasets: [{
                label: 'Daily Dates',
                data: daysInRange.map(date => {
                    const dateData = props.dailyDateData.find(data => data.date === date);
                    return dateData ? dateData.count : 0; // Default to 0 if no data for that date
                }),
                backgroundColor: '#f583a9',
            }],
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date',
                    },
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Dates',
                    },
                    beginAtZero: true,
                },
            },
        },
    });
});
</script>

<style scoped>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: scale(1.05);
}
</style>
