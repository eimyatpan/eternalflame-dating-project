<template>
    <UserNavbar :userProfile="myProfile" />
    <div class="profile-page">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-left">
                <img :src="user.profileImage.profile_picture" alt="Profile Picture" class="profile-picture" />
            </div>
            <div class="profile-right">
                <div class="header-content">
                    <h1 class="user-name">
                        <span>{{ user.name }}</span>
                    </h1>
                    <div class="basic-info">
                        <div>
                            <strong class="d-inline pe-2">Age:</strong>
                            <span>{{ user.age }}</span>
                        </div>
                        <div>
                            <strong class="d-inline pe-2">Location:</strong>
                            <span>{{ user.location }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- A Few Words about Myself Section -->
        <div class="about-section">
            <h2>A Few Words about Myself</h2>
            <hr>
            <!-- Display Bio -->
            <p>{{ user.bio }}</p>
        </div>
        <!-- Photo Gallery -->
        <div class="gallery-section">
            <h2>My Photo Gallery</h2>
            <hr>
            <div class="photo-grid">
                <img v-for="(photo, index) in user.photos" :key="index" :src="photo" alt="User Photo"
                    class="gallery-photo" @click="viewPhoto(photo)" />
            </div>
        </div>
        <br>

        <!-- My Interests Section -->
        <div class="interests-section">
            <h2>My Interests</h2>
            <hr />

            <div>
                <ul class="interests-list">
                    <li v-for="(hobby, index) in userInterests" :key="index">
                        {{ hobby }}
                    </li>
                </ul>
            </div>

        </div>

        <div class="about-me-section">
            <div class="section-header">
                <h2>About Me</h2>
            </div>
            <hr>

            <!-- Display Preferences -->
            <div class="preferences-info">
                <strong>Occupation:</strong> {{ user.occupation }}
            </div>
            <div class="preferences-info">
                <strong>Education:</strong>{{ user.education }}
            </div>
            <div class="preferences-info">
                <strong>Height:</strong>{{ user.height }} cm
            </div>
            <div class="preferences-info">
                <strong>Body Type:</strong>{{ user.bodyType }}
            </div>
            <div class="preferences-info">
                <strong>Eye Color:</strong> {{ user.eyeColor }}
            </div>
            <div class="preferences-info">
                <strong>Hair Color:</strong> {{ user.hairColor }}
            </div>
            <div class="preferences-info">
                <strong>Relationship Status:</strong> {{ user.relationshipStatus }}
            </div>
        </div>

        <!-- Preferences Section -->
        <div class="preferences-section">
            <div class="section-header">
                <h2>My Preferences</h2>
            </div>
            <hr>

            <!-- Display Preferences -->
            <div class="preferences-info">
                <strong>Looking for:</strong> {{ user.genderPreference }}
            </div>
            <div class="preferences-info">
                <strong>Age:</strong> From {{ user.minAgePreference }} to {{ user.maxAgePreference }}
            </div>
            <div class="preferences-info">
                <strong>Weight:</strong> From {{ user.minWeightPreference }} to {{ user.maxWeightPreference }} kg
            </div>
            <div class="preferences-info">
                <strong>Height:</strong> From {{ user.minHeightPreference }} to {{ user.maxHeightPreference }} cm
            </div>
            <div class="preferences-info">
                <strong>Description:</strong> {{ user.descriptionPreference }}
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import UserNavbar from '../Components/UserNavbar.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { route } from 'ziggy-js';
const props = defineProps({
    userProfile: Object,
    countries:Array,
    hobbies:Array,
});


const user = ref({
    name: props.userProfile?.user_name || 'Unknown User',
    age: props.userProfile?.user_age || 'Unknown Age',
    birthday: props.userProfile?.user_dateOfBirth || 'Unknown Birthday',
    country_id: props.userProfile?.country?.country_id || null,
    location: props.userProfile?.country?.country_name || 'Unknown Location', // Fetch country name safely
    profileImage: props.userProfile.profiles,
    bio: props.userProfile?.profiles?.bio || 'No bio available',
    occupation: props.userProfile?.about_me?.occupation || 'Unknown Occupation',
    education: props.userProfile?.about_me?.education || 'Unknown Education',
    height: props.userProfile?.about_me?.height || 'Unknown Height',
    bodyType: props.userProfile?.about_me?.body_type || 'Unknown Body Type',
    eyeColor: props.userProfile?.about_me?.eye_color || 'Unknown Eye Color',
    hairColor: props.userProfile?.about_me?.hair_color || 'Unknown Hair Color',
    relationshipStatus: props.userProfile?.about_me?.relationship_status || 'Unknown Status',
    gender: props.userProfile?.user_gender || 'Unknown Gender',
    genderPreference: props.userProfile?.preferences?.preference_gender || 'Unknown Preference', // Handle preferences safely
    minAgePreference: props.userProfile?.preferences?.preference_min_age || 'Unknown Min Age',
    maxAgePreference: props.userProfile?.preferences?.preference_max_age || 'Unknown Max Age',
    minWeightPreference: props.userProfile?.preferences?.preference_min_weight || 'Unknown min weight',
    maxWeightPreference: props.userProfile?.preferences?.preference_max_weight || 'Unknown max weight',
    minHeightPreference: props.userProfile?.preferences?.preference_min_height || 'Unknown min height',
    maxHeightPreference: props.userProfile?.preferences?.preference_max_height || 'Unknown max height',
    descriptionPreference: props.userProfile?.preferences?.preference_description || 'Unknown Eye Color',
    photos: props.userProfile?.photos?.group_of_photos?.url || [], // Safely access group of photos
    hobbies: props.userProfile?.hobbies?.map(hobby => hobby.hobby_name).join(', ') || 'No Hobbies'
});

const userInterests = ref([...props.hobbies]); // Initialize with hobbies from props

</script>



<style scoped>
/* Profile Page Styles */
.profile-page {
    max-width: 100%;
    margin: 0 auto;
    padding: 200px;
    font-family: 'Arial', sans-serif;
}

/* Profile Header */
.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f5f5f5;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.profile-left {
    flex: 0 0 150px;
}

.profile-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    position: relative;
}

