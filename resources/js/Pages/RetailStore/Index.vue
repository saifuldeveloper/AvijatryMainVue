<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    retailStores: {
        type: Object,
        required: true,
    },
    onetimeStores: {
        type: Object,
        required: true,
    },
    trashStores: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ name: '', address: '', mobile_no: '', commission: '' }),
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
            'due_books': 'Retailer Books',
            'due_book': 'Retailer Books',
            'new_party': 'New Retailer',
            'retail_party': 'Retail Retailer',
            'delete_due_book': 'Deleted Retailer Books',
            'name': 'Name',
            'shop_name': 'Shop Name',
            'address': 'Address',
            'mobile_no': 'Mobile No',
            'commission': 'Commission',
            'company_share': 'Company Share',
            'secret_key': 'Secret Key',
            'onetime_buyer': 'One-time Buyer',
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
            'date': 'Deleted Date',
            'Are you sure?': 'Are you sure?',
            'No records found.': 'No records found.',
            'processing': 'Processing...',
        },
        bn: {
            'due_books': 'বাকি খাতা',
            'due_book': 'বাকি খাতা',
            'new_party': 'নতুন পার্টি',
            'retail_party': 'খুচরা পার্টি',
            'delete_due_book': 'মুছে ফেলা বাকি খাতাসমূহ',
            'name': 'নাম',
            'shop_name': 'দোকানের নাম',
            'address': 'ঠিকানা',
            'mobile_no': 'মোবাইল নং',
            'commission': 'কমিশন %',
            'company_share': 'কোম্পানির শেয়ার',
            'secret_key': 'সিক্রেট কী',
            'onetime_buyer': 'খুচরা পার্টি?',
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
            'date': 'মুছে ফেলার তারিখ',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const filterName = ref(props.filters?.name || '');
const filterAddress = ref(props.filters?.address || '');
const filterMobileNo = ref(props.filters?.mobile_no || '');
const filterCommission = ref(props.filters?.commission || '');
const perPage = ref(parseInt(props.filters?.per_page) || 50);
const onetimePerPage = ref(parseInt(props.filters?.onetime_per_page) || 25);
const sortBy = ref(props.filters?.sort_by || 'id');
const sortDirection = ref(props.filters?.sort_direction || 'desc');
const isFilterCollapsed = ref(true);

const toggleFilter = () => {
    isFilterCollapsed.value = !isFilterCollapsed.value;
};

// Server-side filtering with debounce
let filterTimeout;
const runFilters = (debounceTime = 400) => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('retail-store.index'), {
            name: filterName.value,
            address: filterAddress.value,
            mobile_no: filterMobileNo.value,
            commission: filterCommission.value,
            per_page: perPage.value,
            onetime_per_page: onetimePerPage.value,
            sort_by: sortBy.value,
            sort_direction: sortDirection.value,
            page: 1,
            onetime_page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch([filterName, filterAddress, filterMobileNo, filterCommission], () => runFilters(400));
watch([perPage, onetimePerPage], () => runFilters(0));

const clearFilter = () => {
    filterName.value = '';
    filterAddress.value = '';
    filterMobileNo.value = '';
    filterCommission.value = '';
    sortBy.value = 'id';
    sortDirection.value = 'desc';
    router.get(route('retail-store.index'), {
        per_page: perPage.value,
        onetime_per_page: onetimePerPage.value,
        sort_by: 'id',
        sort_direction: 'desc'
    }, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    router.get(route('retail-store.index'), {
        name: filterName.value,
        address: filterAddress.value,
        mobile_no: filterMobileNo.value,
        commission: filterCommission.value,
        per_page: perPage.value,
        onetime_per_page: onetimePerPage.value,
        sort_by: sortBy.value,
        sort_direction: sortDirection.value,
        page: pageNumber,
        onetime_page: props.onetimeStores.current_page
    }, {
        preserveState: true
    });
};

const goToOnetimePage = (pageNumber) => {
    router.get(route('retail-store.index'), {
        name: filterName.value,
        address: filterAddress.value,
        mobile_no: filterMobileNo.value,
        commission: filterCommission.value,
        per_page: perPage.value,
        onetime_per_page: onetimePerPage.value,
        sort_by: sortBy.value,
        sort_direction: sortDirection.value,
        page: props.retailStores.current_page,
        onetime_page: pageNumber
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

// Modal creation & editing
const isModalOpen = ref(false);
const editingStore = ref(null);

const form = useForm({
    shop_name: '',
    address: '',
    mobile_no: '',
    commission: 0,
    onetime_buyer: false,
    company_share: false,
    secret_key: '',
});

const openCreateModal = () => {
    editingStore.value = null;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openEditModal = (storeRecord) => {
    editingStore.value = storeRecord;
    form.shop_name = storeRecord.shop_name;
    form.address = storeRecord.address || '';
    form.mobile_no = storeRecord.mobile_no || '';
    form.commission = storeRecord.commission || 0;
    form.onetime_buyer = !!storeRecord.onetime_buyer;
    form.company_share = !!storeRecord.company_share;
    form.secret_key = storeRecord.secret_key || '';
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const submitForm = () => {
    if (editingStore.value) {
        form.put(route('retail-store.update', editingStore.value.id), {
            onSuccess: () => {
                closeModal();
            }
        });
    } else {
        form.post(route('retail-store.store'), {
            onSuccess: () => {
                closeModal();
            }
        });
    }
};

const deleteStore = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('retail-store.destroy', id), {
            preserveScroll: true
        });
    }
};

const restoreStore = (id) => {
    router.post(route('retail-store.restore', id), {}, {
        preserveScroll: true
    });
};

const forceDeleteStore = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('retail-store.forceDelete', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head :title="t('due_books')" />

    <AuthenticatedLayout>
        <!-- Main Regular Store Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">
                    {{ t('due_books') }}
                </h3>
                <div class="flex items-center gap-2">
                    <button
                        @click="openCreateModal"
                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer"
                    >
                        {{ t('new_party') }}
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
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label for="filter-name" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1.5">
                            {{ t('name') }}
                        </label>
                        <input
                            id="filter-name"
                            v-model="filterName"
                            type="text"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label for="filter-address" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1.5">
                            {{ t('address') }}
                        </label>
                        <input
                            id="filter-address"
                            v-model="filterAddress"
                            type="text"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label for="filter-mobile" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1.5">
                            {{ t('mobile_no') }}
                        </label>
                        <input
                            id="filter-mobile"
                            v-model="filterMobileNo"
                            type="text"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label for="filter-commission" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1.5">
                            {{ t('commission') }}
                        </label>
                        <input
                            id="filter-commission"
                            v-model="filterCommission"
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
                <table class="w-full text-left text-[15px] border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-24 cursor-pointer select-none" @click="toggleSort('id')">
                                <div class="inline-flex items-center justify-center gap-1">
                                    <span>{{ t('sl') }}</span>
                                    <span class="inline-flex flex-col justify-center items-center text-[8px] leading-[6px]">
                                        <span :class="sortBy === 'id' && sortDirection === 'asc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▲</span>
                                        <span :class="sortBy === 'id' && sortDirection === 'desc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▼</span>
                                    </span>
                                </div>
                            </th>
                            <th class="px-4 py-2.5">{{ t('name') }}</th>
                            <th class="px-4 py-2.5">{{ t('address') }}</th>
                            <th class="px-4 py-2.5">{{ t('mobile_no') }}</th>
                            <th class="px-4 py-2.5 text-center cursor-pointer select-none" @click="toggleSort('commission')">
                                <div class="inline-flex items-center justify-center gap-1">
                                    <span>{{ t('commission') }}</span>
                                    <span class="inline-flex flex-col justify-center items-center text-[8px] leading-[6px]">
                                        <span :class="sortBy === 'commission' && sortDirection === 'asc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▲</span>
                                        <span :class="sortBy === 'commission' && sortDirection === 'desc' ? 'text-slate-800 dark:text-slate-100' : 'text-slate-300 dark:text-slate-600'">▼</span>
                                    </span>
                                </div>
                            </th>
                            <th class="px-4 py-2.5 text-center w-28">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(storeItem, index) in retailStores.data"
                            :key="storeItem.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5 text-center font-medium">
                                {{ (retailStores.current_page - 1) * retailStores.per_page + index + 1 }}
                            </td>
                            <td class="px-4 py-2.5 font-bold">
                                <Link :href="route('retail-store.show', storeItem.id)" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ storeItem.name }}
                                </Link>
                            </td>
                            <td class="px-4 py-2.5 font-medium">{{ storeItem.address }}</td>
                            <td class="px-4 py-2.5 font-medium">{{ storeItem.mobile_no }}</td>
                            <td class="px-4 py-2.5 text-center font-medium">{{ storeItem.commission }}%</td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="openEditModal(storeItem)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                                        title="Edit"
                                    >
                                        <i class="fa fa-edit text-xs"></i>
                                    </button>
                                    <button
                                        @click="deleteStore(storeItem.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                                        title="Delete"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="retailStores.data.length === 0">
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No records found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="retailStores" @page-changed="goToPage" />
            </div>
        </div>

        <!-- One-Time Store Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                    {{ t('retail_party') }}
                </h3>
            </div>

            <!-- Length Menu / Shows Entry control for One-Time -->
            <div class="px-6 py-4 flex items-center gap-1.5 text-[15px] text-slate-700 dark:text-slate-300">
                <span>{{ page.props.locale === 'bn' ? 'দেখাচ্ছে' : 'Show' }}</span>
                <select
                    v-model="onetimePerPage"
                    class="h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 py-0.5 px-2"
                >
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                    <option :value="100">100</option>
                </select>
                <span>{{ page.props.locale === 'bn' ? 'এন্ট্রি' : 'entries' }}</span>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-24">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5">{{ t('name') }}</th>
                            <th class="px-4 py-2.5">{{ t('address') }}</th>
                            <th class="px-4 py-2.5">{{ t('mobile_no') }}</th>
                            <th class="px-4 py-2.5 text-center w-28">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(storeItem, index) in onetimeStores.data"
                            :key="storeItem.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5 text-center font-medium">
                                {{ (onetimeStores.current_page - 1) * onetimeStores.per_page + index + 1 }}
                            </td>
                            <td class="px-4 py-2.5 font-bold">
                                <Link :href="route('retail-store.show', storeItem.id)" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ storeItem.name }}
                                </Link>
                            </td>
                            <td class="px-4 py-2.5 font-medium">{{ storeItem.address }}</td>
                            <td class="px-4 py-2.5 font-medium">{{ storeItem.mobile_no }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="openEditModal(storeItem)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                                        title="Edit"
                                    >
                                        <i class="fa fa-edit text-xs"></i>
                                    </button>
                                    <button
                                        @click="deleteStore(storeItem.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                                        title="Delete"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="onetimeStores.data.length === 0">
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No records found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="onetimeStores" @page-changed="goToOnetimePage" />
            </div>
        </div>

        <!-- Trash Bin -->
        <div v-if="trashStores.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-800 dark:text-white">
                    {{ t('delete_due_book') }}
                </h4>
            </div>

            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse text-center">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 w-16">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5 text-left">{{ t('name') }}</th>
                            <th class="px-4 py-2.5 text-left">{{ t('mobile_no') }}</th>
                            <th class="px-4 py-2.5 text-left">{{ t('date') }}</th>
                            <th class="px-4 py-2.5 text-left">{{ t('deleted_by') }}</th>
                            <th class="px-4 py-2.5 w-56">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(storeItem, index) in trashStores"
                            :key="storeItem.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200 text-center"
                        >
                            <td class="px-4 py-2.5 font-medium">{{ index + 1 }}</td>
                            <td class="px-4 py-2.5 text-left font-medium">{{ storeItem.name }}</td>
                            <td class="px-4 py-2.5 text-left font-medium">{{ storeItem.mobile_no }}</td>
                            <td class="px-4 py-2.5 text-left font-medium">
                                {{ storeItem.deleted_at ? new Date(storeItem.deleted_at).toLocaleString() : '—' }}
                            </td>
                            <td class="px-4 py-2.5 text-left font-medium">{{ storeItem.deleted_by?.name ?? '—' }}</td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="restoreStore(storeItem.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm cursor-pointer"
                                    >
                                        <i class="fa fa-undo text-xs"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteStore(storeItem.id)"
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

        <!-- Form Modal (Create / Edit store profile) -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm overflow-x-hidden overflow-y-auto outline-none">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal"></div>
            <div class="relative w-full max-w-2xl mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ editingStore ? t('pages.edit') : t('new_party') }}
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
                            <!-- Shop Name -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('shop_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.shop_name"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-855 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('shop_name')"
                                />
                                <div v-if="form.errors.shop_name" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.shop_name }}
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('address') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.address"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-855 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('address')"
                                />
                                <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.address }}
                                </div>
                            </div>

                            <!-- Mobile No -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('mobile_no') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.mobile_no"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-855 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('mobile_no')"
                                />
                                <div v-if="form.errors.mobile_no" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.mobile_no }}
                                </div>
                            </div>

                            <!-- Commission -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('commission') }} (%)
                                </label>
                                <input
                                    v-model="form.commission"
                                    type="number"
                                    step="0.01"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-855 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('commission')"
                                />
                                <div v-if="form.errors.commission" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.commission }}
                                </div>
                            </div>

                            <!-- Secret Key -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('secret_key') }}
                                </label>
                                <input
                                    v-model="form.secret_key"
                                    type="text"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-855 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('secret_key')"
                                />
                                <div v-if="form.errors.secret_key" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.secret_key }}
                                </div>
                            </div>

                            <!-- Checkboxes (One-Time and Company Share) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                                <div class="flex items-center gap-2">
                                    <input
                                        id="onetime_buyer"
                                        v-model="form.onetime_buyer"
                                        type="checkbox"
                                        class="rounded border-slate-350 dark:border-slate-650 text-blue-600 focus:ring-blue-500"
                                    />
                                    <label for="onetime_buyer" class="text-sm font-bold text-slate-700 dark:text-slate-300 cursor-pointer">
                                        {{ t('onetime_buyer') }}
                                    </label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input
                                        id="company_share"
                                        v-model="form.company_share"
                                        type="checkbox"
                                        class="rounded border-slate-350 dark:border-slate-650 text-blue-600 focus:ring-blue-500"
                                    />
                                    <label for="company_share" class="text-sm font-bold text-slate-700 dark:text-slate-300 cursor-pointer">
                                        {{ t('company_share') }}
                                    </label>
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
                                {{ t('clear') }}
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
