<template>
    <div class="app-container">
        <!-- Navbar -->
        <header class="navbar-container">
            <UserNavbar />
        </header>

        <div class="messenger-layout">
            <!-- Chat List -->
            <div class="chat-list border">
                <div class="chat-list-header">
                    <h2>Chats</h2>
                    <div class="header-icons">
                        <i class="fas fa-pencil-alt" @click="toggleUserModal"></i>
                    </div>
                </div>
                <!-- List of Chats with scrollable content -->
                <div class="chat-list-content">
                    <div v-for="(chat, index) in chatListArray" :key="index" class="chat-item"
                        @mouseenter="hoveredChat = index" @mouseleave="hoveredChat = null" @click="selectChat(chat)">
                        <img class="chat-avatar" :src="chat.image" alt="User" />
                        <div class="chat-info">
                            <h4>{{ chat.name }}</h4>
                            <p class="last-message">{{ chat.lastMessage }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- User Modal -->
            <div v-if="showUserModal" class="modal-overlay" @click.self="toggleUserModal">
                <div class="modal-content">
                    <h2>Select User to Start Chat</h2>
                    <input type="text" v-model="searchQuery" placeholder="Search users..." @input="fetchUsers" />
                    <ul v-if="searchResults.length" class="user-list">
                        <li v-for="user in searchResults" :key="user.user_id" @click="startChatWithUser(user.user_id)">
                            <img :src="user.profiles.profile_picture" class="chat-avatar" alt="Profile Picture" />
                            <span>{{ user.user_name }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Chat Container -->
            <div class="chat-container">
                <div class="chat-header">
                    <div class="user">
                        <img class="profile-image" :src="receiverImage" alt="User" />
                        <div class="user-info">
                            <h3>{{ receiverName }}</h3>
                            <span class="status">{{ receiverStatus }}</span>
                        </div>
                    </div>
                    <!-- New Header Icons -->
                    <div class="header-icons">
                        <i class="fas fa-phone" @click="audioCall"></i>
                        <i class="fas fa-video" @click="videoCall"></i>
                        <i class="fas fa-ellipsis-v" @click="toggleHeaderDropdown"></i>

                        <!-- Dropdown for chat header options -->
                        <ul v-if="showHeaderDropdown" class="dropdown">
                            <li @click="viewProfile">View Profile</li>
                        </ul>
                    </div>
                </div>
                <!-- Call Modal -->
                <div v-if="showPhoneCallModal" class="call-modal">
                    <div class="call-container">
                        <div class="call-header">
                            <img :src="receiverImage" alt="User" class="call-avatar" />
                            <div class="call-info">
                                <h3>{{ receiverName }}</h3>
                                <span>Phone Call Started</span>
                            </div>
                        </div>
                        <div class="call-controls">
                            <button @click="endCall" class="control-button end-call">
                                <i class="fas fa-phone-slash"></i>
                            </button>
                            <button @click="toggleMute" class="control-button">
                                <i :class="isMuted ? 'fas fa-microphone-slash' : 'fas fa-microphone'"></i>
                            </button>
                            <button @click="videoCall" class="control-button">
                                <i class="fas fa-video"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Video Call Modal -->
                <div v-if="showVideoCallModal" class="video-call-modal">
                    <div class="video-call-container">
                        <div class="video-call-header">
                            <img :src="receiverImage" alt="User" class="call-avatar" />
                            <div class="call-info">
                                <h3>{{ receiverName }}</h3>
                                <span>Video Call Started</span>
                            </div>
                        </div>

                        <!-- Video Call Controls -->
                        <div class="video-call-controls">
                            <button @click="endVideoCall" class="control-button end-call">
                                <i class="fas fa-phone-slash"></i>
                            </button>
                            <button @click="toggleMute" class="control-button">
                                <i :class="isMuted ? 'fas fa-microphone-slash' : 'fas fa-microphone'"></i>
                            </button>
                            <button @click="audioCall" class="control-button">
                                <i class="fas fa-phone"></i>
                            </button>
                        </div>

                        <!-- Smaller box for logged-in user -->
                        <div class="small-box">
                            <img :src="loggedInUserImage" alt="User" class="small-avatar" />
                            <div class="small-user-info">
                                <h4>{{ loggedInUserName }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Messages Display with independent scroll -->
                <div class="messages" ref="messageContainer">
                    <div v-for="(msg, index) in messages" :key="index"
                        :class="['message-container', msg.sender_id === props.userId ? 'sent' : 'received']">
                        <p>{{ msg.message_content }}</p>
                        <span class="timestamp">{{ formatTime(msg.created_at) }}</span>
                    </div>
                </div>

                <!-- Message Input Area -->
                <div class="input-area">
                    <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type a message" />
                    <button @click="sendMessage">
                        <i class="send-icon">&#10148;</i>
                    </button>
                </div>
            </div>

            <!-- User Profile -->
            <div class="user-profile">
                <h2>User Profile</h2>
                <img :src="receiverImage" class="profile-avatar" alt="User" />
                <p><strong>Name:</strong> {{ receiverName }}</p>
                <p><strong>Status:</strong> {{ receiverStatus }}</p>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted, nextTick, watch } from 'vue';
import { route } from 'ziggy-js';
import UserNavbar from '../Components/UserNavbar.vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

// Props from parent
const props = defineProps({
    chatList: Array,
    messages: Array,
    userId: Number,
    receiverId: Number,
    userProfile: Object,
    users: Array,
    loginUser: Object,
});

const chatListArray = ref(props.chatList);

const newMessage = ref('');
const receiverImage = ref('');
const receiverName = ref('');
const receiverStatus = ref('online');
const hoveredChat = ref(null);
const showSettingsDropdown = ref(false);
const showHeaderDropdown = ref(false);
const messageContainer = ref(null);
const showNewMessage = ref(false); // To toggle the new message search box
const searchQuery = ref(''); // For search input
const searchResults = ref([]); // Store search results
const showPhoneCallModal = ref(false); // To toggle phone call modal
const isMuted = ref(false); // For mute toggle
const showVideoCallModal = ref(false); // Toggle for video call modal
const loggedInUserImage = ref(''); // User's profile image for small box in video call modal
const loggedInUserName = ref('');
const showUserModal = ref(false);
// Scroll to bottom of the chat messages
const scrollToBottom = () => {
    nextTick(() => {
        if (messageContainer.value) {
            messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
        }
    });
};

// Fetch authenticated user's data on mount
onMounted(() => {

    receiverImage.value = props.userProfile ? props.userProfile[0].image : '';
    receiverName.value = props.userProfile ? props.userProfile[0].name : '';
    loggedInUserImage.value = props.loginUser.profiles.profile_picture; // Assign the logged-in user’s image
    loggedInUserName.value = props.loginUser.user_name;
});

const toggleUserModal = () => {
    showUserModal.value = !showUserModal.value;
};


const fetchUsers = async () => {
    console.log(props.users);
    if (Array.isArray(props.users) && searchQuery.value.trim()) {
        // Filter users based on search query
        searchResults.value = props.users.filter(user =>
            user.user_name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    } else {
        searchResults.value = [];
    }
};

// Start chat with selected user
const startChatWithUser = async (receiverId) => {
    try {
        axios.get(route('message.start'), {
            params: { receiver_id: receiverId },
        }).then(response => {
            if (response.status === 201) {
                const newMessage = response.data;
                window.location.reload();
                
            } else {
                console.log('Chat already exists or other message:', response.data);
            }
        });
    } catch (error) {
        console.error('Failed to start new chat:', error);
    }
};
// Send a new message
const sendMessage = async () => {
    if (!newMessage.value.trim()) return;

    try {
        const response = await router.post(route('message.send'), {
            sender_id: props.userId,
            receiver_id: props.receiverId,
            message_content: newMessage.value,
        }, {
            onFinish: () => {
                newMessage.value = null;
            }
        });

        // Assuming response contains the new message data
        const newMsg = response.data;
        console.log(newMsg);
        // Add the new message to the messages array
        props.messages.push(newMsg);
        scrollToBottom(); // Ensure chat stays at the bottom
    } catch (error) {
        console.error('Error sending message:', error);
    }
}

const toggleHeaderDropdown = () => {
    showHeaderDropdown.value = !showHeaderDropdown.value;
};

const selectChat = async (chat) => {
    router.get(route('chat.list', {
        receiverId: chat.id
    }));

};

const viewProfile = async () => {
    router.get(route('viewProfile.show', {
        userId: props.receiverId
    }));
};

// Additional actions
const blockUser = () => alert('User Blocked');
const deleteChat = () => alert('Chat Deleted');
const reportChat = () => alert('Chat Reported');
const startNewMessage = () => alert('Start a new message!');
const blockedUsers = () => alert('Viewing blocked users');
const openSettings = () => alert('Opening settings');
const messageRequests = () => alert('Viewing message requests');

// Watch for changes in initialMessages and update messages
watch(() => props.messages, () => {
    scrollToBottom(); // Ensure the chat stays at the bottom when new messages arrive
});

// Helper to format timestamps
const formatTime = (timestamp) =>
    new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

// Function to open the phone call modal
const audioCall = () => {
    showPhoneCallModal.value = true; // Close phone call modal
};

// Function to close the phone call modal
const endCall = () => {
    showPhoneCallModal.value = false;
};
const videoCall = () => {
    showPhoneCallModal.value = false;
    showVideoCallModal.value = true;
};

// End video call and close the modal
const endVideoCall = () => {
    showVideoCallModal.value = false;
};

// Function to toggle mute
const toggleMute = () => {
    isMuted.value = !isMuted.value;
};

</script>


<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    max-width: 90%;
}

.user-list {
    list-style: none;
    padding: 0;
    margin-top: 10px;
}

.user-list li {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    cursor: pointer;
}

.chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

/* Chat List Header */
.chat-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.user {
    display: flex;
    align-items: center;
    flex-grow: 1;
}

.chat-header .header-icons {
    display: flex;
    margin-left: auto;
    position: relative;
}

.chat-list-header .header-icons {
    display: flex;
    margin-left: auto;
    position: relative;
}

.header-icons i {
    margin-left: 30px;
    cursor: pointer;
}

.chat-header .dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    /* Aligns it to the right of the parent (icon) */
    background: white;
    border: 1px solid #ddd;
    z-index: 1000;
    list-style: none;
    padding: 10px;
    color: black;
    width: 150px;
    cursor: pointer;
}

/* Main container to prevent overlap */
.app-container {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

/* Navbar Styling */
.navbar-container {
    flex-shrink: 0;
    background-color: #ff4081;
    padding: 10px;
    color: white;
}

/* Messenger Layout */
.messenger-layout {
    display: flex;
    flex: 1;
    margin-top: 130px;
    height: calc(100vh - 130px);
    /* Adjust height for layout */
}

/* Chat List */
.chat-list {
    width: 25%;
    background-color: #fbd7df;
    padding: 20px;
    border-right: 1px solid #575052;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.chat-list-content {
    flex: 1;
    overflow-y: auto;
    padding-right: 10px;
    /* Add padding to avoid scroll bar overlap */
}

.chat-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    cursor: pointer;
    margin-top: 10px;
}

.chat-item:hover {
    background-color: #e0e0e0;
    border-radius: 8px;
}

.chat-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    /* Makes the chat avatars circular */
    object-fit: cover;
    /* Ensures the image covers the area while maintaining aspect ratio */
    margin-right: 10px;
}

