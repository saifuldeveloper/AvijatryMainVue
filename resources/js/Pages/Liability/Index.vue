<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    liabilities: {
        type: Object,
        required: true,
    },
    trashLiabilities: {
        type: Array,
        default: () => [],
    },
    accountBooks: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ name: '' }),
    },
});

const page = usePage();

// Translation Helper
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
            'liabilities': 'Liabilities Register',
            'liabilities books': 'Liabilities Books',
            'new liabilities book': 'New Liabilities Book',
            'edit liabilities book': 'Edit Liabilities Book',
            'delete liabilities books': 'Deleted Liabilities Books',
            'liabilities entry': 'Liabilities Entry',
            'liabilities book name': 'Liabilities Account Book',
            'Liabilities Amount': 'Amount (Taka)',
            'details': 'Details / Note',
            'select': '-- Select --',
            'name': 'Name',
            'mobile_no': 'Mobile No',
            'option': 'Option',
            'sl': 'SL',
            'save': 'Save',
            'clear': 'Clear',
            'filter': 'Filter',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'force_delete': 'Delete Permanently',
            'deleted_by': 'Deleted By',
            'date': 'Date',
            'Are you sure?': 'Are you sure?',
            'No records found.': 'No records found.',
            'Search name...': 'Search by name...',
            'processing': 'Processing...',
            'liabilites_entry_create': 'Create Liabilities Entry',
            'liabilites_entry_edit': 'Edit Liabilities Entry',
        },
        bn: {
            'liabilities': 'দেনা খাতা রেজিস্টার',
            'liabilities books': 'দেনা খাতাসমূহ',
            'new liabilities book': 'নতুন দেনা খাতা',
            'edit liabilities book': 'দেনা খাতা সম্পাদন',
            'delete liabilities books': 'মুছে ফেলা দেনা খাতাসমূহ',
            'liabilities entry': 'দেনা এন্ট্রি',
            'liabilities book name': 'দেনা খাতা হিসাব',
            'Liabilities Amount': 'দেনার পরিমাণ (টাকা)',
            'details': 'বিস্তারিত বিবরণ',
            'select': '-- নির্বাচন করুন --',
            'name': 'নাম',
            'mobile_no': 'মোবাইল নং',
            'option': 'অপশন',
            'sl': 'ক্রমিক',
            'save': 'সংরক্ষণ',
            'clear': 'পরিষ্কার করুন',
            'filter': 'ফিল্টার',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'force_delete': 'স্থায়ীভাবে মুছুন',
            'deleted_by': 'মুছে ফেলেছেন',
            'date': 'তারিখ',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'Search name...': 'নাম দিয়ে খুঁজুন...',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
            'liabilites_entry_create': 'নতুন দেনা এন্ট্রি',
            'liabilites_entry_edit': 'দেনা এন্ট্রি সম্পাদন',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter state
const filterSearch = ref(props.filters?.name || '');

// Server-side filtering
let filterTimeout;
const runFilters = () => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('liabilities.index'), {
            name: filterSearch.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, 400);
};

watch(filterSearch, runFilters);

const clearFilter = () => {
    filterSearch.value = '';
    router.get(route('liabilities.index'), {}, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    router.get(route('liabilities.index'), {
        name: filterSearch.value,
        page: pageNumber
    }, {
        preserveState: true
    });
};

// Form handling (Create / Edit Modal)
const isModalOpen = ref(false);
const editingLiability = ref(null);

const form = useForm({
    name: '',
    mobile_no: '',
});

const openCreateModal = () => {
    editingLiability.value = null;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openEditModal = (liability) => {
    editingLiability.value = liability;
    form.name = liability.name;
    form.mobile_no = liability.mobile_no || '';
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const submitForm = () => {
    if (editingLiability.value) {
        form.put(route('liabilities.update', editingLiability.value.id), {
            onSuccess: () => {
                closeModal();
            }
        });
    } else {
        form.post(route('liabilities.store'), {
            onSuccess: () => {
                closeModal();
            }
        });
    }
};



// Actions
const deleteLiability = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('liabilities.destroy', id), {
            preserveScroll: true
        });
    }
};

