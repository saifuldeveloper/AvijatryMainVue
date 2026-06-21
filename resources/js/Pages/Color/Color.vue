<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    colors: {
        type: Object,
        required: true,
    },
    trashColor: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ name: '' }),
    },
});

const page = usePage();

// Translation helper
const t = (key) => {
    let cleanKey = key;
    if (key.startsWith('pages.')) {
        cleanKey = key.replace('pages.', '');
    }
    if (page.props.translations?.pages?.[cleanKey]) {
        return page.props.translations.pages[cleanKey];
    }
    if (page.props.translations?.menu?.[cleanKey]) {
        return page.props.translations.menu[cleanKey];
    }
    const fallbacks = {
        en: {
            'shoe_colors': 'Shoe Colors',
            'new_color': 'New Color',
            'color_edit': 'Edit Color',
            'name': 'Name',
            'option': 'Option',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'save': 'Save',
            'update': 'Update',
            'cancel': 'Cancel',
            'Are you sure?': 'Are you sure?',
            'Trash Bin': 'Trash Bin',
            'Active Colors': 'Active Colors',
            'No colors found.': 'No colors found.',
            'No trashed colors found.': 'No trashed colors found.',
            'Search colors...': 'Search colors...',
            'deleted_by': 'Deleted By',
            'date': 'Date',
        },
        bn: {
            'shoe_colors': 'জুতার রং',
            'new_color': 'নতুন রং',
            'color_edit': 'রং সম্পাদন',
            'name': 'নাম',
            'option': 'অপশন',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'save': 'সংরক্ষণ',
            'update': 'আপডেট',
            'cancel': 'বাতিল',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'Trash Bin': 'মুছে ফেলা রংসমূহ',
            'Active Colors': 'সক্রিয় রংসমূহ',
            'No colors found.': 'কোনো জুতার রং পাওয়া যায়নি।',
            'No trashed colors found.': 'মুছে ফেলা কোনো রং পাওয়া যায়নি।',
            'Search colors...': 'রং খুঁজুন...',
            'deleted_by': 'মুছে ফেলেছেন',
            'date': 'তারিখ',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Search filter state
const searchQuery = ref(props.filters?.name || '');

// Server-side filtering with debounce
let searchTimeout;
watch(searchQuery, (newVal) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('color.index'), {
            name: newVal,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, 400);
});

// Page change handler
const goToPage = (pageNumber) => {
    router.get(route('color.index'), {
        name: searchQuery.value,
        page: pageNumber
    }, {
        preserveState: true
    });
};

// Modal states
const isModalOpen = ref(false);
const isEditing = ref(false);

// Form
const form = useForm({
    id: null,
    name: '',
});

// Open modal for adding
const openAddModal = () => {
    isEditing.value = false;
    form.clearErrors();
    form.reset();
    isModalOpen.value = true;
};

// Open modal for editing
const openEditModal = (color) => {
    isEditing.value = true;
    form.clearErrors();
    form.id = color.id;
    form.name = color.name;
    isModalOpen.value = true;
};

// Close modal
const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

// Submit form
const submitForm = () => {
    if (isEditing.value) {
        form.put(route('color.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('color.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// Delete color
const deleteColor = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('color.destroy', id));
    }
};

// Restore color
const restoreColor = (id) => {
    form.post(route('color.restore', id));
};

// Force delete color
const forceDeleteColor = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('color.forceDelete', id));
    }
};
</script>

