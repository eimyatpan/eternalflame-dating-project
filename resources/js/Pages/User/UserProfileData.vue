<template>
    <UserNavbar :userProfile="myProfile" />
    <div class="profile-page">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-left">
                <img :src="user.profileImage.profile_picture" alt="Profile Picture" class="profile-picture" />
            </div>
            <div class="profile-right">
                <div class="header-content">
                    <h1 class="user-name">
                        <span v-if="!isEditing">{{ user.name }}</span>
                        <input v-if="isEditing" v-model="user.name" type="text" />
                    </h1>
                    <div class="basic-info">
                        <div>
                            <strong class="d-inline pe-2">Age:</strong>
                            <span v-if="!isEditing">{{ user.age }}</span>
                            <input v-if="isEditing" v-model="user.age" type="number" />
                        </div>
                        <div>
                            <strong class="d-inline pe-2">Location:</strong>
                            <span v-if="!isEditing">{{ user.location }}</span>
                            <input v-if="isEditing" v-model="user.location" type="text" />
                        </div>
                    </div>
                    <div>
                        <strong class="d-inline pe-2">Account Type:</strong>
                        <span>{{ accountType }}</span>
                    </div>
                </div>

                <i v-if="!isEditing" class="bi bi-pencil-square edit-icon" @click="toggleModal"></i>

                <!-- Modal Popup -->
                <div v-if="isModalVisible" class="modal">
                    <div class="modal-content">
                        <span class="close" @click="toggleModal">&times;</span>
                        <h2>Edit Profile</h2>
                        <form @submit.prevent="saveChanges">
                            <!-- Username -->
                            <div>
                                <label>Username:</label>
                                <input v-model="form.name" type="text" required />
                            </div>

                            <!-- Gender -->
                            <div>
                                <label>Gender:</label>
                                <select v-model="form.gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <!-- Birthday -->
                            <div>
                                <label>Birthday:</label>
                                <input v-model="form.birthday" type="date" required />
                            </div>

                            <!-- Location -->
                            <div>
                                <label>Location:</label>
                                <select v-model="form.country_id" name="country_id">
                                    <option v-for="country in countries" :key="country.country_id"
                                        :value="country.country_id">
                                        {{ country.country_name }}
                                    </option>
                                </select>

                            </div>

                            <button type="button" @click="updateProfile">Save Changes</button>
                        </form>
                    </div>
                </div>
                <div class="action-buttons">
                    <button @click="triggerFileUpload" class="secondary-btn">Change Profile Picture</button>
                </div>
                <!-- Hidden file input -->
                <input type="file" ref="fileInput" @change="changeProfilePicture" style="display: none"
                    accept="image/*" />
            </div>
        </div>

        <!-- A Few Words about Myself Section -->
        <div class="about-section">
            <h2>A Few Words about Myself</h2>
            <hr>
            <!-- Display Bio -->
            <p v-if="!isEditingBio">{{ user.bio }}</p>

            <!-- Edit Icon -->
            <i v-if="!isEditingBio" class="bi bi-pencil-square edit-icon" @click="toggleEditBio"></i>

            <!-- Modal Popup for Editing Bio -->
            <div v-if="isEditingBio" class="modal">
                <div class="modal-content1">
                    <!-- Close Button -->
                    <span class="close1" @click="toggleEditBio">&times;</span>

                    <!-- Example for About Section -->
                    <p class="example-text">
                        Eg. "I am a software developer with a passion for creating amazing user
                        experiences. I love working with cutting-edge technologies and
                        constantly learning new skills."
                    </p>

                    <!-- Textarea for Bio Edit -->
                    <textarea class="w-100 h-50" v-model="form.bio" rows="4"
                        placeholder="Write something about yourself..."></textarea>
                    <!-- Save Button -->
                    <div class="button-group">
                        <button class="save-btn" @click="updateProfile">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Interests Section -->
        <div class="interests-section">
            <h2>My Interests</h2>
            <i class="bi bi-pencil-square edit-icon" @click="toggleInterestModal"></i>
            <hr />

            <div v-if="!isModalOpen">
                <ul class="interests-list">
                    <li v-for="(hobby, index) in userInterests" :key="index">
                        {{ hobby }}
                    </li>
                </ul>
            </div>

            <!-- Modal for Editing Interests -->
            <div v-if="isModalOpen" class="modal-overlay">
                <div class="modal-content">
                    <!-- Close Button at the Top-Right Corner -->
                    <button class="close-btn" @click="toggleInterestModal">✖</button>

                    <h3>Edit Interests</h3>
                    <div class="hobbies-container">
                        <div v-for="(hobby, index) in userInterests" :key="index" class="hobby-item">
                            <input type="text" v-model="userInterests[index]" placeholder="Enter hobby"
                                class="hobby-input" />
                            <button @click="removeHobby(index)" class="remove-btn">✖</button>
                        </div>
                    </div>

                    <!-- Button Section -->
                    <div class="button-container">
                        <button @click="addHobby" class="add-btn">+ Add Hobby</button>
                        <button @click="saveInterests" class="primary-btn">Save Interests</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="about-me-section">
            <div class="section-header">
                <h2>About Me</h2>
                <!-- Ensure the click event triggers correctly -->
                <span @click="openEditAppearanceModal" class="bi bi-pencil-square edit-icon" role="button"></span>
            </div>
            <hr>

            <!-- Display Preferences -->
            <div class="preferences-info">
                <strong>Occupation:</strong> {{ user.occupation }}
            </div>
            <div class="preferences-info">
                <strong>Education:</strong>{{ user.education }}
            </div>
            <div class="preferences-info">
                <strong>Height:</strong>{{ user.height }} cm
            </div>
            <div class="preferences-info">
                <strong>Body Type:</strong>{{ user.bodyType }}
            </div>
            <div class="preferences-info">
                <strong>Eye Color:</strong> {{ user.eyeColor }}
            </div>
            <div class="preferences-info">
                <strong>Hair Color:</strong> {{ user.hairColor }}
            </div>
            <div class="preferences-info">
                <strong>Relationship Status:</strong> {{ user.relationshipStatus }}
            </div>
            <div v-if="isAppearanceModalOpen" class="modal">
                <div class="modal-content2">
                    <span @click="closeEditAppearanceModal" class="close-btn">&times;</span>
                    <h3>Edit About Me</h3>

                    <div class="preferences-info-row">
                        <strong>Occupation:</strong>
                        <input type="text" v-model="form.occupation" />
                    </div>

                    <div class="preferences-info-row">
                        <strong>Education:</strong>
                        <input type="text" v-model="form.education" />
                    </div>

                    <div class="preferences-info-row">
                        <strong>Height (cm):</strong>
                        <input type="number" v-model="form.height" />
                    </div>

                    <div class="preferences-info-row">
                        <strong>Body Type:</strong>
                        <input type="text" v-model="form.bodyType" />
                    </div>

                    <div class="preferences-info-row">
                        <strong>Eye Color:</strong>
                        <input type="text" v-model="form.eyeColor" />
                    </div>

                    <div class="preferences-info-row">
                        <strong>Hair Color:</strong>
                        <input type="text" v-model="form.hairColor" />
                    </div>

                    <div class="preferences-info-row">
                        <strong>Relationship Status:</strong>
                        <select v-model="form.relationshipStatus">
                            <option value="single">Single</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                        </select>
                    </div>

                    <button @click="saveAppearance" class="save-btn">Save Changes</button>
                    <button @click="closeEditAppearanceModal" class="cancel-btn">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Preferences Section -->
        <div class="preferences-section">
            <div class="section-header">
                <h2>My Preferences</h2>
                <!-- Ensure the click event triggers correctly -->
                <span @click="openEditPreferenceModal" class="bi bi-pencil-square edit-icon" role="button"></span>
            </div>
            <hr>

            <!-- Display Preferences -->
            <div class="preferences-info">
                <strong>Looking for:</strong> {{ user.genderPreference }}
            </div>
            <div class="preferences-info">
                <strong>Age:</strong> From {{ user.minAgePreference }} to {{ user.maxAgePreference }}
            </div>
            <div class="preferences-info">
                <strong>Weight:</strong> From {{ user.minWeightPreference }} to {{ user.maxWeightPreference }} kg
            </div>
            <div class="preferences-info">
                <strong>Height:</strong> From {{ user.minHeightPreference }} to {{ user.maxHeightPreference }} cm
            </div>
            <div class="preferences-info">
                <strong>Description:</strong> {{ user.descriptionPreference }}
            </div>

            <!-- Edit Preferences Modal -->
            <div v-if="isPreferenceModalOpen" class="modal-overlay1">
                <div class="modal-content2">
                    <span @click="closeEditPreferenceModal" class="close-btn">&times;</span>
                    <h3>Edit Preferences</h3>

                    <!-- Gender Preference -->
                    <div class="preferences-info">
                        <strong>Looking for:</strong>
                        <select v-model="form.genderPreference">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Age Preference -->
                    <div class="preferences-info">
                        <strong>Age:</strong>
                        <div class="input-row">
                            <input type="number" v-model="form.minAgePreference" placeholder="From" />
                            <input type="number" v-model="form.maxAgePreference" placeholder="To" />
                        </div>
                    </div>

                    <!-- Weight Preference -->
                    <div class="preferences-info">
                        <strong>Weight:</strong>
                        <div class="input-row">
                            <input type="number" v-model="form.minWeightPreference" placeholder="From (kg)" />
                            <input type="number" v-model="form.maxWeightPreference" placeholder="To (kg)" />
                        </div>
                    </div>

                    <!-- Height Preference -->
                    <div class="preferences-info">
                        <strong>Height:</strong>
                        <div class="input-row">
                            <input type="number" v-model="form.minHeightPreference" placeholder="From (cm)" />
                            <input type="number" v-model="form.maxHeightPreference" placeholder="To (cm)" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="preferences-info">
                        <strong>Description:</strong>
                        <textarea v-model="form.descriptionPreference"
                            placeholder="Describe the qualities you're looking for"></textarea>
                    </div>

                    <button @click="savePreferences" class="save-btn">Save Changes</button>
                    <button @click="closeEditPreferenceModal" class="cancel-btn">Cancel</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { computed } from 'vue';
