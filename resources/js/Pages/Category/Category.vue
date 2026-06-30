<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    parents: {
        type: Array,
        required: true,
    },
    parentCategory: {
        type: Array,
        required: true,
    },
    trashCategory: {
        type: Array,
        default: () => [],
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
            'shoe_categories': 'Shoe Categories',
            'new_type': 'New Category',
            'shoe_type_edit': 'Edit Category',
            'name': 'Name',
            'parent_id': 'Parent Category',
            'main_type': 'Main Category (No Parent)',
            'option': 'Option',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'save': 'Save',
            'update': 'Update',
            'cancel': 'Cancel',
            'Are you sure?': 'Are you sure?',
            'Trash Bin': 'Trash Bin',
            'Active Categories': 'Active Categories',
            'No categories found.': 'No categories found.',
            'No trashed categories found.': 'No trashed categories found.',
            'Search categories...': 'Search categories...',
            'deleted_by': 'Deleted By',
            'date': 'Date',
        },
        bn: {
            'shoe_categories': 'জুতার ধরণ',
            'new_type': 'নতুন ধরণ',
            'shoe_type_edit': 'ধরণ সম্পাদন',
            'name': 'নাম',
            'parent_id': 'প্রধান ধরণ',
            'main_type': 'প্রধান ধরণ (কোনো অভিভাবক নেই)',
            'option': 'অপশন',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'save': 'সংরক্ষণ',
            'update': 'আপডেট',
            'cancel': 'বাতিল',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'Trash Bin': 'মুছে ফেলা ধরণসমূহ',
            'Active Categories': 'সক্রিয় ধরণসমূহ',
            'No categories found.': 'কোনো জুতার ধরণ পাওয়া যায়নি।',
            'No trashed categories found.': 'মুছে ফেলা কোনো ধরণ পাওয়া যায়নি।',
            'Search categories...': 'ধরণ খুঁজুন...',
            'deleted_by': 'মুছে ফেলেছেন',
            'date': 'তারিখ',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Search filter state
const searchQuery = ref('');

// Filtered categories grid
const filteredParents = computed(() => {
    if (!searchQuery.value) return props.parents;
    const query = searchQuery.value.toLowerCase();
    
    return props.parents.map(parent => {
        // Filter children
        const filteredChildren = parent.children ? parent.children.filter(child => 
            child.name?.toLowerCase().includes(query)
        ) : [];
        
        // If parent matches or any child matches, keep it
        const parentMatches = parent.name?.toLowerCase().includes(query);
        
        if (parentMatches || filteredChildren.length > 0) {
            return {
                ...parent,
                // If parent matches but no children matched, show all children of this parent
                children: filteredChildren.length > 0 ? filteredChildren : parent.children
            };
        }
        return null;
    }).filter(Boolean);
});

// Modal states
const isModalOpen = ref(false);
const isEditing = ref(false);

// Form
const form = useForm({
    id: null,
    name: '',
    parent_id: '',
});

// Open modal for adding
const openAddModal = () => {
    isEditing.value = false;
    form.clearErrors();
    form.reset();
    isModalOpen.value = true;
};

// Open modal for editing
const openEditModal = (category) => {
    isEditing.value = true;
    form.clearErrors();
    form.id = category.id;
    form.name = category.name;
    form.parent_id = category.parent_id || '';
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
        form.put(route('category.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('category.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// Delete category
const deleteCategory = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('category.destroy', id));
    }
};

// Restore category
const restoreCategory = (id) => {
    form.post(route('category.restore', id));
};

// Force delete category
const forceDeleteCategory = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('category.forceDelete', id));
    }
};
</script>

