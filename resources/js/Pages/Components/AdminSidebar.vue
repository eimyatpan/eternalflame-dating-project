<template>
    <!-- Sidebar -->
    <nav id="sidebar" :class="isCollapsed ? 'collapsed' : ''" class="border-right">
        <div class="sidebar-header p-2 text-white" :class="isCollapsed ? 'collapsed-header' : ''">
            <img src="../img/logo.png" alt="Logo" class="img-fluid mb-1" style="width: 90px;">
            <h4 v-if="!isCollapsed">EternalFlame</h4>
        </div>
        <ul class="list-unstyled components">
            <li>
                <Link href="/admin/dashboard" :class="isCollapsed ? 'no-border' : ''"
                    class="text-light d-flex align-items-center justify-content-center">
                <i class="bi bi-speedometer2"></i>
                <span v-if="!isCollapsed">Dashboard</span>
                </Link>
            </li>
            <li>
                <Link href="/admin/usersTable" :class="isCollapsed ? 'no-border' : ''"
                    class="text-light d-flex align-items-center justify-content-center">
                <i class="bi bi-people"></i>
                <span v-if="!isCollapsed">Users</span>
                </Link>
            </li>
            <li>
                <Link href="/admin/countryTable" :class="isCollapsed ? 'no-border' : ''"
                    class="text-light d-flex align-items-center justify-content-center">
                <i class="bi bi-flag"></i>
                <span v-if="!isCollapsed">Countries</span>
                </Link>
            </li>
            <li>
                <Link href="/admin/datePlaces" :class="isCollapsed ? 'no-border' : ''"
                    class="text-light d-flex align-items-center justify-content-center">
                <i class="bi bi-shop"></i>
                <span v-if="!isCollapsed">Restaurants</span>
                </Link>
            </li>
            <li>
                <Link href="/admin/bookings" :class="isCollapsed ? 'no-border' : ''"
                    class="text-light d-flex align-items-center justify-content-center">
                <i class="bi bi-calendar-check"></i>
                <span v-if="!isCollapsed">Bookings</span>
                </Link>
            </li>
            <li>
                <Link href="/admin/paymentLists" :class="isCollapsed ? 'no-border' : ''"
                    class="text-light d-flex align-items-center justify-content-center">
                <i class="bi bi-credit-card"></i>
                <span v-if="!isCollapsed">Payment Lists</span>
                </Link>
            </li>
        </ul>

        <!-- Logout Section -->
        <div class="logout-section mt-auto p-2">
            <a @click="logoutAdmin" class="text-light d-flex align-items-center justify-content-center"
                style="cursor: pointer;">
                <i class="bi bi-box-arrow-right"></i>
                <span v-if="!isCollapsed">Logout</span>
            </a>
        </div>
    </nav>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/vue3';
defineProps({
    isCollapsed: Boolean,
});

const logoutAdmin = () => {
    Inertia.post('/admin/logout', {}, {
        onFinish: () => Inertia.visit('/admin/login'),
    });
};
</script>

<style scoped>
/* Sidebar Styles */
#sidebar {
    width: 300px;
    background-color: rgb(89, 87, 87);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    transition: width 0.3s ease;
}

#sidebar.collapsed {
    width: 80px;
}

.sidebar-header {
    background-color: rgb(89, 87, 87);
    text-align: center;
    transition: height 0.3s ease;
}

.sidebar-header img {
    transition: width 0.3s ease;
}

.sidebar-header h4 {
    transition: opacity 0.3s ease;
}

.sidebar-header.collapsed-header h4 {
    opacity: 0;
    visibility: hidden;
}

ul.components li a,
.logout-section a {
    padding: 10px 15px;
    font-size: 16px;
    width: 200px;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #fff;
    border-radius: 5px;
    margin: 5px 0;
    transition: background-color 0.3s ease, border 0.3s ease, width 0.3s ease;
}

#sidebar.collapsed ul.components li a,
#sidebar.collapsed .logout-section a {
    width: 60px;
    justify-content: center;
    padding-left: 0;
}

/* Remove border when collapsed for non-logout items */
#sidebar.collapsed ul.components li a.no-border {
    border: none;
}

ul.components li a:hover,
.logout-section a:hover {
    background-color: #ff95b8;
    text-decoration: none;
}

ul.components li a i {
    font-size: 20px;
}

/* Additional sidebar hover effect */
ul.components li a:hover,
.logout-section a:hover {
    background-color: #ff95b8;
    border-color: #ff95b8;
    text-decoration: none;
}

.logout-section {
    background-color: transparent;
    text-align: center;
    margin-top: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.logout-section a {
    background-color: #ff4081;
    color: #fff;
    border: 1px solid #fff;
    border-radius: 5px;
    padding: 10px 15px;
    transition: background-color 0.3s ease;
}

.logout-section a:hover {
    background-color: #f583a9;
    text-decoration: none;
}
</style>