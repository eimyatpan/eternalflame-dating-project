<template>
    <HeaderComponent/>
    <section class="user-stories">
        <h1 class="main-title">User Stories</h1>
        <div class="stories-container">
            <div class="story-card" v-for="story in stories" :key="story.id">
                <img :src="story.image" alt="Story Image" class="story-image"/>
                <div class="story-content">
                    <h2 class="story-title">{{ story.title }}</h2>
                    <p class="story-date">{{ story.date }}</p>
                    <p class="story-excerpt">{{ story.excerpt }}</p>
                    <Link :href="`/user-story-detail?id=${story.id}`" class="read-more">Read More</Link>
                </div>
            </div>
        </div>
    </section>
    <FooterComponent/>
</template>

<script setup>
import HeaderComponent from './Components/HeaderComponent.vue';
import FooterComponent from './Components/FooterComponent.vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const { props } = usePage();
const stories = props.stories || [
    {
        id: 1,
        title: 'Love Story of John and Jane',
        date: 'September 12, 2023',
        image: 'img/usercouple1.jpg',
        excerpt: 'A beautiful love story that began with a simple hello...'
    },
    {
        id: 2,
        title: 'A Journey of Friendship',
        date: 'August 23, 2023',
        image: 'img/usercouple2.jpg',
        excerpt: 'A bond that grew into a lifelong relationship, nurtured by trust...'
    }
];
</script>

<style scoped>
/* Your existing styles here */
.user-stories {
    padding: 50px 20px;
    background-color: #f9f9f9;
    text-align: center;
}

.main-title {
    font-size: 2.5rem;
    margin-bottom: 40px;
    color: #333;
}

.stories-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    gap: 30px;
}

.story-card {
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    width: 300px;
    transition: box-shadow 0.3s ease;
}

.story-card:hover {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

.story-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.story-content {
    padding: 20px;
}

.story-title {
    font-size: 1.8rem;
    margin-bottom: 10px;
    color: #333;
}

.story-date {
    font-size: 0.9rem;
    color: #888;
    margin-bottom: 15px;
}

.story-excerpt {
    font-size: 1rem;
    color: #555;
    margin-bottom: 20px;
}

.read-more {
    display: inline-block;
    background-color: #ff6f61;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.read-more:hover {
    background-color: #ff4a3a;
}
</style>
