<template>
    <AdminLayout>
        <div class="place-detail">
            <!-- Header Section -->
            <div class="header-section">
                <img :src="restaurant.shopLogo" alt="Shop Logo" class="shop-logo" />

                <div class="details-section">
                    <h1 class="place-title">
                        {{ restaurant.restaurantName }}
                        <span class="stars">{{ generateStars(restaurant.rating) }}</span>
                    </h1>
                    <div class="info-grid">
                        <p><strong>Address:</strong> {{ restaurant.address || 'Not available' }}</p>
                        <p><strong>City:</strong> {{ restaurant.city || 'Not available' }}</p>
                        <p><strong>Country:</strong> {{ restaurant.country || 'Not available' }}</p>
                        <p><strong>Phone:</strong> {{ restaurant.phone || 'N/A' }}</p>
                        <p><strong>Open Time:</strong> {{ formatTime(restaurant.openTime) || 'N/A' }}</p>
                        <p><strong>Close Time:</strong> {{ formatTime(restaurant.closeTime) || 'N/A' }}</p>
                        <p><strong>Open Days:</strong> {{ restaurant.openDays || 'N/A' }}</p>
                        <p><strong>Average Cost:</strong> {{ formatAverageCost(restaurant.averageCost) }}</p>
                    </div>
                </div>

                <!-- Edit and Delete Icons -->
                <div class="action-icons">
                    <span @click="openEditRestaurantModal" class="bi bi-pencil-square edit-icon" role="button"></span>
                    <span @click="deleteRestaurant" class="bi bi-trash delete-icon" role="button"></span>"
                </div>
            </div>

            <!-- Description Section -->
            <div class="place-description">
                <h2>Description</h2>
                <p>{{ restaurant.description || 'No description available' }}</p>
            </div>

            <!-- Photos Section -->
            <div class="place-photos">
                <h2>Photos</h2>
                <div class="photo-gallery">
                    <img v-for="photo in restaurant.photos" :key="photo.restaurant_photo_id"
                        :src="photo.restaurant_photo_name" alt="Restaurant Photo" class="photo-item" />
                </div>
            </div>

            <!-- Edit Modal -->
            <div v-if="isEditModalOpen" class="modal-overlay">
                <div class="container mt-5">
                    <span @click="closeEditRestaurantModal" class="close-btn">&times;</span>
                    <h2 class="mb-4">Edit Restaurant</h2>

                    <div class="card p-4 modal-content">
                        <form @submit.prevent="submitEdit">
                            <!-- Restaurant Logo -->
                            <div class="mb-3">
                                <label for="restaurantLogo" class="form-label">Restaurant Logo</label>

                                <!-- Show Existing Logo from Database -->
                                <div v-if="form.shopLogo" class="mb-3">
                                    <img :src="form.shopLogo" alt="Restaurant Logo" class="img-fluid"
                                        style="max-height: 150px;" />
                                </div>

                                <!-- File Input to Update Logo -->
                                <input type="file" class="form-control" id="restaurantLogo"
                                    @change="handleLogoUpload" />
                            </div>

                            <!-- Restaurant Name -->
                            <div class="mb-3">
                                <label for="restaurantName" class="form-label">Restaurant Name</label>
                                <input type="text" class="form-control" v-model="form.name" id="restaurantName"
                                    placeholder="Enter restaurant name" />
                            </div>

                            <!-- Phone -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" v-model="form.phone" id="phone"
                                    placeholder="Enter phone number" />
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" v-model="form.address" id="address"
                                    placeholder="Enter address" />
                            </div>

                            <!-- Country -->
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" v-model="form.country" id="country"
                                    placeholder="Enter country" />
                            </div>

                            <!-- City -->
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" v-model="form.city" id="city"
                                    placeholder="Enter city" />
                            </div>

                            <!-- Latitude and Longitude -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="number" step="any" class="form-control" v-model="form.latitude"
                                        id="latitude" placeholder="Enter latitude" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="number" step="any" class="form-control" v-model="form.longitude"
                                        id="longitude" placeholder="Enter longitude" />
                                </div>
                            </div>

                            <!-- Average Cost -->
                            <div class="mb-3">
                                <label for="averageCost" class="form-label">Average Cost</label>
                                <input type="number" step="0.01" class="form-control" v-model="form.averageCost"
                                    id="averageCost" placeholder="Enter average cost" />
                            </div>

                            <!-- Rating -->
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <input type="number" step="0.1" class="form-control" v-model="form.rating" id="rating"
                                    placeholder="Enter rating (0-5)" min="0" max="5" />
                            </div>

                            <!-- Open and Close Time -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="openTime" class="form-label">Open Time</label>
                                    <input type="time" class="form-control" v-model="form.openTime" id="openTime" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="closeTime" class="form-label">Close Time</label>
                                    <input type="time" class="form-control" v-model="form.closeTime" id="closeTime" />
                                </div>
                            </div>

                            <!-- Days Open -->
                            <div class="mb-3">
                                <label for="openDays" class="form-label">Days Open</label>
                                <select class="form-select" v-model="form.openDays" id="openDays">
                                    <option value="Weekdays">Weekdays</option>
                                    <option value="Weekends">Weekends</option>
                                    <option value="Daily">Daily</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" v-model="form.description" id="description" rows="4"
                                    placeholder="Enter restaurant description"></textarea>
                            </div>

                            <!-- Restaurant Photos -->
                            <div class="mb-3">
                                <label for="restaurantPhotos" class="form-label">Restaurant Photos</label>

                                <!-- Photos Preview with Delete Icons -->
                                <div class="photos-grid">
                                    <div v-for="(photo, index) in form.photos" :key="index" class="photo-wrapper">
                                        <img :src="photo.restaurant_photo_name" alt="Restaurant Photo" class="photo" />
                                        <button type="button" class="close-icon" @click="removePhoto(index)">✕</button>
                                    </div>
                                </div>

                                <!-- File Input to Upload Multiple Photos -->
                                <input type="file" class="form-control mt-3" id="restaurantPhotos"
                                    @change="handlePhotoUpload" multiple />
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100">Create Restaurant</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import AdminLayout from '../Layout/AdminLayout.vue';
import { defineProps } from 'vue';

