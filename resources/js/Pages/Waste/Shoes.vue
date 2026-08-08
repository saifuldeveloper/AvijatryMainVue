<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, usePage, useForm, router, Link } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    wastes: {
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
    fileraccountTypes: {
        type: Array,
        required: true,
    },
    trashedShoeWasteEntries: {
        type: Array,
        default: () => [],
    },
    factoryCount: {
        type: Number,
        default: 0,
    },
    retailstoreCount: {
        type: Number,
        default: 0,
    },
    otherCount: {
        type: Number,
        default: 0,
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
    categories: {
        type: Array,
        default: () => [],
    },
    colors: {
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
            'shoe_waste': 'Shoe Waste',
            'filter': 'Filter',
            'type': 'Type',
            'book': 'Book',
            'id': 'ID',
            'color': 'Color',
            'price': 'Price',
            'details': 'Details',
            'pair': 'Pair',
            'date': 'Date',
            'clear': 'Clear',
            'submit_now': 'Submit Now',
            'others_waste': 'Others Waste',
            'deleted shoe waste': 'Deleted Shoe Waste',
            'book_type': 'Book Type',
            'name': 'Name',
            'picture': 'Picture',
            'selling_price': 'Selling Price',
            'option': 'Option',
            'deleted_by': 'Deleted By',
            'restore': 'Restore',
            'delete': 'Delete',
            'Are you sure?': 'Are you sure?',
            'select': 'Select',
            'No records found.': 'No records found.',
            'all': 'All',
            'Search': 'Search',
        },
        bn: {
            'shoe_waste': 'জুতা জোলাপ',
            'filter': 'ফিল্টার',
            'type': 'ধরন',
            'book': 'খাতা',
            'id': 'আইডি',
            'color': 'রঙ',
            'price': 'দর',
            'details': 'বিবরণ',
            'pair': 'জোড়া',
            'date': 'তারিখ',
            'clear': 'পরিষ্কার করুন',
            'submit_now': 'জমা দিন',
            'others_waste': 'অন্যানো জোলাপ',
            'deleted shoe waste': 'মুছে ফেলা জুতা জোলাপ',
            'book_type': 'খাতা টাইপ',
            'name': 'নাম',
            'picture': 'ছবি',
            'selling_price': 'গায়ের দাম',
            'option': 'অপশন',
            'deleted_by': 'মুছে ফেলেছেন',
            'restore': 'পুনরুদ্ধার',
            'delete': 'মুছুন',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'select': 'সিলেক্ট',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'all': 'সব',
            'Search': 'খুঁজুন',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// State toggles
const showFilter = ref(false);
const previewShoe = ref(null);
const previewLoading = ref(false);

// Filter Form State
const filterForm = ref({
    entries_type: props.filters?.entries_type || '',
    account_id: props.filters?.account_id || '',
    shoe_id: props.filters?.shoe_id || '',
    category: props.filters?.category || '',
    color: props.filters?.color || '',
    retail_price: props.filters?.retail_price || '',
    description: props.filters?.description || '',
    count: props.filters?.count || '',
    daterange: props.filters?.daterange || (props.dates[0] && props.dates[1] ? `${props.dates[0]} - ${props.dates[1]}` : ''),
});

// Accounts dropdown based on selected type in Filter Form
const filterAccounts = computed(() => {
    switch (filterForm.value.entries_type) {
        case 'factory': return props.factories;
        case 'gift-supplier': return props.giftSuppliers;
        case 'employee': return props.employees;
        case 'loan-payment': return props.loans;
        case 'retail-store': return props.retailStores;
        default: return [];
    }
});

// Create Form State
const form = useForm({
    account_type: '',
    account_id: '',
    shoe_id: '',
    description: '',
    count: '',
});

// Accounts dropdown based on selected type in Create Form
const createAccounts = computed(() => {
    switch (form.account_type) {
        case 'factory': return props.factories;
        case 'gift-supplier': return props.giftSuppliers;
        case 'employee': return props.employees;
        case 'loan-payment': return props.loans;
        case 'retail-store': return props.retailStores;
        default: return [];
    }
});

// Watch Create Form account type change to reset account ID
watch(() => form.account_type, () => {
    form.account_id = '';
});

// Watch Filter Form account type change to reset account ID
watch(() => filterForm.value.entries_type, () => {
    filterForm.value.account_id = '';
});

// Fetch Shoe Preview info when Shoe ID changes in Create Form
watch(() => form.shoe_id, (newVal) => {
    if (!newVal || newVal.length < 3) {
        previewShoe.value = null;
        return;
    }
    previewLoading.value = true;
    axios.get(route('ajax.shoe.show', { shoe: newVal }))
        .then(res => {
            previewShoe.value = res.data;
        })
        .catch(() => {
            previewShoe.value = null;
        })
        .finally(() => {
            previewLoading.value = false;
        });
});

// Others Shoe Waste DataTable State
const listData = computed(() => props.wastes);
const listLoading = ref(false);
const perPage = ref(parseInt(props.filters?.length) || 10);
const sortBy = ref(props.filters?.order_by || 'created_at');
const sortDir = ref(props.filters?.order_dir || 'desc');

// Fetch listings
const fetchListings = (pageNumber = 1) => {
    listLoading.value = true;
    router.get(route('waste.shoes-page'), {
        page: pageNumber,
        length: perPage.value,
        order_by: sortBy.value,
        order_dir: sortDir.value,
        entries_type: filterForm.value.entries_type,
        account_id: filterForm.value.account_id,
        shoe_id: filterForm.value.shoe_id,
        category: filterForm.value.category,
        color: filterForm.value.color,
        retail_price: filterForm.value.retail_price,
        description: filterForm.value.description,
        count: filterForm.value.count,
        daterange: filterForm.value.daterange,
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            listLoading.value = false;
        }
    });
};

const handleSort = (column) => {
    if (sortBy.value === column) {
        sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = column;
        sortDir.value = 'desc';
    }
    fetchListings(1);
};

const submitCreateForm = () => {
    form.post(route('waste.shoes'), {
        onSuccess: () => {
            form.reset();
            previewShoe.value = null;
            fetchListings(1);
        }
    });
};

const softDeleteRecord = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.post(route('waste.shoe.softDelete', id), {}, {
            onSuccess: () => fetchListings(listData.value.current_page)
        });
    }
};

