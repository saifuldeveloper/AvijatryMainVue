<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const props = defineProps({
    partyShoeWastes: {
        type: Object,
        required: true,
    },
    partyGifts: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    fileraccountTypes: {
        type: Array,
        required: true,
    },
    trashedShoeWasteEntries: {
        type: Array,
        default: () => [],
    },
    trashedGiftEntries: {
        type: Array,
        default: () => [],
    },
    dates: {
        type: Array,
        default: () => ['', ''],
    },
    retailStores: {
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
            'party_gift': 'Party Gift',
            'party_shoe_gift': 'Party Shoe Waste',
            'party_gift_item': 'Party Gift Waste',
            'name': 'Name',
            'picture': 'Picture',
            'id': 'ID',
            'type': 'Type',
            'color': 'Color',
            'details': 'Details',
            'selling_price': 'Selling Price',
            'pair': 'Pair',
            'date': 'Date',
            'option': 'Option',
            'deleted shoe waste': 'Deleted Party Shoe Waste',
            'deleted gift waste': 'Deleted Party Gift Waste',
            'restore': 'Restore',
            'delete': 'Delete',
            'gift': 'Gift Item',
            'count': 'Count',
            'Are you sure?': 'Are you sure?',
            'select': 'Select',
            'No records found.': 'No records found.',
            'Search': 'Search',
        },
        bn: {
            'party_gift': 'পার্টি উপহার',
            'party_shoe_gift': 'পার্টি জুতা জোলাপ',
            'party_gift_item': 'পার্টি গিফট জোলাপ',
            'name': 'নাম',
            'picture': 'ছবি',
            'id': 'আইডি',
            'type': 'ধরণ',
            'color': 'রঙ',
            'details': 'বিবরণ',
            'selling_price': 'গায়ের দাম',
            'pair': 'জোড়া',
            'date': 'তারিখ',
            'option': 'অপশন',
            'deleted shoe waste': 'মুছে ফেলা পার্টি জুতা জোলাপ',
            'deleted gift waste': 'মুছে ফেলা পার্টি গিফট জোলাপ',
            'restore': 'পুনরুদ্ধার',
            'delete': 'মুছুন',
            'gift': 'গিফট সামগ্রী',
            'count': 'পরিমাণ',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'select': 'সিলেক্ট',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'Search': 'খুঁজুন',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Tabs state
const activeTab = ref('shoe'); // 'shoe' or 'gift'

// Shoe Filters & Table State
const shoeFilter = ref({
    retailer_name: props.filters?.retailer_name || '',
    shoe_id: props.filters?.shoe_id || '',
    category: props.filters?.category || '',
    color: props.filters?.color || '',
    description: props.filters?.description || '',
    shoe_retail_price: props.filters?.shoe_retail_price || '',
    count: props.filters?.count || '',
    daterange: props.filters?.daterange || (props.dates[0] && props.dates[1] ? `${props.dates[0]} - ${props.dates[1]}` : ''),
});

const shoeData = computed(() => props.partyShoeWastes);
const shoeLoading = ref(false);
const shoePerPage = ref(parseInt(props.filters?.length) || 10);
const shoeSortBy = ref(props.filters?.order_by || 'created_at');
const shoeSortDir = ref(props.filters?.order_dir || 'desc');

const fetchShoeListings = (pageNumber = 1) => {
    shoeLoading.value = true;
    router.get(route('waste.party.gift'), {
        shoe_page: pageNumber,
        length: shoePerPage.value,
        order_by: shoeSortBy.value,
        order_dir: shoeSortDir.value,
        retailer_name: shoeFilter.value.retailer_name,
        shoe_id: shoeFilter.value.shoe_id,
        category: shoeFilter.value.category,
        color: shoeFilter.value.color,
        description: shoeFilter.value.description,
        shoe_retail_price: shoeFilter.value.shoe_retail_price,
        count: shoeFilter.value.count,
        daterange: shoeFilter.value.daterange,
        gift_page: giftData.value.current_page, // preserve gift page
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            shoeLoading.value = false;
        }
    });
};

const handleShoeSort = (column) => {
    if (shoeSortBy.value === column) {
        shoeSortDir.value = shoeSortDir.value === 'asc' ? 'desc' : 'asc';
    } else {
        shoeSortBy.value = column;
        shoeSortDir.value = 'desc';
    }
    fetchShoeListings(1);
};

// Gift Filters & Table State
const giftFilter = ref({
    gift_retailer_name: props.filters?.gift_retailer_name || '',
    gift_name: props.filters?.gift_name || '',
    gift_details: props.filters?.gift_details || '',
    count: props.filters?.count || '',
    daterange: props.filters?.daterange || (props.dates[0] && props.dates[1] ? `${props.dates[0]} - ${props.dates[1]}` : ''),
});

const giftData = computed(() => props.partyGifts);
const giftLoading = ref(false);
const giftPerPage = ref(parseInt(props.filters?.length) || 10);

const fetchGiftListings = (pageNumber = 1) => {
    giftLoading.value = true;
    router.get(route('waste.party.gift'), {
        gift_page: pageNumber,
        length: giftPerPage.value,
        gift_retailer_name: giftFilter.value.gift_retailer_name,
        gift_name: giftFilter.value.gift_name,
        gift_details: giftFilter.value.gift_details,
        count: giftFilter.value.count,
        daterange: giftFilter.value.daterange,
        shoe_page: shoeData.value.current_page, // preserve shoe page
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            giftLoading.value = false;
        }
    });
};

// Actions
const softDeleteShoe = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.post(route('waste.shoe.softDelete', id), {}, {
            onSuccess: () => fetchShoeListings(shoeData.value.current_page)
        });
    }
};

