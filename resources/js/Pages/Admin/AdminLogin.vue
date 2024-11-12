<template>
    <div class="login-container">
        <div class="login-form">
            <img src="../img/logo.png" alt="EternalFlame Logo" class="logo" />
            <h1>EternalFlame</h1>
            <h2>Admin Login</h2>
            <form @submit.prevent="adminLogin">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" v-model="form.email" required placeholder="Enter your email" />
                    <span class="text-danger" v-if="loginErrorAdmin.email?.length">
                        {{ loginErrorAdmin.email[0] }}
                    </span>

                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="loginPassword" placeholder="Enter your password"
                            v-model="form.password" />
                        <span class="input-group-text toggle-password" @click="togglePassword">
                            <i :class="passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                    </div>
                    <span class="text-danger" v-if="loginErrorAdmin.password?.length">
                        {{ loginErrorAdmin.password[0] }}
                    </span>
                </div>

                <!-- General Error Message -->
                <div class="error" v-if="generalError">
                    {{ generalError }}
                </div>

                <button type="submit" class="btn">Login</button>
            </form>

            <p class="footer-link">
                <Link href="/admin/register">Don't have Admin Account?</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
});

const loginErrorAdmin = ref({});
const generalError = ref(''); // General error message state
const passwordVisible = ref(false);

function adminLogin() {
    form.post('/admin/login', {
        onError: (errorBag) => {
            console.log('Error Bag:', errorBag); // Debugging: Check error structure
            if (errorBag.email || errorBag.password) {
                loginErrorAdmin.value = errorBag; // Store field-specific errors
            } else if (errorBag.login) {
                generalError.value = errorBag.login; // General login error
            }
        },
        onSuccess: () => {
            // Clear errors on successful login
            loginErrorAdmin.value = {};
            generalError.value = '';
        },
    });
}

function togglePassword() {
    const passwordField = document.getElementById('loginPassword');
    passwordVisible.value = !passwordVisible.value;
    passwordField.type = passwordVisible.value ? 'text' : 'password';
}
</script>

<style scoped>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: rgb(255, 212, 219);
}

.login-form {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    width: 550px;
    text-align: center;
}

.logo {
    width: 80px;
    margin-bottom: 20px;
}

h1 {
    margin-bottom: 5px;
    font-size: 24px;
    color: #333;
}

h2 {
    margin-bottom: 20px;
    font-size: 18px;
    color: #555;
}

.form-group {
    margin-bottom: 15px;
    text-align: left;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s;
}

input:focus {
    border-color: #ff8bb2;
}

.btn {
    width: 100%;
    padding: 12px;
    background-color: #ff4081;
    border: none;
    border-radius: 5px;
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #ff8bb2;
}

.error {
    color: red;
    margin-top: 10px;
    font-size: 14px;
}

.footer-link {
    margin-top: 20px;
}

.footer-link a {
    color: #ff4081;
    text-decoration: none;
}

.footer-link a:hover {
    text-decoration: underline;
}

.toggle-password {
    background: transparent;
    border: none;
}

.toggle-password i {
    color: #777;
}
</style>