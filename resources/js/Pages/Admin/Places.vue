<template>
    <AdminLayout>
        <div class="container mt-5">
            <h2 class="mb-4">Place Management</h2>

            <!-- Search and Filters -->
            <div class="row mb-4">
                <div class="col-lg-4">
                    <input type="text" class="form-control" placeholder="Search Places..." />
                </div>
                <div class="col-lg-3">
                    <select class="form-select" v-model="selectedCountry" @change="filterPlaces">
                        <option value="" disabled selected>Filter by Country</option>
                        <option v-for="country in countries" :key="country.id" :value="country.country_name">
                            {{ country.country_name }}
                        </option>
                    </select>
                </div>

                <div class="col-lg-2 text-end">
                    <Link href="/admin/datePlaces/create">
                        <button style="background-color:#ff4081;border-color:#ff4081" class="btn btn-primary">Add New Place</button>
                    </Link>
                </div>
            </div>

            <!-- Places Card View -->
            <div class="row">
                <div class="col-md-3" v-for="place in filteredRestaurants" :key="place.restaurant_id">
                    <div class="card mb-4 shadow-sm fixed-card">
                        <img :src="place.shop_logo" class="card-img-top" alt="Place Image" />
                        <div class="card-body">
                            <h5 class="card-title">{{ place.restaurant_name }}</h5>
                            <p class="card-text">
                                <strong>Country:</strong> {{ place.country_name }}<br>
                                <strong>Address:</strong> {{ place.address }}
                            </p>
                            <!-- Rating Stars -->
                            <div class="mb-2">
                                <span v-for="n in 5" :key="n" class="fa"
                                    :class="n <= place.rating ? 'fa-star checked' : 'fa-star'"></span>
                            </div>
                            <!-- Details Button -->
                            <div class="d-flex justify-content-between align-items-center">
                                <button style="background-color:#ff4081;border-color:#ff4081;color:white;" class="btn btn-info btn-sm" @click="viewDetails(place.restaurant_id)">Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '../Layout/AdminLayout.vue';
import { ref, computed} from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    countries: Array,
    restaurants: Array,
});

const selectedCountry = ref('');
// Computed property to filter restaurants based on selected country
const filteredRestaurants = computed(() => {
    if (!selectedCountry.value) return props.restaurants;
    return props.restaurants.filter(
        (restaurant) => restaurant.country_name === selectedCountry.value
    );
});
const viewDetails = (restaurantId) => {
    router.get(route('admin.viewDetail', { restaurantId }));
};
</script>

<style scoped>
#wrapper {
    display: flex;
}

#page-content-wrapper {
    flex: 1;
}

.fixed-card {
    height: 100%;
    max-height: 400px; /* Adjust as needed */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.card-body {
    flex-grow: 1;
    padding: 1rem;
    text-align: left;
}

.card-title {
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.card-text {
    font-size: 0.9rem;
    color: #555;
}

.fa-star {
    font-size: 18px;
    color: #ddd;
}

.checked {
    color: #ffaa00;
}
</style>
