<template>
    <AdminLayout>
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Dating Lists</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Restaurant Logo</th>
                                    <th>Restaurant Name</th>
                                    <th>Booked User</th>
                                    <th>Date User</th>
                                    <th>Date Time</th>
                                    <th>Countdown</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="date in dates" :key="date.date_id">
                                    <td><img :src="date.shop_logo" alt="Shop Logo" class="logo-image" /></td>
                                    <td>{{ date.restaurant_name }}</td>
                                    <td>{{ date.booked_user }}</td>
                                    <td>{{ date.date_user }}</td>
                                    <td>{{ new Date(date.date_time).toLocaleString() }}</td>
                                    <td>{{ date.countdown }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { defineProps } from 'vue';
import AdminLayout from '../Layout/AdminLayout.vue';

const props = defineProps({
    dates: Array,
});
</script>

<style>
#wrapper {
    display: flex;
}

#page-content-wrapper {
    flex: 1;
}
.container-fluid {
    margin-top: 20px;
}

.card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.card-header {
    background-color: #343a40;
    color: white;
    padding: 15px;
    border-bottom: none;
}

.table {
    margin-bottom: 0;
}

.table thead {
    background-color: #f8f9fa;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f2f2f2;
}

.table td,
.table th {
    vertical-align: middle;
}

.logo-image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
</style>
