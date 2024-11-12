<template>
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom d-flex justify-content-between">
                <button class="btn pink-toggle" @click="$emit('toggle')">
                    <i class="bi bi-list"></i>
                </button>
                <div class="ml-auto d-flex">
                    <a class="nav-link p-2" href="#profile">
                        <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                    </a>
                </div>
            </nav>
</template>

<script setup>

</script>

<style scoped>
.pink-toggle {
    background-color: transparent;
    color: black;
    padding: 10px 15px;
}
</style>