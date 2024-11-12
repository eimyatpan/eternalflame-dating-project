<template>
    <HeaderComponent />
    <section class="contact-header">
        <p>Need Help?</p>
        <h1 class="fw-bold">Contact Us</h1>
        <p>
            Our Support Team is happy to assist you with any questions you have. Send
            us a message, and we will get back to you within a day.
        </p>
    </section>

    <div class="contact-container">
        <!-- Contact Form Card -->
        <div class="card contact-form">
            <h2>Let's Have A Chat</h2>
            <form @submit.prevent="sendMessage">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" v-model="form.name" required />
                </div>
                <div class="form-group">
                    <label for="email">Your Email *</label>
                    <input type="email" v-model="form.email" required />
                </div>
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <input type="text" v-model="form.subject" required />
                </div>
                <div class="form-group">
                    <label for="message">Your Message *</label>
                    <textarea v-model="form.message" required></textarea>
                </div>
                <button type="submit">Send Message</button>
            </form>
        </div>

        <!-- Contact Details Card -->
        <div class="card contact-details">
            <h2>Our Contact Details</h2>
            <p><strong>Email:</strong> support@eternalflamedating.com</p>
            <p><strong>Phone:</strong> +959 420 134 573</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-telegram-plane"></i></a>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="faq">
        <h2>Frequently Asked Questions</h2>
        <div v-for="(faq, index) in faqs" :key="index" class="faq-item">
            <h3 @click="toggleFAQ(index)">
                {{ faq.question }}
                <span v-if="faq.open">-</span>
                <span v-else>+</span>
            </h3>
            <p v-if="faq.open">{{ faq.answer }}</p>
        </div>
    </div>

    <FooterComponent />
</template>


<script setup>
import FooterComponent from './Components/FooterComponent.vue'
import HeaderComponent from './Components/HeaderComponent.vue'
import { reactive } from 'vue'

const form = reactive({
    name: '',
    email: '',
    subject: '',
    message: ''
})

const faqs = reactive([
    {
        question: 'To whom is your website primarily aimed?',
        answer:
            'We mainly offer our service to singles who are either looking for friendship, casual or serious relationships.',
        open: false
    },
    {
        question: 'How secure is my personal data in Relish Dating?',
        answer:
            'Your personal data is secure with us, protected by industry-standard encryption methods.',
        open: false
    },
    {
        question: 'I don’t want to share my pictures with strangers. What can I do?',
        answer: 'You can set privacy settings to control who sees your pictures.',
        open: false
    },
    {
        question: 'Can I use a social media account to register on your site?',
        answer: 'Yes, you can use Facebook or Google to register.',
        open: false
    }
])

const toggleFAQ = (index) => {
    faqs.forEach((faq, i) => {
        if (i === index) faq.open = !faq.open
        else faq.open = false // Close other FAQs
    })
}

const sendMessage = () => {
    console.log(form)
    alert('Message sent successfully!')
}
</script>


<style scoped>
/* Styling for the header */
.contact-header {
    background-color: #fa5e75;
    padding: 80px;
    text-align: center;
    margin-top: 80px;
}

.contact-header h1 {
    font-size: 3em;
    color: white;
}

.contact-header p {
    font-size: 1.1em;
    color: #fff;
}

/* Contact Container */
.contact-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    margin: 50px auto;
    max-width: 1200px;
    gap: 20px;
}

/* Contact Card Styles */
.card {
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    border-radius: 8px;
}

.contact-form {
    flex: 1;
}

.contact-form h2,
.contact-details h2 {
    margin-bottom: 20px;
    font-size: 1.5rem;
}

.form-group {
    margin-bottom: 15px;
}

input,
textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1rem;
}

button {
    padding: 12px 20px;
    background-color: #fa5e75;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}

button:hover {
    background-color: #e04c61;
}

/* Contact Details */
.contact-details {
    flex: 0 1 40%; /* Adjust width of details card to 40% */
}

.contact-details p {
    margin-bottom: 10px;
    font-size: 1rem;
}

.social-links a {
    display: inline-block;
    margin-right: 10px;
    text-decoration: none;
    color: #333;
    font-size: 1.2rem;
}

.social-links i {
    margin-right: 5px;
}

/* FAQ Section */
.faq {
    max-width: 800px;
    margin: 50px auto;
}

.faq-item {
    margin-bottom: 20px;
    padding: 15px;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
}

.faq-item h3 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 18px;
    margin: 0;
    transition: color 0.3s ease;
}

.faq-item h3:hover {
    color: #fa5e75;
}

.faq-item p {
    margin-top: 10px;
    font-size: 16px;
    line-height: 1.6;
}

/* Responsive Layout */
@media (max-width: 768px) {
    .contact-container {
        flex-direction: column;
        align-items: center;
    }

    .card {
        width: 100%;
    }

    .contact-details {
        flex: 1 1 100%; /* Full width for smaller screens */
    }
}
</style>
