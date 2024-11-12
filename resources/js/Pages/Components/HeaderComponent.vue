<template>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#FFEEF2">
      <div class="container-fluid">
        <!-- Logo -->
        <router-link class="navbar-brand" to="/">
          <img src="../img/logo.png" alt="Logo" style="width:100%;height:100%" class="logo-img">
        </router-link>

        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <Link class="fw-bold nav-link" href="/">Home</Link>
            </li>
            <li class="nav-item">
              <Link class="fw-bold nav-link" href="/profile">Profiles</Link>
            </li>
            <li class="nav-item">
              <Link class="fw-bold nav-link" href="/userstory">User Stories</Link>
            </li>
            <li class="nav-item">
              <Link class="fw-bold nav-link" href="/testimonial">Testimonial</Link>
            </li>
            <li class="nav-item">
              <Link class="fw-bold nav-link" href="/contactus">Contact Us</Link>
            </li>

            <!-- Login/Register Button with Icon -->
            <li class="nav-item">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#authModal">
                <i class="fas fa-user"></i> Login / Register
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Modal for Login/Register -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="authModalLabel">
              {{ isForgotPasswordVisible ? 'Forgot Password' : 'Create or Enter Account!' }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Conditional Rendering -->
            <div v-if="isForgotPasswordVisible">
              <!-- Forgot Password Form -->
              <form @submit.prevent="forgotPassword()">

                <div class="mb-3">
                  <label for="forgotPasswordEmail" class="form-label">Enter your email</label>
                  <input type="email" class="form-control" id="forgotPasswordEmail" v-model="forgotPasswordForm.email"
                    placeholder="Enter your email">
                  <span class="text-danger" v-if="forgotPasswordForm.errors.email">{{ forgotPasswordForm.errors.email
                    }}</span>
                </div>
                <div class="mb-3">
                  <label for="forgotPassword" class="form-label">New Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="forgotPassword"
                      v-model="forgotPasswordForm.password" placeholder="Enter your new password">
                    <span class="input-group-text toggle-password" @click="toggleForgotPassword">
                      <i :class="forgot_passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </span>
                  </div>
                  <span class="text-danger" v-if="forgotPasswordForm.errors.password">{{
                    forgotPasswordForm.errors.password }}</span>
                </div>
                <div class="mb-3">
                  <label for="forgotConfirmPassword" class="form-label">Confirm Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="forgotConfirmPassword"
                      v-model="forgotPasswordForm.password_confirmation" placeholder="Confirm your password">
                    <span class="input-group-text toggle-password" @click="toggleForgotConfirmPassword">
                      <i :class="forgot_confirm_passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </span>
                  </div>
                  <span class="text-danger" v-if="forgotPasswordForm.errors.password_confirmation">{{
                    forgotPasswordForm.errors.password_confirmation }}</span>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
                <button type="button" class="btn btn-link w-100 mt-2" @click="showLoginRegisterForm">
                  Back to Login/Register
                </button>
              </form>
            </div>
            <div v-else>
              <!-- Tabs for switching between login and register -->
              <ul class="nav nav-tabs" id="authTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login"
                    type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register"
                    type="button" role="tab" aria-controls="register" aria-selected="false">Register</button>
                </li>
              </ul>

              <!-- Tab Content for Login and Register -->
              <div class="tab-content" id="authTabContent">
                <!-- Login Form -->
                <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                  <form @submit.prevent="userLogin" class="mt-3">
                    <div class="mb-3 position-relative">
                      <label for="loginUsernameOrEmail" class="form-label">Your Email</label>
                      <input type="text" class="form-control" id="loginUsernameOrEmail"
                        placeholder="Enter your username or email" v-model="loginForm.email">
                      <span class="text-danger" v-if="loginErrorUser.email">{{ loginErrorUser.email }}</span>
                    </div>
                    <div class="mb-3 position-relative">
                      <label for="loginPassword" class="form-label">Password</label>
                      <div class="input-group">
                        <input type="password" class="form-control" id="loginPassword" placeholder="Enter your password"
                          v-model="loginForm.password">
                        <span class="input-group-text toggle-password" @click="togglePassword">
                          <i :class="passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                      </div>
                      <span class="text-danger" v-if="loginErrorUser.password">{{ loginErrorUser.password }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" v-model="loginForm.remember">
                        <label class="form-check-label" for="rememberMe">Remember Me</label>
                      </div>
                      <a href="#" @click.prevent="showForgotPasswordForm">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 text-center"
                      data-bs-dismiss="modal">Login</button>
                  </form>
                </div>

                <!-- Register Form -->
                <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                  <form @submit.prevent="userRegister" class="mt-3">
                    <div class="mb-3">
                      <label for="registerUsername" class="form-label">Username</label>
                      <input type="text" class="form-control" id="registerUsername" placeholder="Enter your username"
                        v-model="form.user_name">
                      <span class="text-danger" v-if="registerErrorUser.user_name">{{ registerErrorUser.user_name
                        }}</span>
                    </div>
                    <div class="mb-3">
                      <label for="registerGender" class="form-label">Gender</label>
                      <select class="form-control" id="registerGender" v-model="form.user_gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                      </select>
                      <span class="text-danger" v-if="registerErrorUser.user_gender">{{ registerErrorUser.user_gender
                        }}</span>
                    </div>
                    <div class="mb-3">
                      <label for="registerEmail" class="form-label">Email address</label>
                      <input type="email" class="form-control" id="registerEmail" placeholder="Enter your email"
                        v-model="form.email">
                      <span class="text-danger" v-if="registerErrorUser.email">{{ registerErrorUser.email }}</span>
                    </div>
                    <div class="mb-3">
                      <label for="registerBirthday" class="form-label">Birthday</label>
                      <input type="date" class="form-control" id="registerBirthday" v-model="form.user_dateOfBirth">
                      <span class="text-danger" v-if="registerErrorUser.user_dateOfBirth">{{
                        registerErrorUser.user_dateOfBirth }}</span>
                    </div>
                    <div class="mb-3">
                      <label for="registerPassword" class="form-label">Password</label>
                      <div class="input-group">
                        <input type="password" class="form-control" id="registerPassword"
                          placeholder="Enter your password" v-model="form.password">
                        <span class="input-group-text toggle-password" @click="togglePasswordRegister">
                          <i :class="reg_passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                      </div>
                      <span class="text-danger" v-if="registerErrorUser.password">{{ registerErrorUser.password
                        }}</span>
                    </div>
                    <div class="mb-3">
                      <label for="registerConfirmPassword" class="form-label">Confirm Password</label>
                      <div class="input-group">
                        <input type="password" class="form-control" id="registerConfirmPassword"
                          placeholder="Confirm your password" v-model="form.password_confirmation">
                        <span class="input-group-text toggle-password" @click="toggleConfirmPassword">
                          <i :class="confirm_passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                      </div>
                      <span class="text-danger" v-if="registerErrorUser.password_confirmation">{{
                        registerErrorUser.password_confirmation }}</span>

                    </div>
                    <div class="form-check mb-3">
                      <input type="checkbox" class="form-check-input" id="termsAndConditions">
                      <label class="form-check-label" for="termsAndConditions">
                        I agree to the <a href="#">Terms and Conditions</a>
                      </label>
                      <span class="text-danger" v-if="registerErrorUser.terms">{{ registerErrorUser.terms }}</span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" data-bs-dismiss="modal">Register</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
const form = useForm({
  user_name: '',
  email: '',
  user_gender: '',
  user_dateOfBirth: '',
  password: '',
  password_confirmation: '',
  terms: false,
});

const isForgotPasswordVisible = ref(false);

// Forgot Password Form
const forgotPasswordForm = useForm({
  email: '',
  password: '',
  password_confirmation: '',
});

// Show the Forgot Password Form
function showForgotPasswordForm() {
  isForgotPasswordVisible.value = true;
}
const registerErrorUser = ref({});

function userRegister() {
  form.post('/register', {
    onError: (errorBag) => {
      registerErrorUser.value = errorBag;
    },
  });

}
console.log(form);
const loginForm = useForm({
  email: '',
  password: '',
  remember: false
});
const loginErrorUser = ref({});

const forgotPassword = () => {
  forgotPasswordForm.post('/user/forgotPassword', {
    onSuccess: () => {
      // Navigate to home page and refresh after successful password update
      Inertia.visit('/', { preserveState: false });
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
};
function userLogin() {
  loginForm.post('/login', {
    onError: (errorBag) => {
      loginErrorUser.value = errorBag;
    },
  });

}

const passwordVisible = ref(false);

function togglePassword() {
  const passwordField = document.getElementById('loginPassword');
  passwordVisible.value = !passwordVisible.value;
  passwordField.type = passwordVisible.value ? 'text' : 'password';
}

const reg_passwordVisible = ref(false);

function togglePasswordRegister() {
  const passwordField = document.getElementById('registerPassword');
  reg_passwordVisible.value = !reg_passwordVisible.value;
  passwordField.type = reg_passwordVisible.value ? 'text' : 'password';
}
const confirm_passwordVisible = ref(false);

function toggleConfirmPassword() {
  const passwordField = document.getElementById('registerConfirmPassword');
  confirm_passwordVisible.value = !confirm_passwordVisible.value;
  passwordField.type = confirm_passwordVisible.value ? 'text' : 'password';
}

function showLoginRegisterForm() {
  isForgotPasswordVisible.value = false;
}
const forgot_passwordVisible = ref(false);
function toggleForgotPassword() {
  const passwordField = document.getElementById('forgotPassword');
  forgot_passwordVisible.value = !forgot_passwordVisible.value;
  passwordField.type = forgot_passwordVisible.value ? 'text' : 'password';
}

const forgot_confirm_passwordVisible = ref(false);
function toggleForgotConfirmPassword() {
  const passwordField = document.getElementById('forgotConfirmPassword');
  forgot_confirm_passwordVisible.value = !forgot_confirm_passwordVisible.value;
  passwordField.type = forgot_confirm_passwordVisible.value ? 'text' : 'password';
}
</script>

<style scoped>
/* Navbar styles */
.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
  padding: 15px 20px;
}

.logo-img {
  max-height: 90px;
}

.navbar-nav .nav-link {
  color: black;
  font-size: 1.1rem;
  font-family: Arial, Helvetica, sans-serif;
  padding: 10px 15px;
  transition: color 0.3s ease;
}

.navbar-nav .nav-link:hover {
  color: #FB4C64;
}

.btn-primary {
  background-color: #FB4C64;
  border-color: #FB4C64;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 5px;
}

.btn-primary:hover {
  background-color: #ff667f;
  border-color: #ff667f;
}

/* Password toggle icon styles */
.toggle-password {
  cursor: pointer;
}

/* Modal Form Styles */
.modal-content {
  border-radius: 10px;
  padding: 20px;
  background-color: #fff;
  box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
}

.modal-header {
  border-bottom: none;
  padding-bottom: 0;
}

.modal-title {
  font-size: 1.5rem;
  color: #333;
  font-weight: 600;
}

.modal-body {
  padding-top: 15px;
}

.nav-tabs {
  border-bottom: 2px solid #e9e9e9;
  margin-bottom: 20px;
}

.nav-tabs .nav-link {
  color: #555;
  border-radius: 0;
}

.nav-tabs .nav-link.active {
  background-color: transparent;
  color: #FB4C64;
  border-color: transparent transparent #FB4C64;
  font-weight: 600;
}

.form-label {
  color: #555;
  font-weight: 500;
  margin-bottom: 8px;
}

.form-control {
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ddd;
}

.form-check-label {
  font-size: 0.9rem;
  color: #333;
}

.btn-primary {
  background-color: #FB4C64;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  font-size: 1rem;
  font-weight: 600;
  width: 100%;
  text-transform: uppercase;
  transition: background-color 0.3s ease;
}

.btn-primary:hover {
  background-color: #ff667f;
}

/* Toggle Password Icon */
.toggle-password {
  background: transparent;
  border: none;
}

.toggle-password i {
  color: #777;
}

/* Error messages (optional for form validation) */
.error-message {
  color: #FB4C64;
  font-size: 0.9rem;
  margin-top: 5px;
}
</style>
