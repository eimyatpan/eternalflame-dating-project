<template>
    <HeaderComponent />
    <div class="testimonial-page">
        <h1 class="page-title">Testimonials</h1>

        <div class="testimonial-grid">
            <div class="testimonial-card" v-for="testimonial in testimonials" :key="testimonial.id">
                <img :src="testimonial.image" alt="User Image" class="testimonial-image" />
                <h3 class="testimonial-name">{{ testimonial.name }}</h3>
                <p class="testimonial-content">{{ testimonial.content }}</p>
            </div>
        </div>

        <button v-if="showMore" @click="loadMore" class="load-more-button">Load More</button>
    </div>
    <FooterComponent />
</template>

<script setup>
import HeaderComponent from './Components/HeaderComponent.vue';
import FooterComponent from './Components/FooterComponent.vue';
import { ref } from 'vue';

// Sample testimonial data (In real scenarios, this would come from the backend)
const testimonials = ref([
    {
        id: 1,
        name: 'John Doe',
        image: 'img/Man2.jpg',
        content: 'This platform helped me find the love of my life. I am forever grateful!',
    },
    {
        id: 2,
        name: 'Jane Smith',
        image: 'img/Woman1.png',
        content: 'A seamless experience that made dating much more enjoyable and stress-free!',
    },
    // Add more testimonials here
]);

// Show "Load More" button when there are more testimonials to display
const showMore = ref(true);

// Function to load more testimonials (simulated here)
const loadMore = () => {
    // Load more testimonials (example)
    testimonials.value.push(
        {
            id: 3,
            name: 'Alice Johnson',
            image: 'img/Man1.jfif',
            content: 'I never imagined I would meet my partner here, but it happened!',
        },
        {
            id: 4,
            name: 'David Lee',
            image: 'img/Woman1.jfif',
            content: 'Amazing platform with great features that made online dating fun and safe.',
        }
    );
    // Set showMore to false once all testimonials are loaded
    showMore.value = false;
};
</script>

<style scoped>
.testimonial-page {
    padding: 50px 20px;
    background-color: #f8f9fa;
    text-align: center;
}

.page-title {
    font-size: 2.5rem;
    margin-bottom: 40px;
    color: #333;
}

.testimonial-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 30px;
    justify-content: center;
}

.testimonial-card {
    background-color: white;
    border: 1px solid #e3e3e3;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.3s;
}

.testimonial-card:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.testimonial-image {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
}

.testimonial-name {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.testimonial-content {
    font-size: 1rem;
    color: #555;
    line-height: 1.6;
}

.load-more-button {
    margin-top: 40px;
    padding: 10px 20px;
    background-color: #ff6f61;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.load-more-button:hover {
    background-color: #e85d4a;
}
</style>