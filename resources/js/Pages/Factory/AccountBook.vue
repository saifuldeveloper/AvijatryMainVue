<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps({
    factory: {
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
        default: () => ({ start_date: '', end_date: '', description: '', amount: '' }),
    },
    defaultDateRange: {
        type: String,
        default: '',
    },
    summary: {
        type: Object,
        required: true,
    }
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
            'supplier_book': 'Supplier Book',
            'date': 'Date',
            'details': 'Details / Memo',
            'pair': 'Pair (Count)',
            'purchase_price': 'Purchase Price',
            'total_price': 'Total Price',
            'payment': 'Payment',
            'running_balance': 'Running Balance',
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
            'back': 'Back to closing periods',
            'add_entry': 'New Entry',
            'edit_entry': 'Edit Entry',
            'start_date': 'Start Date',
            'end_date': 'End Date',
            'description': 'Description / Note',
            'amount': 'Amount',
            'save': 'Save',
            'processing': 'Processing...',
            'entry_type': 'Entry Type',
            'purchase': 'Purchase',
            'return': 'Return',
            'payment_method': 'Payment Method / Source Account',
            'retail_price': 'Retail Price',
            'summary': 'Period Summary',
            'opening_balance': 'Opening Balance',
            'total_purchases': 'Total Purchases',
            'total_returns': 'Total Returns',
            'total_payments': 'Total Payments',
            'current_balance': 'Current Balance',
            'calculation': 'Calculation',
            'total_pair': 'Total Pairs',
            'total_product': 'Total Product Worth',
            'returns': 'Returns',
            'staff_shoe': 'Staff Shoe',
            'commission': 'Commission',
            'staff_expences': 'Staff Expenses',
            'total_closing_payment': 'Total Closing Payment',
            'total_debt': 'Total Debt',
            'dummy_closing': 'Dummy Closing',
            'closing': 'Closing',
            'memo': 'Memo',
            'type': 'Type',
            'color': 'Color',
            'selling_price': 'Selling Price',
            'balance': 'Balance',
            'all_dues_paid': 'All dues on this account have been paid',
            'old': 'Opening Balance',
        },
        bn: {
            'supplier_book': 'মহাজন খাতা',
            'date': 'তারিখ',
            'details': 'বিবরণ / খাত',
            'pair': 'জোড়া (সংখ্যা)',
            'purchase_price': 'মহাজন দর (ডজন)',
            'total_price': 'মোট বিল',
            'payment': 'পরিশোধ',
            'running_balance': ' জের (Running Balance)',
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
            'back': 'পূর্ববর্তী পেইজে যান',
            'add_entry': 'নতুন এন্ট্রি',
            'edit_entry': 'এন্ট্রি সম্পাদন',
            'start_date': 'শুরুর তারিখ',
            'end_date': 'শেষের তারিখ',
            'description': 'বিবরণ / নোট',
            'amount': 'টাকার পরিমাণ',
            'save': 'সংরক্ষণ',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
            'entry_type': 'এন্ট্রির ধরণ',
            'purchase': 'ক্রয়',
            'return': 'ফেরত',
            'payment_method': 'পরিশোধের মাধ্যম / ক্যাশ খাতা',
            'retail_price': 'খুচরা মূল্য',
            'summary': 'হিসাব সারসংক্ষেপ',
            'opening_balance': 'সাবেক (Opening Balance)',
            'total_purchases': 'মোট ক্রয়',
            'total_returns': 'মোট ফেরত',
            'total_payments': 'মোট পরিশোধ',
            'current_balance': 'বর্তমান জের',
            'calculation': 'হিসাব',
            'total_pair': 'মোট জোড়া',
            'total_product': 'মোট মাল',
            'returns': 'ফেরত',
            'staff_shoe': 'স্টাফ জুতা',
            'commission': 'কমিশন',
            'staff_expences': 'স্টাফ খরচ',
            'total_closing_payment': 'মোট ক্লোজিং পরিশোধ',
            'total_debt': 'মোট দেনা',
            'dummy_closing': 'ডামি ক্লোজিং',
            'closing': 'ক্লোজিং',
            'memo': 'মেমো',
            'type': 'টাইপ',
            'color': 'রং',
            'selling_price': 'গায়ের দাম',
            'balance': 'ব্যালেন্স',
            'all_dues_paid': 'এই অ্যাকাউন্টের সমস্ত বকেয়া পরিশোধ করা হয়েছে',
            'old': 'সাবেক',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const startDate = ref(props.filters?.start_date || '');
const endDate = ref(props.filters?.end_date || '');
const filterDescription = ref(props.filters?.description || '');
const filterAmount = ref(props.filters?.amount || '');
const isFilterCollapsed = ref(true);

const toggleFilter = () => {
    isFilterCollapsed.value = !isFilterCollapsed.value;
};

// Server-side filtering
let filterTimeout;
const runFilters = (debounceTime = 400) => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('account-book.show', props.accountBook.id), {
            start_date: startDate.value,
            end_date: endDate.value,
            description: filterDescription.value,
            amount: filterAmount.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch(filterDescription, () => runFilters(400));
watch(filterAmount, () => runFilters(400));
watch([startDate, endDate], () => {
    if (startDate.value && endDate.value) {
        runFilters(0);
    }
});

const clearFilter = () => {
    startDate.value = '';
    endDate.value = '';
    filterDescription.value = '';
    filterAmount.value = '';
    
    if (fpStart) fpStart.clear();
    if (fpEnd) fpEnd.clear();
    
    router.get(route('account-book.show', props.accountBook.id), {}, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    router.get(route('account-book.show', props.accountBook.id), {
        start_date: startDate.value,
        end_date: endDate.value,
        description: filterDescription.value,
        amount: filterAmount.value,
        page: pageNumber
    }, {
        preserveState: true
    });
};

// Actions
const deleteEntry = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('factory.entry.destroy', id), {
            preserveScroll: true
        });
    }
};

const restoreEntry = (id) => {
    router.post(route('factory.entry.restore', id), {}, {
        preserveScroll: true
    });
};

const forceDeleteEntry = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('factory.entry.forcedelete', id), {
            preserveScroll: true
        });
    }
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

