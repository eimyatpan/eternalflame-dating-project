<template>
    <AdminLayout>
        <div class="container mt-5">
            <h2 class="mb-4">Create a New Restaurant</h2>

            <div class="card p-4">
                <form @submit.prevent="submitForm">

                    <!-- Restaurant Logo -->
                    <div class="mb-3">
                        <label for="restaurantLogo" class="form-label">Restaurant Logo</label>
                        <input type="file" class="form-control" id="restaurantLogo" @change="handleLogoUpload" />
                    </div>

                    <!-- Restaurant Name -->
                    <div class="mb-3">
                        <label for="restaurantName" class="form-label">Restaurant Name</label>
                        <input type="text" class="form-control" v-model="formData.restaurantName" id="restaurantName"
                            placeholder="Enter restaurant name" />
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" v-model="formData.phone" id="phone"
                            placeholder="Enter phone number" />
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" v-model="formData.address" id="address"
                            placeholder="Enter address" />
                    </div>

                    <!-- Country -->
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" v-model="formData.country" id="country"
                            placeholder="Enter country" />
                    </div>

                    <!-- City -->
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" v-model="formData.city" id="city"
                            placeholder="Enter city" />
                    </div>

                    <!-- Latitude and Longitude -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control" v-model="formData.latitude"
                                id="latitude" placeholder="Enter latitude" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control" v-model="formData.longitude"
                                id="longitude" placeholder="Enter longitude" />
                        </div>
                    </div>

                    <!-- Average Cost -->
                    <div class="mb-3">
                        <label for="averageCost" class="form-label">Average Cost</label>
                        <input type="number" step="0.01" class="form-control" v-model="formData.averageCost"
                            id="averageCost" placeholder="Enter average cost" />
                    </div>

                    <!-- Rating -->
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <input type="number" step="0.1" class="form-control" v-model="formData.rating" id="rating"
                            placeholder="Enter rating (0-5)" min="0" max="5" />
                    </div>

                    <!-- Open and Close Time -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="openTime" class="form-label">Open Time</label>
                            <input type="time" class="form-control" v-model="formData.openTime" id="openTime" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="closeTime" class="form-label">Close Time</label>
                            <input type="time" class="form-control" v-model="formData.closeTime" id="closeTime" />
                        </div>
                    </div>

                    <!-- Days Open -->
                    <div class="mb-3">
                        <label for="openDays" class="form-label">Days Open</label>
                        <select class="form-select" v-model="formData.openDays" id="openDays">
                            <option value="" disabled selected>Select days</option>
                            <option value="Weekdays">Weekdays</option>
                            <option value="Weekends">Weekends</option>
                            <option value="Daily">Daily</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" v-model="formData.description" id="description" rows="4"
                            placeholder="Enter restaurant description"></textarea>
                    </div>

                    <!-- Restaurant Photos -->
                    <div class="mb-3">
                        <label for="restaurantPhotos" class="form-label">Restaurant Photos</label>
                        <input type="file" class="form-control" id="restaurantPhotos" @change="handlePhotoUpload"
                            multiple />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100">Create Restaurant</button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '../Layout/AdminLayout.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

// Initialize form data
const formData = useForm({
    restaurantName: '',
    phone: '',
    address: '',
    country: '',
    city: '',
    latitude: '',
    longitude: '',
    averageCost: '', // Added average cost
    rating: '' ,// Added rating
    openTime: '',
    closeTime: '',
    openDays: '',
    description: '',
    logo: null, // This will hold the restaurant logo file
    photos: [] // This will hold the selected photo files
});

// Variable to store errors
const formErrors = ref(null);

// Function to handle file input change for logo
const handleLogoUpload = (event) => {
    const file = event.target.files[0]; // Get the selected logo file
    formData.logo = file; // Assign the selected file to logo
};

// Function to handle file input change for multiple photos
const handlePhotoUpload = (event) => {
    const files = event.target.files; // Get the FileList from the input
    if (files.length > 0) {
        formData.photos = Array.from(files); // Convert FileList to array and store the actual files
    } else {
        formData.photos = []; // Reset if no files are selected
    }
};

// Function to handle form submission
function submitForm() {
    const formDataObj = new FormData(); // Create FormData instance

    // Append form fields to FormData
    formDataObj.append('restaurantName', formData.restaurantName);
    formDataObj.append('phone', formData.phone);
    formDataObj.append('address', formData.address);
    formDataObj.append('country', formData.country);
    formDataObj.append('city', formData.city);
    formDataObj.append('latitude', formData.latitude);
    formDataObj.append('longitude', formData.longitude);
    formDataObj.append('averageCost', formData.averageCost);
    formDataObj.append('rating', formData.rating);
    formDataObj.append('openTime', formData.openTime);
    formDataObj.append('closeTime', formData.closeTime);
    formDataObj.append('openDays', formData.openDays);
    formDataObj.append('description', formData.description);

    // Append the restaurant logo
    if (formData.logo) {
        formDataObj.append('logo', formData.logo);
    }

    // Append multiple photo files
    formData.photos.forEach((photo) => {
        formDataObj.append('photos[]', photo);
    });

    // Send the FormData object via post request
    formData.post('/admin/datePlaces/create', {
        onError: (errorBag) => {
            formErrors.value = errorBag; // Set the errors if any
        },
        forceFormData: true, // Ensure Inertia treats it as FormData
        data: formDataObj // Pass the FormData object directly
    });
}

</script>

<style scoped>
.container {
    max-width: 800px;
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
</style>
