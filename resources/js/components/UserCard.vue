<template>
    <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">{{ fullName }}</h3>
                <p class="text-sm text-slate-600">{{ user.email }}</p>
                <span class="mt-2 inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-700">
                    {{ user.role }}
                </span>
            </div>
            <div class="flex gap-2">
                <button @click="emit('edit', user)"
                    class="rounded bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700 hover:bg-blue-200">
                    Edit
                </button>
                <button @click="emit('delete', user.id)"
                    class="rounded bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">
                    Delete
                </button>
            </div>
        </div>
    </article>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['edit', 'delete']);

const fullName = computed(() => `${props.user.firstName} ${props.user.lastName}`.trim());
</script>