// Entry Form Modal Handling
const isEntryModalOpen = ref(false);
const editingEntry = ref(null);

const entryForm = useForm({
    account_book_id: props.accountBook?.id || '',
    entry_type: 0,
    purchase_id: '',
    count: '',
    purchase_price: '',
    retail_price: '',
    total_amount: '',
    description: '',
    account_name: '',
    created_at: new Date().toISOString().split('T')[0],
});

// Watch entry_type or inputs to auto-compute total amount for Purchase and Return
watch([() => entryForm.entry_type, () => entryForm.count, () => entryForm.purchase_price], () => {
    if (entryForm.entry_type == 0 || entryForm.entry_type == 1) {
        const cnt = parseFloat(entryForm.count) || 0;
        const prc = parseFloat(entryForm.purchase_price) || 0;
        entryForm.total_amount = ((cnt * prc) / 12).toFixed(2);
    }
});

// Watch isEntryModalOpen to initialize Flatpickr on the modal date input
watch(isEntryModalOpen, (isOpen) => {
    if (isOpen) {
        setTimeout(() => {
            if (entryDateRef.value) {
                fpEntry = flatpickr(entryDateRef.value, {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd/m/Y',
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
    entryForm.reset();
    entryForm.created_at = new Date().toISOString().split('T')[0];
    entryForm.account_book_id = props.accountBook?.id || '';
    entryForm.clearErrors();
    isEntryModalOpen.value = true;
};

const openEditEntryModal = (entry) => {
    editingEntry.value = entry;
    entryForm.account_book_id = props.accountBook?.id || '';
    entryForm.entry_type = entry.entry_type.value !== undefined ? entry.entry_type.value : entry.entry_type;
    entryForm.purchase_id = entry.purchase_id || '';
    entryForm.count = entry.count;
    entryForm.purchase_price = entry.purchase_price;
    entryForm.retail_price = entry.retail_price;
    entryForm.total_amount = entry.total_amount;
    entryForm.description = entry.description || '';
    entryForm.account_name = entry.account_name || '';
    entryForm.created_at = entry.created_at ? entry.created_at.split(' ')[0] : new Date().toISOString().split('T')[0];
    entryForm.clearErrors();
    isEntryModalOpen.value = true;
};

const closeEntryModal = () => {
    isEntryModalOpen.value = false;
    editingEntry.value = null;
    entryForm.reset();
    entryForm.clearErrors();
};

const submitEntryForm = () => {
    if (editingEntry.value) {
        entryForm.put(route('factory.entry.update', editingEntry.value.id), {
            onSuccess: () => {
                closeEntryModal();
            }
        });
    } else {
        entryForm.post(route('factory.entry.store'), {
            onSuccess: () => {
                closeEntryModal();
            }
        });
    }
};
</script>

<template>
    <Head :title="t('supplier_book') + ' ' + factory.name" />

    <AuthenticatedLayout>
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                {{ t('supplier_book') }}
            </h1>
        </div>

        <!-- 3-Column Split card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Left: Supplier Details -->
                <div class="md:col-span-4 md:border-r border-slate-200 dark:border-slate-700/80 md:pr-6">
                    <h5 class="text-blue-600 dark:text-blue-400 font-bold mb-3 flex items-center gap-1.5">
                        <i class="fa fa-industry"></i>
                        <span>{{ t('supplier_book') }}</span>
                    </h5>
                    <p class="mb-1 text-slate-800 dark:text-white"><strong>{{ factory.name }}</strong></p>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mb-2 flex items-center gap-1.5">
                        <i class="fa fa-map-marker-alt text-slate-450"></i>
                        <span>{{ factory.address }}</span>
                    </p>
                    <p class="text-slate-800 dark:text-slate-200 text-sm mb-2 flex items-center gap-1.5">
                        <i class="fa fa-phone text-slate-450"></i>
                        <span>{{ factory.mobile_no }}</span>
                    </p>
                    <p class="text-slate-800 dark:text-slate-200 text-sm flex items-center gap-1.5">
                        <i class="fa fa-calendar-alt text-slate-450"></i>
                        <span>{{ summary.description }}</span>
                    </p>
                </div>

                <!-- Middle: Calculations list -->
                <div class="md:col-span-5 md:border-r border-slate-200 dark:border-slate-700/80 md:px-6">
                    <h5 class="text-green-600 dark:text-green-400 font-bold mb-3 flex items-center gap-1.5">
                        <i class="fa fa-calculator"></i>
                        <span>{{ t('calculation') }}</span>
                    </h5>
                    
                    <div class="flex justify-between items-center mb-1.5 text-sm text-slate-700 dark:text-slate-300">
                        <span>{{ t('total_pair') }}:</span>
                        <strong class="text-slate-900 dark:text-white">{{ summary.total_pairs }} {{ t('pair') }}</strong>
                    </div>

                    <div class="flex justify-between items-center mb-1.5 text-sm text-slate-700 dark:text-slate-300">
                        <span>{{ t('total_product') }}:</span>
                        <strong class="text-slate-900 dark:text-white">{{ parseFloat(summary.total_purchases).toFixed(2) }} ৳</strong>
                    </div>

                    <div v-if="summary.total_returns > 0" class="flex justify-between items-center mb-1.5 text-sm text-slate-700 dark:text-slate-300">
                        <span>{{ t('returns') }}:</span>
                        <strong class="text-slate-900 dark:text-white">{{ parseFloat(summary.total_returns).toFixed(2) }} ৳</strong>
                    </div>

                    <div class="flex justify-between items-center mb-1.5 text-sm text-slate-700 dark:text-slate-300">
                        <span>{{ t('payment') }}:</span>
                        <div>
                            <span
                                class="inline-block px-1.5 py-0.5 rounded text-xs font-bold mr-1.5"
                                :class="summary.paid_percentage >= 100 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'"
                            >
                                {{ parseFloat(summary.paid_percentage).toFixed(2) }}%
                            </span>
                            <strong class="text-slate-900 dark:text-white">{{ parseFloat(summary.total_payments).toFixed(2) }} ৳</strong>
                        </div>
                    </div>

                    <div v-if="accountBook.staff_shoe_amount > 0" class="flex justify-between items-center mb-1.5 text-sm text-slate-700 dark:text-slate-300">
                        <span>{{ t('staff_shoe') }}:</span>
                        <span class="text-slate-900 dark:text-white">{{ parseFloat(accountBook.staff_shoe_amount).toFixed(2) }} ৳</span>
                    </div>

                    <div v-if="!accountBook.open && accountBook.commission > 0" class="flex justify-between items-center mb-1.5 text-sm text-slate-700 dark:text-slate-300">
                        <span>{{ t('commission') }}:</span>
                        <div>
                            <span class="inline-block px-1.5 py-0.5 rounded text-xs font-bold mr-1.5 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                {{ parseFloat(accountBook.commission_percentage).toFixed(2) }}%
                            </span>
                            <strong class="text-slate-900 dark:text-white">{{ parseFloat(accountBook.commission).toFixed(2) }} ৳</strong>
                        </div>
                    </div>

                    <div v-if="!accountBook.open && accountBook.staff > 0" class="flex justify-between items-center mb-1.5 text-sm text-slate-700 dark:text-slate-300">
                        <span>{{ t('staff_expences') }}:</span>
                        <div>
                            <span class="inline-block px-1.5 py-0.5 rounded text-xs font-bold mr-1.5 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                {{ parseFloat(accountBook.staff_percentage).toFixed(2) }}%
                            </span>
                            <strong class="text-slate-900 dark:text-white">{{ parseFloat(accountBook.staff).toFixed(2) }} ৳</strong>
                        </div>
                    </div>

                    <div v-if="!accountBook.open && summary.total_closing_payment > 0" class="flex justify-between items-center mb-1.5 text-sm text-slate-700 dark:text-slate-300">
                        <span>{{ t('total_closing_payment') }}:</span>
                        <span class="text-slate-900 dark:text-white">{{ parseFloat(summary.total_closing_payment).toFixed(2) }} ৳</span>
                    </div>

                    <hr class="my-2 border-slate-200 dark:border-slate-700">

                    <div class="flex justify-between items-center mt-2 text-sm text-slate-750 dark:text-slate-300">
                        <span>{{ t('total_debt') }}:</span>
                        <strong class="text-red-500 font-extrabold text-lg">{{ parseFloat(summary.current_balance).toFixed(2) }} ৳</strong>
                    </div>
                </div>

                <!-- Right: Actions panel -->
                <div class="md:col-span-3 md:pl-6 flex flex-col justify-center gap-3">
                    <button
                        @click="toggleFilter"
                        class="border border-blue-600 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/20 font-semibold rounded px-4 py-2 text-sm transition duration-150 shadow-sm flex items-center justify-center gap-1.5 cursor-pointer w-full"
                    >
                        <i class="fa fa-filter"></i>
                        <span>{{ t('filter') }}</span>
                    </button>

                    <Link
                        :href="route('account-book.closing', accountBook.id)"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold rounded px-4 py-2 text-sm transition duration-150 shadow-sm flex items-center justify-center gap-1.5 cursor-pointer w-full text-center"
                    >
                        <i class="fa fa-check-circle"></i>
                        <span>{{ t('closing') }}</span>
                    </Link>

                    <Link
                        :href="route('account-book.closing', accountBook.id) + '?dummy=1'"
                        class="border border-green-600 text-green-600 hover:bg-green-50 dark:hover:bg-green-950/20 font-semibold rounded px-4 py-2 text-sm transition duration-150 shadow-sm flex items-center justify-center gap-1.5 cursor-pointer w-full text-center"
                    >
                        <i class="fa fa-file-invoice"></i>
                        <span>{{ t('dummy_closing') }}</span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Ledger table card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Table Header Removed to match old layout directly -->

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
                            {{ t('amount') }}
                        </label>
                        <input
                            v-model="filterAmount"
                            type="text"
                            :placeholder="t('amount')"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <button
                            @click="clearFilter"
                            class="w-full h-10 rounded bg-[#dc3545] hover:bg-[#c82333] text-white px-4 text-sm font-semibold transition shadow-sm flex items-center justify-center shrink-0 cursor-pointer"
                        >
                            {{ t('clear') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Ledger Table -->
            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse text-center">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-3 py-2.5 w-12">#</th>
                            <th class="px-3 py-2.5 text-left w-28">{{ t('date') }}</th>
                            <th class="px-3 py-2.5 w-20">{{ t('memo') }}</th>
                            <th class="px-3 py-2.5 text-left w-24">{{ t('details') }}</th>
                            <th class="px-3 py-2.5 text-left">{{ t('type') }}</th>
                            <th class="px-3 py-2.5 text-left">{{ t('color') }}</th>
                            <th class="px-3 py-2.5 w-16">{{ t('pair') }}</th>
                            <th class="px-3 py-2.5 w-24">{{ t('selling_price') }}</th>
                            <th class="px-3 py-2.5 w-24">{{ t('purchase_price') }}</th>
                            <th class="px-3 py-2.5 w-24">{{ t('total_price') }}</th>
                            <th class="px-3 py-2.5 w-24">{{ t('payment') }}</th>
                            <th class="px-3 py-2.5 w-28">{{ t('balance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Closed Extra Rows (Prepended on Page 1) -->
                        <template v-if="entries.current_page === 1 && !accountBook.open">
                            <!-- Commission Row -->
                            <tr v-if="accountBook.commission > 0" class="border-b border-slate-200 dark:border-slate-700/80 bg-slate-100/50 dark:bg-slate-900/20 text-slate-800 dark:text-slate-200 italic">
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5 text-left">—</td>
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5 text-left">
                                    <span class="text-indigo-600 bg-indigo-100 dark:bg-indigo-900/40 px-1.5 py-0.5 rounded text-xs">{{ t('closing') }}</span>
                                </td>
                                <td class="px-3 py-2.5 text-left font-semibold">{{ t('commission') }}</td>
                                <td class="px-3 py-2.5 text-left">—</td>
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5 font-bold text-green-600">{{ parseFloat(accountBook.commission).toFixed(2) }}</td>
                                <td class="px-3 py-2.5 font-extrabold text-red-500">{{ parseFloat(summary.description_balance).toFixed(2) }}</td>
                            </tr>
                            <!-- Staff Expenses Row -->
                            <tr v-if="accountBook.staff > 0" class="border-b border-slate-200 dark:border-slate-700/80 bg-slate-100/50 dark:bg-slate-900/20 text-slate-800 dark:text-slate-200 italic">
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5 text-left">—</td>
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5 text-left">
                                    <span class="text-indigo-600 bg-indigo-100 dark:bg-indigo-900/40 px-1.5 py-0.5 rounded text-xs">{{ t('closing') }}</span>
                                </td>
                                <td class="px-3 py-2.5 text-left font-semibold">{{ t('staff_expences') }}</td>
                                <td class="px-3 py-2.5 text-left">—</td>
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5">—</td>
                                <td class="px-3 py-2.5 font-bold text-green-600">{{ parseFloat(accountBook.staff).toFixed(2) }}</td>
                                <td class="px-3 py-2.5 font-extrabold text-red-500">{{ (parseFloat(summary.description_balance) + parseFloat(accountBook.commission)).toFixed(2) }}</td>
                            </tr>
                        </template>

                        <!-- Main Entries -->
                        <tr
                            v-for="(entry, index) in entries.data"
                            :key="entry.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-3 py-2.5">
                                {{ (entries.current_page - 1) * entries.per_page + index + 1 }}
                            </td>
                            <td class="px-3 py-2.5 text-left">
                                {{ entry.formatted_created_at }}
                            </td>
                            <td class="px-3 py-2.5 font-semibold text-blue-600 dark:text-blue-400">
                                {{ entry.purchase_id || '—' }}
                            </td>
                            <td class="px-3 py-2.5 text-left">
                                <span v-if="entry.entry_type.value == 0" class="text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/40 px-1.5 py-0.5 rounded text-xs">{{ t('purchase') }}</span>
                                <span v-else-if="entry.entry_type.value == 1" class="text-orange-500 bg-orange-100 dark:bg-orange-900/35 px-1.5 py-0.5 rounded text-xs">{{ t('return') }}</span>
                                <span v-else class="text-green-600 bg-green-100 dark:bg-green-900/35 px-1.5 py-0.5 rounded text-xs">{{ t('payment') }}</span>
                            </td>
                            <!-- type / category name -->
                            <td class="px-3 py-2.5 text-left">
                                {{ entry.account_name || '—' }}
                            </td>
                            <!-- color / description -->
                            <td class="px-3 py-2.5 text-left">
                                {{ entry.description || '—' }}
                            </td>
                            <!-- count / pair -->
                            <td class="px-3 py-2.5 font-semibold">
                                {{ (entry.entry_type.value == 0 || entry.entry_type.value == 1) ? entry.count : '—' }}
                            </td>
                            <!-- selling price (গায়ের দাম) -->
                            <td class="px-3 py-2.5 text-slate-600 dark:text-slate-400">
                                {{ (entry.entry_type.value == 0 || entry.entry_type.value == 1) ? parseFloat(entry.retail_price).toFixed(2) : '—' }}
                            </td>
                            <!-- price per dozen (ডজন দাম) -->
                            <td class="px-3 py-2.5 text-slate-600 dark:text-slate-400">
                                {{ (entry.entry_type.value == 0 || entry.entry_type.value == 1) ? parseFloat(entry.purchase_price).toFixed(2) : '—' }}
                            </td>
                            <!-- total price (মোট দাম) -->
                            <td class="px-3 py-2.5 font-bold text-slate-850 dark:text-slate-100">
                                {{ entry.entry_type.value == 0 ? parseFloat(entry.total_amount).toFixed(2) : '—' }}
                            </td>
                            <!-- payment / returns (তাগাদা) -->
                            <td class="px-3 py-2.5 font-bold text-green-600 dark:text-green-400">
                                {{ (entry.entry_type.value == 1 || entry.entry_type.value == 2) ? parseFloat(entry.total_amount).toFixed(2) : '—' }}
                            </td>
                            <!-- running balance -->
                            <td class="px-3 py-2.5 font-extrabold text-red-500">
                                {{ parseFloat(entry.running_balance).toFixed(2) }}
                            </td>
                        </tr>

                        <!-- Opening Balance Row (Appended on Last Page) -->
                        <tr v-if="entries.current_page === entries.last_page && summary.opening_balance != 0" class="border-b border-slate-200 dark:border-slate-700/80 bg-slate-100/50 dark:bg-slate-900/20 text-slate-800 dark:text-slate-200 italic font-semibold">
                            <td class="px-3 py-2.5">—</td>
                            <td class="px-3 py-2.5 text-left">—</td>
                            <td class="px-3 py-2.5">—</td>
                            <td class="px-3 py-2.5 text-left">
                                <span class="text-teal-600 bg-teal-100 dark:bg-teal-900/40 px-1.5 py-0.5 rounded text-xs">{{ t('old') }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-left">—</td>
                            <td class="px-3 py-2.5 text-left">—</td>
                            <td class="px-3 py-2.5">—</td>
                            <td class="px-3 py-2.5">—</td>
                            <td class="px-3 py-2.5">—</td>
                            <td class="px-3 py-2.5">—</td>
                            <td class="px-3 py-2.5">—</td>
                            <td class="px-3 py-2.5 font-extrabold text-red-500">{{ parseFloat(summary.opening_balance).toFixed(2) }}</td>
                        </tr>

                        <tr v-if="entries.data.length === 0">
                            <td colspan="12" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No records found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Showing Info Text -->
            <div class="px-6 pb-2 text-center text-xs text-slate-500 dark:text-slate-400 font-bold">
                <span>{{ page.props.locale === 'bn' ? `দেখানো হচ্ছে ${entries.from || 0} থেকে ${entries.to || 0} এর মধ্যে, মোট ${entries.total || 0} টি` : `Showing ${entries.from || 0} to ${entries.to || 0} of ${entries.total || 0} entries` }}</span>
            </div>


            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="entries" @page-changed="goToPage" />
            </div>
        </div>

        <!-- Trashed entries (Delete history) -->
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
                            <th class="px-4 py-2.5 text-left w-28">{{ t('date') }}</th>
                            <th class="px-4 py-2.5 text-left">{{ t('details') }}</th>
                            <th class="px-4 py-2.5 w-36">{{ t('total_price') }}</th>
                            <th class="px-4 py-2.5 w-36">{{ t('payment') }}</th>
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
                            <td class="px-4 py-2.5 text-left">{{ entry.formatted_created_at }}</td>
                            <td class="px-4 py-2.5 text-left">
                                <div class="font-semibold text-slate-500">
                                    <span v-if="entry.entry_type.value == 0" class="text-blue-600 bg-blue-100/60 px-1 py-0.5 rounded text-xs">{{ t('purchase') }}</span>
                                    <span v-else-if="entry.entry_type.value == 1" class="text-orange-500 bg-orange-100/60 px-1 py-0.5 rounded text-xs">{{ t('return') }}</span>
                                    <span v-else class="text-green-600 bg-green-100/60 px-1 py-0.5 rounded text-xs">{{ t('payment') }}</span>
                                    <span class="ml-1">{{ entry.description || '' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 font-bold">{{ entry.entry_type.value == 0 ? parseFloat(entry.total_amount).toFixed(2) : '—' }}</td>
                            <td class="px-4 py-2.5 font-bold text-green-600">{{ (entry.entry_type.value == 1 || entry.entry_type.value == 2) ? parseFloat(entry.total_amount).toFixed(2) : '—' }}</td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="restoreEntry(entry.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm cursor-pointer"
                                    >
                                        <i class="fa fa-undo text-xs"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteEntry(entry.id)"
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

        <!-- Factory Entry Form Modal -->
        <div v-if="isEntryModalOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeEntryModal"></div>
            <div class="relative w-full max-w-lg mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ editingEntry ? t('edit_entry') : t('add_entry') }}
                        </h3>
                        <button
                            @click="closeEntryModal"
                            class="p-1 ml-auto bg-transparent border-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cursor-pointer"
                        >
                            ×
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form @submit.prevent="submitEntryForm" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Entry Type -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('entry_type') }} <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="entryForm.entry_type"
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                >
                                    <option :value="0">{{ t('purchase') }}</option>
                                    <option :value="1">{{ t('return') }}</option>
                                    <option :value="2">{{ t('payment') }}</option>
                                </select>
                                <div v-if="entryForm.errors.entry_type" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.entry_type }}
                                </div>
                            </div>

                            <!-- Memo / Purchase ID (For Purchase & Return) -->
                            <div v-if="entryForm.entry_type == 0 || entryForm.entry_type == 1">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('memo') }} (Purchase ID)
                                </label>
                                <input
                                    v-model="entryForm.purchase_id"
                                    type="number"
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="e.g. 7156"
                                />
                                <div v-if="entryForm.errors.purchase_id" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.purchase_id }}
                                </div>
                            </div>

                            <!-- Type / Category (For Purchase & Return) -->
                            <div v-if="entryForm.entry_type == 0 || entryForm.entry_type == 1">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('type') }} (Category)
                                </label>
                                <input
                                    v-model="entryForm.account_name"
                                    type="text"
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="e.g. লে-নাপড়া হীল"
                                />
                                <div v-if="entryForm.errors.account_name" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.account_name }}
                                </div>
                            </div>

                            <!-- Color / Description (For Purchase & Return) -->
                            <div v-if="entryForm.entry_type == 0 || entryForm.entry_type == 1" class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('color') }} (Description)
                                </label>
                                <input
                                    v-model="entryForm.description"
                                    type="text"
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="e.g. Black Pink"
                                />
                                <div v-if="entryForm.errors.description" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.description }}
                                </div>
                            </div>

                            <!-- Count/Pair (For Purchase & Return) -->
                            <div v-if="entryForm.entry_type == 0 || entryForm.entry_type == 1">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('pair') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="entryForm.count"
                                    type="number"
                                    required
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="entryForm.errors.count" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.count }}
                                </div>
                            </div>

                            <!-- Purchase Price (For Purchase & Return) -->
                            <div v-if="entryForm.entry_type == 0 || entryForm.entry_type == 1">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('purchase_price') }} (Dozen) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="entryForm.purchase_price"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="entryForm.errors.purchase_price" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.purchase_price }}
                                </div>
                            </div>

                            <!-- Retail Price (Optional for Purchase & Return) -->
                            <div v-if="entryForm.entry_type == 0 || entryForm.entry_type == 1">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('retail_price') }} (Retail)
                                </label>
                                <input
                                    v-model="entryForm.retail_price"
                                    type="number"
                                    step="0.01"
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="entryForm.errors.retail_price" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.retail_price }}
                                </div>
                            </div>

                            <!-- Payment Source/Method (For Payment) -->
                            <div v-if="entryForm.entry_type == 2" class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('payment_method') }}
                                </label>
                                <input
                                    v-model="entryForm.account_name"
                                    type="text"
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="e.g. Cash, Bank Account Name"
                                />
                                <div v-if="entryForm.errors.account_name" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.account_name }}
                                </div>
                            </div>

                            <!-- Amount (For Payment, or Auto-Calculated for Purchase/Return) -->
                            <div>
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('amount') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="entryForm.total_amount"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :readonly="entryForm.entry_type == 0 || entryForm.entry_type == 1"
                                    :class="{'bg-slate-100 dark:bg-slate-800 cursor-not-allowed': entryForm.entry_type == 0 || entryForm.entry_type == 1}"
                                />
                                <div v-if="entryForm.errors.total_amount" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.total_amount }}
                                </div>
                            </div>

                            <!-- Created At / Date -->
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

                            <!-- Description/Note (For Payment only) -->
                            <div v-if="entryForm.entry_type == 2" class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-750 dark:text-slate-350 mb-1">
                                    {{ t('description') }}
                                </label>
                                <textarea
                                    v-model="entryForm.description"
                                    rows="2"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('description')"
                                ></textarea>
                                <div v-if="entryForm.errors.description" class="text-red-500 text-xs mt-1">
                                    {{ entryForm.errors.description }}
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
