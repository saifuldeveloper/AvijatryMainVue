<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps({
    liability: {
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
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ daterange: '', description: '', total_amount: '' }),
    },
    defaultDateRange: {
        type: String,
        default: '',
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
            'liabilities books': 'Liabilities Books: ',
            'liabilities': 'Liabilities',
            'paid': 'Paid',
            'name': 'Name',
            'date': 'Date',
            'description': 'Description',
            'option': 'Option',
            'taka': 'Taka (Amount)',
            'clear': 'Clear Filter',
            'filter': 'Filter',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'force_delete': 'Delete Permanently',
            'delete history': 'Delete History',
            'total': 'Total',
            'Are you sure?': 'Are you sure?',
            'No records found.': 'No records found.',
            'back': 'Back to List',
            'liabilities entry': 'Liabilities Entry',
            'Liabilities Amount': 'Amount (Taka)',
            'details': 'Details / Note',
            'save': 'Save',
            'processing': 'Processing...',
            'liabilites_entry_create': 'Create Liabilities Entry',
            'liabilites_entry_edit': 'Edit Liabilities Entry',
            'start_date': 'Start Date',
            'end_date': 'End Date',
        },
        bn: {
            'liabilities books': 'দেনা খাতা: ',
            'liabilities': 'দেনা',
            'paid': 'পরিশোধ',
            'name': 'নাম',
            'date': 'তারিখ',
            'description': 'বিবরণ',
            'option': 'অপশন',
            'taka': 'টাকা',
            'clear': 'ফিল্টার পরিষ্কার',
            'filter': 'ফিল্টার',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'force_delete': 'স্থায়ীভাবে মুছুন',
            'delete history': 'ডিলিট হিস্টোরি',
            'total': 'মোট',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'back': 'তালিকায় ফিরে যান',
            'liabilities entry': 'দেনা এন্ট্রি',
            'Liabilities Amount': 'দেনার পরিমাণ (টাকা)',
            'details': 'বিস্তারিত বিবরণ',
            'save': 'সংরক্ষণ',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
            'liabilites_entry_create': 'নতুন দেনা এন্ট্রি',
            'liabilites_entry_edit': 'দেনা এন্ট্রি সম্পাদন',
            'start_date': 'শুরুর তারিখ',
            'end_date': 'শেষের তারিখ',
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
const filterDescription = ref(props.filters?.description || '');
const filterTotalAmount = ref(props.filters?.total_amount || '');
const isFilterCollapsed = ref(true);

const toggleFilter = () => {
    isFilterCollapsed.value = !isFilterCollapsed.value;
};

// Server-side filtering
let filterTimeout;
const runFilters = (debounceTime = 400) => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        let daterangeVal = '';
        if (startDate.value && endDate.value) {
            daterangeVal = `${startDate.value} - ${endDate.value}`;
        }
        router.get(route('liabilities.show', props.liability.id), {
            daterange: daterangeVal,
            description: filterDescription.value,
            total_amount: filterTotalAmount.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch(filterDescription, () => runFilters(400));
watch(filterTotalAmount, () => runFilters(400));
watch([startDate, endDate], () => {
    if (startDate.value && endDate.value) {
        runFilters(0);
    }
});

const clearFilter = () => {
    startDate.value = '';
    endDate.value = '';
    filterDescription.value = '';
    filterTotalAmount.value = '';
    
    if (fpStart) fpStart.clear();
    if (fpEnd) fpEnd.clear();
    
    router.get(route('liabilities.show', props.liability.id), {}, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    let daterangeVal = '';
    if (startDate.value && endDate.value) {
        daterangeVal = `${startDate.value} - ${endDate.value}`;
    }
    router.get(route('liabilities.show', props.liability.id), {
        daterange: daterangeVal,
        description: filterDescription.value,
        total_amount: filterTotalAmount.value,
        page: pageNumber
    }, {
        preserveState: true
    });
};

// Actions
const deleteEntry = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('liabilities.entry.destroy', id), {
            preserveScroll: true
        });
    }
};

const restoreEntry = (id) => {
    router.post(route('liabilities.entry.restore', id), {}, {
        preserveScroll: true
    });
};

const forceDeleteEntry = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('liabilities.entry.forcedelete', id), {
            preserveScroll: true
        });
    }
};

// Calculation of sums for active entries on current page
const totals = computed(() => {
    let liabilitiesSum = 0;
    let paidSum = 0;
    props.entries.data.forEach(entry => {
        const val = parseFloat(entry.total_amount) || 0;
        if (entry.entry_type == 0) {
            liabilitiesSum += val;
        } else if (entry.entry_type == 1) {
            paidSum += val;
        }
    });
    return {
        liabilities: liabilitiesSum.toFixed(2),
        paid: paidSum.toFixed(2),
        balance: (liabilitiesSum - paidSum).toFixed(2),
    };
});