const props = defineProps({
    restaurantDetails: Object,
});

const isEditModalOpen = ref(false); // Ref to manage the modal state

// Restaurant Data
const restaurant = computed(() => ({
    restaurantId: props.restaurantDetails?. restaurant_id,
    shopLogo: props.restaurantDetails?.shop_logo || '',
    restaurantName: props.restaurantDetails?.restaurant_name || 'N/A',
    address: props.restaurantDetails?.location?.address || null,
    city: props.restaurantDetails?.location?.city || null,
    country: props.restaurantDetails?.location?.country?.country_name || null,
    phone: props.restaurantDetails?.restaurant_phone || 'N/A',
    openTime: props.restaurantDetails?.open_time || 'N/A',
    closeTime: props.restaurantDetails?.close_time || 'N/A',
    openDays: props.restaurantDetails?.open_days || 'N/A',
    rating: props.restaurantDetails?.rating || 'N/A',
    averageCost: props.restaurantDetails?.averageCost || 'N/A',
    description: props.restaurantDetails?.restaurant_description || '',
    photos: props.restaurantDetails?.restaurant_photos || [],
    latitude: props.restaurantDetails?.location?.latitude || null,
    longitude: props.restaurantDetails?.location?.longitude || null,
}));
const openEditRestaurantModal = () => {
    isEditModalOpen.value = true;
};

const closeEditRestaurantModal = () => {
    isEditModalOpen.value = false;
};


const form = useForm({
    id: restaurant.value.restaurantId,
    name: restaurant.value.restaurantName,
    address: restaurant.value.address,
    city: restaurant.value.city,
    country: restaurant.value.country,
    latitude: restaurant.value.latitude,
    longitude: restaurant.value.longitude,
    averageCost: restaurant.value.averageCost,
    rating: restaurant.value.rating,
    phone: restaurant.value.phone,
    openTime: restaurant.value.openTime,
    closeTime: restaurant.value.closeTime,
    openDays: restaurant.value.openDays,
    description: restaurant.value.description,
    shopLogo: restaurant.value.shopLogo,
    newLogo: null,
    photos: restaurant.value.photos,
    newPhotos: [],
});
function submitEdit() {
    form.post(route('admin.updateRestaurant', { restaurantId: form.id }), {
        onSuccess: () => {
            console.log('Restaurant updated successfully');
        },
        onError: (errors) => {
            console.error('Failed to update restaurant:', errors);
        },
        preserveScroll: true,
    });
}

