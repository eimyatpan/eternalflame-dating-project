<template>
    <AdminLayout>
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Country Management</h3>
                </div>
                <div class="card-body">
                    <!-- Add New Country Button -->
                    <div class="mb-4 d-flex justify-content-end">
                        <button class="btn btn-icon" @click="showModal = true">
                            <i class="fas fa-plus"></i> <!-- Plus icon -->
                        </button>
                    </div>

                    <!-- Country List Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th @click="sortCountries('country_id')">No</th>
                                    <th>Flag</th>
                                    <th @click="sortCountries('country_name')">Country Name</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(country, index) in sortedCountries" :key="country.country_id">
                                    <td>{{ country.country_id }}</td>
                                    <td>
                                        <img :src="country.country_image" alt="Flag" class="rounded-circle border-img"
                                            width="50" height="50" />
                                    </td>

                                    <td>{{ country.country_name }}</td>
                                    <td class="text-right">
                                        <button class="btn btn-danger btn-sm"
                                            @click="deleteCountry(index, country.country_id)">
                                            <i class="fas fa-trash-alt"></i> <!-- Trash icon -->
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination mt-3 d-flex justify-content-between">
                        <button class="btn btn-custom" :disabled="currentPage === 1" @click="prevPage">
                            <i class="fas fa-arrow-left"></i> <!-- Previous icon -->
                        </button>
                        <span>Page {{ currentPage }} of {{ totalPages }}</span>
                        <button class="btn btn-custom" :disabled="currentPage === totalPages" @click="nextPage">
                            <i class="fas fa-arrow-right"></i> <!-- Next icon -->
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Country Modal -->
        <div v-if="showModal" class="modal-overlay">
            <div class="modal-content">
                <h3>Add New Country</h3>
                <form @submit.prevent="submitCountry">
                    <div class="form-group">
                        <label for="countryName">Country Name:</label>
                        <input type="text" v-model="form.country_name" id="countryName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="countryImage">Country Flag:</label>
                        <input type="file" @change="handleFileUpload($event)" id="countryImage" class="form-control">
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-custom">Add Country</button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import AdminLayout from '../Layout/AdminLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, computed } from 'vue';

const props = defineProps({
    countriesData: Array,
    totalCountries: Number
});

const showModal = ref(false);
const form = useForm({
    country_name: '',
    country_image: []
});

// Methods to handle modal actions
const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const handleFileUpload = (event) => {
    form.country_image = event.target.files[0]; // Save the file object to the form
};

const submitCountry = () => {
    const formData = new FormData();
    formData.append('country_name', form.country_name);
    formData.append('country_image', form.country_image);

    form.post('/admin/countryTable', {
        data: formData,
        onSuccess: () => {
            closeModal(); // Close the modal on success
        },
        onError: (errors) => {
            console.error(errors); // Handle validation errors
        },
        headers: {
            'Content-Type': 'multipart/form-data', // Set multipart for file upload
        }
    });
};

const deleteCountry = (index, countryId) => {
    if (confirm('Are you sure you want to delete this country?')) {
        // Correctly pass the countryId to the route
        Inertia.delete(`/admin/countryTable/${countryId}`, {
            onSuccess: () => {
                // Remove the country from the local list for immediate UI update
                props.countriesData.splice(index, 1);
            },
            onError: (errors) => {
                console.error(errors); // Handle any errors that occur during deletion
            }
        });
    }
};

// Pagination logic and sorting
const currentPage = ref(1);
const perPage = ref(5);
const sortBy = ref('');
const sortDirection = ref('asc');
const totalPages = computed(() => Math.ceil(props.totalCountries / perPage.value));

const paginatedCountries = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    const end = start + perPage.value;
    return props.countriesData.slice(start, end);
});

const sortedCountries = computed(() => {
    if (!sortBy.value) return paginatedCountries.value;

    return [...paginatedCountries.value].sort((a, b) => {
        const compareA = a[sortBy.value];
        const compareB = b[sortBy.value];

        if (typeof compareA === 'string' && typeof compareB === 'string') {
            return sortDirection.value === 'asc'
                ? compareA.localeCompare(compareB)
                : compareB.localeCompare(compareA);
        }

        return sortDirection.value === 'asc'
            ? compareA > compareB ? 1 : -1
            : compareA < compareB ? 1 : -1;
    });
});

const sortCountries = (key) => {
    if (sortBy.value === key) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = key;
        sortDirection.value = 'asc';
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};
</script>


<style scoped>
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

.text-right {
    text-align: right;
}

img {
    object-fit: cover;
}

.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination button {
    min-width: 100px;
}

.btn-custom {
    background-color: #ff4081;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-custom:hover {
    background-color: pink;
    color: white;
}

.btn-custom:disabled {
    background-color: #cccccc;
    color: white;
    cursor: not-allowed;
}

.btn-custom i {
    color: white !important;
}


.btn-icon {
    color: white !important;
    background-color: #ff4081;
    border: 1px solid #ff4081;
    border-radius: 5px;
    padding: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-icon:hover {
    background-color: #ff4081;
    color: white !important;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
}


.btn-secondary {
    background-color: #6c757d;
    color: white;
}
.border-img {
    border: 3px solid #6c757d; 
    padding: 2px;
}

</style>