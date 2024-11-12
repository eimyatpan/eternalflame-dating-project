<!-- src/pages/SearchPage.vue -->
<template>
    <div class="search-page">
        <h1>Search for Matches</h1>
        <form @submit.prevent="searchMatches">
            <div>
                <label>Gender:</label>
                <select v-model="searchCriteria.gender">
                    <option>Male</option>
                    <option>Female</option>
                </select>
            </div>
            <div>
                <label>Age:</label>
                <input type="number" v-model="searchCriteria.age" />
            </div>
            <div>
                <label>Location:</label>
                <input type="text" v-model="searchCriteria.location" />
            </div>
            <button type="submit">Search</button>
        </form>

        <div v-if="matches.length">
            <h2>Search Results</h2>
            <ul>
                <li v-for="match in matches" :key="match.id">
                    {{ match.name }} - {{ match.age }} - {{ match.location }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

// Define reactive state
const searchCriteria = ref({
    gender: '',
    age: null,
    location: '',
});

const matches = ref([]);

// Define methods
function searchMatches() {
    // Simulate search result
    matches.value = [
        { id: 1, name: 'Jane Doe', age: 25, location: 'New York' },
        { id: 2, name: 'Anna Smith', age: 30, location: 'Los Angeles' },
    ];
}
</script>

<style scoped>
.search-page {
    padding: 20px;
}

form div {
    margin-bottom: 10px;
}

button {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}
</style>