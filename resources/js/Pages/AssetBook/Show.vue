<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps({
    assetBook: {
        type: Object,
        required: true,
    },
    accountBook: {
        type: Object,
        required: true,
    },
    entries: {
        type: Object,
        required: true,
    },
    trashedEntries: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ daterange: '', note: '', asset_cost: '' }),
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
            'asset_book': 'Asset Ledger: ',
            'name': 'Name',
            'date': 'Date',
            'description': 'Details',
            'asset': 'Asset (Debit)',
            'asset return': 'Asset Return (Credit)',
            'option': 'Option',
            'total': 'Total',
            'balance': 'Net Balance',
            'delete history': 'Delete History / Trashed Records',
            'Are you sure?': 'Are you sure?',
            'Asset Entry': 'New Asset Entry',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'clear': 'Clear Filter',
            'filter': 'Filter',
            'Search details...': 'Search details...',
            'Search price...': 'Search price...',
            'sl': 'S.L.',
            'back': 'Back to List',
            'asset_entry_create': 'Create Asset Entry',
            'asset_entry_edit': 'Edit Asset Entry',
            'Asset Price': 'Amount (Taka)',
            'note': 'Note / Description',
            'save': 'Save',
            'processing': 'Processing...',
            'attachments': 'Attachments',
            'remove_selected': 'Remove selected',
            'select_files': 'Select Files',
            'start_date': 'Start Date',
            'end_date': 'End Date',
            'Delete Permanently': 'Delete Permanently',
            'No entries found.': 'No records found.',
        },
        bn: {
            'asset_book': 'অ্যাসেট লেজার: ',
            'name': 'নাম',
            'date': 'তারিখ',
            'description': 'বিবরণ',
            'asset': 'অ্যাসেট (ডেবিট)',
            'asset return': 'অ্যাসেট ফেরত (ক্রেডিট)',
            'option': 'অপশন',
            'total': 'মোট',
            'balance': 'মোট স্থিতি',
            'delete history': 'মুছে ফেলার ইতিহাস / ট্র্যাশ',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'Asset Entry': 'নতুন অ্যাসেট এন্ট্রি',
            'edit': 'সম্পাদন',
            'view_ledger': 'লেজার দেখুন',
            'back': 'তালিকায় ফিরে যান',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'clear': 'ফিল্টার পরিষ্কার',
            'filter': 'ফিল্টার',
            'Search details...': 'বিবরণ দিয়ে খুঁজুন...',
            'Search price...': 'টাকার পরিমাণ...',
            'sl': 'ক্রমিক',
            'asset_entry_create': 'নতুন অ্যাসেট এন্ট্রি',
            'asset_entry_edit': 'অ্যাসেট এন্ট্রি সম্পাদন',
            'Asset Price': 'টাকার পরিমাণ',
            'note': 'বিবরণ',
            'save': 'সংরক্ষণ',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
            'attachments': 'সংযুক্ত ফাইলসমূহ',
            'remove_selected': 'নির্বাচন সরান',
            'select_files': 'ফাইল নির্বাচন করুন',
            'start_date': 'শুরুর তারিখ',
            'end_date': 'শেষের তারিখ',
            'Delete Permanently': 'স্থায়ীভাবে মুছুন',
            'No entries found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const parseDaterange = () => {
    if (props.filters?.daterange) {
        const parts = props.filters.daterange.split(' - ');
        if (parts.length === 2) {
            return { start: parts[0], end: parts[1] };
        }
    }
    return { start: '', end: '' };
};

const initialDates = parseDaterange();
const startDate = ref(initialDates.start);
const endDate = ref(initialDates.end);
const filterNote = ref(props.filters?.note || '');
const filterCost = ref(props.filters?.asset_cost || '');

// Server-side filtering with debounce
let filterTimeout;
const runFilters = (debounceTime = 400) => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        let daterangeVal = '';
        if (startDate.value && endDate.value) {
            daterangeVal = `${startDate.value} - ${endDate.value}`;
        }
        router.get(route('asset-book.show', props.assetBook.id), {
            daterange: daterangeVal,
            note: filterNote.value,
            asset_cost: filterCost.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch(filterNote, () => runFilters(400));
watch(filterCost, () => runFilters(400));
watch([startDate, endDate], () => {
    if (startDate.value && endDate.value) {
        runFilters(0);
    }
});

const clearFilter = () => {
    startDate.value = '';
    endDate.value = '';
    filterNote.value = '';
    filterCost.value = '';
    if (fpStart) fpStart.clear();
    if (fpEnd) fpEnd.clear();
    router.get(route('asset-book.show', props.assetBook.id), {}, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    let daterangeVal = '';
    if (startDate.value && endDate.value) {
        daterangeVal = `${startDate.value} - ${endDate.value}`;
    }
    router.get(route('asset-book.show', props.assetBook.id), {
        daterange: daterangeVal,
        note: filterNote.value,
        asset_cost: filterCost.value,
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

// Calculations for active entries (totals in current page)
const pageTotals = computed(() => {
    let debit = 0;
    let credit = 0;
    props.entries.data.forEach(entry => {
        const cost = parseFloat(entry.asset_cost) || 0;
        if (entry.entry_type === 1) {
            debit += cost;
        } else {
            credit += cost;
        }
    });
    return {
        debit: debit.toFixed(2),
        credit: credit.toFixed(2),
        balance: (debit - credit).toFixed(2),
    };
});

// Actions
const deleteEntry = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('assets.destroy', id), {
            preserveScroll: true
        });
    }
};

const restoreEntry = (id) => {
    router.post(route('assets.restore', id), {}, {
        preserveScroll: true
    });
};

const forceDeleteEntry = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('assets.forceDelete', id), {
            preserveScroll: true
        });
    }
};

