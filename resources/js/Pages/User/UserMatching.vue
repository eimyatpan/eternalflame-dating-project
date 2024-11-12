<template>
    <UserNavbar />
    <div class="matching-cards-container">
        <div v-for="match in matches" :key="match.id" class="matching-card">
            <div class="matching-card-left">
                <div class="profile-pictures">
                    <!-- Logged-in User's Profile Picture -->
                    <img :src="myProfile.profiles.profile_picture" alt="Your Profile Picture" class="profile-picture" />
                    <!-- Match User's Profile Picture -->
                    <img :src="match.profiles.profile_picture" alt="Match Profile Picture" class="profile-picture" />
                </div>
                <div class="score-wrapper">
                    <div class="score-circle">
                        <span class="score-percentage">{{ match.compatibilityScore }}</span>
                    </div>
                    <p class="score-text">You and {{ match.user_name }} are {{ match.compatibilityScore }} compatible!
                    </p>
                </div>
            </div>

            <!-- Divider Line -->
            <div class="divider"></div>

            <div class="matching-card-right">
                <h3>Here’s what matters to both of you</h3>
                <p><strong>Country:</strong> {{match.country.country_name }} ({{ myProfile.country_id === match.country_id ? 'Same' : 'Different' }}) </p>
                <p>
                    <strong>Age:</strong> {{ match.user_age }} years old
                    <span v-if="myProfile.user_age !== match.user_age">
                        ({{ Math.abs(myProfile.user_age - match.user_age) }} years difference)
                    </span>
                    <span v-else>({{ 'Same Age' }})</span>
                </p>
                <p v-if="commonHobbies(match).length > 0">
                    <strong>Same hobbies: </strong>{{ commonHobbies(match).join(', ') }}
                </p>
                <p v-else>No common hobbies</p>
                <p v-if="differentHobbies(match).length > 0">
                    <strong>Different hobbies: </strong>{{ differentHobbies(match).join(', ') }}
                </p>
                <!-- Move Chat Icon Button here -->
                <button class="btn btn-light chat-button" @click="sendMessage">
                    <i class="bi bi-chat-left-heart-fill"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import UserNavbar from '../Components/UserNavbar.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
// Use props to get data passed from Inertia
const props = defineProps({
    myProfile: Array,
    matches: Array
});

// Function to find common hobbies between logged-in user and the match
const commonHobbies = (match) => {
    return props.myProfile.hobbies
        .map(hobby => hobby.hobby_name)  // Extract hobby_name from objects
        .filter(hobby_name => match.hobbies.map(h => h.hobby_name).includes(hobby_name));
};

const differentHobbies = (match) => {
    return match.hobbies
        .map(hobby => hobby.hobby_name)  // Extract hobby_name from objects
        .filter(hobby_name => !props.myProfile.hobbies.map(h => h.hobby_name).includes(hobby_name));
};

const sendMessage = () => {
    Inertia.visit('/user/messages');
};
</script>

<style scoped>
.matching-cards-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 190px auto;
    max-width: 960px;
}

.matching-card {
    display: flex;
    justify-content: space-between;
    border: 2px solid #ff4081;
    border-radius: 8px;
    padding: 20px;
    background-color: white;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

.matching-card-left {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    flex: 1;
}

.matching-card-right {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    flex: 1;
    text-align: right;
}

.divider {
    width: 2px;
    background-color: #ff4081;
    margin: 0 10px;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 45%;
    transform: translateX(-50%);
}

.profile-pictures {
    display: flex;
    justify-content: space-between;
    width: 160px;
    margin-bottom: 15px;
}

.profile-picture {
    width: 80px;
    height: 80px;
    object-fit: cover;
    object-position: center;
    border-radius: 50%;
    border: 2px solid #ff4081;
}

.score-wrapper {
    margin-top: 20px;
    align-items: center;
}

.score-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: #ff4081;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 20px;
    font-weight: bold;
}

.score-percentage {
    font-size: 20px;
}

.score-text {
    margin-top: 10px;
    font-size: 14px;
    color: #333;
}

.chat-button {
    margin-top: 20px;
    display: flex;
    align-items: center;
    font-size: 18px;
    color: white;
    background-color: #ff4081;
    border: none;
    padding: 10px 30px;
    border-radius: 10px;
    cursor: pointer;
}

.chat-button:hover {
    background-color: #e53670;
}

h3 {
    margin-bottom: 10px;
    font-size: 18px;
    color: #333;
}

p {
    margin: 5px 0;
    font-size: 14px;
    color: #555;
}
</style>
