<template>
    <HeaderComponent />
    <div class="story-detail">
        <h1 class="story-title">{{ story.title }}</h1>
        <img :src="story.image" alt="Story Image" class="story-image" />
        <p class="story-date">{{ story.date }}</p>
        <div class="story-content">
            <p>{{ story.content }}</p>
        </div>
        <Link href="/userstory" class="back-link">Back to Stories</Link>
    </div>
    <FooterComponent />
</template>

<script setup>
import HeaderComponent from './Components/HeaderComponent.vue';
import FooterComponent from './Components/FooterComponent.vue';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const { props } = usePage();
const story = ref(props.story || {
    title: 'Our Love Feels Like a Business Relationship',
    image: 'img/usercouple1.jpg',
    date: 'September 12, 2023',
    content: 'In the world of modern relationships, ours has developed into something that feels both structured and transactional. While this might sound cold, it has allowed us to navigate our lives together with clarity and purpose. We set goals, share responsibilities, and even schedule our romantic outings like business meetings. Through this approach, we have discovered a unique bond that combines love with practicality. The balance of emotions and logic has helped us to support each other in ways we never imagined, making our partnership stronger every day.'
});
</script>

<style scoped>
.story-detail {
    padding: 200px 200px; 
    max-width: 1000px;
    margin: auto;
    text-align: left;
    background-color: #fff; 
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); 
    border-radius: 10px; 
    overflow: hidden; 
}

.story-title {
    font-size: 2.5rem; 
    font-weight: bold; 
    margin-bottom: 20px; 
}

.story-image {
    width: 100%; 
    height: auto; 
    border-radius: 10px 10px 0 0; 
}

.story-date {
    font-style: italic;
    color: #888;
    margin: 10px 0;
}

.story-content {
    font-size: 1.1rem;
    color: #555;
    line-height: 1.6;
    margin-top: 20px;
    text-align: justify; 
    padding: 0 15px;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    background-color: #ff6f61;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.back-link:hover {
    background-color: #e85d4a; 
}
</style>