// Format Date helper
const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Flatpickr refs
const startDateRef = ref(null);
const endDateRef = ref(null);
const entryDateRef = ref(null);

let fpStart, fpEnd, fpEntry;

onMounted(() => {
    fpStart = flatpickr(startDateRef.value, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        defaultDate: startDate.value,
        onChange: (selectedDates, dateStr) => {
            startDate.value = dateStr;
        }
    });

    fpEnd = flatpickr(endDateRef.value, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        defaultDate: endDate.value,
        onChange: (selectedDates, dateStr) => {
            endDate.value = dateStr;
        }
    });
});

// Modal states & form
const isEntryModalOpen = ref(false);
const editingEntry = ref(null);
const existingAttachments = ref([]);
const entryForm = useForm({
    account_book_id: props.accountBook?.id || '',
    asset_cost: '',
    start_date: new Date().toISOString().split('T')[0],
    note: '',
    status: 1,
    attachments: [],
    remove_media: [],
});

watch(isEntryModalOpen, (isOpen) => {
    if (isOpen) {
        setTimeout(() => {
            if (entryDateRef.value) {
                fpEntry = flatpickr(entryDateRef.value, {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd/m/Y',
                    defaultDate: entryForm.start_date,
                    onChange: (selectedDates, dateStr) => {
                        entryForm.start_date = dateStr;
                    }
                });
            }
        }, 50);
    } else {
        if (fpEntry) {
            fpEntry.destroy();
            fpEntry = null;
        }
    }
});

const handleFileSelect = (event) => {
    entryForm.attachments = Array.from(event.target.files);
};

const toggleRemoveExisting = (mediaId) => {
    const idx = entryForm.remove_media.indexOf(mediaId);
    if (idx > -1) {
        entryForm.remove_media.splice(idx, 1);
    } else {
        entryForm.remove_media.push(mediaId);
    }
};

const openAddEntryModal = () => {
    editingEntry.value = null;
    entryForm.reset();
    entryForm.account_book_id = props.accountBook?.id || '';
    entryForm.start_date = new Date().toISOString().split('T')[0];
    existingAttachments.value = [];
    entryForm.clearErrors();
    isEntryModalOpen.value = true;
    if (fpEntry) {
        fpEntry.setDate(entryForm.start_date);
    }
};

const openEditEntryModal = (entry) => {
    editingEntry.value = entry;
    entryForm.account_book_id = props.accountBook?.id || '';
    entryForm.asset_cost = entry.asset_cost;
    entryForm.start_date = entry.created_at ? entry.created_at.split(' ')[0] : new Date().toISOString().split('T')[0];
    entryForm.note = entry.note || '';
    entryForm.status = entry.status ? 1 : 0;
    entryForm.attachments = [];
    entryForm.remove_media = [];
    
    existingAttachments.value = (entry.media || []).map(media => {
        return {
            id: media.id,
            name: media.file_name,
            url: `/storage/${media.id}/${media.file_name}`
        };
    });
    
    entryForm.clearErrors();
    isEntryModalOpen.value = true;
    if (fpEntry) {
        fpEntry.setDate(entryForm.start_date);
    }
};

const closeEntryModal = () => {
    isEntryModalOpen.value = false;
    editingEntry.value = null;
    entryForm.reset();
    entryForm.clearErrors();
};

const submitEntryForm = () => {
    if (editingEntry.value) {
        entryForm.transform((data) => ({
            ...data,
            _method: 'PUT'
        })).post(route('assets.update', editingEntry.value.id), {
            onSuccess: () => {
                closeEntryModal();
            }
        });
    } else {
        entryForm.post(route('assets.store'), {
            onSuccess: () => {
                closeEntryModal();
            }
        });
    }
};
</script>