.chat-info h4 {
    margin: 0;
    font-size: 16px;
}

.last-message {
    font-size: 12px;
    color: #555;
}

/* Chat Interface */
.chat-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: #fbd7df;
    height: 100%;
}

.chat-header {
    display: flex;
    align-items: center;
    padding: 10px;
    background-color: #ff4081;
    color: white;
}

.profile-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    /* Makes the chat avatars circular */
    object-fit: cover;
    /* Ensures the image covers the area while maintaining aspect ratio */
    margin-right: 10px;
}

/* Wrapper for all messages */
.messages {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 10px;
    height: calc(100vh - 180px);
    /* Adjust height based on header and input area */
}

/* Individual message container */
.message-container {
    display: flex;
    flex-direction: column;
    padding: 8px;
    border-radius: 8px;
    word-wrap: break-word;
    max-width: 60%;
    /* Prevent the message from spanning too wide */
}

/* Sent messages aligned to the right */
.sent {
    align-self: flex-end;
    /* Align to the right */
    background-color: #f96c9b;
    color: white;
}

/* Received messages aligned to the left */
.received {
    align-self: flex-start;
    /* Align to the left */
    background-color: #e0e0e0;
}

/* Timestamp styling */
.timestamp {
    font-size: 0.8rem;
    color: #777;
    margin-top: 4px;
    align-self: flex-end;
    /* Align timestamp to the end of message */
}


