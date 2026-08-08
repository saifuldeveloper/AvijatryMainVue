<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    giftWastes: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    accountTypes: {
        type: Array,
        required: true,
    },
    gifts: {
        type: Array,
        required: true,
    },
    trashedWasteEntries: {
        type: Array,
        default: () => [],
    },
    dates: {
        type: Array,
        default: () => ['', ''],
    },
    factories: {
        type: Array,
        default: () => [],
    },
    retailStores: {
        type: Array,
        default: () => [],
    },
    giftSuppliers: {
        type: Array,
        default: () => [],
    },
    employees: {
        type: Array,
        default: () => [],
    },
    loans: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();

// Translation Helper
const t = (key) => {
    let cleanKey = key;
    if (key.startsWith('pages.')) {
        cleanKey = key.replace('pages.', '');
    } else if (key.startsWith('menu.')) {
        cleanKey = key.replace('menu.', '');
    }
    if (page.props.translations?.pages?.[cleanKey]) {
        return page.props.translations.pages[cleanKey];
    }
    if (page.props.translations?.menu?.[cleanKey]) {
        return page.props.translations.menu[cleanKey];
    }
    const fallbacks = {
        en: {
            'gift_waste': 'Gift Waste',
            'filter': 'Filter',
            'type': 'Type',
            'book': 'Book',
            'gift': 'Gift Item',
            'details': 'Details',
            'count': 'Count',
            'date': 'Date',
            'clear': 'Clear',
            'submit': 'Submit',
            'option': 'Option',
            'deleted gift waste': 'Deleted Gift Waste',
            'supplier': 'Supplier',
            'party': 'Party',
            'gift_supplier': 'Gift Supplier',
            'staff': 'Staff',
            'loan': 'Loan',
            'others': 'Others',
            'restore': 'Restore',
            'delete': 'Delete',
            'name': 'Name',
            'Are you sure?': 'Are you sure?',
            'select': 'Select',
            'No records found.': 'No records found.',
            'Search': 'Search',
        },
        bn: {
            'gift_waste': 'গিফট জোলাপ',
            'filter': 'ফিল্টার',
            'type': 'ধরন',
            'book': 'খাতা',
            'gift': 'গিফট সামগ্রী',
            'details': 'বিবরণ',
            'count': 'পরিমাণ',
            'date': 'তারিখ',
            'clear': 'পরিষ্কার করুন',
            'submit': 'জমা দিন',
            'option': 'অপশন',
            'deleted gift waste': 'মুছে ফেলা গিফট জোলাপ',
            'supplier': 'মহাজন',
            'party': 'পার্টি',
            'gift_supplier': 'গিফট মহাজন',
            'staff': 'স্টাফ',
            'loan': 'হাওলাত',
            'others': 'অন্যান্য',
            'restore': 'পুনরুদ্ধার',
            'delete': 'মুছুন',
            'name': 'নাম',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'select': 'সিলেক্ট',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'Search': 'খুঁজুন',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

const showFilter = ref(false);

// Filter Form State
const filterForm = ref({
    daterange: props.filters?.daterange || (props.dates[0] && props.dates[1] ? `${props.dates[0]} - ${props.dates[1]}` : ''),
    account_type: props.filters?.account_type || '',
    name: props.filters?.name || '',
    gift_id: props.filters?.gift_id || '',
    description: props.filters?.description || '',
    count: props.filters?.count || '',
});

// Create Form State
const form = useForm({
    gift_id: '',
    account_type: '',
    account_id: '',
    count: '',
    description: '',
});

// Create accounts dropdown list based on selected account type
const createAccounts = computed(() => {
    switch (form.account_type) {
        case 'factory': return props.factories;
        case 'retail-store': return props.retailStores;
        case 'gift-supplier': return props.giftSuppliers;
        case 'employee': return props.employees;
        case 'loan-payment': return props.loans;
        default: return [];
    }
});

// Watch Create Form account type to reset account ID
watch(() => form.account_type, () => {
    form.account_id = '';
});

// List Data State
const listData = computed(() => props.giftWastes);
const listLoading = ref(false);
const perPage = ref(parseInt(props.filters?.length) || 10);

const fetchListings = (pageNumber = 1) => {
    listLoading.value = true;
    router.get(route('waste.gifts-page'), {
        page: pageNumber,
        length: perPage.value,
        daterange: filterForm.value.daterange,
        account_type: filterForm.value.account_type,
        name: filterForm.value.name,
        gift_id: filterForm.value.gift_id,
        description: filterForm.value.description,
        count: filterForm.value.count,
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            listLoading.value = false;
        }
    });
};

const submitCreateForm = () => {
    form.post(route('waste.gifts'), {
        onSuccess: () => {
            form.reset();
            fetchListings(1);
        }
    });
};

const softDeleteRecord = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.post(route('waste.gift.softDelete', id), {}, {
            onSuccess: () => fetchListings(listData.value.current_page)
        });
    }
};

