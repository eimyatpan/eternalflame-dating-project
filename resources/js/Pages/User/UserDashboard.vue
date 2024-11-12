<template>
    <UserNavbar />
    <div class="dashboard-container">
        <div class="col-md-4">
            <!-- Booking Cards Section -->
            <aside v-if="dates.length" class="bookings bg-light">
                <h2>Your Bookings</h2>
                <div v-for="booking in dates" :key="booking.date_id" class="booking-card">
                    <div v-if="booking.user_id === authUserId">
                    <img :src="booking.restaurantPhoto" alt="Restaurant Photo" class="restaurant-img" />
                    <div class="booking-info">
                        <h5>{{ booking.restaurantName }}</h5>
                        <p>Date: {{ formatDate(booking.date_time) }}</p>
                        <p>Booked User: {{ booking.bookedUser }}</p>
                        <p>Countdown: {{ countdown(booking.date_time) }}</p>
                    </div>
                </div>
                </div>
            </aside>
        </div>
        <div class="row">
            <div class="col-md-8">
                <!-- Match Suggestions Section -->
                <section class="matches bg-light">
                    <h2>Potential Matches</h2>
                    <div class="match-grid">
                        <div v-for="user in users" :key="user.user_id" class="match-card"
                            @click="goToProfile(user.user_id)">
                            <div class="profile-card-content">
                                <img v-if="user.profiles.profile_picture" :src="user.profiles.profile_picture"
                                    class="profile-img" alt="Profile Image" />

                                <img v-else
                                    src="data:image/jpeg;base64,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"
                                    alt="">
                                <div class="match-info">
                                    <h4 class="text-pink">{{ user.user_name }}, {{ user.user_age }}</h4>
                                    <p class="country">{{ user.countryid }}</p>
                                </div>
                            </div>
                            <button class="btn btn-pink" @click="sendMessages">
                                <i class="fa fa-heart like-icon"></i> Send Message
                            </button>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-4">
                <!-- Sidebar for Popular Members -->
                <aside class="popular-members bg-light">
                    <h2>Premium Members</h2>
                    <div v-if="currentMember" class="member-card">
                        <div class="meet-to-info dsp-meet-to-info dspdp-text-center">
                            <h2>Want to Meet Me?</h2>
                            <div class="image-box dspdp-spacer-md dsp-meetme-image">
                                <!-- Link to user's profile -->
                                <a :href="`/profile/${currentMember.user_id}`">
                                    <img v-if="currentMember.profile_picture" :src="currentMember.profile_picture"
                                        :alt="currentMember.user_name" />
                                    <img v-else
                                        src="data:image/jpeg;base64,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"
                                        alt="">
                                </a>
                            </div>
                            <div class="user-meetto-info dspdp-font-2x dspdp-spacer-md dsp-meet-details">
                                <div class="user-info">
                                    {{ currentMember.user_age }}, {{ currentMember.countryid }}
                                </div>
                                <div class="user-name fw-bold">
                                    <span class="dspdp-medium">{{ currentMember.user_name }}</span>
                                </div>
                            </div>
                            <div class="wpee-meetme-action-wrap">
                                <!-- Meet Me Actions -->
                                <a href="javascript:void(0);" class="button wpee-meet-me-trigger"
                                @click="sendMessages">
                                    <i class="fa fa-heart"></i> Yes
                                </a>
                                <a href="javascript:void(0);" class="button wpee-meet-me-trigger" @click="handleNo">
                                    <i class="fa fa-times"></i> No
                                </a>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <p>No more members to show!</p>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import UserNavbar from '../Components/UserNavbar.vue';
import dayjs from 'dayjs';
import duration from 'dayjs/plugin/duration';
import relativeTime from 'dayjs/plugin/relativeTime';
import { Inertia } from '@inertiajs/inertia';
dayjs.extend(duration);
dayjs.extend(relativeTime);
const props = defineProps({
    users: Array,
    profileData: Object,
    premiumUsers: Array,
    dates: Array,
    authUserId : Number
});

const formatDate = (date) => dayjs(date).format('MMMM D, YYYY h:mm A');

const countdown = (date) => {
  const now = dayjs();
  const eventDate = dayjs(date);
  const diff = eventDate.diff(now, 'hour');
  return diff > 0 ? `${diff} hours left` : 'Event started';
};

// Create a local reference from the props for premiumUsers
const premiumUsers = ref(props.premiumUsers);

// Index to track the current member being shown
const currentIndex = ref(0);

// Get the current member based on the index
const currentMember = computed(() => {
    return premiumUsers.value[currentIndex.value] || null;
});

const handleNo = () => {
    if (currentIndex.value < premiumUsers.value.length - 1) {
        currentIndex.value++;
    } else {
        alert('No more members to show.');
    }
};

const sendMessages = () =>{
    Inertia.visit('/user/messages');
}
const goToProfile = async (userId) => {
    // Navigate to the user profile using the userId parameter
    router.get(route('viewProfile.show', { userId }));
};
</script>

<style scoped>
.dashboard-container {
    max-width: 100%;
    margin: 0 auto;
    padding: 150px;
}

.match-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
    transition: transform 0.3s ease;
    cursor: pointer;
}

.booking-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 15px;
    transition: transform 0.2s;
}

.match-card:hover,
.booking-card:hover {
    transform: scale(1.05);
    /* Enlarges the card slightly on hover */
}

.profile-card-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.profile-img,
.restaurant-img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 10px;
    object-fit: cover;
    object-position: center;
    border: 2px solid #E91E63;
}

.match-info {
    margin-top: 10px;
}

.booking-info {
    padding: 10px;
}

.text-pink {
    color: #E91E63;
    font-weight: bold;
    font-size: 1.2rem;
}

.country {
    color: #757575;
}

.btn-pink {
    background-color: #E91E63;
    color: white;
    border: none;
    padding: 10px 20px;
    margin-top: 15px;
    border-radius: 5px;
    font-size: 0.9rem;
    text-transform: uppercase;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-pink:hover {
    background-color: #D81B60;
}

.like-icon {
    margin-right: 8px;
    font-size: 1.1rem;
}

.match-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.meet-to-info {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.dspdp-text-center {
    text-align: center;
}

.image-box img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
}

.user-info,
.user-name {
    margin-top: 10px;
    font-size: 1.1rem;
    color: #333;
}

.button {
    display: inline-block;
    background-color: #e91e63;
    color: white;
    padding: 10px 20px;
    margin: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    text-decoration: none;
}

.button i {
    margin-right: 5px;
}

.button:hover {
    background-color: #d81b60;
}
</style>