const restoreShoe = (id) => {
    router.get(route('waste.shoe.restore', id), {}, {
        onSuccess: () => fetchShoeListings(shoeData.value.current_page)
    });
};

const forceDeleteShoe = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.get(route('waste.shoe.forceDelete', id), {}, {
            onSuccess: () => fetchShoeListings(shoeData.value.current_page)
        });
    }
};

const softDeleteGift = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.post(route('waste.gift.softDelete', id), {}, {
            onSuccess: () => fetchGiftListings(giftData.value.current_page)
        });
    }
};

const restoreGift = (id) => {
    router.post(route('waste.gift.restore', id), {}, {
        onSuccess: () => fetchGiftListings(giftData.value.current_page)
    });
};

const forceDeleteGift = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.post(route('waste.gift.forceDelete', id), {}, {
            onSuccess: () => fetchGiftListings(giftData.value.current_page)
        });
    }
};

// Watchers on inputs for instant search reloading
watch([
    () => shoeFilter.value.retailer_name,
    () => shoeFilter.value.shoe_id,
    () => shoeFilter.value.category,
    () => shoeFilter.value.color,
    () => shoeFilter.value.description,
    () => shoeFilter.value.shoe_retail_price,
    () => shoeFilter.value.count,
    () => shoeFilter.value.daterange,
], () => {
    if (activeTab.value === 'shoe') {
        fetchShoeListings(1);
    }
});

watch([
    () => giftFilter.value.gift_retailer_name,
    () => giftFilter.value.gift_name,
    () => giftFilter.value.gift_details,
    () => giftFilter.value.count,
    () => giftFilter.value.daterange,
], () => {
    if (activeTab.value === 'gift') {
        fetchGiftListings(1);
    }
});
</script>

