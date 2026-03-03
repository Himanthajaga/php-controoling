<template>
    <div class="rounded-lg border border-slate-200 bg-white p-4">
        <h3 class="mb-3 font-semibold text-slate-900">{{ formTitle }}</h3>
        <form @submit.prevent="handleSubmit" class="grid gap-3 sm:grid-cols-2">
            <input
                v-model="localForm.firstName"
                type="text"
                placeholder="First Name"
                required
                class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
            />
            <input
                v-model="localForm.lastName"
                type="text"
                placeholder="Last Name"
                required
                class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
            />
            <input
                v-model="localForm.email"
                type="email"
                placeholder="Email"
                required
                class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
            />
            <select
                v-model="localForm.role"
                required
                class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
            >
                <option value="">Select Role</option>
                <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
            </select>
            <div class="flex gap-2 sm:col-span-2">
                <button
                    type="submit"
                    :disabled="submitting"
                    class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                >
                    {{ submitButtonText }}
                </button>
                <button
                    v-if="isEditing"
                    type="button"
                    @click="handleCancel"
                    class="rounded bg-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { reactive, computed, watch } from 'vue';

// Props - receiving data from parent
const props = defineProps({
    // User being edited (null when adding new user)
    editingUser: {
        type: Object,
        default: null,
    },
    // Loading state during form submission
    submitting: {
        type: Boolean,
        default: false,
    },
    // Available roles for the dropdown
    roles: {
        type: Array,
        default: () => ['Admin', 'Editor', 'Author', 'Viewer'],
    },
});

// Emit events to parent
const emit = defineEmits(['submit', 'cancel']);

// Local form state
const localForm = reactive({
    firstName: '',
    lastName: '',
    email: '',
    role: '',
});

// Computed properties
const isEditing = computed(() => props.editingUser !== null);

const formTitle = computed(() =>
    isEditing.value ? 'Edit User' : 'Add New User'
);

const submitButtonText = computed(() => {
    if (props.submitting) return 'Saving...';
    return isEditing.value ? 'Update User' : 'Add User';
});

// Watch for changes in editingUser prop to populate form
watch(
    () => props.editingUser,
    (newUser) => {
        if (newUser) {
            localForm.firstName = newUser.firstName || '';
            localForm.lastName = newUser.lastName || '';
            localForm.email = newUser.email || '';
            localForm.role = newUser.role || '';
        } else {
            resetForm();
        }
    },
    { immediate: true }
);

// Methods
function resetForm() {
    localForm.firstName = '';
    localForm.lastName = '';
    localForm.email = '';
    localForm.role = '';
}

function handleSubmit() {
    // Emit 'submit' event with form data to parent
    emit('submit', {
        firstName: localForm.firstName,
        lastName: localForm.lastName,
        email: localForm.email,
        role: localForm.role,
    });
}

function handleCancel() {
    // Emit 'cancel' event to parent
    emit('cancel');
    resetForm();
}

// Expose resetForm method to parent (optional)
defineExpose({ resetForm });
</script>