const restoreLiability = (id) => {
    router.post(route('liabilities.restore', id), {}, {
        preserveScroll: true
    });
};

const forceDeleteLiability = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('liabilities.forceDelete', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head :title="t('liabilities books')" />

    <AuthenticatedLayout>
        <!-- Main Card Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <div class="flex items-baseline gap-2.5">
                    <h3 class="text-[20px] font-bold text-slate-800 dark:text-white">
                        {{ t('liabilities books') }}
                    </h3>
                    <button
                        @click="openCreateModal"
                        class="text-blue-600 dark:text-blue-400 hover:underline font-bold text-sm bg-transparent border-0 cursor-pointer"
                    >
                        {{ t('new liabilities book') }}
                    </button>
                </div>

                <!-- Simple Inline Filter Search -->
                <div class="flex items-center gap-2">
                    <input
                        v-model="filterSearch"
                        type="text"
                        :placeholder="t('Search name...')"
                        class="h-8 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500 w-48"
                    />
                    <button
                        v-if="filterSearch"
                        @click="clearFilter"
                        class="bg-red-600 hover:bg-red-700 text-white rounded px-2.5 py-1 text-xs transition font-semibold"
                    >
                        {{ t('clear') }}
                    </button>
                </div>
            </div>

            <!-- Liabilities Table -->
            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-16">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5">{{ t('liabilities books') }}</th>
                            <th class="px-4 py-2.5 text-center w-28">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(liabilityItem, index) in liabilities.data"
                            :key="liabilityItem.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5 text-center">
                                {{ (liabilities.current_page - 1) * liabilities.per_page + index + 1 }}
                            </td>
                            <td class="px-4 py-2.5 font-bold">
                                <Link :href="route('liabilities.show', liabilityItem.id)" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ liabilityItem.name }}
                                </Link>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="openEditModal(liabilityItem)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Edit"
                                    >
                                        <i class="fa fa-edit text-xs"></i>
                                    </button>
                                    <button
                                        @click="deleteLiability(liabilityItem.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Delete"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="liabilities.data.length === 0">
                            <td colspan="3" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No records found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="liabilities" @page-changed="goToPage" />
            </div>
        </div>

        <!-- Trash Bin -->
        <div v-if="trashLiabilities.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-850 dark:text-white">
                    {{ t('delete liabilities books') }}
                </h4>
            </div>

            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse text-center">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 w-16">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5 text-left">{{ t('name') }}</th>
                            <th class="px-4 py-2.5 text-left">{{ t('deleted_by') }}</th>
                            <th class="px-4 py-2.5 w-56">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(liabilityItem, index) in trashLiabilities"
                            :key="liabilityItem.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200 text-center"
                        >
                            <td class="px-4 py-2.5">{{ index + 1 }}</td>
                            <td class="px-4 py-2.5 text-left font-medium">{{ liabilityItem.name }}</td>
                            <td class="px-4 py-2.5 text-left">{{ liabilityItem.deleted_by?.name ?? '—' }}</td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="restoreLiability(liabilityItem.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm"
                                    >
                                        <i class="fa fa-undo text-xs"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteLiability(liabilityItem.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                        <span>{{ t('force_delete') }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Modal (Create / Edit Book) -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-8 overflow-x-hidden overflow-y-auto outline-none bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal"></div>
            <div class="relative w-full max-w-xl mx-auto z-50">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ editingLiability ? t('edit liabilities book') : t('new liabilities book') }}
                        </h3>
                        <button
                            @click="closeModal"
                            class="p-1 ml-auto bg-transparent border-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                        >
                            ×
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form @submit.prevent="submitForm">
                        <div class="relative p-6 flex-auto">
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('name') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('name')"
                                />
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.name }}
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('mobile_no') }}
                                </label>
                                <input
                                    v-model="form.mobile_no"
                                    type="text"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('mobile_no')"
                                />
                                <div v-if="form.errors.mobile_no" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.mobile_no }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-slate-200 dark:border-slate-700 rounded-b gap-2">
                            <button
                                type="button"
                                @click="closeModal"
                                class="bg-slate-500 hover:bg-slate-600 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm"
                            >
                                {{ t('clear') }}
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm disabled:opacity-50"
                            >
                                {{ t('save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