/* Message Input */
.input-area {
    display: flex;
    align-items: center;
    padding: 10px;
    background-color: #fc8cb1;
    color: white;
    position: sticky;
    bottom: 0;
    z-index: 1;
}

input {
    flex: 1;
    padding: 8px;
    border: none;
    border-radius: 5px;
    margin-right: 10px;
}

button {
    background-color: #ffffff;
    border: none;
    color: #ff4081;
    font-size: 16px;
    padding: 8px;
    cursor: pointer;
}

button i {
    font-size: 24px;
}

/* User Profile */
.user-profile {
    width: 25%;
    background-color: #fbd7df;
    padding: 20px;
    border-left: 1px solid #575052;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    text-align: center;
}

/* User Profile */
.user-profile {
    width: 25%;
    background-color: #fbd7df;
    padding: 20px;
    border-left: 1px solid #575052;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    text-align: center;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    /* Makes the image circular */
    object-fit: cover;
    /* Ensures the image covers the area while maintaining aspect ratio */
    margin-bottom: 20px;
    border: 4px solid #ff4081;
    /* Adds a border for better appearance */
}

.user-profile p {
    margin: 10px 0;
    font-size: 16px;
    color: #333;
}

.user-profile strong {
    font-weight: bold;
    color: #555;
}

/* Styles for the call modal */
.call-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.call-container {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 300px;
    text-align: center;
}

.call-header {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.call-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
}

.call-info h3 {
    margin: 0;
    font-size: 20px;
}

.call-controls {
    display: flex;
    justify-content: space-around;
}

.control-button {
    background: #f1f1f1;
    border: none;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
}

.control-button.end-call {
    background: #ff4b5c;
    color: white;
}

.control-button i {
    font-size: 20px;
}

.control-button.end-call i {
    font-size: 24px;
}

/* Video Call Modal Styles */
.video-call-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.video-call-container {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
    text-align: center;
    position: relative;
}

.video-call-header {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.call-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
}

.video-call-controls {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}

.small-box {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 100px;
    padding: 10px;
    background: #fff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.small-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
}

.small-user-info h4 {
    font-size: 14px;
    margin: 0;
}
</style>