import { ref, watch } from 'vue';
import UserNavbar from '../Components/UserNavbar.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { route } from 'ziggy-js';
const props = defineProps({
    myProfile: Object,
    countries: Array,
    hobbies: Array,
    loginUser: Array,
    isPremium: Boolean,
});

// Determine the account type based on whether the user is a premium user
const accountType = computed(() => (props.isPremium ? 'Premium' : 'Free'));
const user = ref({
    name: props.myProfile?.user_name || 'Unknown User',
    age: props.myProfile?.user_age || 'Unknown Age',
    birthday: props.myProfile?.user_dateOfBirth || 'Unknown Birthday',
    country_id: props.myProfile?.country?.country_id || null,
    location: props.myProfile?.country?.country_name || 'Unknown Location', // Fetch country name safely
    profileImage: props.myProfile.profiles,
    bio: props.myProfile?.profiles?.bio || 'No bio available',
    occupation: props.myProfile?.about_me?.occupation || 'Unknown Occupation',
    education: props.myProfile?.about_me?.education || 'Unknown Education',
    height: props.myProfile?.about_me?.height || 'Unknown Height',
    bodyType: props.myProfile?.about_me?.body_type || 'Unknown Body Type',
    eyeColor: props.myProfile?.about_me?.eye_color || 'Unknown Eye Color',
    hairColor: props.myProfile?.about_me?.hair_color || 'Unknown Hair Color',
    relationshipStatus: props.myProfile?.about_me?.relationship_status || 'Unknown Status',
    gender: props.myProfile?.user_gender || 'Unknown Gender',
    genderPreference: props.myProfile?.preferences?.preference_gender || 'Unknown Preference', // Handle preferences safely
    minAgePreference: props.myProfile?.preferences?.preference_min_age || 'Unknown Min Age',
    maxAgePreference: props.myProfile?.preferences?.preference_max_age || 'Unknown Max Age',
    minWeightPreference: props.myProfile?.preferences?.preference_min_weight || 'Unknown min weight',
    maxWeightPreference: props.myProfile?.preferences?.preference_max_weight || 'Unknown max weight',
    minHeightPreference: props.myProfile?.preferences?.preference_min_height || 'Unknown min height',
    maxHeightPreference: props.myProfile?.preferences?.preference_max_height || 'Unknown max height',
    descriptionPreference: props.myProfile?.preferences?.preference_description || 'Unknown Eye Color',
    photos: props.myProfile?.photos?.group_of_photos?.url || [], // Safely access group of photos
    hobbies: props.myProfile?.hobbies?.map(hobby => hobby.hobby_name).join(', ') || 'No Hobbies'
});



