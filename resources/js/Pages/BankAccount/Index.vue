<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    bankAccounts: {
        type: Object,
        required: true,
    },
    trashBankAccounts: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ bank: '', branch: '', account_no: '', sort_by: 'id', sort_direction: 'desc', per_page: 50 }),
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
            'bank_book': 'Bank Accounts',
            'new bank account': 'New Bank Account',
            'edit bank book': 'Edit Bank Account',
            'delete bank book': 'Deleted Bank Accounts',
            'bank': 'Bank Name',
            'branch': 'Branch',
            'account_no': 'Account No',
            'option': 'Option',
            'sl': 'SL',
            'save': 'Save',
            'clear': 'Clear',
            'filter': 'Filter',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'force_delete': 'Delete Permanently',
            'Are you sure?': 'Are you sure?',
            'No records found.': 'No records found.',
            'processing': 'Processing...',
        },
        bn: {
            'bank_book': 'ব্যাংক খাতা',
            'new bank account': 'নতুন ব্যাংক অ্যাকাউন্ট',
            'edit bank book': 'ব্যাংক খাতা সম্পাদন',
            'delete bank book': 'মুছে ফেলা ব্যাংক অ্যাকাউন্ট',
            'bank': 'ব্যাংকের নাম',
            'branch': 'শাখা',
            'account_no': 'অ্যাকাউন্ট নং',
            'option': 'অপশন',
            'sl': 'ক্রমিক',
            'save': 'সংরক্ষণ',
            'clear': 'পরিষ্কার করুন',
            'filter': 'ফিল্টার',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'force_delete': 'স্থায়ীভাবে মুছুন',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const filterBank = ref(props.filters?.bank || '');
const filterBranch = ref(props.filters?.branch || '');
const filterAccountNo = ref(props.filters?.account_no || '');
const perPage = ref(parseInt(props.filters?.per_page) || 50);
const sortBy = ref(props.filters?.sort_by || 'id');
const sortDirection = ref(props.filters?.sort_direction || 'desc');
const isFilterCollapsed = ref(true);

const toggleFilter = () => {
    isFilterCollapsed.value = !isFilterCollapsed.value;
};

// Server-side filtering
let filterTimeout;
const runFilters = (debounceTime = 400) => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('bank-account.index'), {
            bank: filterBank.value,
            branch: filterBranch.value,
            account_no: filterAccountNo.value,
            per_page: perPage.value,
            sort_by: sortBy.value,
            sort_direction: sortDirection.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch([filterBank, filterBranch, filterAccountNo], () => runFilters(400));
watch(perPage, () => runFilters(0));

const clearFilter = () => {
    filterBank.value = '';
    filterBranch.value = '';
    filterAccountNo.value = '';
    sortBy.value = 'id';
    sortDirection.value = 'desc';
    router.get(route('bank-account.index'), {
        per_page: perPage.value,
        sort_by: 'id',
        sort_direction: 'desc'
    }, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    router.get(route('bank-account.index'), {
        bank: filterBank.value,
        branch: filterBranch.value,
        account_no: filterAccountNo.value,
        per_page: perPage.value,
        sort_by: sortBy.value,
        sort_direction: sortDirection.value,
        page: pageNumber
    }, {
        preserveState: true
    });
};

const toggleSort = (column) => {
    if (sortBy.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = column;
        sortDirection.value = 'desc';
    }
    runFilters(0);
};

// Modal form handling
const isModalOpen = ref(false);
const editingAccount = ref(null);

const form = useForm({
    bank: '',
    branch: '',
    account_no: '',
});

const openCreateModal = () => {
    editingAccount.value = null;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openEditModal = (account) => {
    editingAccount.value = account;
    form.bank = account.bank;
    form.branch = account.branch;
    form.account_no = account.account_no;
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const submitForm = () => {
    if (editingAccount.value) {
        form.put(route('bank-account.update', editingAccount.value.id), {
            onSuccess: () => {
                closeModal();
            }
        });
    } else {
        form.post(route('bank-account.store'), {
            onSuccess: () => {
                closeModal();
            }
        });
    }
};

const deleteAccount = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('bank-account.destroy', id), {
            preserveScroll: true
        });
    }
};

const restoreAccount = (id) => {
    router.post(route('bank-account.restore', id), {}, {
        preserveScroll: true
    });
};

const forceDeleteAccount = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('bank-account.forceDelete', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head :title="t('bank_book')" />

    <AuthenticatedLayout>
        <!-- Main Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">
                    {{ t('bank_book') }}
                </h3>
                <div class="flex items-center gap-2">
                    <button
                        @click="openCreateModal"
                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer"
                    >
                        {{ t('new bank account') }}
                    </button>
                    <button
                        @click="toggleFilter"
                        class="bg-[#007bff] hover:bg-[#0069d9] text-white px-3 py-1.5 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer"
                    >
                        {{ t('filter') }}
                    </button>
                </div>
            </div>

            <!-- Collapsible Filter Panel -->
            <div v-show="!isFilterCollapsed" class="p-6 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            {{ t('bank') }}
                        </label>
                        <input
                            v-model="filterBank"
                            type="text"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            {{ t('branch') }}
                        </label>
                        <input
                            v-model="filterBranch"
                            type="text"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            {{ t('account_no') }}
                        </label>
                        <input
                            v-model="filterAccountNo"
                            type="text"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <button
                            @click="clearFilter"
                            class="w-full h-10 bg-[#dc3545] hover:bg-[#c82333] text-white rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer"
                        >
                            {{ t('clear') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Length Menu / Shows Entry control -->
            <div class="px-6 py-4 flex items-center gap-1.5 text-[15px] text-slate-700 dark:text-slate-300">
                <span>{{ page.props.locale === 'bn' ? 'দেখাচ্ছে' : 'Show' }}</span>
                <select
                    v-model="perPage"
                    class="h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 py-0.5 px-2"
                >
                    <option :value="50">50</option>
                    <option :value="100">100</option>
                    <option :value="200">200</option>
                    <option :value="500">500</option>
                </select>
                <span>{{ page.props.locale === 'bn' ? 'এন্ট্রি' : 'entries' }}</span>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-center text-[14px] border-collapse border border-slate-300 dark:border-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center border border-slate-300 dark:border-slate-700 w-24 cursor-pointer select-none" @click="toggleSort('id')">
                                <div class="inline-flex items-center justify-center gap-1">
                                    <span>{{ t('sl') }}</span>
                                    <span class="inline-flex flex-col justify-center items-center text-[8px] leading-[6px]">
                                        <span :class="sortBy === 'id' && sortDirection === 'asc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▲</span>
                                        <span :class="sortBy === 'id' && sortDirection === 'desc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▼</span>
                                    </span>
                                </div>
                            </th>
                            <th class="px-4 py-2.5 text-center border border-slate-300 dark:border-slate-700 cursor-pointer select-none" @click="toggleSort('bank')">
                                <div class="inline-flex items-center justify-center gap-1">
                                    <span>{{ t('bank') }}</span>
                                    <span class="inline-flex flex-col justify-center items-center text-[8px] leading-[6px]">
                                        <span :class="sortBy === 'bank' && sortDirection === 'asc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▲</span>
                                        <span :class="sortBy === 'bank' && sortDirection === 'desc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▼</span>
                                    </span>
                                </div>
                            </th>
                            <th class="px-4 py-2.5 text-center border border-slate-300 dark:border-slate-700 cursor-pointer select-none" @click="toggleSort('branch')">
                                <div class="inline-flex items-center justify-center gap-1">
                                    <span>{{ t('branch') }}</span>
                                    <span class="inline-flex flex-col justify-center items-center text-[8px] leading-[6px]">
                                        <span :class="sortBy === 'branch' && sortDirection === 'asc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▲</span>
                                        <span :class="sortBy === 'branch' && sortDirection === 'desc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▼</span>
                                    </span>
                                </div>
                            </th>
                            <th class="px-4 py-2.5 text-center border border-slate-300 dark:border-slate-700 cursor-pointer select-none" @click="toggleSort('account_no')">
                                <div class="inline-flex items-center justify-center gap-1">
                                    <span>{{ t('account_no') }}</span>
                                    <span class="inline-flex flex-col justify-center items-center text-[8px] leading-[6px]">
                                        <span :class="sortBy === 'account_no' && sortDirection === 'asc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▲</span>
                                        <span :class="sortBy === 'account_no' && sortDirection === 'desc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▼</span>
                                    </span>
                                </div>
                            </th>
                            <th class="px-4 py-2.5 text-center border border-slate-300 dark:border-slate-700 w-28">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(account, index) in bankAccounts.data"
                            :key="account.id"
                            class="border-b border-slate-300 dark:border-slate-700 odd:bg-white even:bg-slate-50/50 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-medium">
                                {{ (bankAccounts.current_page - 1) * bankAccounts.per_page + index + 1 }}
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-bold">
                                <Link
                                    :href="route('bank-account.show', account.id)"
                                    class="text-blue-600 dark:text-blue-400 hover:underline"
                                >
                                    {{ account.bank }}
                                </Link>
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-medium">{{ account.branch }}</td>
                            <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-medium text-slate-800 dark:text-slate-200">
                                {{ account.account_no }}
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="openEditModal(account)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                                        title="Edit"
                                    >
                                        <i class="fa fa-edit text-xs"></i>
                                    </button>
                                    <button
                                        v-if="account.account_no !== 'cash'"
                                        @click="deleteAccount(account.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                                        title="Delete"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="bankAccounts.data.length === 0">
                            <td colspan="5" class="px-6 py-10 text-center border border-slate-300 dark:border-slate-700 text-slate-500 dark:text-slate-400">
                                {{ t('No records found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="bankAccounts" @page-changed="goToPage" />
            </div>
        </div>

        <!-- Trashed accounts panel if any exist -->
        <div v-if="trashBankAccounts.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-800 dark:text-white">
                    {{ t('delete bank book') }}
                </h4>
            </div>
            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-center text-[14px] border-collapse border border-slate-300 dark:border-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center border border-slate-300 dark:border-slate-700 w-16">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5 text-center border border-slate-300 dark:border-slate-700">{{ t('bank') }}</th>
                            <th class="px-4 py-2.5 text-center border border-slate-300 dark:border-slate-700">{{ t('account_no') }}</th>
                            <th class="px-4 py-2.5 text-center border border-slate-300 dark:border-slate-700 w-56">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(account, index) in trashBankAccounts"
                            :key="account.id"
                            class="border-b border-slate-300 dark:border-slate-700 odd:bg-white even:bg-slate-50/50 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200 text-center"
                        >
                            <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-medium">{{ index + 1 }}</td>
                            <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-medium">{{ account.bank }} ({{ account.branch }})</td>
                            <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-medium">{{ account.account_no }}</td>
                            <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        @click="restoreAccount(account.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm cursor-pointer"
                                    >
                                        <i class="fa fa-undo text-xs"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteAccount(account.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm cursor-pointer"
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

        <!-- Form Modal (Create / Edit Account) -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm overflow-x-hidden overflow-y-auto outline-none">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal"></div>
            <div class="relative w-full max-w-lg mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                            {{ editingAccount ? t('edit bank book') : t('new bank account') }}
                        </h3>
                        <button
                            @click="closeModal"
                            class="p-1 ml-auto bg-transparent border-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cursor-pointer"
                        >
                            ×
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form @submit.prevent="submitForm">
                        <div class="relative p-6 flex-auto">
                            <!-- Bank -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('bank') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.bank"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="form.errors.bank" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.bank }}
                                </div>
                            </div>

                            <!-- Branch -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('branch') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.branch"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="form.errors.branch" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.branch }}
                                </div>
                            </div>

                            <!-- Account No -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('account_no') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.account_no"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="form.errors.account_no" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.account_no }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-slate-200 dark:border-slate-700 rounded-b gap-2">
                            <button
                                type="button"
                                @click="closeModal"
                                class="bg-slate-500 hover:bg-slate-600 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm disabled:opacity-50 cursor-pointer"
                            >
                                {{ form.processing ? t('processing') : t('save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
