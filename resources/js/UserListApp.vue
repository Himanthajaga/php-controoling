<template>
    <section class="mx-auto max-w-4xl space-y-4 p-6">
        <header class="space-y-1">
            <h2 class="text-2xl font-bold text-slate-900">Users</h2>
            <p class="text-sm text-slate-600">Rendered with v-for and a computed full name. Data saved to database.</p>
        </header>

        <!-- Loading/Error State -->
        <div v-if="loading" class="text-center text-slate-500">Loading...</div>
        <div v-if="error" class="rounded bg-red-100 p-3 text-red-700">{{ error }}</div>

        <!-- Add User Form -->
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <h3 class="mb-3 font-semibold text-slate-900">{{ editingUser ? 'Edit User' : 'Add New User' }}</h3>
            <form @submit.prevent="editingUser ? updateUser() : addUser()" class="grid gap-3 sm:grid-cols-2">
                <input v-model="form.firstName" type="text" placeholder="First Name" required
                    class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" />
                <input v-model="form.lastName" type="text" placeholder="Last Name" required
                    class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" />
                <input v-model="form.email" type="email" placeholder="Email" required
                    class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" />
                <select v-model="form.role" required
                    class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Select Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Editor">Editor</option>
                    <option value="Author">Author</option>
                    <option value="Viewer">Viewer</option>
                </select>
                <div class="flex gap-2 sm:col-span-2">
                    <button type="submit" :disabled="submitting"
                        class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50">
                        {{ submitting ? 'Saving...' : (editingUser ? 'Update User' : 'Add User') }}
                    </button>
                    <button v-if="editingUser" type="button" @click="cancelEdit"
                        class="rounded bg-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <UserCard
                v-for="user in users"
                :key="user.id"
                :user="user"
                @edit="startEdit"
                @delete="deleteUser"
            />
        </div>

        <div v-if="!loading && users.length === 0" class="text-center text-slate-500">
            No users yet. Add one above!
        </div>
    </section>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import UserCard from './components/UserCard.vue';

const users = ref([]);
const loading = ref(true);
const submitting = ref(false);
const error = ref('');

const form = reactive({
    firstName: '',
    lastName: '',
    email: '',
    role: '',
});

const editingUser = ref(null);

// Fetch users on mount
onMounted(async () => {
    await fetchUsers();
});

async function fetchUsers() {
    loading.value = true;
    error.value = '';
    try {
        const response = await axios.get('/api/members');
        users.value = response.data;
    } catch (e) {
        error.value = 'Failed to load users';
        console.error(e);
    } finally {
        loading.value = false;
    }
}

function resetForm() {
    form.firstName = '';
    form.lastName = '';
    form.email = '';
    form.role = '';
}

async function addUser() {
    submitting.value = true;
    error.value = '';
    try {
        const response = await axios.post('/api/members', {
            firstName: form.firstName,
            lastName: form.lastName,
            email: form.email,
            role: form.role,
        });
        users.value.push(response.data);
        resetForm();
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to add user';
        console.error(e);
    } finally {
        submitting.value = false;
    }
}

function startEdit(user) {
    editingUser.value = user;
    form.firstName = user.firstName;
    form.lastName = user.lastName;
    form.email = user.email;
    form.role = user.role;
}

async function updateUser() {
    submitting.value = true;
    error.value = '';
    try {
        const response = await axios.put(`/api/members/${editingUser.value.id}`, {
            firstName: form.firstName,
            lastName: form.lastName,
            email: form.email,
            role: form.role,
        });
        const index = users.value.findIndex(u => u.id === editingUser.value.id);
        if (index !== -1) {
            users.value[index] = response.data;
        }
        cancelEdit();
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to update user';
        console.error(e);
    } finally {
        submitting.value = false;
    }
}

function cancelEdit() {
    editingUser.value = null;
    resetForm();
}

async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user?')) return;

    error.value = '';
    try {
        await axios.delete(`/api/members/${userId}`);
        users.value = users.value.filter(u => u.id !== userId);
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to delete user';
        console.error(e);
    }
}
</script>