const restoreRecord = (id) => {
    router.post(route('waste.gift.restore', id), {}, {
        onSuccess: () => fetchListings(listData.value.current_page)
    });
};

const forceDeleteRecord = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.post(route('waste.gift.forceDelete', id), {}, {
            onSuccess: () => fetchListings(listData.value.current_page)
        });
    }
};

const clearFilters = () => {
    filterForm.value = {
        daterange: props.dates[0] && props.dates[1] ? `${props.dates[0]} - ${props.dates[1]}` : '',
        account_type: '',
        name: '',
        gift_id: '',
        description: '',
        count: '',
    };
    fetchListings(1);
};

const getTypeName = (type) => {
    switch (type) {
        case 'factory': return t('pages.supplier');
        case 'retail-store': return t('pages.party');
        case 'gift-supplier': return t('pages.gift_supplier');
        case 'employee': return t('pages.staff');
        case 'loan-payment': return t('pages.loan');
        case 'other': return t('pages.others');
        default: return type;
    }
};
</script>

<template>
    <Head :title="t('gift_waste')" />

    <AuthenticatedLayout>
        <!-- Main Card Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            
            <!-- Header Block -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">
                    {{ t('gift_waste') }}
                </h3>
                <button
                    @click="showFilter = !showFilter"
                    class="bg-[#007bff] hover:bg-[#0069d9] text-white px-3 py-1.5 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer"
                >
                    {{ t('filter') }}
                </button>
            </div>

            <!-- Collapsible Filter Panel -->
            <div v-show="showFilter" class="p-6 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('date') }} (YYYY-MM-DD - YYYY-MM-DD)</label>
                        <input v-model="filterForm.daterange" type="text" placeholder="YYYY-MM-DD - YYYY-MM-DD" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('type') }}</label>
                        <select v-model="filterForm.account_type" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option value="">{{ t('select') }}</option>
                            <option v-for="type in accountTypes" :key="type.id" :value="type.id">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('name') }}</label>
                        <input v-model="filterForm.name" type="text" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('gift') }}</label>
                        <select v-model="filterForm.gift_id" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option value="">{{ t('select') }}</option>
                            <option v-for="gift in gifts" :key="gift.id" :value="gift.id">
                                {{ gift.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <div class="w-1/2 md:w-1/3">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('details') }}</label>
                        <input v-model="filterForm.description" type="text" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white" />
                    </div>
                    <div class="w-1/4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('count') }}</label>
                        <input v-model="filterForm.count" type="text" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" />
                    </div>
                    <div class="flex gap-2">
                        <button @click="clearFilters" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-semibold transition text-sm cursor-pointer shadow-sm">
                            {{ t('clear') }}
                        </button>
                        <button @click="fetchListings(1)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold transition text-sm cursor-pointer shadow-sm">
                            {{ t('Search') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Create Form Area (Always Visible) -->
            <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                <form @submit.prevent="submitCreateForm" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 font-bold">{{ t('gift') }} *</label>
                        <select v-model="form.gift_id" required class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option value="">{{ t('select') }}</option>
                            <option v-for="gift in gifts" :key="gift.id" :value="gift.id">
                                {{ gift.name }}
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 font-bold">{{ t('type') }}</label>
                        <select v-model="form.account_type" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option value="">{{ t('select') }}</option>
                            <option v-for="type in accountTypes" :key="type.id" :value="type.id">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 font-bold">{{ t('book') }}</label>
                        <select v-model="form.account_id" :disabled="!form.account_type || form.account_type === 'other'" :required="form.account_type && form.account_type !== 'other'" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white disabled:opacity-50">
                            <option value="">{{ t('select') }}</option>
                            <option v-for="acc in createAccounts" :key="acc.id" :value="acc.id">
                                {{ acc.name || acc.shop_name }}
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 font-bold">{{ t('count') }} (সংখ্যা) *</label>
                        <input v-model="form.count" type="number" required min="1" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center font-semibold" />
                    </div>

                    <div class="md:col-span-6">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 font-bold">{{ t('details') }} (বিবরণ)</label>
                        <input v-model="form.description" type="text" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white" />
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" :disabled="form.processing" class="w-full h-10 bg-[#007bff] hover:bg-[#0069d9] text-white rounded font-bold transition cursor-pointer text-sm shadow-sm disabled:opacity-50">
                            {{ t('submit') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Main Gifts List Table -->
            <div class="p-6">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">
                        {{ t('gift_waste') }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500">দেখাচ্ছে</span>
                        <select v-model="perPage" @change="fetchListings(1)" class="rounded border-slate-300 text-xs py-1 px-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option :value="10">10</option>
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                        </select>
                        <span class="text-xs text-slate-500">এন্ট্রি</span>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-lg">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300">
                                <th class="px-4 py-3 font-bold border-r dark:border-slate-700" style="width: 5%">#</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('date') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('type') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('name') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('gift') }}</th>
                                <th class="px-4 py-3 font-bold border-r dark:border-slate-700">{{ t('details') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('count') }}</th>
                                <th class="px-4 py-3 font-bold text-center">{{ t('option') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-800 dark:text-slate-200">
                            <tr v-if="listLoading" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                                <td colspan="8" class="px-4 py-8 text-center text-slate-500">Loading data...</td>
                            </tr>
                            <tr v-else-if="listData.data.length === 0" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                                <td colspan="8" class="px-4 py-8 text-center text-slate-500">{{ t('No records found.') }}</td>
                            </tr>
                            <tr v-else v-for="(row, idx) in listData.data" :key="row.id" class="odd:bg-white even:bg-slate-50/40 dark:odd:bg-slate-800 dark:even:bg-slate-900/30 hover:bg-slate-50/80 dark:hover:bg-slate-700/40">
                                <td class="px-4 py-3 border-r dark:border-slate-700">{{ (listData.current_page - 1) * perPage + idx + 1 }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.formatted_date }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.attachment_type }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.attachment_name || '-' }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700 font-semibold">{{ row.gift?.name || '-' }}</td>
                                <td class="px-4 py-3 border-r dark:border-slate-700">{{ row.description || '-' }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700 font-semibold">{{ row.count }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button
                                        v-if="page.props.auth.permissions.includes('delete gift waste')"
                                        @click="softDeleteRecord(row.id)"
                                        class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded transition cursor-pointer inline-flex items-center justify-center shadow-sm"
                                        title="Delete"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                    <span v-else>-</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="mt-4 flex justify-center">
                    <Pagination :pagination="listData" @page-changed="fetchListings" />
                </div>
            </div>
        </div>

        <!-- Trashed Gift Waste Card -->
        <div v-if="trashedWasteEntries.length > 0" class="bg-white dark:bg-slate-800 rounded shadow border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-red-50 dark:bg-red-950/20">
                <h3 class="text-lg font-bold text-red-800 dark:text-red-400">
                    {{ t('deleted gift waste') }}
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-55 border-b border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300">
                            <th class="px-6 py-3 font-semibold text-center">{{ t('date') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('type') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('name') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('gift') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('details') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('count') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-800 dark:text-slate-200">
                        <tr v-for="row in trashedWasteEntries" :key="row.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 text-center">{{ row.created_at }}</td>
                            <td class="px-6 py-4 text-center">{{ getTypeName(row.attachment_type) }}</td>
                            <td class="px-6 py-4 text-center">{{ row.attachment_name || '-' }}</td>
                            <td class="px-6 py-4 text-center">{{ row.gift?.name || '-' }}</td>
                            <td class="px-6 py-4">{{ row.description || '-' }}</td>
                            <td class="px-6 py-4 text-center font-semibold">{{ row.count }}</td>
                            <td class="px-6 py-4 text-center flex justify-center gap-3">
                                <button
                                    v-if="page.props.auth.permissions.includes('restore deleted gift waste')"
                                    @click="restoreRecord(row.id)"
                                    class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs transition cursor-pointer"
                                >
                                    {{ t('restore') }}
                                </button>
                                <button
                                    v-if="page.props.auth.permissions.includes('force delete gift waste')"
                                    @click="forceDeleteRecord(row.id)"
                                    class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs transition cursor-pointer"
                                >
                                    {{ t('delete') }}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
