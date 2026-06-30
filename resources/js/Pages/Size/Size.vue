<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    sizes: {
        type: Object,
        required: true,
    },
    trashSize: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        required: true,
    },
    parentscategories: {
        type: Array,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ category: '', size: '' }),
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
            'shoe_sizes': 'Shoe Sizes',
            'shoe_size': 'Shoe Size',
            'new_size': 'New Size',
            'size_edit': 'Edit Size',
            'name': 'Name',
            'shoe_type': 'Shoe Type',
            'type': 'Sub-category',
            'main_type': 'Main Category',
            'option': 'Option',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'save': 'Save',
            'update': 'Update',
            'cancel': 'Cancel',
            'select': 'Select Category',
            'clear': 'Clear Filter',
            'filter': 'Filter',
            'Are you sure?': 'Are you sure?',
            'Trash Bin': 'Trash Bin',
            'Active Sizes': 'Active Sizes',
            'No sizes found.': 'No sizes found.',
            'No trashed sizes found.': 'No trashed sizes found.',
            'Search sizes...': 'Search sizes...',
            'deleted_by': 'Deleted By',
            'date': 'Date',
        },
        bn: {
            'shoe_sizes': 'জুতার সাইজ',
            'shoe_size': 'জুতার সাইজ',
            'new_size': 'নতুন সাইজ',
            'size_edit': 'সাইজ সম্পাদন',
            'name': 'নাম',
            'shoe_type': 'জুতার ধরণ',
            'type': 'উপ-ধরণ',
            'main_type': 'প্রধান ধরণ',
            'option': 'অপশন',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'save': 'সংরক্ষণ',
            'update': 'আপডেট',
            'cancel': 'বাতিল',
            'select': 'ক্যাটাগরি নির্বাচন',
            'clear': 'ফিল্টার পরিষ্কার',
            'filter': 'ফিল্টার',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'Trash Bin': 'মুছে ফেলা সাইজসমূহ',
            'Active Sizes': 'সক্রিয় সাইজসমূহ',
            'No sizes found.': 'কোনো সাইজ পাওয়া যায়নি।',
            'No trashed sizes found.': 'মুছে ফেলা কোনো সাইজ পাওয়া যায়নি।',
            'Search sizes...': 'সাইজ খুঁজুন...',
            'deleted_by': 'মুছে ফেলেছেন',
            'date': 'তারিখ',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const filterCategory = ref(props.filters?.category || '');
const filterSearch = ref(props.filters?.size || '');

// Server-side filtering with debounce
let filterTimeout;
const runFilters = (debounceTime = 400) => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('size.index'), {
            category: filterCategory.value,
            size: filterSearch.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch(filterCategory, () => runFilters(0));
watch(filterSearch, () => runFilters(400));

const clearFilter = () => {
    filterCategory.value = '';
    filterSearch.value = '';
    router.get(route('size.index'), {}, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    router.get(route('size.index'), {
        category: filterCategory.value,
        size: filterSearch.value,
        page: pageNumber
    }, {
        preserveState: true
    });
};

// Toggle Filter Collapse
const isFilterCollapsed = ref(true);
const toggleFilter = () => {
    isFilterCollapsed.value = !isFilterCollapsed.value;
};

// Modal states
const isModalOpen = ref(false);
const isEditing = ref(false);

// Form
const form = useForm({
    id: null,
    name: '',
    parent_category_id: '',
    category_id: '',
});

// Watch parent category in form to filter subcategories
const formSubCategories = computed(() => {
    if (!form.parent_category_id) return [];
    return props.categories.filter(cat => cat.parent_id === parseInt(form.parent_category_id));
});

// Open modal for adding
const openAddModal = () => {
    isEditing.value = false;
    form.clearErrors();
    form.reset();
    isModalOpen.value = true;
};

// Open modal for editing
const openEditModal = (size) => {
    isEditing.value = true;
    form.clearErrors();
    form.id = size.id;
    form.name = size.name;
    form.parent_category_id = size.category?.parent_id || '';
    form.category_id = size.category_id || '';
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
        form.put(route('size.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('size.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// Delete size
const deleteSize = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('size.destroy', id));
    }
};

// Restore size
const restoreSize = (id) => {
    form.post(route('size.restore', id));
};

// Force delete size
const forceDeleteSize = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('size.forceDelete', id));
    }
};
</script>

