<!-- AdminLayout.vue -->
<template>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <AdminSidebar :isCollapsed="isCollapsed" />

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <AdminTopbar @toggle="toggleSidebar" />

            <!-- Main Content -->
            <div class="container-fluid mt-4">
                <slot /> 
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import AdminTopbar from '../Components/AdminTopbar.vue';
import AdminSidebar from '../Components/AdminSidebar.vue';

const isCollapsed = ref(false);

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
};
</script>

<style scoped>
#wrapper {
    display: flex;
}

#page-content-wrapper {
    flex: 1;
}
</style>