<template>
    <Head :title="t('shoe_categories')" />

    <AuthenticatedLayout>
        <!-- Categories Layout Card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-[20px] font-bold text-slate-800 dark:text-white">
                    {{ t('shoe_categories') }}
                </h3>
                
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <!-- Search input -->
                    <div class="relative flex-1 sm:w-64">
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="t('Search categories...')"
                            class="w-full rounded border-slate-300 bg-white px-3 py-1.5 pl-9 text-[15px] text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-slate-650 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500"
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
                        {{ t('new_type') }}
                    </button>
                </div>
            </div>

            <!-- Body (Active categories grouped by parents) -->
            <div class="p-6">
                <div v-if="filteredParents.length === 0" class="text-center py-12 text-slate-500 dark:text-slate-400">
                    {{ t('No categories found.') }}
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div
                        v-for="parent in filteredParents"
                        :key="parent.id"
                        class="border border-slate-200 dark:border-slate-700 rounded overflow-hidden shadow-sm flex flex-col bg-white dark:bg-slate-900"
                    >
                        <!-- Parent Category Title -->
                        <div class="bg-slate-100 dark:bg-slate-800 px-4 py-2.5 font-bold text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                            <span>{{ parent.name }}</span>
                            <div class="flex items-center gap-1.5">
                                <button
                                    @click="openEditModal(parent)"
                                    class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1 rounded transition duration-150 shadow-sm"
                                    title="Edit"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button
                                    @click="deleteCategory(parent.id)"
                                    class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1 rounded transition duration-150 shadow-sm"
                                    title="Delete"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Children Categories Table -->
                        <div class="flex-1 overflow-x-auto min-h-[120px]">
                            <table class="w-full text-[14px]">
                                <tbody>
                                    <tr
                                        v-for="child in parent.children"
                                        :key="child.id"
                                        class="border-b border-slate-100 dark:border-slate-800/80 hover:bg-slate-50/50 dark:hover:bg-slate-800/30"
                                    >
                                        <td class="px-4 py-2 text-slate-700 dark:text-slate-300">
                                            {{ child.name }}
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            <button
                                                @click="openEditModal(child)"
                                                class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1 rounded transition duration-150 shadow-sm"
                                                title="Edit"
                                            >
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button
                                                @click="deleteCategory(child.id)"
                                                class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1 rounded transition duration-150 shadow-sm ml-1.5"
                                                title="Delete"
                                            >
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="!parent.children || parent.children.length === 0">
                                        <td colspan="2" class="text-center py-6 text-slate-400 dark:text-slate-500 italic">
                                            No subcategories
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trash Section (Soft Deleted Categories) -->
        <div v-if="trashCategory.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-800 dark:text-white">
                    {{ t('Trash Bin') }} ({{ t('Deleted Category List') || 'মুছে ফেলা তালিকা' }})
                </h4>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-[15px]">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-semibold">{{ t('name') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('parent_id') }}</th>
                            <th class="px-6 py-3 font-semibold text-right">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="cat in trashCategory"
                            :key="cat.id"
                            class="border-b border-slate-150 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-800/30"
                        >
                            <td class="px-6 py-3 text-slate-800 dark:text-slate-200 font-medium">
                                {{ cat.name }}
                            </td>
                            <td class="px-6 py-3 text-slate-600 dark:text-slate-400">
                                {{ cat.parent ? cat.parent.name : '—' }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                    <button
                                        @click="restoreCategory(cat.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-sm font-semibold inline-flex items-center gap-1.5 transition duration-150"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteCategory(cat.id)"
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

        <!-- Add/Edit Category Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        >
            <div class="w-full max-w-md rounded-lg bg-white dark:bg-slate-800 shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700 transition-transform scale-100">
                <!-- Modal Header -->
                <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                        {{ isEditing ? t('shoe_type_edit') : t('new_type') }}
                    </h3>
                </div>

                <!-- Modal Body -->
                <form @submit.prevent="submitForm">
                    <div class="p-6 space-y-4">
                        <!-- Parent Category Select -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                {{ t('parent_id') }}
                            </label>
                            <select
                                v-model="form.parent_id"
                                class="w-full rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">{{ t('main_type') }}</option>
                                <option
                                    v-for="parent in parentCategory"
                                    :key="parent.id"
                                    :value="parent.id"
                                    :disabled="isEditing && form.id === parent.id"
                                >
                                    {{ parent.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.parent_id" class="text-xs text-rose-600 mt-1">
                                {{ form.errors.parent_id }}
                            </div>
                        </div>

                        <!-- Category Name Input -->
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