// Format Date helper
const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Flatpickr Element References
const startDateRef = ref(null);
const endDateRef = ref(null);
const entryDateRef = ref(null);

let fpStart, fpEnd, fpEntry;

onMounted(() => {
    fpStart = flatpickr(startDateRef.value, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y', // date/month/year format
        defaultDate: startDate.value,
        onChange: (selectedDates, dateStr) => {
            startDate.value = dateStr;
        }
    });

    fpEnd = flatpickr(endDateRef.value, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y', // date/month/year format
        defaultDate: endDate.value,
        onChange: (selectedDates, dateStr) => {
            endDate.value = dateStr;
        }
    });
});

// Liabilities Entry Form Modal Handling
const isEntryModalOpen = ref(false);
const editingEntry = ref(null);
const entryForm = useForm({
    account_book_id: props.accountBook?.id || '',
    total_amount: '',
    created_at: new Date().toISOString().split('T')[0],
    note: '',
});

// Watch isEntryModalOpen to initialize Flatpickr on the modal date input when opened
watch(isEntryModalOpen, (isOpen) => {
    if (isOpen) {
        setTimeout(() => {
            if (entryDateRef.value) {
                fpEntry = flatpickr(entryDateRef.value, {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd/m/Y', // date/month/year format
                    defaultDate: entryForm.created_at,
                    onChange: (selectedDates, dateStr) => {
                        entryForm.created_at = dateStr;
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

const openAddEntryModal = () => {
    editingEntry.value = null;
    entryForm.total_amount = '';
    entryForm.note = '';
    entryForm.created_at = new Date().toISOString().split('T')[0];
    entryForm.account_book_id = props.accountBook?.id || '';
    entryForm.clearErrors();
    isEntryModalOpen.value = true;
    if (fpEntry) {
        fpEntry.setDate(entryForm.created_at);
    }
};

const openEditEntryModal = (entry) => {
    editingEntry.value = entry;
    entryForm.account_book_id = props.accountBook?.id || '';
    entryForm.total_amount = entry.total_amount;
    entryForm.created_at = entry.created_at ? entry.created_at.split(' ')[0] : new Date().toISOString().split('T')[0];
    entryForm.note = entry.description || '';
    entryForm.clearErrors();
    isEntryModalOpen.value = true;
    if (fpEntry) {
        fpEntry.setDate(entryForm.created_at);
    }
};

const closeEntryModal = () => {
    isEntryModalOpen.value = false;
    editingEntry.value = null;
    entryForm.total_amount = '';
    entryForm.note = '';
    entryForm.created_at = new Date().toISOString().split('T')[0];
    entryForm.account_book_id = props.accountBook?.id || '';
    entryForm.clearErrors();
};

const submitEntryForm = () => {
    if (editingEntry.value) {
        entryForm.put(route('liabilities.entry.update', editingEntry.value.id), {
            onSuccess: () => {
                closeEntryModal();
            }
        });
    } else {
        entryForm.post(route('liabilities.entry.store'), {
            onSuccess: () => {
                closeEntryModal();
            }
        });
    }
};
</script>

<template>
    <Head :title="t('liabilities books') + ' ' + liability.name" />

    <AuthenticatedLayout>
        <!-- Back button and Main Card -->
        <div class="mb-4 flex items-center justify-between">
            <Link
                :href="route('liabilities.index')"
                class="bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition shadow-sm flex items-center gap-1.5"
            >
                <i class="fa fa-arrow-left"></i>
                <span>{{ t('back') }}</span>
            </Link>
        </div>

        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <div>
                    <h3 class="text-[20px] font-bold text-slate-800 dark:text-white">
                        {{ t('liabilities books') }} <span class="text-blue-600 dark:text-blue-400">{{ liability.name }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        {{ t('mobile_no') }}: {{ liability.mobile_no || '—' }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        @click="openAddEntryModal"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition duration-150 shadow-sm flex items-center gap-1.5"
                    >
                        <i class="fa fa-plus"></i>
                        <span>{{ t('liabilities entry') }}</span>
                    </button>
                    <button
                        @click="toggleFilter"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition duration-150 shadow-sm flex items-center gap-1.5"
                    >
                        <i class="fa fa-filter"></i>
                        <span>{{ t('filter') }}</span>
                    </button>
                </div>
            </div>

            <!-- Collapsible Filter Panel -->
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
                            v-model="filterDescription"
                            type="text"
                            :placeholder="t('description')"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('taka') }}
                        </label>
                        <input
                            v-model="filterTotalAmount"
                            type="text"
                            :placeholder="t('taka')"
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
                            <th class="px-4 py-2.5 w-16">#</th>
                            <th class="px-4 py-2.5 text-left" width="18%">{{ t('date') }}</th>
                            <th class="px-4 py-2.5 text-left" width="48%">{{ t('description') }}</th>
                            <th class="px-4 py-2.5" width="12%">{{ t('liabilities') }}</th>
                            <th class="px-4 py-2.5" width="12%">{{ t('paid') }}</th>
                            <th class="px-4 py-2.5 w-24">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(entry, index) in entries.data"
                            :key="entry.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200 text-center"
                        >
                            <td class="px-4 py-2.5">
                                {{ (entries.current_page - 1) * entries.per_page + index + 1 }}
                            </td>
                            <td class="px-4 py-2.5 text-left">
                                {{ formatDate(entry.created_at) }}
                            </td>
                            <td class="px-4 py-2.5 text-left" v-html="entry.description || '—'"></td>
                            <td class="px-4 py-2.5 font-bold text-slate-850 dark:text-slate-100">
                                {{ entry.entry_type == 0 ? entry.total_amount : '—' }}
                            </td>
                            <td class="px-4 py-2.5 font-bold text-green-600 dark:text-green-400">
                                {{ entry.entry_type == 1 ? entry.total_amount : '—' }}
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center justify-center gap-1.5" v-if="entry.entry_type == 0">
                                    <button
                                        @click="openEditEntryModal(entry)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Edit"
                                    >
                                        <i class="fa fa-edit text-xs"></i>
                                    </button>
                                    <button
                                        @click="deleteEntry(entry.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Delete"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="entries.data.length === 0">
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No records found.') }}
                            </td>
                        </tr>
                    </tbody>
                    <!-- Footer Sums -->
                    <tfoot class="bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-extrabold text-center">
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="px-4 py-2.5 text-left">{{ t('total') }}</td>
                            <td class="px-4 py-2.5 text-slate-850 dark:text-slate-100">{{ totals.liabilities }}</td>
                            <td class="px-4 py-2.5 text-green-600 dark:text-green-400">{{ totals.paid }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="entries" @page-changed="goToPage" />
            </div>
        </div>

        <!-- Trashed Entries (Delete History) -->
        <div v-if="trashedEntries.data.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-850 dark:text-white">
                    {{ t('delete history') }}
                </h4>
            </div>

            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse text-center">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 w-16">#</th>
                            <th class="px-4 py-2.5 text-left" width="18%">{{ t('date') }}</th>
                            <th class="px-4 py-2.5 text-left" width="48%">{{ t('description') }}</th>
                            <th class="px-4 py-2.5" width="12%">{{ t('liabilities') }}</th>
                            <th class="px-4 py-2.5" width="12%">{{ t('paid') }}</th>
                            <th class="px-4 py-2.5 w-56">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(entry, index) in trashedEntries.data"
                            :key="entry.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5">{{ index + 1 }}</td>
                            <td class="px-4 py-2.5 text-left">{{ formatDate(entry.created_at) }}</td>
                            <td class="px-4 py-2.5 text-left" v-html="entry.description || '—'"></td>
                            <td class="px-4 py-2.5 font-bold">{{ entry.entry_type == 0 ? entry.total_amount : '—' }}</td>
                            <td class="px-4 py-2.5 font-bold">{{ entry.entry_type == 1 ? entry.total_amount : '—' }}</td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="restoreEntry(entry.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm"
                                    >
                                        <i class="fa fa-undo text-xs"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteEntry(entry.id)"
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

        <!-- Liabilities Entry Modal -->
        <div v-if="isEntryModalOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeEntryModal"></div>
            <div class="relative w-full max-w-lg mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ editingEntry ? t('liabilites_entry_edit') : t('liabilites_entry_create') }}
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
                                    {{ t('Liabilities Amount') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="entryForm.total_amount"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="entryForm.errors.total_amount" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.total_amount }}
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
                                <div v-if="entryForm.errors.created_at" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.created_at }}
                                </div>
                            </div>

                            <!-- Details Note -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('details') }}
                                </label>
                                <textarea
                                    v-model="entryForm.note"
                                    rows="3"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('details')"
                                ></textarea>
                                <div v-if="entryForm.errors.note" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.note }}
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end border-t border-solid border-slate-200 dark:border-slate-700 pt-4 gap-2">
                            <button
                                type="button"
                                @click="closeEntryModal"
                                class="bg-slate-500 hover:bg-slate-600 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm"
                            >
                                {{ t('clear') }}
                            </button>
                            <button
                                type="submit"
                                :disabled="entryForm.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm disabled:opacity-50"
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
