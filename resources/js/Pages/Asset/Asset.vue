<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    assets: {
        type: Object,
        required: true,
    },
    trashAssets: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ name: '', daterange: '' }),
    },
    defaultDateRange: {
        type: String,
        default: '',
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
            'assets': 'Assets Register',
            'asset': 'Asset',
            'new_asset': 'Asset Entry',
            'asset_type': 'Asset Account',
            'asset_price': 'Asset Cost',
            'quantity': 'Amount (Taka)',
            'created_by': 'Created By',
            'date': 'Date',
            'expire_date': 'Expire Date',
            'option': 'Option',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'clear': 'Clear Filter',
            'filter': 'Filter',
            'Are you sure?': 'Are you sure?',
            'Trash Bin': 'Trash Bin',
            'Active Assets': 'Active Assets Register',
            'No assets found.': 'No asset records found.',
            'No trashed assets found.': 'No deleted asset records found.',
            'Search assets...': 'Search by asset account name...',
            'deleted_by': 'Deleted By',
        },
        bn: {
            'assets': 'অ্যাসেট রেজিস্টার',
            'asset': 'অ্যাসেট',
            'new_asset': 'নতুন অ্যাসেট',
            'asset_type': 'অ্যাসেট হিসাব',
            'asset_price': 'অ্যাসেট মূল্য',
            'quantity': 'টাকার পরিমাণ',
            'created_by': 'তৈরি করেছেন',
            'date': 'তারিখ',
            'expire_date': 'মেয়াদ উত্তীর্ণের তারিখ',
            'option': 'অপশন',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'clear': 'ফিল্টার পরিষ্কার',
            'filter': 'ফিল্টার',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'Trash Bin': 'মুছে ফেলা অ্যাসেটসমূহ',
            'Active Assets': 'সক্রিয় অ্যাসেট রেজিস্টার',
            'No assets found.': 'কোনো অ্যাসেট পাওয়া যায়নি।',
            'No trashed assets found.': 'মুছে ফেলা কোনো অ্যাসেট পাওয়া যায়নি।',
            'Search assets...': 'অ্যাসেট হিসাবের নাম দিয়ে খুঁজুন...',
            'deleted_by': 'মুছে ফেলেছেন',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const filterSearch = ref(props.filters?.name || '');
const filterDateRange = ref(props.filters?.daterange || '');

// Server-side filtering with debounce
let filterTimeout;
const runFilters = (debounceTime = 400) => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('assets.index'), {
            name: filterSearch.value,
            daterange: filterDateRange.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch(filterSearch, () => runFilters(400));
watch(filterDateRange, () => runFilters(0));

const clearFilter = () => {
    filterSearch.value = '';
    filterDateRange.value = '';
    router.get(route('assets.index'), {}, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    router.get(route('assets.index'), {
        name: filterSearch.value,
        daterange: filterDateRange.value,
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

// Delete asset
const deleteAsset = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('assets.destroy', id), {
            preserveScroll: true
        });
    }
};

// Restore asset
const restoreAsset = (id) => {
    router.post(route('assets.restore', id), {}, {
        preserveScroll: true
    });
};

// Force delete asset
const forceDeleteAsset = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('assets.forceDelete', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head :title="t('assets')" />

    <AuthenticatedLayout>
        <!-- Assets layout card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <div class="flex items-baseline gap-2.5">
                    <h3 class="text-[20px] font-bold text-slate-800 dark:text-white">
                        {{ t('assets') }}
                    </h3>
                    <a
                        :href="route('assets.create')"
                        class="text-blue-600 dark:text-blue-400 hover:underline font-bold text-sm"
                    >
                        {{ t('new_asset') }}
                    </a>
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
                            {{ t('date') }}
                        </label>
                        <input
                            v-model="filterDateRange"
                            type="text"
                            placeholder="YYYY-MM-DD - YYYY-MM-DD"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center font-semibold"
                        />
                    </div>

                    <div class="w-full sm:flex-1">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('Search assets...') }}
                        </label>
                        <input
                            v-model="filterSearch"
                            type="text"
                            :placeholder="t('Search assets...')"
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

            <!-- Assets Table -->
            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-16">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5">{{ t('asset_type') }}</th>
                            <th class="px-4 py-2.5">{{ t('quantity') }}</th>
                            <th class="px-4 py-2.5">{{ t('created_by') }}</th>
                            <th class="px-4 py-2.5">{{ t('date') }}</th>
                            <th class="px-4 py-2.5">{{ t('expire_date') }}</th>
                            <th class="px-4 py-2.5 text-center w-28">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(assetItem, index) in assets.data"
                            :key="assetItem.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5 text-center">
                                {{ (assets.current_page - 1) * assets.per_page + index + 1 }}
                            </td>
                            <td class="px-4 py-2.5 text-blue-600 dark:text-blue-400 font-bold">
                                <a :href="route('asset-book.show', assetItem.accountBook?.account_id || 1)" class="hover:underline">
                                    {{ assetItem.asset_type_name }}
                                </a>
                            </td>
                            <td class="px-4 py-2.5 font-extrabold text-slate-850 dark:text-slate-100">
                                {{ assetItem.asset_cost }}
                            </td>
                            <td class="px-4 py-2.5">
                                {{ assetItem.user?.name ?? '—' }}
                            </td>
                            <td class="px-4 py-2.5">
                                {{ assetItem.formatted_created_at }}
                            </td>
                            <td class="px-4 py-2.5">
                                {{ assetItem.formatted_expire_date }}
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a
                                        v-if="assetItem.type === 'normal'"
                                        :href="route('assets.edit', assetItem.id)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Edit"
                                    >
                                        <i class="fa fa-edit text-xs"></i>
                                    </a>
                                    <button
                                        v-if="assetItem.type === 'normal'"
                                        @click="deleteAsset(assetItem.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Delete"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="assets.data.length === 0">
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No assets found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="assets" @page-changed="goToPage" />
            </div>
        </div>

        <!-- Trash Bin -->
        <div v-if="trashAssets.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-850 dark:text-white">
                    {{ t('Trash Bin') }}
                </h4>
            </div>

            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-16">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5">{{ t('asset_type') }}</th>
                            <th class="px-4 py-2.5">{{ t('quantity') }}</th>
                            <th class="px-4 py-2.5">{{ t('date') }}</th>
                            <th class="px-4 py-2.5">{{ t('deleted_by') }}</th>
                            <th class="px-4 py-2.5 text-center w-56">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(assetItem, index) in trashAssets"
                            :key="assetItem.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5 text-center">{{ index + 1 }}</td>
                            <td class="px-4 py-2.5 font-medium">{{ assetItem.asset_type_name }}</td>
                            <td class="px-4 py-2.5 font-extrabold text-slate-850 dark:text-slate-100">{{ assetItem.asset_cost }}</td>
                            <td class="px-4 py-2.5">{{ assetItem.formatted_deleted_at }}</td>
                            <td class="px-4 py-2.5">{{ assetItem.deleted_by?.name ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="restoreAsset(assetItem.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm"
                                    >
                                        <i class="fa fa-undo text-xs"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteAsset(assetItem.id)"
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
    </AuthenticatedLayout>
</template>
