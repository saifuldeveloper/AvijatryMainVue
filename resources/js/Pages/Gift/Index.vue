<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, usePage, useForm, router, Link } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    gifts: {
        type: Array,
        required: true,
    },
    trashGifts: {
        type: Array,
        default: () => [],
    },
    giftTypes: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ gift: '', gift_type_id: '' }),
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
    const fallbacks = {
        en: {
            'gifts': 'Gift Inventory',
            'new_gift': 'New Gift',
            'gift_edit': 'Edit Gift',
            'name': 'Name',
            'gift_type': 'Gift Type',
            'option': 'Option',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'save': 'Save',
            'update': 'Update',
            'cancel': 'Cancel',
            'Are you sure?': 'Are you sure?',
            'Trash Bin': 'Deleted Gift Inventory',
            'Active Gifts': 'Active Gifts',
            'No gifts found.': 'No gifts found.',
            'No trashed gifts found.': 'No trashed gifts found.',
            'Search gifts...': 'Search gifts...',
            'deleted_by': 'Deleted By',
            'date': 'Date',
            'transactions': 'Transactions',
            'type': 'Type',
            'select': 'Select',
            'clear': 'Clear Filter',
            'filter': 'Filter',
            'purchase_count': 'Purchase Count',
            'gift_sell': 'Gift Sell',
            'waste': 'Waste',
            'used': 'Used',
            'stock': 'Stock',
            'purchae_count': 'Min Purchase',
            'sell_count': 'Min Sale',
            'gift_purchase': 'Gift Purchase Memo',
        },
        bn: {
            'gifts': 'গিফট ইনভেন্টরি',
            'new_gift': 'নতুন গিফট',
            'gift_edit': 'গিফট সম্পাদন',
            'name': 'নাম',
            'gift_type': 'গিফট টাইপ',
            'option': 'অপশন',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'save': 'সংরক্ষণ',
            'update': 'আপডেট',
            'cancel': 'বাতিল',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'Trash Bin': 'মুছে ফেলা গিফটসমূহ',
            'Active Gifts': 'সক্রিয় গিফটসমূহ',
            'No gifts found.': 'কোনো গিফট পাওয়া হয়নি।',
            'No trashed gifts found.': 'মুছে ফেলা কোনো গিফট পাওয়া যায়নি।',
            'Search gifts...': 'গিফট খুঁজুন...',
            'deleted_by': 'মুছে ফেলেছেন',
            'date': 'তারিখ',
            'transactions': 'লেনদেন',
            'type': 'টাইপ',
            'select': 'নির্বাচন করুন',
            'clear': 'পরিষ্কার করুন',
            'filter': 'ফিল্টার',
            'purchase_count': 'ক্রয় সংখ্যা',
            'gift_sell': 'গিফট বিক্রয়',
            'waste': 'অপচয়',
            'used': 'ব্যবহৃত',
            'stock': 'স্টক',
            'purchae_count': 'ক্রয় সংখ্যা',
            'sell_count': 'বিক্রয় সংখ্যা',
            'gift_purchase': 'গিফট ক্রয় মেমো',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Search filter state
const searchQuery = ref(props.filters?.gift || '');
const typeQuery = ref(props.filters?.gift_type_id || '');
const filterPurchase = ref(props.filters?.giftTransactions?.type_purchase || '');
const filterSale = ref(props.filters?.giftTransactions?.type_sale || '');
const filterWaste = ref(props.filters?.giftTransactions?.type_waste || '');
const filterUse = ref(props.filters?.giftTransactions?.type_use || '');
const isFilterCollapsed = ref(true);

const toggleFilter = () => {
    isFilterCollapsed.value = !isFilterCollapsed.value;
};

// Server-side filtering with debounce
let searchTimeout;
const runFilters = (debounceTime = 400) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('gift.index'), {
            gift: searchQuery.value,
            gift_type_id: typeQuery.value,
            giftTransactions: {
                type_purchase: filterPurchase.value,
                type_sale: filterSale.value,
                type_waste: filterWaste.value,
                type_use: filterUse.value,
            }
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch([searchQuery, typeQuery, filterPurchase, filterSale, filterWaste, filterUse], () => runFilters(400));

const clearFilter = () => {
    searchQuery.value = '';
    typeQuery.value = '';
    filterPurchase.value = '';
    filterSale.value = '';
    filterWaste.value = '';
    filterUse.value = '';
    router.get(route('gift.index'), {}, {
        preserveState: false
    });
};

// Modal states
const isModalOpen = ref(false);
const isEditing = ref(false);

// Form
const form = useForm({
    id: null,
    name: '',
    gift_type_id: '',
});

// Open modal for adding
const openAddModal = () => {
    isEditing.value = false;
    form.clearErrors();
    form.reset();
    if (props.giftTypes.length > 0) {
        form.gift_type_id = props.giftTypes[0].id;
    }
    isModalOpen.value = true;
};

// Open modal for editing
const openEditModal = (gift) => {
    isEditing.value = true;
    form.clearErrors();
    form.id = gift.id;
    form.name = gift.name;
    form.gift_type_id = gift.gift_type_id;
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
        form.put(route('gift.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('gift.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// Delete gift
const deleteGift = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('gift.destroy', id));
    }
};

// Restore gift
const restoreGift = (id) => {
    form.post(route('gift.restore', id));
};

// Force delete gift
const forceDeleteGift = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('gift.forceDelete', id));
    }
};
</script>

