<template>
    <UserNavbar />
    <div class="content-container">
        <h2 class="mb-4 text-center">Date Places</h2>

        <!-- Restaurant Card View -->
        <div class="restaurant-card-container">
            <div v-for="place in uniqueRestaurants" :key="place.restaurant_id" class="restaurant-card mb-4 shadow-sm">
                <!-- Main Content Container (Left and Right Sections) -->
                <div class="main-content">
                    <!-- Left Side: Photo Carousel & Logo -->
                    <div class="photo-container">
                        <img :src="place.photos[currentPhotoIndex[place.restaurant_id] || 0]" class="restaurant-photo"
                            alt="Restaurant Photo" />
                        <img :src="place.shop_logo" class="shop-logo" alt="Shop Logo" />
                        <button v-if="place.photos.length > 1" @click="nextPhoto(place.restaurant_id)"
                            class="photo-arrow">&rarr;</button>
                    </div>

                    <!-- Right Side: Details -->
                    <div class="details-container">
                        <h5 class="restaurant-name">{{ place.restaurant_name }}</h5>
                        <!-- Template Update for Half Stars -->
                        <div class="mb-2">
                            <span v-for="n in 5" :key="n" class="fa" :class="getStarClass(place.rating, n)"></span>
                        </div>


                        <p class="details">
                            <strong>Address:</strong> {{ place.address }},{{ place.city }},{{ place.country_name
                            }}<br />
                            <strong>Avg. Cost:</strong> ${{ place.averageCost }}<br />
                            <strong>Open Days:</strong> {{ place.open_days }}<br />
                            <strong>Time:</strong>
                            {{ formatTime(place.open_time) }} - {{ formatTime(place.close_time) }}<br />
                        </p>
                        <div class="button-group">
                            <button
                            :disabled="!isPremium"
                                style="background-color:#ff4081;border-color:#ff4081;color:white;padding: 8px 16px;border-radius: 5px;font-size: 16px;"
                                class="btn btn-primary btn-sm" @click="openBookingModal(place)">Book</button>
                            <button
                                style="background-color:#ff4081;border-color:#ff4081;color:white; padding: 8px 16px;border-radius: 5px;font-size: 16px;"
                                class="btn btn-secondary btn-sm"
                                @click="openMap(place.latitude, place.longitude)">Location</button>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="description-container">
                    <h6>Description</h6>
                    <p class="description">{{ place.restaurant_description }}</p>
                </div>
            </div>
        </div>

        <!-- Booking Modal -->
        <div v-if="isModalVisible" class="modal-overlay">
            <div class="modal-content">
                <button class="close-btn" @click="closeModal">&times;</button>
                <img :src="selectedPlace?.shop_logo" class="modal-logo" alt="Shop Logo" />
                <h4>{{ selectedPlace?.restaurant_name }}</h4>
                <p><strong>Avg. Cost:</strong> ${{ selectedPlace?.averageCost }}</p>
                <p><strong>Rating:</strong>
                    <span v-for="n in 5" :key="n" class="fa"
                        :class="getStarClass(selectedPlace?.rating || 0, n)"></span>
                </p>
                <p><strong>Country:</strong> {{ selectedPlace?.country_name }}</p>
                <p><strong>Logged-in User:</strong> {{ loginUser.user_name }}</p>

                <!-- Updated Booking Form -->
                <form @submit.prevent="confirmBooking" class="booking-form">
                    <!-- Who You Date (Select Box) -->
                    <div class="form-group">
                        <label for="userDate">Who You Date</label>
                        <select id="userDate" class="form-control" v-model="form.user_id2" required>
                            <option value="" disabled>Select a user</option>
                            <option v-for="user in users" :key="user.user_id" :value="user.user_id">
                                {{ user.user_name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dateTime">Date Time</label>
                        <input type="datetime-local" id="dateTime" class="form-control" v-model="form.date_time"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="bookingDate">Booking Date</label>
                        <input type="date" id="bookingDate" class="form-control" v-model="form.booking_date" required />
                    </div>
                    <div class="form-group">
                        <label for="bookingTime">Booking Time</label>
                        <input type="time" id="bookingTime" class="form-control" v-model="form.booking_time" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Confirm Booking</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import UserNavbar from '../Components/UserNavbar.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    countries: Array,
    restaurants: Array,
    loginUser: Array,
    users: Array,
    isPremium: Boolean,
});
const searchQuery = ref('');
const selectedCountry = ref('');
const currentPhotoIndex = ref({});
const isModalVisible = ref(false);
const selectedPlace = ref(null);
const uniqueRestaurants = computed(() => {
    const restaurantMap = {};

    props.restaurants.forEach((restaurant) => {
        // Check if restaurant entry exists; if not, create it
        if (!restaurantMap[restaurant.restaurant_id]) {
            restaurantMap[restaurant.restaurant_id] = {
                ...restaurant,
                photos: restaurant.restaurant_photo_name ? [restaurant.restaurant_photo_name] : [],
            };
        } else if (restaurant.restaurant_photo_name) {
            // Add photo to existing restaurant entry if available
            restaurantMap[restaurant.restaurant_id].photos.push(restaurant.restaurant_photo_name);
        }
    });

    return Object.values(restaurantMap);
});
const form = useForm({
    restaurant_id: '',
    user_id2: '', // "Who You Date" field
    booking_date: '',
    booking_time: '',
    date_time: '',
});
const dateUser = ref(''); // New field for "User You Date"
const dateTime = ref(''); // New field for "Date Time"
const bookingDate = ref(''); // New field for "Booking Date"
const bookingTime = ref(new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })); // Booking time auto-filled
const formatTime = (time24) => {
    const [hours, minutes] = time24.split(':');
    const suffix = hours >= 12 ? 'PM' : 'AM';
    const hours12 = hours % 12 || 12;
    return `${hours12}:${minutes} ${suffix}`;
};
// Filter restaurants based on search query and selected country
const filterRestaurants = () => {
    // Your filtering logic here
};