.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
}

.header-content {
    margin-left: 20px;
    position: relative;
}

.user-name {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.basic-info div {
    margin: 10px 0;
}

.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

/* Modal styles */
.modal {
    display: block;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 500px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    position: relative;
}

.close {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 30px;
    cursor: pointer;
}

form div {
    margin-bottom: 10px;
}

form label {
    display: inline-block;
    margin-bottom: 5px;
}

form input,
form select {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

form button {
    background-color: #ff4081;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}

form button:hover {
    background-color: #ff4081;
}

.primary-btn,
.secondary-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
}

.secondary-btn {
    background-color: #fff;
    color: #ff4081;
    border: 1px solid #ff4081;
}

/* About Section */
.about-section {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
}

.about-section h2 {
    margin-bottom: 10px;
}

.about-section .edit-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    cursor: pointer;
    color: #ff4081;
}

.preferences-info-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.preferences-info-row strong {
    width: 150px;
    /* Adjust based on your preference */
    text-align: right;
    margin-right: 10px;
}

.preferences-info-row input,
.preferences-info-row select {
    flex: 1;
    /* Take up the remaining space */
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Modal Styles */
.modal {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content1 {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    max-width: 700px;
    width: 100%;
    height: 400px;
    position: relative;
}

.close1 {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 30px;
    cursor: pointer;
}

/* Example Text Styles */
.example-text {
    padding: 20px;
    font-size: 15px;
    font-style: italic;
    color: #888;
    margin-bottom: 20px;

}

/* Textarea for Bio */
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    resize: none;
    margin-bottom: 15px;
}

/* Button Group */
.button-group {
    display: flex;
    padding: 5px;
    justify-content: center;
}

/* Save Button */
.save-btn {
    background-color: #ff4081;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.save-btn:hover {
    background-color: #ff4081;
}

/* Photo Gallery */
.gallery-section {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.photo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

.gallery-photo {
    width: 100%;
    height: 200px;
    object-fit: cover;
    cursor: pointer;
    border-radius: 5px;
    transition: transform 0.3s ease;
}

.gallery-photo:hover {
    transform: scale(1.05);
}

.add-photo-btn {
    padding: 10px;
    background-color: #ff4081;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* My Interests Section */
.interests-section {
    position: relative;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.interests-section h2 {
    margin-bottom: 10px;
}

.interests-list {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
}

.interests-list li {
    background-color: #ff4081;
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    margin-right: 10px;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.edit-icon {
    font-size: 24px;
    cursor: pointer;
    color: #007bff;
    margin-left: 10px;
    position: absolute;
    top: 10px;
    right: 10px;
}

.primary-btn {
    background-color: #ff4081;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
}

.about-me-section {
    position: relative;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 1.5rem 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

h2 {
    font-size: 1.5rem;
    color: #333;
    margin: 0;
}

.edit-icon,
.save-icon {
    font-size: 1.5rem;
    cursor: pointer;
    color: #ff4081;
    transition: color 0.2s;
}

.edit-icon:hover,
.save-icon:hover {
    color: #ff4081;
}

.about-me-content {
    line-height: 1.6;
}

.appearance-info {
    margin: 1rem 0;
}

.appearance-info strong {
    display: inline-block;
    width: 100px;
}

input[type="text"],
input[type="number"],
select {
    padding: 0.5rem;
    border-radius: 4px;
    border: 1px solid #ccc;
    width: calc(100% - 110px);
    margin-left: 10px;
    transition: border-color 0.2s;
}

input[type="text"]:focus,
input[type="number"]:focus,
select:focus {
    border-color: #ff4081;
}

.preferences-section {
    position: relative;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 1.5rem 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.edit-icon {
    font-size: 1.5rem;
    cursor: pointer;
    color: #ff4081;
    transition: color 0.2s;
}

.edit-icon:hover {
    color: #ff4081;
}

.preferences-info {
    margin: 1rem 0;
}

h2 {
    font-size: 1.5rem;
    color: #333;
    margin: 0;
}

strong {
    display: inline-block;
    width: 200px;
}

input[type="number"],
textarea,
select {
    padding: 0.5rem;
    border-radius: 4px;
    border: 1px solid #ccc;
    width: calc(100% - 210px);
    margin-left: 10px;
    transition: border-color 0.2s;
}

input[type="number"]:focus,
select:focus,
textarea:focus {
    border-color: #007bff;
}

textarea {
    height: 80px;
    resize: none;
}

.cancel-btn {
    background-color: gray;
    color: white;
    border: none;
    padding: 10px;
    margin-top: 10px;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 10px;
}

.modal-overlay1 {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content2 {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    position: relative;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-top: 60px;
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

.hobbies-container {
    margin-bottom: 20px;
}

.hobby-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.hobby-input {
    flex: 1;
    margin-right: 8px;
    padding: 5px;
}

.remove-btn {
    background-color: red;
    color: white;
    border: none;
    padding: 5px;
    cursor: pointer;
    border-radius: 4px;
}

.button-container {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.add-btn {
    background-color: rgb(99, 247, 99);
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 4px;
}
</style>