// Handle profile update
const updateProfile = () => {
    console.log("Updating profile with data:", form); // Add this line

    form.post(route('profile.update'), {
        onSuccess: () => {
            console.log('Profile updated successfully');
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
        }
    });
};

// Reactive state to hold user interests
const isModalOpen = ref(false);
const isPreferenceModalOpen = ref(false);
const isAppearanceModalOpen = ref(false);
const userInterests = ref([...props.hobbies]); // Initialize with hobbies from props

// Toggle modal visibility
const toggleInterestModal = () => {
    isModalOpen.value = !isModalOpen.value;
};

// Add a new hobby field
const addHobby = () => {
    userInterests.value.push(''); // Add empty string as a new hobby
};

// Remove a hobby by index
const removeHobby = (index) => {
    userInterests.value.splice(index, 1); // Remove hobby at specified index
};

const form = useForm({
    name: user.value.name,
    gender: user.value.gender,
    birthday: user.value.birthday,
    country_id: user.value.country_id,
    bio: user.value.bio,
    hobbies: userInterests.value,
    profile_picture: null,
    genderPreference: user.value.genderPreference,
    minAgePreference: user.value.minAgePreference,
    maxAgePreference: user.value.maxAgePreference,
    minWeightPreference: user.value.minWeightPreference,
    maxWeightPreference: user.value.maxWeightPreference,
    minHeightPreference: user.value.minHeightPreference,
    maxHeightPreference: user.value.maxHeightPreference,
    descriptionPreference: user.value.descriptionPreference,
    occupation: user.value.occupation,
    education: user.value.education,
    height: user.value.height,
    bodyType: user.value.bodyType,
    eyeColor: user.value.eyeColor,
    hairColor: user.value.hairColor,
    relationshipStatus: user.value.relationshipStatus
});
console.log(form);
// Save hobbies and close the modal
const saveInterests = () => {
    form.hobbies = userInterests.value; // Update the form with current hobbies

    // Send the updated hobbies to the backend
    form.post(route('profile.updateHobbies'), {
        onSuccess: () => {
            console.log('Hobbies saved successfully');
            toggleInterestModal(); // Close the modal on success
        },
    });
};

