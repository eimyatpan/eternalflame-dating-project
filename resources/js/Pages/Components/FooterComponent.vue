<template>
  <footer class="footer" style="background-color:#9A8C98">
    <div class="container">
      <div class="row">
        <!-- About Description -->
        <div class="col-md-3">
          <img src="../img/logo.png" alt="Logo" class="footer-logo mb-3">
          <p>EternalFlame will help you find your perfect soulmate. Join today and start searching for your partner right away.</p>
         <!-- Social Media Icons -->
         <div class="social-icons mt-3">
            <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-telegram"></i></a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-md-2">
          <h5>Quick Links</h5>
          <ul class="list-unstyled">
            <li><Link class="footer-link" href="/">Home</Link></li>
            <li><Link class="footer-link" href="/">About Us</Link></li>
            <li><Link class="footer-link" href="/">Contact Us</Link></li>
            <li><Link class="footer-link" href="/">User Story</Link></li>
            <li><Link class="footer-link" href="/">Testimonials</Link></li>
          </ul>
        </div>

        <!-- Contact Details with Icons -->
        <div class="col-md-3">
          <h5>Contact Details</h5>
          <ul class="list-unstyled">
            <li>
              <i class="bi bi-envelope"></i> 
              <a href="mailto:support@eternalflamedating.com" class="footer-link">support@eternalflamedating.com</a>
            </li>
            <li>
              <i class="bi bi-telephone"></i> 
              <a href="tel:+959420134573" class="footer-link">+959 420 134 573</a>
            </li>
          </ul>
        </div>

        <!-- Newsletter Subscription with Arrow Icon -->
        <div class="col-md-4">
          <h5>Subscribe To Newsletter</h5>
          <p>Be the first one to know about our new features, updates, and many more.</p>
          <form class="newsletter-form">
            <div class="input-group">
              <input type="email" placeholder="Email Address" class="form-control" />
              <span class="input-group-text" id="basic-addon2">
                <i class="bi bi-send-arrow-up-fill"></i>
              </span>
            </div>
          </form>
        </div>
      </div>

      <!-- Footer Bottom (Privacy Policy, Terms, Copyright) -->
      <div class="footer-bottom mt-4 text-center">
        <ul class="list-inline">
          <li class="list-inline-item"><router-link to="/privacy-policy" class="footer-link">Privacy Policy</router-link></li>
          <li class="list-inline-item"><router-link to="/terms-conditions" class="footer-link">Terms & Conditions</router-link></li>
        </ul>
        <p>&copy; 2024 EternalFlame. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.footer {
  padding: 40px 0;
  background-color: #f8f9fa;
  color: #ffefef;
}

.footer-logo {
  width: 200px;
}

.footer h5 {
  font-weight: bold;
  margin-bottom: 20px;
}

.footer ul {
  padding-left: 0;
  list-style: none;
}

.footer ul li {
  margin-bottom: 10px;
}

.footer-link {
  color: rgb(255, 255, 255);
  font-style: oblique;
  text-decoration: none;
  transition: color 0.3s ease;
}

.footer-link:hover {
  color: #fb92a0;
}

.newsletter-form input {
  padding: 10px;
}

.newsletter-form .input-group-text {
  background-color: transparent;
  border: solid;
  cursor: pointer;
  font-size: 24px;
  color: rgb(255, 252, 252);
  transition: color 0.3s ease;
}

.newsletter-form .input-group-text:hover {
  color: #f6f9fc;
}

.social-icons {
  display: flex;
  gap: 10px;
}

.social-icon {
  font-size: 24px;
  color: rgb(255, 253, 253);
  text-decoration: none;
  transition: color 0.3s ease;
}

.social-icon:hover {
  color: #fb92a0;
}

.footer-bottom {
  border-top: 1px solid #ddd;
  padding-top: 20px;
  margin-top: 20px;
  font-size: 14px;
}

.footer-bottom ul {
  padding-left: 0;
  list-style: none;
  margin-bottom: 10px;
}

.footer-bottom ul li {
  display: inline;
  margin: 0 10px;
}

.footer-bottom p {
  margin-top: 10px;
}
</style>
