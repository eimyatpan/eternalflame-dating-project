<template>
  <HeaderComponent />
  <div class="home-page">
    <!-- Hero Section -->
    <section class="hero text-center">
      <div class="overlay">
        <!-- Title and Description -->
        <h1 class="animate__animated animate__fadeInDown">Welcome to EternalFlame</h1>
        <p class="animate__animated animate__fadeIn animate__delay-1s">Find your perfect match today!</p>

        <!-- Search Form -->
        <form class="search-box animate__animated animate__fadeInUp animate__delay-2s">
          <div class="input-group">
            <select class="form-select" v-model="gender">
              <option disabled value="">I am</option>
              <option value="man">Man</option>
              <option value="woman">Woman</option>
            </select>
            <select class="form-select" v-model="seeking">
              <option disabled value="">Seeking a</option>
              <option value="man">Man</option>
              <option value="woman">Woman</option>
              <option value="both">Both</option>
            </select>
            <input type="number" class="form-control" v-model="ageFrom" placeholder="Age from" min="18" max="90" />
            <input type="number" class="form-control" v-model="ageTo" placeholder="Age to" min="18" max="90" />
            <button class="btn-search" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </form>
      </div>
    </section>

    <!-- New Description Section -->
    <section class="description-section text-center animate__animated animate__fadeInUp">
      <div class="content-wrapper">
        <!-- Image Gallery Section -->
        <div class="image-gallery">
          <img src="./img/couple1.jpg" alt="Couple 1" class="gallery-img img-animate animate-img" />
          <img src="./img/couple2.jpeg" alt="Couple 2" class="gallery-img img-animate animate-img" />
          <img src="./img/couple3.jpg" alt="Couple 3" class="gallery-img img-animate animate-img" />
          <img src="./img/couple4.jpg" alt="Couple 4" class="gallery-img img-animate animate-img" />
        </div>
        <!-- Text Section -->
        <div class="text-content">
          <h2 class="text-animate">Welcome to EternalFlame</h2>
          <p class="text-animate">
            EternalFlame is a one-stop spot for all your matrimonial needs. With millions of happy couples already found
            their perfect match on our site, we’re confident that you’ll be able to find yours as well!
          </p>
          <ul class="custom-list">
            <li class="text-animate">
              You can trust us to find your dream partner. We are one of the best and most trusted matrimonial service
              providers.
            </li>
            <li class="text-animate">
              Every user on our platform has been verified by an expert team to make sure they are real and active.
            </li>
            <li class="text-animate">
              The design of this app is sleek and user-friendly. It will make it easy for anyone to find what they need
              in just a few clicks. With the help of this new search tool, you can find your perfect match in no time!
              It has been designed to be quick and efficient.
            </li>
            <li class="text-animate">
              With the help of this new search tool, you can find your perfect match in no time! It has been designed to
              be quick and efficient.
            </li>
          </ul>
          <!-- Let's Start Button -->
          <button class="btn btn-start">Let's Start</button>
        </div>
      </div>
    </section>

    <!-- Featured Profiles Carousel -->
    <section class="featured-profiles mt-5">
      <h2 class="text-center">Featured Profiles</h2>
      <div class="profile-card-container">
        <div class="profile-card" v-for="user in users" :key="user.id">
          <img v-if="user.profiles.profile_picture" :src="user.profiles.profile_picture" class="profile-img" alt="Profile Image" />
          <img v-else src="data:image/jpeg;base64,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" alt="">
          
          <div class="profile-info">
            <h3>{{ user.user_name }}</h3>
            <p><strong>Age:</strong> {{ user.user_age }}</p>
            <p><strong>Location:</strong> {{ user.country_name }}</p>
          </div>
        </div>
      </div>
    </section>
  </div>
  <FooterComponent />
</template>

<script setup>
import { ref } from 'vue';
import HeaderComponent from './Components/HeaderComponent.vue';
import FooterComponent from './Components/FooterComponent.vue';