const restoreRecord = (id) => {
    router.get(route('waste.shoe.restore', id), {}, {
        onSuccess: () => fetchListings(listData.value.current_page)
    });
};

const forceDeleteRecord = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.get(route('waste.shoe.forceDelete', id), {}, {
            onSuccess: () => fetchListings(listData.value.current_page)
        });
    }
};

const clearFilters = () => {
    filterForm.value = {
        entries_type: '',
        account_id: '',
        shoe_id: '',
        category: '',
        color: '',
        retail_price: '',
        description: '',
        count: '',
        daterange: props.dates[0] && props.dates[1] ? `${props.dates[0]} - ${props.dates[1]}` : '',
    };
    fetchListings(1);
};
</script>

<template>
    <Head :title="t('shoe_waste')" />

    <AuthenticatedLayout>
        <!-- Main Card Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            
            <!-- Header Block -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">
                    {{ t('shoe_waste') }}
                </h3>
                <button
                    @click="showFilter = !showFilter"
                    class="bg-[#007bff] hover:bg-[#0069d9] text-white px-3 py-1.5 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer"
                >
                    {{ t('filter') }}
                </button>
            </div>

            <!-- Collapsible Filter Card -->
            <div v-show="showFilter" class="p-6 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('type') }}</label>
                        <select v-model="filterForm.entries_type" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option value="">{{ t('select') }}</option>
                            <option v-for="type in fileraccountTypes" :key="type.id" :value="type.id">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('book') }}</label>
                        <select v-model="filterForm.account_id" :disabled="!filterForm.entries_type || filterForm.entries_type === 'other'" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white disabled:opacity-50">
                            <option value="">{{ t('select') }}</option>
                            <option v-for="acc in filterAccounts" :key="acc.id" :value="acc.id">
                                {{ acc.name || acc.shop_name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('id') }}</label>
                        <input v-model="filterForm.shoe_id" type="text" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('type') }} (Category)</label>
                        <select v-model="filterForm.category" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option value="">{{ t('select') }}</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.full_name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('color') }}</label>
                        <input v-model="filterForm.color" type="text" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('price') }}</label>
                        <input v-model="filterForm.retail_price" type="text" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('details') }}</label>
                        <input v-model="filterForm.description" type="text" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('pair') }}</label>
                        <input v-model="filterForm.count" type="text" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" />
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <div class="w-1/2 md:w-1/3">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ t('date') }} (YYYY-MM-DD - YYYY-MM-DD)</label>
                        <input v-model="filterForm.daterange" type="text" placeholder="YYYY-MM-DD - YYYY-MM-DD" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" />
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
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 font-bold">{{ t('type') }} *</label>
                        <select v-model="form.account_type" required class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option value="">{{ t('select') }}</option>
                            <option v-for="type in accountTypes" :key="type.id" :value="type.id">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 font-bold">{{ t('book') }}</label>
                        <select v-model="form.account_id" :disabled="!form.account_type || form.account_type === 'other'" :required="form.account_type !== 'other'" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white disabled:opacity-50">
                            <option value="">{{ t('select') }}</option>
                            <option v-for="acc in createAccounts" :key="acc.id" :value="acc.id">
                                {{ acc.name || acc.shop_name }}
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 font-bold">{{ t('id') }} (Shoe Code) *</label>
                        <input v-model="form.shoe_id" type="text" required class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" />
                    </div>

                    <div class="md:col-span-6">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 font-bold">{{ t('details') }}</label>
                        <input v-model="form.description" type="text" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white" />
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 font-bold">{{ t('pair') }} *</label>
                        <input v-model="form.count" type="number" required min="1" class="w-full h-10 rounded border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center font-semibold" />
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" :disabled="form.processing" class="w-full h-10 bg-[#007bff] hover:bg-[#0069d9] text-white rounded font-bold transition cursor-pointer text-sm shadow-sm disabled:opacity-50">
                            {{ t('submit_now') }}
                        </button>
                    </div>
                </form>

                <!-- Shoe Preview Alert -->
                <div v-if="previewLoading" class="mt-4 text-sm text-blue-600">
                    Loading shoe details...
                </div>
                <div v-else-if="previewShoe" class="mt-4 p-4 rounded bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-sm flex gap-4 items-center">
                    <img :src="previewShoe.image ? '/images/small-thumbnail/' + previewShoe.image : '/img/shoe.png'" class="w-12 h-12 object-cover rounded border" />
                    <div>
                        <div class="font-bold text-slate-800 dark:text-white">{{ previewShoe.category?.full_name || 'Shoe' }} (Code: {{ previewShoe.code }})</div>
                        <div class="text-slate-600 dark:text-slate-400">
                            Color: <span class="font-semibold">{{ previewShoe.color?.name || '-' }}</span> | 
                            Retail Price: <span class="font-semibold">৳{{ previewShoe.retail_price }}</span> | 
                            Current Stock: <span class="font-bold text-blue-600 dark:text-blue-400">{{ previewShoe.inventory?.count ?? 0 }} Pairs</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Others Shoe Waste Table -->
            <div class="p-6">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">
                        {{ t('others_waste') }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500">দেখাচ্ছে</span>
                        <select v-model="perPage" @change="fetchListings(1)" class="rounded border-slate-300 text-xs py-1 px-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option :value="10">10</option>
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                            <option :value="100">100</option>
                        </select>
                        <span class="text-xs text-slate-500">এন্ট্রি</span>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-lg">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300">
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700" style="width: 5%">#</th>
                                <th @click="handleSort('entries_type')" class="px-4 py-3 font-bold text-center border-r dark:border-slate-700 cursor-pointer select-none">
                                    {{ t('book_type') }}
                                    <span v-if="sortBy === 'entries_type'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
                                </th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('name') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('picture') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('id') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('type') }} (Category)</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('color') }}</th>
                                <th class="px-4 py-3 font-bold border-r dark:border-slate-700">{{ t('details') }}</th>
                                <th @click="handleSort('retail_price')" class="px-4 py-3 font-bold text-center border-r dark:border-slate-700 cursor-pointer select-none">
                                    {{ t('selling_price') }}
                                    <span v-if="sortBy === 'retail_price'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
                                </th>
                                <th @click="handleSort('count')" class="px-4 py-3 font-bold text-center border-r dark:border-slate-700 cursor-pointer select-none">
                                    {{ t('pair') }}
                                    <span v-if="sortBy === 'count'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
                                </th>
                                <th @click="handleSort('created_at')" class="px-4 py-3 font-bold text-center border-r dark:border-slate-700 cursor-pointer select-none">
                                    {{ t('date') }}
                                    <span v-if="sortBy === 'created_at'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
                                </th>
                                <th class="px-4 py-3 font-bold text-center">{{ t('option') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-800 dark:text-slate-200">
                            <tr v-if="listLoading" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                                <td colspan="12" class="px-4 py-8 text-center text-slate-500">Loading data...</td>
                            </tr>
                            <tr v-else-if="listData.data.length === 0" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                                <td colspan="12" class="px-4 py-8 text-center text-slate-500">{{ t('No records found.') }}</td>
                            </tr>
                            <tr v-else v-for="(row, idx) in listData.data" :key="row.id" class="odd:bg-white even:bg-slate-50/40 dark:odd:bg-slate-800 dark:even:bg-slate-900/30 hover:bg-slate-50/80 dark:hover:bg-slate-700/40">
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ (listData.current_page - 1) * perPage + idx + 1 }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.book_type }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.name }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">
                                    <img :src="row.shoe?.image ? '/images/small-thumbnail/' + row.shoe.image : '/img/shoe.png'" class="w-10 h-10 object-cover rounded border mx-auto" />
                                </td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">
                                    <Link :href="route('shoe.show', row.shoe?.code || '#')" class="text-blue-600 hover:underline font-semibold">
                                        {{ row.shoe?.code || row.shoe_id }}
                                    </Link>
                                </td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.shoe?.category?.full_name || '-' }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.shoe?.color?.name || '-' }}</td>
                                <td class="px-4 py-3 border-r dark:border-slate-700">{{ row.description || '-' }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">৳{{ row.shoe?.retail_price }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700 font-semibold">{{ row.count }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.formatted_date }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button
                                        v-if="row.inventory_count === 'instant_waste' && page.props.auth.permissions.includes('delete shoe waste')"
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

        <!-- Trashed Shoe Waste Card -->
        <div v-if="trashedShoeWasteEntries.length > 0" class="bg-white dark:bg-slate-800 rounded shadow border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-red-50 dark:bg-red-950/20">
                <h3 class="text-lg font-bold text-red-800 dark:text-red-400">
                    {{ t('deleted shoe waste') }}
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-55 border-b border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300">
                            <th class="px-6 py-3 font-semibold">{{ t('picture') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('id') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('pair') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('details') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('date') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('deleted_by') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-800 dark:text-slate-200">
                        <tr v-for="row in trashedShoeWasteEntries" :key="row.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4">
                                <img :src="row.shoe?.image ? '/images/small-thumbnail/' + row.shoe.image : '/img/shoe.png'" class="w-10 h-10 object-cover rounded border" />
                            </td>
                            <td class="px-6 py-4">{{ row.shoe?.code }}</td>
                            <td class="px-6 py-4">{{ row.count }}</td>
                            <td class="px-6 py-4">{{ row.description || '-' }}</td>
                            <td class="px-6 py-4">{{ t('date') }}: {{ row.deleted_at }}</td>
                            <td class="px-6 py-4">{{ row.deleted_by?.name || '-' }}</td>
                            <td class="px-6 py-4 text-center flex justify-center gap-3">
                                <button
                                    v-if="page.props.auth.permissions.includes('restore deleted shoe waste')"
                                    @click="restoreRecord(row.id)"
                                    class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs transition cursor-pointer"
                                >
                                    {{ t('restore') }}
                                </button>
                                <button
                                    v-if="page.props.auth.permissions.includes('force delete shoe waste')"
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
