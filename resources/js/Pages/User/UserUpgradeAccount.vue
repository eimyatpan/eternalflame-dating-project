<template>
    <UserNavbar :userProfile="myProfile" />
    <div class="upgrade-page">
        <div class="upgrade-card">
            <h1>Upgrade Your Account</h1>
            <p class="intro-text">Experience more with our premium features, discounts, and priority support.</p>
            <div class="plans-container">
                <div class="plan-box">
                    <h2>Premium Plan</h2>
                    <p class="plan-price">$225/year</p>
                    <p class="plan-description">Unlock exclusive access, discounts, and priority support.</p>
                    <button @click="showModal = true" class="upgrade-button">Upgrade Now</button>
                </div>
            </div>
            <div class="bonuses">
                <h3>Why Choose Premium?</h3>
                <ul class="benefits-list">
                    <li>
                        <i class="icon-star"></i> Book exclusive restaurants
                    </li>
                    <li>
                        <i class="icon-discount"></i> 20% off booking fees when both users are premium
                    </li>
                    <li>
                        <i class="icon-support"></i> Priority customer support
                    </li>
                </ul>
                <p class="note">Note: Only available to users aged 18 and above.</p>
            </div>
        </div>
        <!-- Modal for Payment Form -->
        <transition name="modal-fade">
            <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
                <div class="modal-content">
                    <h2>Complete Your Upgrade</h2>
                    <form @submit.prevent="submitPayment">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" v-model="form.user_name" required />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" v-model="form.email" required />
                        </div>
                        <div class="form-group">
                            <label for="payment-method">Payment Method</label>
                            <input type="text" id="payment-method" placeholder="Payment Method" v-model="form.payment_method" required />
                        </div>
                        <button type="submit" class="confirm-button" :disabled="form.processing">Upgrade Account</button>
                    </form>
                    <button class="close-button" @click="showModal = false">Cancel</button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import UserNavbar from '../Components/UserNavbar.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});
const showModal = ref(false);

// Define the form object using Inertia's useForm for submission
const form = useForm({
    user_name: props.user.user_name,
    email: props.user.email,
    payment_method: '',
    payment_amount: 225,  // Default payment amount
});

// Method to handle form submission
const submitPayment = () => {
    form.post(route('user.upgrade'), {
        onSuccess: () => {
            alert('Payment initiated successfully. Waiting for admin approval.');
            showModal.value = false;
        },
        onError: () => {
            alert('There was an error processing your payment. Please try again.');
        },
    });
};
</script>

<style scoped>
.upgrade-page {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f5f5f5;
    padding-top: 150px;
}

.upgrade-card {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    max-width: 800px;
    width: 100%;
    text-align: center;
}

.intro-text {
    font-size: 1.1rem;
    color: #777;
    margin-top: 10px;
}

.plans-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.plan-box {
    padding: 20px;
    background-color: #f8f8f8;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 300px;
    text-align: center;
}

.plan-price {
    font-size: 1.8rem;
    color: #333;
    margin: 10px 0;
}

.plan-description {
    font-size: 1rem;
    color: #666;
}

.upgrade-button {
    margin-top: 15px;
    padding: 10px 20px;
    background-color: #ff4081;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
}

.upgrade-button:hover {
    background-color: #ff6b9c;
}

.bonuses {
    margin-top: 30px;
    text-align: left;
}

.benefits-list {
    list-style: none;
    padding: 0;
}

.benefits-list li {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.icon-star,
.icon-discount,
.icon-support {
    margin-right: 8px;
    color: #ffcc00;
    font-size: 1.2rem;
}

.testimonials {
    margin-top: 30px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
}

.testimonial {
    font-style: italic;
    color: #555;
    margin: 10px 0;
}

.note {
    margin-top: 15px;
    font-size: 0.9rem;
    color: #666;
}

/* Modal styling */
.modal-overlay {
    position: fixed;
    top: 50px;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 8px;
    width: 400px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    text-align: center;
}

.modal-content h2 {
    margin-bottom: 20px;
    font-size: 1.5rem;
}

.form-group {
    margin-bottom: 15px;
    text-align: left;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.confirm-button {
    padding: 10px 20px;
    background-color: #ff4081;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    width: 100%;
    margin-top: 20px;
}

.close-button {
    margin-top: 10px;
    font-size: 0.9rem;
    color: #ff4081;
    cursor: pointer;
    border: none;
    background: none;
    text-decoration: underline;
}

.modal-fade-enter-active, .modal-fade-leave-active {
    transition: opacity 0.3s;
}
.modal-fade-enter, .modal-fade-leave-to {
    opacity: 0;
}
</style>