// Navigate to the next photo
const nextPhoto = (restaurantId) => {
    const maxIndex = uniqueRestaurants.value.find(place => place.restaurant_id === restaurantId).photos.length - 1;
    currentPhotoIndex.value[restaurantId] = ((currentPhotoIndex.value[restaurantId] || 0) + 1) % (maxIndex + 1);
};

const openBookingModal = (place) => {
    selectedPlace.value = place;
    form.restaurant_id = place.restaurant_id; // Ensure restaurant_id is set here
    isModalVisible.value = true;
};

const closeModal = () => {
    isModalVisible.value = false;
};
// Function to confirm booking and submit form
const confirmBooking = () => {
    form.post(route('user.bookDate'), {
        onSuccess: () => {
            alert('Booking confirmed!');
            closeModal();
        },
    });
};
function openMap(latitude, longitude) {
    window.open(`https://www.google.com/maps/search/?api=1&query=${latitude},${longitude}`, '_blank');
}

// Script Update for Half Stars
const getStarClass = (rating, starNumber) => {
    const integerPart = Math.floor(rating);
    const decimalPart = rating - integerPart;

    if (starNumber <= integerPart) {
        return 'fa-star checked'; // Full star
    } else if (starNumber === integerPart + 1 && decimalPart >= 0.5) {
        return 'fa-star-half checked'; // Half star
    } else {
        return 'fa-star'; // Empty star
    }
};


</script>

<style scoped>
.btn-primary:disabled {
    background-color: #9f9a9a;
    cursor: not-allowed;
}
.modal-overlay {
    position: fixed;
    top: 100px;
    /* Adjusts position to appear below the navbar */
    left: 0;
    width: 100%;
    height: calc(100% - 80px);
    /* Adjusts height to cover only below the navbar */
    display: flex;
    justify-content: center;
    align-items: flex-start;
    /* Aligns modal at the top of the screen below navbar */
    background: rgba(0, 0, 0, 0.5);
    /* Slightly darkens outside modal */
    overflow-y: auto;
    /* Enables scrolling when content overflows */
    padding-top: 30px;
    /* Additional padding for modal content */
}

/* Adjust the modal content to add some margin at the top */
.modal-content {
    background: #ffffff;
    padding: 50px;
    border-radius: 10px;
    max-width: 400px;
    width: 100%;
    max-height: 80vh;
    /* Limits modal height for viewport */
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
    text-align: center;
    position: relative;
    overflow-y: auto;
    /* Enables internal scrolling */
    margin-top: 20px;
    /* Adds margin between the navbar and modal content */
}


/* Close Button */
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    background: none;
    border: none;
    cursor: pointer;
}

/* Additional styling for form and other elements */
.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn-primary {
    background-color: #ff4081;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

/* Logo styling within the modal */
.modal-logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 15px;
    border: solid #ff4081;
}

.content-container {
    padding-top: 150px;
    /* Adjust if necessary based on navbar height */
    padding-left: 15px;
    padding-right: 15px;
}

.search-filter-container {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.restaurant-card-container {
    display: flex;
    flex-direction: column;
    /* Arrange cards in a single column */
    align-items: center;
}

.restaurant-card {
    display: flex;
    flex-direction: column;
    /* Stacks main content and description vertically */
    width: 100%;
    max-width: 900px;
    background: #fcd9e5;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0px 4px 12px rgba(222, 70, 70, 0.1);
    transition: transform 0.3s;
}

.main-content {
    display: flex;
    flex-direction: row;
    /* Left and Right side side-by-side */
}

.restaurant-card:hover {
    transform: scale(1.02);
}

.photo-container {
    position: relative;
    width: 300px;
    /* Fixed width for uniform size */
    height: 200px;
    /* Fixed height for uniform size */
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 8px 0 0 8px;
}

.restaurant-photo {
    width: 100%;
    /* Ensures the photo fills the container */
    height: 100%;
    /* Ensures the photo fills the container */
    object-fit: cover;
    /* Ensures photo aspect ratio is preserved */
}

.shop-logo {
    position: absolute;
    top: 10px;
    left: 10px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 2px solid #fff;
    background-color: #fff;
    object-fit: cover;
}

.photo-arrow {
    position: absolute;
    top: 50%;
    right: 10px;
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 5px;
    cursor: pointer;
    transform: translateY(-50%);
    border-radius: 50%;
}

.details-container {
    padding: 15px;
    width: calc(100% - 250px);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: auto;
    /* Adjust height based on content */
}

.description-container {
    padding: 15px;
    border-top: 1px solid #ddd;
}

.description-container h6 {
    font-weight: bold;
    margin-bottom: 5px;
}

.description {
    text-align: justify;
    margin: 0;
    color: #666;
    overflow-wrap: break-word;
}

.restaurant-name {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.rating .fa-star {
    font-size: 16px;
    color: white;
    border: solid black;
}

.checked {
    color: #fcd600;
}

.details {
    font-size: 0.9rem;
    color: #555;
}

.description {
    margin-top: 10px;
    color: #666;
}

.button-group {
    display: flex;
    gap: 10px;
}

.btn-primary {
    background-color: #ff4081;
    border-color: #ff4081;
    color: white;
}

.btn-secondary {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}
</style>
