<!-- src/pages/MessagesPage.vue -->
<template>
    <div class="messages-page">
        <h1>Your Messages</h1>
        <div class="messages-list">
            <div v-for="message in messages" :key="message.id" class="message">
                <p><strong>{{ message.sender }}:</strong> {{ message.content }}</p>
            </div>
        </div>
        <div class="message-input">
            <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type a message..." />
            <button @click="sendMessage">Send</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

// Define reactive data
const newMessage = ref('');
const messages = ref([
    { id: 1, sender: 'John', content: 'Hi there!' },
    { id: 2, sender: 'Jane', content: 'Hello!' },
]);

// Define methods
function sendMessage() {
    if (newMessage.value.trim()) {
        messages.value.push({ id: Date.now(), sender: 'You', content: newMessage.value });
        newMessage.value = '';
    }
}
</script>

<style scoped>
.messages-list {
    background-color: #f8f9fa;
    padding: 20px;
    margin-bottom: 20px;
}

.message {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.message-input {
    display: flex;
    gap: 10px;
}

button {
    background-color: #007bff;
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}
</style>