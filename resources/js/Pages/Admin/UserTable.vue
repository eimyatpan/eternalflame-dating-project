<template>
    <AdminLayout>
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Management</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>UserID</th>
                                    <th>Image</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Type</th>
                                    <th>Birthday</th>
                                    <th>Country</th>
                                    <th>Joined Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in userData" :key="user.id">
                                    <td>{{ user.user_id }}</td>
                                    <td>
                                        <img :src="user.profiles.profile_picture" alt="User Image" class="rounded-circle" width="50"
                                            height="50" />
                                    </td>
                                    <td>{{ user.user_name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.user_gender }}</td>
                                    <td>{{ user.user_age }}</td>
                                    <td>
                                        <span v-if="user.premium_user">Premium</span>
                                        <span v-else>Free</span>
                                    </td>
                                    <td>{{ user.user_dateOfBirth }}</td>
                                    <td>{{ user.country_name }}</td>
                                    <td>{{ formatDate(user.created_at) }}</td>
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
import AdminLayout from '../Layout/AdminLayout.vue';

const props = defineProps({
    userData: Object,
});

// Function to format date to "MM/DD/YYYY" or desired format
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    })
}
</script>

<style scoped>
#wrapper {
    display: flex;
}

#page-content-wrapper {
    flex: 1;
}

/* Styling for the table and container */
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

img {
    object-fit: cover;
}
</style>