const isEditing = ref(false);
const isEditingBio = ref(false);

const isModalVisible = ref(false);
const toggleModal = () => {
    isModalVisible.value = !isModalVisible.value;
};

// Watch the props to log if data changes
watch(() => props.myProfile, (newVal) => {
    console.log('Updated myProfile:', newVal);
});

// Function to toggle editing state
const toggleEdit = () => {
    isEditing.value = !isEditing.value;
};

// Function to toggle editing bio
const toggleEditBio = () => {
    isEditingBio.value = !isEditingBio.value;
};
const fileInput = ref(null);

// Trigger the file input click
const triggerFileUpload = () => {
    fileInput.value.click();
};

// Function to handle the file input change and update the profile picture
const changeProfilePicture = (event) => {
    const file = event.target.files[0];

    if (file) {
        form.profile_picture = file;  // Set the file to the form

        // Submit the form using Inertia post
        form.post(route('profile.uploadProfilePicture'), {
            preserveScroll: true, // Preserve the scroll position on the page
            onSuccess: () => {
                // Reset the file input after successful upload
                form.reset('profile_picture');
            },
            onError: (errors) => {
                console.error('Upload failed', errors);  // Handle errors
            },
        });
    }
};

const openEditPreferenceModal = () => {
    isPreferenceModalOpen.value = true;
};
// Close the edit modal
const closeEditPreferenceModal = () => {
    isPreferenceModalOpen.value = false;
};

