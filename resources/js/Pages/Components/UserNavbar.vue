<template>
    <nav class="navbar navbar-expand-lg navbar-light user-navbar" style="background-color:#FFEEF2">
        <div class="container-fluid">
            <!-- Logo -->
            <router-link class="navbar-brand logo-container" to="/">
                <img src="../img/logo.png" alt="Logo" class="logo-img">
            </router-link>

            <!-- Toggler for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto" style="margin-right:100px;">
                    <li class="nav-item mx-3">
                        <Link class="nav-link fw-bold" href="/user/dashboard">Dashboard</Link>
                    </li>
                    <li class="nav-item mx-3">
                        <Link class="nav-link fw-bold" href="/user/messages">Messages</Link>
                    </li>
                    <li class="nav-item mx-3">
                        <Link class="nav-link fw-bold" :href="`/user/matching/${userId}`">Matching</Link>
                    </li>
                    <li class="nav-item mx-3">
                        <Link class="nav-link fw-bold" href="/user/datePlaces">Date Places</Link>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <!-- Conditional rendering: only show image if userProfile and userimage are defined -->
                            <img
                                v-if="profilePicture"
                                :src="profilePicture" 
                                alt="Profile Picture" 
                                class="profile-pic dropdown-toggle" 
                                id="profileDropdown" 
                                role="button" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false"
                            />
                            <!-- Fallback: if userimage is not available, show a default avatar -->
                            <img 
                                v-else
                                src="data:image/jpeg;base64,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"
                                alt="Default Avatar" 
                                class="profile-pic dropdown-toggle"
                                id="profileDropdown"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            />
                            <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                <li><Link class="dropdown-item" href="/user/userProfile">My Profile</Link></li>
                                <li v-if="account_type != 'premium'"><Link class="dropdown-item" href="/user/upgrade-account">Upgrade Account</Link></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button @click="logoutUser" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#authModal">Logout</button></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Link,usePage } from '@inertiajs/inertia-vue3';
const profilePicture = ref(null);
const userId = ref(null);
const account_type = ref('');

onMounted(() => {
    axios.get('/user/loginProfile')
        .then(response => {
            profilePicture.value = response.data.profile_picture;
            userId.value = response.data.user_id;
            account_type.value =  response.data.account_type;

        })
        .catch(error => {
            console.error('Error fetching profile picture:', error);
        });
});

console.log(profilePicture.value);

const logoutUser = () => {
    Inertia.post('/user/logout', {}, {
        onFinish: () => Inertia.visit('/'),
    });
};
</script>

<style scoped>
.user-navbar {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    padding: 15px 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.logo-container {
    display: flex;
    align-items: center;
}

.logo-img {
    max-width: 90px;
    height: auto;
}

/* Navbar links styling */
.nav-link {
    color: #333;
    font-size: 1rem;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #ff4081;
}

.navbar-toggler {
    border: none;
}

.navbar-toggler-icon {
    background-color: #ff4081;
}

/* Profile Picture Styling */
.profile-pic {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    object-fit: cover;
    object-position: center;
}

.dropdown-menu {
    max-width: 250px; 
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-item {
    color: #333;
}

.dropdown-item:hover {
    background-color: #ffeff2;
}

.btn-primary {
    background-color: #ff4081;
    border-color: #ff4081;
    font-weight: bold;
    padding: 5px 10px;
}

.btn-primary:hover {
    background-color: #e0356f;
}

@media (max-width: 991px) {
    .nav-link {
        font-size: 0.9rem;
        text-align: center;
        padding: 12px;
    }

    .navbar-collapse {
        text-align: center;
    }

    .logo-img {
        max-width: 120px;
    }
}
</style>
