<template>
    <section class="mx-auto max-w-4xl space-y-4 p-6">
        <header class="space-y-1">
            <h2 class="text-2xl font-bold text-slate-900">Users</h2>
            <p class="text-sm text-slate-600">Rendered with v-for and a computed full name. Data saved to database.</p>
        </header>

        <!-- Loading/Error State -->
        <div v-if="loading" class="text-center text-slate-500">Loading...</div>
        <div v-if="error" class="rounded bg-red-100 p-3 text-red-700">{{ error }}</div>

        <!-- Add User Form Component -->
        <AddUserForm
            :editing-user="editingUser"
            :submitting="submitting"
            :roles="availableRoles"
            @submit="handleFormSubmit"
            @cancel="cancelEdit"
            ref="userFormRef"
        />

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
import { ref, onMounted } from 'vue';
import axios from 'axios';
import UserCard from './components/UserCard.vue';
import AddUserForm from './components/AddUserForm.vue';

const users = ref([]);
const loading = ref(true);
const submitting = ref(false);
const error = ref('');

// Reference to the form component
const userFormRef = ref(null);

// Available roles for the form
const availableRoles = ['Admin', 'Editor', 'Author', 'Viewer'];

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

// Handle form submission (emitted from AddUserForm)
async function handleFormSubmit(formData) {
    if (editingUser.value) {
        await updateUser(formData);
    } else {
        await addUser(formData);
    }
}

async function addUser(formData) {
    submitting.value = true;
    error.value = '';
    try {
        const response = await axios.post('/api/members', formData);
        users.value.push(response.data);
        // Reset the form via the component ref
        userFormRef.value?.resetForm();
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to add user';
        console.error(e);
    } finally {
        submitting.value = false;
    }
}

function startEdit(user) {
    editingUser.value = user;
}

async function updateUser(formData) {
    submitting.value = true;
    error.value = '';
    try {
        const response = await axios.put(`/api/members/${editingUser.value.id}`, formData);
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