const openEditAppearanceModal = () => {
    isAppearanceModalOpen.value = true;
};

const closeEditAppearanceModal = () => {
    isAppearanceModalOpen.value = false;
};
const savePreferences = () => {
    console.log('Saving preferences:', form);

    form.post(route('profile.updatePreference'), {
        onSuccess: () => {
            console.log('Preferences updated successfully');
            closeEditPreferenceModal(); // Close modal on success
        },
        onError: (errors) => {
            console.error('Error updating preferences:', errors);
        },
    });
};

// Save appearance data to the backend
const saveAppearance = () => {
    console.log('Saving appearance:', form); // Debugging log

    form.post(route('profile.updateAboutMe'), {  // Update to use the correct route name
        onSuccess: () => {
            console.log('Appearance updated successfully');
            closeEditAppearanceModal(); // Close modal on success
        },
        onError: (errors) => {
            console.error('Error updating appearance:', errors); // Handle errors
        },
    });
};

// Function to view account settings
const viewSettings = () => {
    alert('Navigating to account settings...');
};

// Function to view a larger photo
const viewPhoto = (photo) => {
    window.open(photo, '_blank');
};

// Function to add a new photo (you'll need to implement the actual logic)
const addPhoto = () => {
    alert('Function to add a new photo should be implemented');
};
// Method to save bio and close modal
const saveBio = () => {
    console.log('Bio saved:', user.bio);
    toggleEditBio();
};
</script>



<style scoped>
/* Profile Page Styles */
.profile-page {
    max-width: 100%;
    margin: 0 auto;
    padding: 200px;
    font-family: 'Arial', sans-serif;
}

/* Profile Header */
.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f5f5f5;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.profile-left {
    flex: 0 0 150px;
}

.profile-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    position: relative;
}

.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
}

.header-content {
    margin-left: 20px;
    position: relative;
}

