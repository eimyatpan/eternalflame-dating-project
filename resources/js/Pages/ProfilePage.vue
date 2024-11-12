<template>
    <HeaderComponent />
    <div class="profile-page">
        <h1>Members</h1>
        <!-- List of Profiles in a Card Layout -->
        <div class="profiles-list">
            <div v-for="user in paginatedUsers" :key="user.user_id" class="profile-card">
                <div class="profile-image">
                    <img v-if="user.profiles.profile_picture" :src="user.profiles.profile_picture" class="profile-img" alt="Profile Image" />
                    <img v-else
                        src="data:image/jpeg;base64,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"
                        alt="">
                </div>
                <div class="profile-info">
                    <p><strong>{{ user.user_name }}</strong></p>
                    <p>{{ user.user_age }} years old</p>
                    <p>{{ user.country_name }}</p>
                    <router-link :to="`/profile/${user.user_id}`">
                        <button class="view-profile-btn">View Profile</button>
                    </router-link>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
            <span>Page {{ currentPage }} of {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
        </div>
    </div>
    <FooterComponent />
</template>

<script setup>
import { reactive, computed, ref, watch } from 'vue'
import HeaderComponent from './Components/HeaderComponent.vue'
import FooterComponent from './Components/FooterComponent.vue'

// Props to receive user data and countries
const props = defineProps({
    users: Array,
    countries: Array,
});

// Reactive properties for filters and pagination
const selectedIam = ref('')
const selectedSeeking = ref('')
const selectedCountry = ref('')
const selectedCity = ref('')
const minAge = ref('')
const maxAge = ref('')
const searchName = ref('')
const currentPage = ref(1)
const perPage = 4 // Number of profiles per page
const showFilters = ref(false) // Show or hide filters

// Toggle filters sidebar
const toggleFilters = () => {
    showFilters.value = !showFilters.value
}

// Clear all filters
const clearFilters = () => {
    selectedIam.value = ''
    selectedSeeking.value = ''
    selectedCountry.value = ''
    selectedCity.value = ''
    minAge.value = ''
    maxAge.value = ''
    filterProfiles()
}

// Filter and Reset Pagination
const filterProfiles = () => {
    currentPage.value = 1;
    filteredProfiles.value;
}

// Computed property for filtered profiles
const filteredProfiles = computed(() => {
    return props.users.filter(user => {
        return (
            (selectedIam.value === '' || user.gender === selectedIam.value) &&
            (selectedSeeking.value === '' || user.gender === selectedSeeking.value) &&
            (selectedCountry.value === '' || user.country_id === selectedCountry.value) && // Changed from user.country_name to user.country_id
            (!minAge.value || user.user_age >= minAge.value) &&
            (!maxAge.value || user.user_age <= maxAge.value)
        )
    })
})


// Pagination logic
const totalPages = computed(() => Math.ceil(filteredProfiles.value.length / perPage))
const paginatedUsers = computed(() => {
    const start = (currentPage.value - 1) * perPage
    return filteredProfiles.value.slice(start, start + perPage)
})

// Pagination methods
const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++
}

const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--
}

// Watch filters
watch([searchName, selectedIam, selectedSeeking, selectedCountry, selectedCity, minAge, maxAge], filterProfiles)

// Smooth transitions for sidebar
const beforeEnter = (el) => {
    el.style.transition = 'opacity 0.3s, transform 0.3s';
    el.style.opacity = '0';
};

const enter = (el) => {
    el.offsetHeight; // Trigger reflow
    el.style.opacity = '1';
    el.style.transform = 'translateX(0)';
};

const leave = (el) => {
    el.style.opacity = '0';
    el.style.transform = 'translateX(100%)';
};
</script>

<style scoped>
/* Profile Page */
.profile-page {
    padding: 40px;
    max-width: 1200px;
    margin: 0 auto;
    margin-top: 100px;
}

/* Search Bar */
.search-bar {
    margin-bottom: 20px;
}

.search-box {
    display: flex;
    align-items: center;
    position: relative;
}

.search-box input {
    padding: 10px 15px;
    width: 100%;
    font-size: 16px;
    border: 2px solid #007bff;
    border-radius: 25px;
}

.search-icon-wrapper {
    margin-left: 10px;
    width: 50px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    pointer-events: none;
}

.search-icon {
    font-size: 18px;
    color: #ff667f;
}


.filter-icon {
    margin-left: 10px;
    font-size: 18px;
    color: #ff667f;
    cursor: pointer;
}

/* Sidebar */
.filter-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 300px;
    height: 100%;
    background-color: #fff;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    z-index: 1000;
    transition: transform 0.3s ease-in-out;
    transform: translateX(0);
}

.sidebar-header {
    margin-bottom: 20px;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

/* Filter Form */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.age-inputs {
    display: flex;
    align-items: center;
}

.age-inputs input {
    width: 80px;
    margin-right: 10px;
}

.age-inputs span {
    margin-right: 10px;
}

/* Profile Cards */
.profiles-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 40px;
}

.profile-card {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    max-width: 250px;
    width: 100%;
}

.profile-image img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
}

.profile-info p {
    margin: 5px 0;
}

.view-profile-btn {
    padding: 10px 20px;
    background-color: #ff667f;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.view-profile-btn:hover {
    background-color: #ff526f;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
}

.pagination button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.pagination button:disabled {
    background-color: #ddd;
    cursor: not-allowed;
}

/* Filter Buttons (styled like the ones on the provided link) */
.filter-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.apply-btn,
.clear-btn {
    padding: 10px 20px;
    font-size: 16px;
    border: 2px solid transparent;
    border-radius: 25px;
    cursor: pointer;
    width: 45%;
    text-transform: uppercase;
    font-weight: bold;
    transition: all 0.3s ease;
}

.apply-btn {
    background-color: #ff667f;
    color: white;
}

.apply-btn:hover {
    background-color: #f93959;
}

.clear-btn {
    background-color: transparent;
    color: #ff667f;
    border-color: #ff667f;
}

.clear-btn:hover {
    background-color: #ff667f;
    color: white;
}

/* Custom Scrollbar for Sidebar */
.filter-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 300px;
    height: 100%;
    background-color: #fff;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    z-index: 1000;
    transition: transform 0.3s ease-in-out;
    transform: translateX(0);
    overflow-y: auto;
    /* Enables vertical scrolling */
}

.filter-sidebar::-webkit-scrollbar {
    width: 8px;
}

.filter-sidebar::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}

.filter-sidebar::-webkit-scrollbar-thumb {
    background-color: #c1c1c1;
    border-radius: 10px;
}

.filter-sidebar::-webkit-scrollbar-thumb:hover {
    background-color: #999;
}
</style>