<template>
    <Head :title="t('party_gift')" />

    <AuthenticatedLayout>
        <!-- Main Card Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            
            <!-- Header Block -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">
                    {{ t('party_gift') }}
                </h3>

                <!-- Tab Selectors -->
                <div class="flex bg-slate-200/60 dark:bg-slate-800 p-0.5 rounded-lg border border-slate-300 dark:border-slate-700">
                    <button
                        @click="activeTab = 'shoe'"
                        :class="activeTab === 'shoe' ? 'bg-white dark:bg-slate-700 shadow text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800'"
                        class="px-4 py-1 rounded-md text-xs font-bold transition cursor-pointer"
                    >
                        {{ t('party_shoe_gift') }}
                    </button>
                    <button
                        @click="activeTab = 'gift'"
                        :class="activeTab === 'gift' ? 'bg-white dark:bg-slate-700 shadow text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800'"
                        class="px-4 py-1 rounded-md text-xs font-bold transition cursor-pointer"
                    >
                        {{ t('party_gift_item') }}
                    </button>
                </div>
            </div>

            <!-- ========================= Tab 1: Party Shoe Waste ========================= -->
            <div v-show="activeTab === 'shoe'" class="p-6">
                <div class="mb-4 flex items-center gap-2">
                    <span class="text-xs text-slate-500">দেখাচ্ছে</span>
                    <select v-model="shoePerPage" @change="fetchShoeListings(1)" class="rounded border-slate-300 text-xs py-1 px-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                        <option :value="10">10</option>
                        <option :value="25">25</option>
                        <option :value="50">50</option>
                    </select>
                    <span class="text-xs text-slate-500">এন্ট্রি</span>
                </div>
                <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-lg">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300">
                                <th class="px-4 py-3 font-bold border-r dark:border-slate-700" style="width: 5%">#</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('name') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('picture') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('id') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('type') }} (Category)</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('color') }}</th>
                                <th class="px-4 py-3 font-bold border-r dark:border-slate-700">{{ t('details') }}</th>
                                <th @click="handleShoeSort('retail_price')" class="px-4 py-3 font-bold text-center border-r dark:border-slate-700 cursor-pointer select-none">
                                    {{ t('selling_price') }}
                                    <span v-if="shoeSortBy === 'retail_price'">{{ shoeSortDir === 'asc' ? '▲' : '▼' }}</span>
                                </th>
                                <th @click="handleShoeSort('count')" class="px-4 py-3 font-bold text-center border-r dark:border-slate-700 cursor-pointer select-none">
                                    {{ t('pair') }}
                                    <span v-if="shoeSortBy === 'count'">{{ shoeSortDir === 'asc' ? '▲' : '▼' }}</span>
                                </th>
                                <th @click="handleShoeSort('created_at')" class="px-4 py-3 font-bold text-center border-r dark:border-slate-700 cursor-pointer select-none">
                                    {{ t('date') }}
                                    <span v-if="shoeSortBy === 'created_at'">{{ shoeSortDir === 'asc' ? '▲' : '▼' }}</span>
                                </th>
                                <th class="px-4 py-3 font-bold text-center">{{ t('option') }}</th>
                            </tr>
                            <!-- Inline Filters row -->
                            <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
                                <th class="border-r dark:border-slate-700"></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="shoeFilter.retailer_name" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white" /></th>
                                <th class="border-r dark:border-slate-700"></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="shoeFilter.shoe_id" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white" /></th>
                                <th class="p-2 border-r dark:border-slate-700">
                                    <select v-model="shoeFilter.category" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                        <option value="">{{ t('select') }}</option>
                                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.full_name }}</option>
                                    </select>
                                </th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="shoeFilter.color" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white" /></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="shoeFilter.description" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white" /></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="shoeFilter.shoe_retail_price" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" /></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="shoeFilter.count" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" /></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="shoeFilter.daterange" placeholder="Date range" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" /></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-800 dark:text-slate-200">
                            <tr v-if="shoeLoading" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                                <td colspan="11" class="px-4 py-8 text-center text-slate-500">Loading data...</td>
                            </tr>
                            <tr v-else-if="shoeData.data.length === 0" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                                <td colspan="11" class="px-4 py-8 text-center text-slate-500">{{ t('No records found.') }}</td>
                            </tr>
                            <tr v-else v-for="(row, idx) in shoeData.data" :key="row.id" class="odd:bg-white even:bg-slate-50/40 dark:odd:bg-slate-800 dark:even:bg-slate-900/30 hover:bg-slate-50/80 dark:hover:bg-slate-700/40">
                                <td class="px-4 py-3 border-r dark:border-slate-700">{{ (shoeData.current_page - 1) * shoePerPage + idx + 1 }}</td>
                                <td class="px-4 py-3 border-r dark:border-slate-700">{{ row.name }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">
                                    <img :src="row.shoe?.image ? '/images/small-thumbnail/' + row.shoe.image : '/img/shoe.png'" class="w-10 h-10 object-cover rounded border mx-auto" />
                                </td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700 font-semibold">{{ row.shoe?.code || row.shoe_id }}</td>
                                <td class="px-4 py-3 border-r dark:border-slate-700">{{ row.shoe?.category?.full_name || '-' }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.shoe?.color?.name || '-' }}</td>
                                <td class="px-4 py-3 border-r dark:border-slate-700">{{ row.description || '-' }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">৳{{ row.shoe?.retail_price }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700 font-semibold">{{ row.count }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.formatted_date }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button
                                        v-if="row.inventory_count === 'instant_waste' && page.props.auth.permissions.includes('delete party gift')"
                                        @click="softDeleteShoe(row.id)"
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
                    <Pagination :pagination="shoeData" @page-changed="fetchShoeListings" />
                </div>
            </div>

            <!-- ========================= Tab 2: Party Gift Waste ========================= -->
            <div v-show="activeTab === 'gift'" class="p-6">
                <div class="mb-4 flex items-center gap-2">
                    <span class="text-xs text-slate-500">দেখাচ্ছে</span>
                    <select v-model="giftPerPage" @change="fetchGiftListings(1)" class="rounded border-slate-300 text-xs py-1 px-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                        <option :value="10">10</option>
                        <option :value="25">25</option>
                        <option :value="50">50</option>
                    </select>
                    <span class="text-xs text-slate-500">এন্ট্রি</span>
                </div>
                <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-lg">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300">
                                <th class="px-4 py-3 font-bold border-r dark:border-slate-700" style="width: 5%">#</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('name') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('gift') }}</th>
                                <th class="px-4 py-3 font-bold border-r dark:border-slate-700">{{ t('details') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('count') }}</th>
                                <th class="px-4 py-3 font-bold text-center border-r dark:border-slate-700">{{ t('date') }}</th>
                                <th class="px-4 py-3 font-bold text-center">{{ t('option') }}</th>
                            </tr>
                            <!-- Inline Filter row -->
                            <tr class="bg-slate-55 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
                                <th class="border-r dark:border-slate-700"></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="giftFilter.gift_retailer_name" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white" /></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="giftFilter.gift_name" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white" /></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="giftFilter.gift_details" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white" /></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="giftFilter.count" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" /></th>
                                <th class="p-2 border-r dark:border-slate-700"><input v-model="giftFilter.daterange" placeholder="Date Filter" type="text" class="w-full text-xs rounded border-slate-300 p-1 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center" /></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-800 dark:text-slate-200">
                            <tr v-if="giftLoading" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">Loading data...</td>
                            </tr>
                            <tr v-else-if="giftData.data.length === 0" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">{{ t('No records found.') }}</td>
                            </tr>
                            <tr v-else v-for="(row, idx) in giftData.data" :key="row.id" class="odd:bg-white even:bg-slate-50/40 dark:odd:bg-slate-800 dark:even:bg-slate-900/30 hover:bg-slate-50/80 dark:hover:bg-slate-700/40">
                                <td class="px-4 py-3 border-r dark:border-slate-700">{{ (giftData.current_page - 1) * giftPerPage + idx + 1 }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.party_name }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700 font-semibold">{{ row.gift?.name || '-' }}</td>
                                <td class="px-4 py-3 border-r dark:border-slate-700">{{ row.description || '-' }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700 font-semibold">{{ row.count }}</td>
                                <td class="px-4 py-3 text-center border-r dark:border-slate-700">{{ row.formatted_date }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button
                                        v-if="page.props.auth.permissions.includes('delete party gift')"
                                        @click="softDeleteGift(row.id)"
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
                    <Pagination :pagination="giftData" @page-changed="fetchGiftListings" />
                </div>
            </div>
        </div>

        <!-- Trashed Shoe Waste Section -->
        <div v-show="activeTab === 'shoe' && trashedShoeWasteEntries.length > 0" class="bg-white dark:bg-slate-800 rounded shadow border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
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
                            <th class="px-6 py-3 font-semibold text-center">{{ t('id') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('pair') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('details') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('date') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('deleted_by') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-800 dark:text-slate-200">
                        <tr v-for="row in trashedShoeWasteEntries" :key="row.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4">
                                <img :src="row.shoe?.image ? '/images/small-thumbnail/' + row.shoe.image : '/img/shoe.png'" class="w-10 h-10 object-cover rounded border mx-auto" />
                            </td>
                            <td class="px-6 py-4 text-center">{{ row.shoe?.code }}</td>
                            <td class="px-6 py-4 text-center">{{ row.count }}</td>
                            <td class="px-6 py-4">{{ row.description || '-' }}</td>
                            <td class="px-6 py-4 text-center">{{ row.deleted_at }}</td>
                            <td class="px-6 py-4 text-center">{{ row.deleted_by?.name || '-' }}</td>
                            <td class="px-6 py-4 text-center flex justify-center gap-3">
                                <button
                                    v-if="page.props.auth.permissions.includes('restore deleted shoe waste')"
                                    @click="restoreShoe(row.id)"
                                    class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs transition cursor-pointer shadow-sm"
                                >
                                    {{ t('restore') }}
                                </button>
                                <button
                                    v-if="page.props.auth.permissions.includes('force delete shoe waste')"
                                    @click="forceDeleteShoe(row.id)"
                                    class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs transition cursor-pointer shadow-sm"
                                >
                                    {{ t('delete') }}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Trashed Gift Waste Section -->
        <div v-show="activeTab === 'gift' && trashedGiftEntries.length > 0" class="bg-white dark:bg-slate-800 rounded shadow border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
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
                            <th class="px-6 py-3 font-semibold text-center">{{ t('name') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('gift') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ t('details') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('count') }}</th>
                            <th class="px-6 py-3 font-semibold text-center">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-800 dark:text-slate-200">
                        <tr v-for="row in trashedGiftEntries" :key="row.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 text-center">{{ row.created_at }}</td>
                            <td class="px-6 py-4 text-center">{{ row.attachment_name || '-' }}</td>
                            <td class="px-6 py-4 text-center">{{ row.gift?.name || '-' }}</td>
                            <td class="px-6 py-4">{{ row.description || '-' }}</td>
                            <td class="px-6 py-4 text-center font-semibold">{{ row.count }}</td>
                            <td class="px-6 py-4 text-center flex justify-center gap-3">
                                <button
                                    v-if="page.props.auth.permissions.includes('restore deleted gift waste')"
                                    @click="restoreGift(row.id)"
                                    class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs transition cursor-pointer shadow-sm"
                                >
                                    {{ t('restore') }}
                                </button>
                                <button
                                    v-if="page.props.auth.permissions.includes('force delete gift waste')"
                                    @click="forceDeleteGift(row.id)"
                                    class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs transition cursor-pointer shadow-sm"
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