.user-name {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.basic-info div {
    margin: 10px 0;
}

.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

/* Modal styles */
.modal {
    display: block;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 500px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    position: relative;
}

.close {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 30px;
    cursor: pointer;
}

form div {
    margin-bottom: 10px;
}

form label {
    display: inline-block;
    margin-bottom: 5px;
}

form input,
form select {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

form button {
    background-color: #ff4081;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}

form button:hover {
    background-color: #ff4081;
}

.primary-btn,
.secondary-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
}

.secondary-btn {
    background-color: #fff;
    color: #ff4081;
    border: 1px solid #ff4081;
}

/* About Section */
.about-section {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
}

.about-section h2 {
    margin-bottom: 10px;
}

.about-section .edit-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    cursor: pointer;
    color: #ff4081;
}

.preferences-info-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.preferences-info-row strong {
    width: 150px;
    /* Adjust based on your preference */
    text-align: right;
    margin-right: 10px;
}

.preferences-info-row input,
.preferences-info-row select {
    flex: 1;
    /* Take up the remaining space */
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Modal Styles */
.modal {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content1 {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    max-width: 700px;
    width: 100%;
    height: 400px;
    position: relative;
}

.close1 {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 30px;
    cursor: pointer;
}

/* Example Text Styles */
.example-text {
    padding: 20px;
    font-size: 15px;
    font-style: italic;
    color: #888;
    margin-bottom: 20px;

}

/* Textarea for Bio */
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    resize: none;
    margin-bottom: 15px;
}

/* Button Group */
.button-group {
    display: flex;
    padding: 5px;
    justify-content: center;
}

/* Save Button */
.save-btn {
    background-color: #ff4081;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.save-btn:hover {
    background-color: #ff4081;
}

/* Photo Gallery */
.gallery-section {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.photo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

.gallery-photo {
    width: 100%;
    height: 200px;
    object-fit: cover;
    cursor: pointer;
    border-radius: 5px;
    transition: transform 0.3s ease;
}

.gallery-photo:hover {
    transform: scale(1.05);
}

.add-photo-btn {
    padding: 10px;
    background-color: #ff4081;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* My Interests Section */
.interests-section {
    position: relative;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.interests-section h2 {
    margin-bottom: 10px;
}

.interests-list {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
}

.interests-list li {
    background-color: #ff4081;
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    margin-right: 10px;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.edit-icon {
    font-size: 24px;
    cursor: pointer;
    color: #007bff;
    margin-left: 10px;
    position: absolute;
    top: 10px;
    right: 10px;
}

.primary-btn {
    background-color: #ff4081;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
}

.about-me-section {
    position: relative;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 1.5rem 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

h2 {
    font-size: 1.5rem;
    color: #333;
    margin: 0;
}

.edit-icon,
.save-icon {
    font-size: 1.5rem;
    cursor: pointer;
    color: #ff4081;
    transition: color 0.2s;
}

.edit-icon:hover,
.save-icon:hover {
    color: #ff4081;
}

.about-me-content {
    line-height: 1.6;
}

.appearance-info {
    margin: 1rem 0;
}

.appearance-info strong {
    display: inline-block;
    width: 100px;
}

input[type="text"],
input[type="number"],
select {
    padding: 0.5rem;
    border-radius: 4px;
    border: 1px solid #ccc;
    width: calc(100% - 110px);
    margin-left: 10px;
    transition: border-color 0.2s;
}

input[type="text"]:focus,
input[type="number"]:focus,
select:focus {
    border-color: #ff4081;
}

.preferences-section {
    position: relative;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 1.5rem 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.edit-icon {
    font-size: 1.5rem;
    cursor: pointer;
    color: #ff4081;
    transition: color 0.2s;
}

.edit-icon:hover {
    color: #ff4081;
}

.preferences-info {
    margin: 1rem 0;
}

h2 {
    font-size: 1.5rem;
    color: #333;
    margin: 0;
}

strong {
    display: inline-block;
    width: 200px;
}

input[type="number"],
textarea,
select {
    padding: 0.5rem;
    border-radius: 4px;
    border: 1px solid #ccc;
    width: calc(100% - 210px);
    margin-left: 10px;
    transition: border-color 0.2s;
}

input[type="number"]:focus,
select:focus,
textarea:focus {
    border-color: #007bff;
}

textarea {
    height: 80px;
    resize: none;
}

.cancel-btn {
    background-color: gray;
    color: white;
    border: none;
    padding: 10px;
    margin-top: 10px;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 10px;
}

.modal-overlay1 {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content2 {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    position: relative;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-top: 60px;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: black;
}

.hobbies-container {
    margin-bottom: 20px;
}

.hobby-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.hobby-input {
    flex: 1;
    margin-right: 8px;
    padding: 5px;
}

.remove-btn {
    background-color: red;
    color: white;
    border: none;
    padding: 5px;
    cursor: pointer;
    border-radius: 4px;
}

.button-container {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.add-btn {
    background-color: rgb(99, 247, 99);
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 4px;
}
</style>