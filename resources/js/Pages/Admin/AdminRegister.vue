<template>
    <div class="login-container">
        <div class="login-form">
            <img src="../img/logo.png" alt="EternalFlame Logo" class="logo" />
            <h1>EternalFlame</h1>
            <h2>Admin Register</h2>
            <form @submit.prevent="adminRegister">
                <div class="form-group">
                    <label for="adminName">Admin Name</label>
                    <input
                        type="text"
                        id="adminName"
                        v-model="RegisterForm.admin_name"
                        required
                        placeholder="Enter your name"
                    />
                    <span class="text-danger" v-if="registerErrorAdmin.admin_name">{{ registerErrorAdmin.admin_name }}</span>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        v-model="RegisterForm.email"
                        required
                        placeholder="Enter your email"
                    />
                    <span class="text-danger" v-if="registerErrorAdmin.email">{{ registerErrorAdmin.email }}</span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="registerPassword"
                          placeholder="Enter your password" v-model="RegisterForm.password">
                        <span class="input-group-text toggle-password" @click="toggleRegisterPassword">
                          <i :class="passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                      </div>
                    <span class="text-danger" v-if="registerErrorAdmin.password">{{ registerErrorAdmin.password }}</span>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirmPassword"
                          placeholder="Confirm your password" v-model="RegisterForm.password_confirmation">
                        <span class="input-group-text toggle-password" @click="toggleRegisterConfirmPassword">
                          <i :class="conpasswordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                      </div>
                    <span class="text-danger" v-if="registerErrorAdmin.password_confirmation">{{ registerErrorAdmin.password_confirmation }}</span>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <p class="footer-link">
                <Link href="/admin/login">Already have your account?</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const RegisterForm = useForm({
    admin_name: '',
    email: '',
    password: '',
    password_confirmation: ''
});
const registerErrorAdmin = ref({});

function adminRegister() {
    if (RegisterForm.password !== RegisterForm.password_confirmation) {
    registerErrorAdmin.value.password_confirmation = "Passwords do not match.";
    return;
}


    RegisterForm.post('/admin/register', {
        onError: (errorBag) => {
            registerErrorAdmin.value = errorBag;
            console.error('Registration errors:', errorBag);
        },
        onSuccess: () => {
            console.log('Registration successful!');
        }
    });
    console.log(RegisterForm);

}

const passwordVisible = ref(false);

function toggleRegisterPassword() {
  const passwordField = document.getElementById('registerPassword');
  passwordVisible.value = !passwordVisible.value;
  passwordField.type = passwordVisible.value ? 'text' : 'password';
}

const conpasswordVisible = ref(false);

function toggleRegisterConfirmPassword() {
  const passwordField = document.getElementById('confirmPassword');
  conpasswordVisible.value = !conpasswordVisible.value;
  passwordField.type = conpasswordVisible.value ? 'text' : 'password';
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
    padding: 20px;
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
    border-color: #007bff;
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
</style>