<template>
    <Head :title="t('shoe_colors')" />

    <AuthenticatedLayout>
        <!-- Colors Layout Card -->
        <div class="max-w-4xl mx-auto border-t-[3px] border-t-blue-600 border-x border-b border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="text-[20px] font-bold text-slate-800 dark:text-white">
                    {{ t('shoe_colors') }}
                </h3>
                
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <!-- Search input -->
                    <div class="relative flex-1 sm:w-64">
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="t('Search colors...')"
                            class="w-full rounded border-slate-250 bg-white px-3 py-1.5 pl-9 text-[15px] text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-slate-650 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500"
                        />
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>

                    <!-- Add Button -->
                    <button
                        @click="openAddModal"
                        class="rounded bg-blue-600 px-4 py-1.5 text-[15px] font-semibold text-white hover:bg-blue-700 transition duration-150 shadow-sm shrink-0"
                    >
                        {{ t('new_color') }}
                    </button>
                </div>
            </div>

            <!-- Body (Active colors) -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[15px]">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-semibold w-16">#</th>
                            <th class="px-6 py-3 font-semibold">{{ t('name') }}</th>
                            <th class="px-6 py-3 font-semibold text-right">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(color, index) in colors.data"
                            :key="color.id"
                            class="border-b border-slate-150 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-800/30"
                        >
                            <td class="px-6 py-3 text-slate-500 dark:text-slate-400">
                                {{ (colors.current_page - 1) * colors.per_page + index + 1 }}
                            </td>
                            <td class="px-6 py-3 text-slate-800 dark:text-slate-200 font-medium">
                                {{ color.name }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                    <button
                                        @click="openEditModal(color)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm"
                                        title="Edit"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="deleteColor(color.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm ml-2"
                                        title="Delete"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                            </td>
                        </tr>
                        <tr v-if="colors.data.length === 0">
                            <td colspan="3" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No colors found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="colors" @page-changed="goToPage" />
            </div>
        </div>

        <!-- Trash Section (Soft Deleted Colors) -->
        <div v-if="trashColor.length > 0" class="max-w-4xl mx-auto border-t-[3px] border-t-rose-500 border-x border-b border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50">
                <h4 class="text-[17px] font-bold text-slate-800 dark:text-white">
                    {{ t('Trash Bin') }} ({{ t('Deleted Color List') || 'মুছে ফেলা তালিকা' }})
                </h4>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-[15px]">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-semibold w-16">#</th>
                            <th class="px-6 py-3 font-semibold">{{ t('name') }}</th>
                            <th class="px-6 py-3 font-semibold text-right">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(color, index) in trashColor"
                            :key="color.id"
                            class="border-b border-slate-150 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-800/30"
                        >
                            <td class="px-6 py-3 text-slate-500 dark:text-slate-400">
                                {{ index + 1 }}
                            </td>
                            <td class="px-6 py-3 text-slate-800 dark:text-slate-200 font-medium">
                                {{ color.name }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                    <button
                                        @click="restoreColor(color.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-sm font-semibold inline-flex items-center gap-1.5 transition duration-150"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteColor(color.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white px-3 py-1.5 rounded text-sm font-semibold inline-flex items-center gap-1.5 transition duration-150 ml-2"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>{{ t('Delete Permanently') || 'স্থায়ীভাবে মুছুন' }}</span>
                                    </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Color Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        >
            <div class="w-full max-w-md rounded-lg bg-white dark:bg-slate-800 shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700 transition-transform scale-100">
                <!-- Modal Header -->
                <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                        {{ isEditing ? t('color_edit') : t('new_color') }}
                    </h3>
                </div>

                <!-- Modal Body -->
                <form @submit.prevent="submitForm">
                    <div class="p-6 space-y-4">
                        <!-- Color Name Input -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                {{ t('name') }} <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500"
                                :placeholder="t('name')"
                            />
                            <div v-if="form.errors.name" class="text-xs text-rose-600 mt-1">
                                {{ form.errors.name }}
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="border-t border-slate-200 dark:border-slate-700 px-6 py-3 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="closeModal"
                            class="rounded border border-slate-300 dark:border-slate-650 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                        >
                            {{ t('cancel') }}
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded bg-blue-600 hover:bg-blue-700 disabled:opacity-50 px-4 py-2 text-sm font-semibold text-white shadow transition"
                        >
                            {{ isEditing ? t('update') : t('save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
