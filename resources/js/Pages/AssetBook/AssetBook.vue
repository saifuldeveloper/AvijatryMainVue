<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    assetBooks: {
        type: Object,
        required: true,
    },
    trashAssetBooks: {
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
            'asset_accounts': 'Asset Accounts',
            'asset_account': 'Asset Account',
            'new_asset_account': 'New Asset Account',
            'asset_edit': 'Edit Asset Account',
            'name': 'Account Name',
            'option': 'Option',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'save': 'Save',
            'update': 'Update',
            'cancel': 'Cancel',
            'clear': 'Clear Filter',
            'filter': 'Filter',
            'Are you sure?': 'Are you sure?',
            'Trash Bin': 'Trash Bin',
            'Active Accounts': 'Active Asset Accounts',
            'No accounts found.': 'No asset accounts found.',
            'No trashed accounts found.': 'No trashed accounts found.',
            'Search accounts...': 'Search accounts...',
            'deleted_by': 'Deleted By',
            'date': 'Date',
            'view_ledger': 'View Ledger',
            'sl': 'S.L.',
        },
        bn: {
            'asset_accounts': 'অ্যাসেট হিসাবসমূহ',
            'asset_account': 'অ্যাসেট হিসাব',
            'new_asset_account': 'নতুন অ্যাসেট হিসাব',
            'asset_edit': 'অ্যাসেট হিসাব সম্পাদন',
            'name': 'হিসাবের নাম',
            'option': 'অপশন',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'save': 'সংরক্ষণ',
            'update': 'আপডেট',
            'cancel': 'বাতিল',
            'clear': 'ফিল্টার পরিষ্কার',
            'filter': 'ফিল্টার',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'Trash Bin': 'মুছে ফেলা অ্যাসেট হিসাবসমূহ',
            'Active Accounts': 'সক্রিয় অ্যাসেট হিসাবসমূহ',
            'No accounts found.': 'কোনো অ্যাসেট হিসাব পাওয়া যায়নি।',
            'No trashed accounts found.': 'মুছে ফেলা কোনো হিসাব পাওয়া যায়নি।',
            'Search accounts...': 'হিসাবের নাম খুঁজুন...',
            'deleted_by': 'মুছে ফেলেছেন',
            'date': 'তারিখ',
            'view_ledger': 'লেজার দেখুন',
            'sl': 'ক্রমিক',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const filterSearch = ref(props.filters?.name || '');

// Server-side filtering with debounce
let filterTimeout;
const runFilters = (debounceTime = 400) => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('asset-book.index'), {
            name: filterSearch.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch(filterSearch, () => runFilters(400));

const clearFilter = () => {
    filterSearch.value = '';
    router.get(route('asset-book.index'), {}, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    router.get(route('asset-book.index'), {
        name: filterSearch.value,
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
const openEditModal = (book) => {
    isEditing.value = true;
    form.clearErrors();
    form.id = book.id;
    form.name = book.name;
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
        form.put(route('asset-book.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('asset-book.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// Delete asset book
const deleteBook = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('asset-book.destroy', id));
    }
};

// Restore asset book
const restoreBook = (id) => {
    router.post(route('asset.types.restore', id));
};

// Force delete asset book
const forceDeleteBook = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('asset.types.forceDelete', id));
    }
};
</script>

<template>
    <Head :title="t('asset_accounts')" />

    <AuthenticatedLayout>
        <!-- Asset Books Layout Card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <div class="flex items-baseline gap-2.5">
                    <h3 class="text-[20px] font-bold text-slate-800 dark:text-white">
                        {{ t('asset_accounts') }}
                    </h3>
                    <button
                        @click="openAddModal"
                        class="text-blue-600 dark:text-blue-400 hover:underline font-bold text-sm"
                    >
                        {{ t('new_asset_account') }}
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

            <!-- Collapsible Filter Panel -->
            <div v-show="!isFilterCollapsed" class="p-6 border-b border-slate-200 dark:border-slate-700 bg-[#fcfcfc] dark:bg-slate-900/50 transition-all duration-300">
                <div class="flex flex-col sm:flex-row items-end gap-4">
                    <div class="w-full sm:flex-1">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('name') }}
                        </label>
                        <input
                            v-model="filterSearch"
                            type="text"
                            :placeholder="t('Search accounts...')"
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

            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-16">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5">{{ t('name') }}</th>
                            <th class="px-4 py-2.5 text-center w-64">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(book, index) in assetBooks.data"
                            :key="book.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5 text-center">
                                {{ (assetBooks.current_page - 1) * assetBooks.per_page + index + 1 }}
                            </td>
                            <td class="px-4 py-2.5 font-bold">
                                <Link
                                    :href="route('asset-book.show', book.id)"
                                    class="text-blue-600 dark:text-blue-400 hover:underline"
                                >
                                    {{ book.name }}
                                </Link>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <Link
                                        :href="route('asset-book.show', book.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1 transition duration-150 shadow-sm"
                                    >
                                        <i class="fa fa-folder-open text-xs"></i>
                                        <span>{{ t('view_ledger') }}</span>
                                    </Link>
                                    <button
                                        @click="openEditModal(book)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Edit"
                                    >
                                        <i class="fa fa-edit text-xs"></i>
                                    </button>
                                    <button
                                        @click="deleteBook(book.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Delete"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="assetBooks.data.length === 0">
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No accounts found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="assetBooks" @page-changed="goToPage" />
            </div>
        </div>

        <!-- Trash Bin -->
        <div v-if="trashAssetBooks.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-850 dark:text-white">
                    {{ t('Trash Bin') }}
                </h4>
            </div>

            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-205 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-16">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5">{{ t('name') }}</th>
                            <th class="px-4 py-2.5">{{ t('deleted_by') }}</th>
                            <th class="px-4 py-2.5 text-center w-64">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(book, index) in trashAssetBooks"
                            :key="book.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5 text-center">{{ index + 1 }}</td>
                            <td class="px-4 py-2.5 font-medium">{{ book.name }}</td>
                            <td class="px-4 py-2.5">{{ book.deleted_by?.name ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="restoreBook(book.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm"
                                    >
                                        <i class="fa fa-undo text-xs"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteBook(book.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                        <span>{{ t('Delete Permanently') || 'স্থায়ীভাবে মুছুন' }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Asset Book Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        >
            <div class="w-full max-w-md rounded-lg bg-white dark:bg-slate-800 shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                <!-- Modal Header -->
                <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                        {{ isEditing ? t('asset_edit') : t('new_asset_account') }}
                    </h3>
                </div>

                <!-- Modal Body -->
                <form @submit.prevent="submitForm">
                    <div class="p-6 space-y-4">
                        <!-- Name Input -->
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