<template>
    <Head :title="t('shoe_sizes')" />

    <AuthenticatedLayout>
        <!-- Sizes Layout Card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header (matching previous project layout) -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <div class="flex items-baseline gap-2.5">
                    <h3 class="text-[20px] font-bold text-slate-800 dark:text-white">
                        {{ t('shoe_sizes') }}
                    </h3>
                    <button
                        @click="openAddModal"
                        class="text-blue-600 dark:text-blue-400 hover:underline font-bold text-sm"
                    >
                        {{ t('new_size') }}
                    </button>
                </div>
                
                <button
                    @click="toggleFilter"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition duration-150 shadow-sm flex items-center gap-1.5"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>{{ t('filter') }}</span>
                </button>
            </div>

            <!-- Collapsible Filter Panel inside Card -->
            <div v-show="!isFilterCollapsed" class="p-6 border-b border-slate-200 dark:border-slate-700 bg-[#fcfcfc] dark:bg-slate-900/50 transition-all duration-300">
                <div class="flex flex-col sm:flex-row items-end gap-4">
                    <div class="w-full sm:flex-1">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('shoe_type') }}
                        </label>
                        <select
                            v-model="filterCategory"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">{{ t('select') }}</option>
                            <option
                                v-for="parent in parentscategories"
                                :key="parent.id"
                                :value="parent.id"
                            >
                                {{ parent.name }}
                            </option>
                        </select>
                    </div>

                    <div class="w-full sm:flex-1">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('shoe_size') }}
                        </label>
                        <input
                            v-model="filterSearch"
                            type="text"
                            :placeholder="t('Search sizes...')"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>

                    <div class="w-full sm:w-auto shrink-0">
                        <button
                            @click="clearFilter"
                            class="w-full sm:w-auto h-10 rounded bg-[#dc3545] hover:bg-[#c82333] text-white px-5 py-2 text-sm font-semibold transition shadow-sm flex items-center justify-center"
                        >
                            {{ t('clear') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Body (Active sizes) -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[15px]">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-semibold w-16">#</th>
                            <th class="px-6 py-3 font-semibold">{{ t('shoe_type') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('shoe_sizes') }}</th>
                            <th class="px-6 py-3 font-semibold text-right">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(size, index) in sizes.data"
                            :key="size.id"
                            class="border-b border-slate-150 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-800/30"
                        >
                            <td class="px-6 py-3 text-slate-500 dark:text-slate-400">
                                {{ (sizes.current_page - 1) * sizes.per_page + index + 1 }}
                            </td>
                            <td class="px-6 py-3 text-slate-800 dark:text-slate-200 font-medium">
                                <span v-if="size.category">
                                    {{ size.category.parent ? size.category.parent.name + ' - ' : '' }}{{ size.category.name }}
                                </span>
                                <span v-else>—</span>
                            </td>
                            <td class="px-6 py-3 text-slate-800 dark:text-slate-200">
                                {{ size.name }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                    <button
                                        @click="openEditModal(size)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm"
                                        title="Edit"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="deleteSize(size.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm ml-2"
                                        title="Delete"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                            </td>
                        </tr>
                        <tr v-if="sizes.data.length === 0">
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No sizes found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="sizes" @page-changed="goToPage" />
            </div>
        </div>

        <!-- Trash Section (Soft Deleted Sizes) -->
        <div v-if="trashSize.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-800 dark:text-white">
                    {{ t('Trash Bin') }} ({{ t('Deleted Size List') || 'মুছে ফেলা তালিকা' }})
                </h4>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-[15px]">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-semibold w-16">#</th>
                            <th class="px-6 py-3 font-semibold">{{ t('shoe_type') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('shoe_sizes') }}</th>
                            <th class="px-6 py-3 font-semibold text-right">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(size, index) in trashSize"
                            :key="size.id"
                            class="border-b border-slate-150 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-800/30"
                        >
                            <td class="px-6 py-3 text-slate-500 dark:text-slate-400">
                                {{ index + 1 }}
                            </td>
                            <td class="px-6 py-3 text-slate-800 dark:text-slate-200 font-medium">
                                <span v-if="size.category">
                                    {{ size.category.parent ? size.category.parent.name + ' - ' : '' }}{{ size.category.name }}
                                </span>
                                <span v-else>—</span>
                            </td>
                            <td class="px-6 py-3 text-slate-800 dark:text-slate-200">
                                {{ size.name }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                    <button
                                        @click="restoreSize(size.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-sm font-semibold inline-flex items-center gap-1.5 transition duration-150"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteSize(size.id)"
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

        <!-- Add/Edit Size Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm"
        >
            <div class="w-full max-w-xl rounded-lg bg-white dark:bg-slate-800 shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700 transition-transform scale-100">
                <!-- Modal Header -->
                <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                        {{ isEditing ? t('size_edit') : t('new_size') }}
                    </h3>
                </div>

                <!-- Modal Body -->
                <form @submit.prevent="submitForm">
                    <div class="p-6 space-y-4">
                        <!-- Parent Category Select -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                {{ t('main_type') }} <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.parent_category_id"
                                required
                                class="w-full rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">{{ t('select') }}</option>
                                <option
                                    v-for="parent in parentscategories"
                                    :key="parent.id"
                                    :value="parent.id"
                                >
                                    {{ parent.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Sub-Category Select -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                {{ t('type') }} <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.category_id"
                                required
                                :disabled="!form.parent_category_id"
                                class="w-full rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50"
                            >
                                <option value="">{{ t('select') }}</option>
                                <option
                                    v-for="sub in formSubCategories"
                                    :key="sub.id"
                                    :value="sub.id"
                                >
                                    {{ sub.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.category_id" class="text-xs text-rose-600 mt-1">
                                {{ form.errors.category_id }}
                            </div>
                        </div>

                        <!-- Size Name Input -->
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