<template>
    <Head :title="t('gifts')" />

    <AuthenticatedLayout>
        <!-- Gifts Layout Card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    {{ t('gifts') }}
                    <span class="text-sm font-semibold flex items-center gap-1.5 ml-2">
                        <button
                            @click="openAddModal"
                            class="bg-[#28a745] hover:bg-[#218838] text-white px-2.5 py-1 rounded text-xs transition shadow-sm cursor-pointer"
                        >
                            {{ t('new_gift') }}
                        </button>
                        <span>|</span>
                        <Link
                            :href="route('gift-purchase.create')"
                            class="bg-[#17a2b8] hover:bg-[#138496] text-white px-2.5 py-1 rounded text-xs transition shadow-sm cursor-pointer"
                        >
                            {{ t('gift_purchase') }}
                        </Link>
                    </span>
                </h3>
                
                <div class="flex items-center gap-2">
                    <button
                        @click="toggleFilter"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-[14px] font-semibold px-4 py-1.5 rounded transition shadow-sm cursor-pointer"
                    >
                        {{ t('filter') }}
                    </button>
                </div>
            </div>

            <!-- Collapsible Filters -->
            <div v-show="!isFilterCollapsed" class="p-6 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-7 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('name') }}
                        </label>
                        <input
                            v-model="searchQuery"
                            type="text"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('type') }}
                        </label>
                        <select
                            v-model="typeQuery"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 py-0.5 px-2"
                        >
                            <option value="">{{ t('select') }}</option>
                            <option v-for="type in giftTypes" :key="type.id" :value="type.id">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('purchae_count') }}
                        </label>
                        <input
                            v-model="filterPurchase"
                            type="number"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('sell_count') }}
                        </label>
                        <input
                            v-model="filterSale"
                            type="number"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('waste') }}
                        </label>
                        <input
                            v-model="filterWaste"
                            type="number"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('used') }}
                        </label>
                        <input
                            v-model="filterUse"
                            type="number"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <button
                            @click="clearFilter"
                            class="w-full h-10 bg-[#dc3545] hover:bg-[#c82333] text-white rounded text-sm font-semibold transition duration-155 shadow-sm cursor-pointer"
                        >
                            {{ t('clear') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto p-6">
                <table class="w-full text-left border-collapse text-[15px]">
                    <thead>
                        <tr class="border-b-[2px] border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-bold">
                            <th class="px-4 py-2.5 w-16 text-center">#</th>
                            <th class="px-4 py-2.5 w-[20%]">{{ t('name') }}</th>
                            <th class="px-4 py-2.5 w-[10%]">{{ t('type') }}</th>
                            <th class="px-4 py-2.5 w-[10%] text-center">{{ t('purchase_count') }}</th>
                            <th class="px-4 py-2.5 w-[15%] text-center">{{ t('gift_sell') }}</th>
                            <th class="px-4 py-2.5 w-[10%] text-center">{{ t('waste') }}</th>
                            <th class="px-4 py-2.5 w-[10%] text-center">{{ t('used') }}</th>
                            <th class="px-4 py-2.5 w-[10%] text-center">{{ t('stock') }}</th>
                            <th class="px-4 py-2.5 w-[15%] text-center">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/60 text-slate-800 dark:text-slate-200">
                        <tr v-if="gifts.length === 0">
                            <td colspan="9" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400 font-bold">
                                {{ t('No gifts found.') }}
                            </td>
                        </tr>
                        <tr
                            v-for="(gift, index) in gifts"
                            :key="gift.id"
                            class="odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 transition"
                        >
                            <td class="px-4 py-2.5 text-center font-medium">{{ index + 1 }}</td>
                            <td class="px-4 py-2.5 font-bold">
                                <Link :href="route('gift.transaction', gift.id)" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ gift.name }}
                                </Link>
                            </td>
                            <td class="px-4 py-2.5 font-medium">{{ gift.gift_type?.name || '' }}</td>
                            <td class="px-4 py-2.5 text-center font-semibold">{{ parseInt(gift.purchase_count) || 0 }}</td>
                            <td class="px-4 py-2.5 text-center font-semibold text-green-600 dark:text-green-400">{{ parseInt(gift.sale_count) || 0 }}</td>
                            <td class="px-4 py-2.5 text-center font-semibold text-red-500">{{ parseInt(gift.waste_count) || 0 }}</td>
                            <td class="px-4 py-2.5 text-center font-semibold text-slate-600 dark:text-slate-450">{{ parseInt(gift.use_count) || 0 }}</td>
                            <td class="px-4 py-2.5 text-center font-black text-slate-950 dark:text-white">
                                {{ (parseInt(gift.purchase_count) || 0) - (parseInt(gift.sale_count) || 0) - (parseInt(gift.waste_count) || 0) - (parseInt(gift.use_count) || 0) }}
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="openEditModal(gift)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                                        title="Edit"
                                    >
                                        <i class="fa fa-edit text-xs"></i>
                                    </button>
                                    <button
                                        @click="deleteGift(gift.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                                        title="Delete"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Trash Bin Card -->
        <div v-if="trashGifts.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-855 dark:text-white">
                    {{ t('Trash Bin') }}
                </h4>
            </div>
            
            <div class="overflow-x-auto p-6">
                <table class="w-full text-left border-collapse text-[15px]">
                    <thead>
                        <tr class="border-b-[2px] border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-bold">
                            <th class="px-4 py-2.5 w-16 text-center">#</th>
                            <th class="px-4 py-2.5 text-left">{{ t('name') }}</th>
                            <th class="px-4 py-2.5 text-left">{{ t('type') }}</th>
                            <th class="px-4 py-2.5 text-center">{{ t('stock') }}</th>
                            <th class="px-4 py-2.5 text-center w-56">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/60 text-slate-800 dark:text-slate-350">
                        <tr v-if="trashGifts.length === 0">
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 dark:text-slate-500">
                                {{ t('No trashed gifts found.') }}
                            </td>
                        </tr>
                        <tr
                            v-for="(gift, index) in trashGifts"
                            :key="gift.id"
                            class="odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40"
                        >
                            <td class="px-4 py-2.5 text-center font-medium">{{ index + 1 }}</td>
                            <td class="px-4 py-2.5 text-left font-medium">{{ gift.name }}</td>
                            <td class="px-4 py-2.5 text-left font-medium">{{ gift.gift_type?.name || '' }}</td>
                            <td class="px-4 py-2.5 text-center font-bold">
                                {{ (parseInt(gift.purchase_count) || 0) - (parseInt(gift.sale_count) || 0) - (parseInt(gift.waste_count) || 0) - (parseInt(gift.use_count) || 0) }}
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        @click="restoreGift(gift.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm cursor-pointer"
                                    >
                                        <i class="fa fa-undo text-xs"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteGift(gift.id)"
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

        <!-- Add/Edit Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm overflow-x-hidden overflow-y-auto outline-none">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal"></div>
            <div class="relative w-full max-w-lg mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h3 class="text-lg font-bold text-slate-855 dark:text-white">
                            {{ isEditing ? t('gift_edit') : t('new_gift') }}
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
                            <!-- Name -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">
                                    {{ t('name') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-855 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <span v-if="form.errors.name" class="text-red-500 text-xs mt-1 block">{{ form.errors.name }}</span>
                            </div>

                            <!-- Gift Type -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">
                                    {{ t('gift_type') }} <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.gift_type_id"
                                    required
                                    class="w-full rounded border-slate-355 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-855 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option v-for="type in giftTypes" :key="type.id" :value="type.id">
                                        {{ type.name }}
                                    </option>
                                </select>
                                <span v-if="form.errors.gift_type_id" class="text-red-500 text-xs mt-1 block">{{ form.errors.gift_type_id }}</span>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-slate-200 dark:border-slate-700 rounded-b gap-2">
                            <button
                                type="button"
                                @click="closeModal"
                                class="bg-slate-500 hover:bg-slate-600 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm cursor-pointer"
                            >
                                {{ t('cancel') }}
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm disabled:opacity-50 cursor-pointer"
                            >
                                {{ isEditing ? t('update') : t('save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
