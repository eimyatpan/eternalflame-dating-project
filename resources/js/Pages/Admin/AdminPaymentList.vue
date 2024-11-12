<template>
    <AdminLayout>
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payments List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">User</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Method</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(payment, index) in paginatedPayments" :key="payment.payment_id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ payment.user_name }}</td>
                                    <td>{{ payment.payment_amount }}</td>
                                    <td>{{ payment.payment_date }}</td>
                                    <td>{{ payment.payment_method }}</td>
                                    <td>
                                        <span class="badge" :class="{
                                            'badge-success': payment.payment_status === 'completed',
                                            'badge-warning': payment.payment_status === 'pending',
                                            'badge-danger': payment.payment_status === 'rejected'
                                        }">
                                            {{ payment.payment_status }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="action-buttons">
                                            <button v-if="payment.payment_status === 'pending'"
                                                @click="openModal('approve', payment)"
                                                class="approve-button custom-button">
                                                Approve
                                            </button>
                                            <button v-if="payment.payment_status === 'pending'"
                                                @click="openModal('reject', payment)"
                                                class="reject-button custom-button">
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination mt-3 d-flex justify-content-between">
                        <button class="btn btn-custom" :disabled="currentPage === 1" @click="prevPage">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <span>Page {{ currentPage }} of {{ totalPages }}</span>
                        <button class="btn btn-custom" :disabled="currentPage === totalPages" @click="nextPage">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Component -->
            <div v-if="showModal" class="modal-overlay">
                <div class="modal-content">
                    <p class="modal-message">{{ modalMessage }}</p>
                    <div class="modal-actions">
                        <button @click="confirmAction" class="btn btn-success">Confirm</button>
                        <button @click="closeModal" class="btn btn-danger">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div v-if="successMessage" class="alert alert-success" role="alert">
                {{ successMessage }}
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '../Layout/AdminLayout.vue';
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

// Props received from the controller via Inertia
const props = defineProps(['payment']);

const currentPage = ref(1);
const perPage = ref(5);
const totalPages = computed(() => Math.ceil((props.payment || []).length / perPage.value));

const paginatedPayments = computed(() => {
    const payments = props.payment || []; // Fallback to empty array if undefined
    const start = (currentPage.value - 1) * perPage.value;
    return payments.slice(start, start + perPage.value);
});



const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
};

// Modal state management
const showModal = ref(false);
const modalAction = ref(null);  
const selectedPayment = ref(null);  
const modalMessage = ref('');
const successMessage = ref('');

// Open the modal and set action & payment
const openModal = (action, payment) => {
    modalAction.value = action;
    selectedPayment.value = payment;
    modalMessage.value = `Are you sure you want to ${action} this payment?`;
    showModal.value = true;
};

// Close the modal without any action
const closeModal = () => {
    showModal.value = false;
    modalAction.value = null;
    selectedPayment.value = null;
};

// Confirm the action and send request to backend
const confirmAction = async () => {
    if (!selectedPayment.value) {
        console.error('No payment selected.');
        return;
    }

    const { user_id, payment_id } = selectedPayment.value;

    // Check if user_id exists before proceeding
    if (!user_id) {
        console.error('User ID is missing from the selected payment:', selectedPayment.value);
        successMessage.value = 'Error: Payment action could not be completed due to missing user information.';
        closeModal();
        return;
    }

    const newStatus = modalAction.value === 'approve' ? 'completed' : 'rejected';
    const route = `/admin/paymentLists/${payment_id}/${modalAction.value}`;

    try {
        // Update payment status and handle premium user creation in the same request
        await Inertia.post(route, {
            payment_status: newStatus,
            user_id: user_id,
            payment_id: payment_id,
            discountRate: 0.25,  // Set default discount rate as 25% for premium user
        });

        selectedPayment.value.payment_status = newStatus;
        successMessage.value = `Payment successfully ${modalAction.value}d.`;
        closeModal();
    } catch (error) {
        console.error('Error updating payment status or inserting into premiumuser:', error);
    }
};
</script>


<style scoped>
.alert {
    margin-top: 15px;
}

.container-fluid {
    margin-top: 20px;
}

.card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-header {
    background-color: #343a40;
    color: white;
    padding: 15px;
    text-align: center;
}

.table {
    margin-bottom: 0;
    table-layout: fixed;
    width: 100%;
}

.table th,
.table td {
    text-align: center;
    vertical-align: middle;
    padding: 12px;
}

/* Adjust column widths */
.table th:nth-child(1),
.table td:nth-child(1) {
    width: 5%;
}

.table th:nth-child(2),
.table td:nth-child(2) {
    width: 20%;
}

.table th:nth-child(3),
.table td:nth-child(3) {
    width: 15%;
}

.table th:nth-child(4),
.table td:nth-child(4) {
    width: 15%;
}

.table th:nth-child(5),
.table td:nth-child(5) {
    width: 15%;
}

.table th:nth-child(6),
.table td:nth-child(6) {
    width: 15%;
}

.table th:nth-child(7),
.table td:nth-child(7) {
    width: 15%;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f2f2f2;
}

.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-custom {
    background-color: #ff4081;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-custom:hover {
    background-color: pink;
}

.badge {
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 14px;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: white;
}
.badge-danger {
    background-color: #dc3545; /* Red for rejected status */
    color: white;
}

.custom-button {
    color: white !important;
    border: 1px solid;
    border-radius: 5px;
    padding: 8px 16px;
    font-size: 16px;
    transition: all 0.3s ease;
    cursor: pointer;
    width: 100%;
    /* Ensure both buttons have the same width */
}

/* Align buttons vertically with spacing */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
    /* Space between buttons */
}

/* Approve Button Styles */
.approve-button {
    background-color: #28a745;
    border-color: #28a745;
}

.approve-button:hover {
    background-color: #218838;
    border-color: #1e7e34;
    transform: scale(1.05);
}

/* Reject Button Styles */
.reject-button {
    background-color: #dc3545;
    border-color: #dc3545;
}

.reject-button:hover {
    background-color: #c82333;
    border-color: #bd2130;
    transform: scale(1.05);
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    width: 300px;
}

.modal-buttons {
    display: flex;
    justify-content: space-around;
    margin-top: 15px;
}

.btn-custom,
.btn-success,
.btn-danger {
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 16px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-custom {
    background-color: #ff4081;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.modal-overlay {
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

.modal-actions {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}

.btn-success {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.modal-message {
    font-size: 17px;
    margin: 10px 0;
}
</style>