<template>
    <Head :title="t('asset_book') + assetBook.name" />

    <AuthenticatedLayout>
        <!-- Back button -->
        <div class="mb-4 flex items-center justify-between">
            <Link
                :href="route('asset-book.index')"
                class="bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition shadow-sm flex items-center gap-1.5"
            >
                <i class="fa fa-arrow-left"></i>
                <span>{{ t('back') }}</span>
            </Link>
        </div>

        <!-- Ledger layout card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <div>
                    <h3 class="text-[20px] font-bold text-slate-800 dark:text-white inline-flex items-center gap-2">
                        <span>{{ t('asset_book') }}</span>
                        <span class="text-blue-600 dark:text-blue-400">{{ assetBook.name }}</span>
                    </h3>
                </div>
                
                <div class="flex items-center gap-3">
                    <button
                        @click="openAddEntryModal"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition duration-150 shadow-sm flex items-center gap-1.5"
                    >
                        <i class="fa fa-plus text-[10px]"></i>
                        <span>{{ t('Asset Entry') }}</span>
                    </button>
                    
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
            </div>

            <!-- Collapsible Filter Panel inside Card -->
            <div v-show="!isFilterCollapsed" class="p-6 border-b border-slate-200 dark:border-slate-700 bg-[#fcfcfc] dark:bg-slate-900/50 transition-all duration-300">
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('start_date') }}
                        </label>
                        <input
                            ref="startDateRef"
                            type="text"
                            placeholder="DD/MM/YYYY"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-semibold text-center"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('end_date') }}
                        </label>
                        <input
                            ref="endDateRef"
                            type="text"
                            placeholder="DD/MM/YYYY"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-semibold text-center"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('description') }}
                        </label>
                        <input
                            v-model="filterNote"
                            type="text"
                            :placeholder="t('Search details...')"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('balance') }}
                        </label>
                        <input
                            v-model="filterCost"
                            type="text"
                            :placeholder="t('Search price...')"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <button
                            @click="clearFilter"
                            class="w-full h-10 rounded bg-[#dc3545] hover:bg-[#c82333] text-white px-4 text-sm font-semibold transition shadow-sm flex items-center justify-center shrink-0"
                        >
                            {{ t('clear') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Ledger Table -->
            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold text-center">
                        <tr>
                            <th class="px-4 py-3 w-12">{{ t('sl') }}</th>
                            <th class="px-4 py-3 w-40">{{ t('date') }}</th>
                            <th class="px-4 py-3 text-left">{{ t('description') }}</th>
                            <th class="px-4 py-3 w-32">{{ t('asset') }}</th>
                            <th class="px-4 py-3 w-36">{{ t('asset return') }}</th>
                            <th class="px-4 py-3 text-center w-24">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(entry, index) in entries.data"
                            :key="entry.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-center"
                        >
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                                {{ (entries.current_page - 1) * entries.per_page + index + 1 }}
                            </td>
                            <td class="px-4 py-3 text-slate-800 dark:text-slate-200">
                                {{ formatDate(entry.created_at) }}
                            </td>
                            <td class="px-4 py-3 text-left text-slate-800 dark:text-slate-200" v-html="entry.note || '—'">
                            </td>
                            <td class="px-4 py-3 text-slate-800 dark:text-slate-200 font-bold">
                                {{ entry.entry_type === 1 ? entry.asset_cost : '—' }}
                            </td>
                            <td class="px-4 py-3 text-slate-800 dark:text-slate-200 font-bold">
                                {{ entry.entry_type !== 1 ? entry.asset_cost : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <template v-if="entry.type === 'normal'">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button
                                            @click="openEditEntryModal(entry)"
                                            class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded inline-flex items-center justify-center transition duration-150 shadow-sm cursor-pointer"
                                            title="Edit"
                                        >
                                            <i class="fa fa-edit text-xs"></i>
                                        </button>
                                        <button
                                            @click="deleteEntry(entry.id)"
                                            class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                                            title="Delete"
                                        >
                                            <i class="fa fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </template>
                            </td>
                        </tr>
                        <tr v-if="entries.data.length === 0">
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No entries found.') }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-slate-50/80 dark:bg-slate-900/60 font-bold border-t border-slate-200 dark:border-slate-700">
                        <tr class="text-center">
                            <td></td>
                            <td></td>
                            <td class="text-left px-4 py-3">{{ t('total') }}</td>
                            <td class="px-4 py-3 text-slate-800 dark:text-white">{{ pageTotals.debit }}</td>
                            <td class="px-4 py-3 text-slate-800 dark:text-white">{{ pageTotals.credit }}</td>
                            <td></td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="3" class="px-4 py-3 text-slate-500 dark:text-slate-400 text-left">{{ t('balance') }}</td>
                            <td colspan="3" class="px-4 py-3 text-[17px] text-blue-600 dark:text-blue-400 font-extrabold text-right pr-12">
                                {{ pageTotals.balance }} Taka
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="entries" @page-changed="goToPage" />
            </div>
        </div>

        <!-- Trashed Section / Delete History -->
        <div v-if="trashedEntries.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-850 dark:text-white">
                    {{ t('delete history') }}
                </h4>
            </div>

            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse text-center">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-3 w-12">{{ t('sl') }}</th>
                            <th class="px-4 py-3 w-40">{{ t('date') }}</th>
                            <th class="px-4 py-3 text-left">{{ t('description') }}</th>
                            <th class="px-4 py-3 w-32">{{ t('asset') }}</th>
                            <th class="px-4 py-3 text-center w-56">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(trash, index) in trashedEntries"
                            :key="trash.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30"
                        >
                            <td class="px-4 py-3 text-slate-500">{{ index + 1 }}</td>
                            <td class="px-4 py-3">{{ formatDate(trash.created_at) }}</td>
                            <td class="px-4 py-3 text-left" v-html="trash.note || '—'"></td>
                            <td class="px-4 py-3 font-bold">{{ trash.asset_cost }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="restoreEntry(trash.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm cursor-pointer"
                                    >
                                        <i class="fa fa-undo text-xs"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteEntry(trash.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm cursor-pointer"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                        <span>{{ t('Delete Permanently') }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Asset Entry Modal -->
        <div v-if="isEntryModalOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-8 overflow-x-hidden overflow-y-auto outline-none bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeEntryModal"></div>
            <div class="relative w-full max-w-2xl mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ editingEntry ? t('asset_entry_edit') : t('asset_entry_create') }}
                        </h3>
                        <button
                            @click="closeEntryModal"
                            class="p-1 ml-auto bg-transparent border-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                        >
                            ×
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form @submit.prevent="submitEntryForm" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Amount -->
                            <div>
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('Asset Price') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="entryForm.asset_cost"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="entryForm.errors.asset_cost" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.asset_cost }}
                                </div>
                            </div>

                            <!-- Date -->
                            <div>
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('date') }}
                                </label>
                                <input
                                    ref="entryDateRef"
                                    type="text"
                                    placeholder="DD/MM/YYYY"
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center font-semibold"
                                />
                                <div v-if="entryForm.errors.start_date" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.start_date }}
                                </div>
                            </div>

                            <!-- Details Note -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('note') }}
                                </label>
                                <textarea
                                    v-model="entryForm.note"
                                    rows="3"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('note')"
                                ></textarea>
                                <div v-if="entryForm.errors.note" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.note }}
                                </div>
                            </div>

                            <!-- Attachments -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('attachments') }}
                                </label>
                                <input
                                    type="file"
                                    multiple
                                    @change="handleFileSelect"
                                    class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-slate-200"
                                />
                                <div v-if="entryForm.errors.attachments" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.attachments }}
                                </div>
                            </div>

                            <!-- Existing Attachments (Edit only) -->
                            <div v-if="editingEntry && existingAttachments.length > 0" class="md:col-span-2 pt-2 border-t border-slate-200 dark:border-slate-700">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-2">
                                    {{ t('attachments') }}
                                </label>
                                <div class="space-y-2">
                                    <div
                                        v-for="attachment in existingAttachments"
                                        :key="attachment.id"
                                        class="flex items-center justify-between bg-slate-50 dark:bg-slate-900 px-3 py-2 rounded text-sm"
                                    >
                                        <a
                                            :href="attachment.url"
                                            target="_blank"
                                            class="text-blue-600 dark:text-blue-400 hover:underline font-bold break-all mr-2"
                                        >
                                            {{ attachment.name }}
                                        </a>
                                        <button
                                            type="button"
                                            @click="toggleRemoveExisting(attachment.id)"
                                            class="px-2.5 py-1 text-xs font-semibold rounded shrink-0 transition"
                                            :class="entryForm.remove_media.includes(attachment.id) ? 'bg-rose-500 text-white hover:bg-rose-600' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-350 hover:bg-slate-300 dark:hover:bg-slate-650'"
                                        >
                                            {{ entryForm.remove_media.includes(attachment.id) ? t('remove_selected') : t('delete') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end border-t border-solid border-slate-200 dark:border-slate-700 pt-4 gap-2">
                            <button
                                type="button"
                                @click="closeEntryModal"
                                class="bg-slate-500 hover:bg-slate-600 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm cursor-pointer"
                            >
                                {{ t('clear') }}
                            </button>
                            <button
                                type="submit"
                                :disabled="entryForm.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm disabled:opacity-50 cursor-pointer"
                            >
                                {{ entryForm.processing ? t('processing') : t('save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