const deleteRestaurant = () => {
    if (confirm('Are you sure you want to delete this restaurant?')) {
        Inertia.delete(route('admin.deleteRestaurant', { id: form.id }), {
            onSuccess: () => {
                alert('Restaurant deleted successfully.');
            },
            onError: (errors) => {
                console.error('Failed to delete restaurant:', errors);
            }
        });
    }
};

function formatTime(time) {
    if (!time) return null;
    const [hour, minute] = time.split(':');
    const formattedHour = hour % 12 || 12;
    const ampm = hour >= 12 ? 'PM' : 'AM';
    return `${formattedHour}:${minute} ${ampm}`;
}

function generateStars(rating) {
    const fullStar = '★';
    const emptyStar = '☆';
    return Array.from({ length: 5 }, (_, i) => (i < rating ? fullStar : emptyStar)).join('');
}

function formatAverageCost(cost) {
    if (cost === null || cost === undefined) return 'N/A';
    const formattedCost = parseFloat(cost);
    return `$${Number.isInteger(formattedCost) ? formattedCost : formattedCost.toFixed(2)}`;
}
const handleLogoUpload = (event) => {
    const file = event.target.files[0]; // Get the selected logo file
    form.newLogo = file; // Assign the selected file to logo
};

// Function to handle file input change for multiple photos
const handlePhotoUpload = (event) => {
    const files = event.target.files; // Get the FileList from the input
    if (files.length > 0) {
        form.newPhotos = Array.from(files); // Convert FileList to array and store the actual files
    } else {
        form.newPhotos = []; // Reset if no files are selected
    }
};
// Remove photo from the preview and new photo list
function removePhoto(index) {
    form.photos.splice(index, 1); // Remove from the preview list

    // If it's a new uploaded photo, also remove it from 'newPhotos'
    if (index >= props.restaurant.photos.length) {
        form.newPhotos.splice(index - props.restaurant.photos.length, 1);
    }
}
</script>


<style scoped>
.place-detail {
    padding: 30px;
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.container {
    background-color: #fff;
    border-radius: 8px;
    width: 50%;
    max-height: 80vh;
    /* Restrict modal height */
    overflow: hidden;
    /* Prevent overflow from breaking the layout */
    display: flex;
    flex-direction: column;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: black;
}
.modal-content {
    overflow-y: auto;
    /* Enable vertical scrolling */
    padding: 20px;
    max-height: 70vh;
    /* Allow scrolling within the content */
}

.modal-content::-webkit-scrollbar {
    width: 8px;
}

.modal-content::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background-color: #555;
}
.photos-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.photo-wrapper {
  position: relative;
  width: 100px;
  height: 100px;
  border-radius: 8px;
  overflow: hidden;
}

.photo {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Ensure square shape */
  border-radius: 8px;
}

.close-icon {
  position: absolute;
  top: 5px;
  right: 5px;
  background-color: rgba(0, 0, 0, 0.7);
  color: white;
  border: none;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}
.card {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
}

.form-label {
    font-weight: bold;
}

.btn-primary {
    background-color: #ff4081;
    border-color: #ff4081;
    font-weight: bold;
}

.header-section {
    position: relative;
    display: flex;
    align-items: center;
    gap: 25px;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.shop-logo {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #ddd;
}

.details-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.place-title {
    font-size: 2.5em;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
}

.stars {
    color: #FFD700;
    font-size: 0.7em;
    margin-left: 10px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 10px;
}

.place-description {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    line-height: 1.6;
}

.photo-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.photo-item {
    width: 140px;
    height: 140px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #ddd;
}


.action-icons {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 15px;
}

.edit-icon,
.delete-icon {
    font-size: 1.5rem;
    cursor: pointer;
    color: #ff4081;
    transition: transform 0.3s;
}

.edit-icon:hover,
.delete-icon:hover {
    transform: scale(1.2);
}
</style>