const props = defineProps({
  users :Array
});

// Search form fields
const gender = ref('');
const seeking = ref('');
const ageFrom = ref(18);
const ageTo = ref(90);
</script>

<style scoped>
.home-page {
  padding-top: 100px;
}

/* Hero Section */
.hero {
  background: url('./img/homepage.jpg') no-repeat center center;
  background-size: cover;
  min-height: 100vh;
  position: relative;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hero .overlay {
  background-color: rgba(55, 54, 54, 0.6);
  padding: 50px 20px;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.hero h1,
.hero p,
.quote,
.search-box {
  position: relative;
  z-index: 1;
}

.hero h1 {
  font-size: 4rem;
  font-weight: bold;
  margin-bottom: 20px;
}

.hero p {
  font-size: 1.8rem;
  margin-bottom: 30px;
}

.search-box {
  background-color: rgba(253, 253, 253, 0.9);
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  max-width: 900px;
  margin: 0 auto 30px auto;
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 20px;
}

.search-box .input-group {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  width: 100%;
}

.search-box .form-select,
.search-box .form-control {
  flex: 1;
  min-width: 150px;
  padding: 15px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1.1rem;
  background-color: #efefef;
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.search-box .btn-search {
  background-color: #FB4C64;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 15px;
  min-width: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.search-box .btn-search:hover {
  background-color: #ed194a;
  transform: scale(1.05);
}

/* New Description Section */
.description-section {
  background-color: #f9f9f9;
  padding: 40px 20px;
}

.content-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.text-content {
  flex: 1;
  padding-right: 20px;
  max-width: 50%;
  text-align: left;
}

.image-gallery {
  flex: 1;
  display: flex;
  justify-content: center;
  gap: 20px;
  flex-wrap: wrap;
}

.gallery-img {
  width: 150px;
  height: 150px;
  border-radius: 10px;
  object-fit: cover;
  opacity: 0;
  transform: translateX(50px);
  animation: slideIn 2s forwards;
}

.img-animate:nth-child(1) {
  animation-delay: 0.5s;
}

.img-animate:nth-child(2) {
  animation-delay: 1s;
}

.img-animate:nth-child(3) {
  animation-delay: 1.5s;
}

.img-animate:nth-child(4) {
  animation-delay: 2s;
}

/* Keyframe for smooth image slide-in */
@keyframes slideIn {
  0% {
    opacity: 0;
    transform: translateX(50px);
  }

  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Text animations */
.text-animate {
  opacity: 0;
  transform: translateY(30px);
  animation: textSlideIn 2s forwards;
  margin-bottom: 20px;
}

.text-animate:nth-child(1) {
  animation-delay: 0.3s;
}

.text-animate:nth-child(2) {
  animation-delay: 0.6s;
}

.text-animate:nth-child(3) {
  animation-delay: 0.9s;
}

@keyframes textSlideIn {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }

  100% {
    opacity: 1;
    transform: translateY(0);
  }
}
.custom-list{
  list-style: none;
  padding-left: 0;
}

.custom-list li{
  position: relative;
  padding-left: 25px;
  margin-bottom: 10px;
}

.custom-list li::before {
  content: '\2713'; 
  position: absolute;
  left: 0;
  color: #FB4C64;
  font-size: 1.2em;
}
/* Let's Start Button Styling */
.btn-start {
  background-color: #FB4C64;
  color: white;
  padding: 15px 30px;
  font-size: 1.2rem;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-start:hover {
  background-color: #ffa9bf;
}

.profile-card-container {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 20px;
  padding: 20px;
}

.profile-card {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 250px;
  padding: 20px;
  text-align: center;
  transition: transform 0.3s ease;
  cursor: pointer;
}

.profile-card:hover {
  transform: scale(1.05);
}

.profile-img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 15px;
}

.profile-info h3 {
  font-size: 1.5rem;
  margin-bottom: 10px;
}

.profile-info p {
  font-size: 1rem;
  margin-bottom: 5px;
}
</style>
