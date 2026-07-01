<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const props = defineProps({
    retailStore: {
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
            'due_books': 'Retailer Book',
            'details': 'Details',
            'calculation': 'Calculation',
            'total_product': 'Total Products',
            'pair': 'Pair',
            'total_selling_price': 'Total Selling Price',
            'commission_free_products': 'Excluding Commission',
            'other_expenses': 'Other Expenses',
            'discount': 'Discount',
            'transport_amount': 'Transport',
            'returns': 'Returns',
            'total_bill': 'Total Bill',
            'paid_amount': 'Paid Amount',
            'commission': 'Commission',
            'staff_expences': 'Staff Expenses',
            'total_due': 'Total Due',
            'filter': 'Filter',
            'commitment': 'Commitment',
            'closing': 'Closing',
            'dummy_closing': 'Dummy Closing',
            'taka': 'Taka',
            'save': 'Save',
            'clear': 'Clear Filter',
            'date': 'Date',
            'memo_no': 'Memo No',
            'returned amount': 'Returned Amount',
            'expense_description': 'Expense Description',
            'price excluding commission': 'Excluding Commission',
            'balance': 'Balance',
            'sl': 'SL',
            'No records found.': 'No records found.',
            'delete history': 'Deleted Entries History',
            'restore': 'Restore',
            'force_delete': 'Delete Permanently',
            'Are you sure?': 'Are you sure?',
            'description': 'Description',
            'amount': 'Amount',
            'start_date': 'Start Date',
            'end_date': 'End Date',
            'all_dues_paid': 'All balances on this account have been paid.',
            'carry_forward_msg': 'The total balance of this ledger is added to the next ledger.',
            'not_paid_msg': 'If the total balance of this account is not paid on time, it will be added to the next account.',
            'change': 'Change Carry Forward',
        },
        bn: {
            'due_books': 'বাকি খাতা',
            'details': 'বিবরণ',
            'calculation': 'হিসাব সারসংক্ষেপ',
            'total_product': 'মোট জোড়া সংখ্যা',
            'pair': 'জোড়া',
            'total_selling_price': 'মোট বিক্রয়মূল্য',
            'commission_free_products': 'কমিশন বাদে মূল্য',
            'other_expenses': 'অন্যান্য খরচ',
            'discount': 'ডিসকাউন্ট',
            'transport_amount': 'পরিবহন খরচ',
            'returns': 'ফেরত',
            'total_bill': 'মোট বিল',
            'paid_amount': 'পরিশোধিত টাকা',
            'commission': 'কমিশন',
            'staff_expences': 'স্টাফ খরচ',
            'total_due': 'মোট বাকি',
            'filter': 'ফিল্টার',
            'commitment': 'অঙ্গীকার',
            'closing': 'ক্লোজিং',
            'dummy_closing': 'ডামি ক্লোজিং',
            'taka': 'টাকা',
            'save': 'সংরক্ষণ করুন',
            'clear': 'ফিল্টার পরিষ্কার',
            'date': 'তারিখ',
            'memo_no': 'মেমো নং',
            'returned amount': 'ফেরত টাকা',
            'expense_description': 'খরচের বিবরণ',
            'price excluding commission': 'কমিশন বাদে মূল্য',
            'balance': 'জের',
            'sl': 'ক্রমিক',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'delete history': 'মুছে ফেলা এন্ট্রি হিস্টোরি',
            'restore': 'পুনরুদ্ধার',
            'force_delete': 'স্থায়ীভাবে মুছুন',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'description': 'বিবরণ',
            'amount': 'টাকার পরিমাণ',
            'start_date': 'শুরুর তারিখ',
            'end_date': 'শেষের তারিখ',
            'all_dues_paid': 'এই হিসাবের সমস্ত জের পরিশোধ করা হয়েছে।',
            'carry_forward_msg': 'এই খাতার মোট জের পরবর্তী খাতায় যুক্ত করা হবে।',
            'not_paid_msg': 'এই হিসাবের মোট জের সময়মত পরিশোধ করা না হলে তা পরবর্তী হিসাবে যোগ হবে।',
            'change': 'জের পরিবর্তন করুন',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const filterStartDate = ref(props.filters?.start_date || '');
const filterEndDate = ref(props.filters?.end_date || '');
const filterDesc = ref(props.filters?.description || '');
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
            start_date: filterStartDate.value,
            end_date: filterEndDate.value,
            description: filterDesc.value,
            amount: filterAmount.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch([filterStartDate, filterEndDate, filterDesc, filterAmount], () => runFilters(400));

const clearFilter = () => {
    filterStartDate.value = '';
    filterEndDate.value = '';
    filterDesc.value = '';
    filterAmount.value = '';
    router.get(route('account-book.show', props.accountBook.id), {}, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    router.get(route('account-book.show', props.accountBook.id), {
        start_date: filterStartDate.value,
        end_date: filterEndDate.value,
        description: filterDesc.value,
        amount: filterAmount.value,
        page: pageNumber
    }, {
        preserveState: true
    });
};

// Expense creation Form
const expenseForm = useForm({
    account_book_id: props.accountBook.id,
    description: '',
    amount: '',
});

const submitExpense = () => {
    expenseForm.post(route('retail-store-expense.store'), {
        onSuccess: () => {
            expenseForm.reset();
        }
    });
};

// Carry forward balance change
const toggleCarryForward = () => {
    if (confirm(t('Are you sure?'))) {
        router.get(route('account-book.forward-balance', { accountBook: props.accountBook.id }), {}, {
            preserveScroll: true
        });
    }
};

const deleteExpense = (id) => {
    if (confirm(t('Are you sure?'))) {
        router.delete(route('retail-store-expense.delete', id), {
            preserveScroll: true
        });
    }
};

// Soft delete / Restore entries
const restoreEntry = (id) => {
    router.post(route('retail-store-expense.restore', id), {}, {
        preserveScroll: true
    });
};

const forceDeleteEntry = (id) => {
    if (confirm(t('Are you sure?'))) {
        router.delete(route('retail-store-expense.forceDelete', id), {
            preserveScroll: true
        });
    }
};

const isType = (entry, typeName) => {
    if (!entry || entry.entry_type === undefined || entry.entry_type === null) return false;
    const typeMap = {
        'Invoice': 0,
        'Return': 1,
        'Expence': 2,
        'Payment': 3,
        'ClosingPayment': 5
    };
    const targetVal = typeMap[typeName];
    if (entry.entry_type === targetVal || entry.entry_type === typeName) return true;
    if (entry.entry_type && (entry.entry_type.value === targetVal || entry.entry_type.name === typeName)) return true;
    return false;
};

const formatTaka = (value) => {
    const val = parseFloat(value);
    return isNaN(val) ? '0.00' : val.toFixed(2);
};

const paidPercentage = computed(() => {
    const totalBill = parseFloat(props.summary.total_sales) + parseFloat(props.summary.total_expenses) - parseFloat(props.summary.total_returns);
    if (totalBill <= 0) return 0;
    return (parseFloat(props.summary.total_payments) / totalBill) * 100;
});
</script>

<template>
    <Head :title="t('due_books') + ' - ' + retailStore.shop_name" />

    <AuthenticatedLayout>
        <!-- Main Wrapper -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8">
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-6">
                {{ t('due_books') }}
            </h1>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Store Info -->
                <div class="border border-slate-200 dark:border-slate-700/80 rounded p-5 bg-slate-50/50 dark:bg-slate-900/30">
                    <h5 class="text-lg font-bold text-blue-600 dark:text-blue-400 mb-3 flex items-center gap-2">
                        <i class="fa fa-store"></i> {{ t('details') }}
                    </h5>
                    <div class="space-y-1.5 text-slate-750 dark:text-slate-250">
                        <div class="font-bold text-[16px]">{{ retailStore.shop_name }}</div>
                        <div class="text-sm text-slate-500 dark:text-slate-400">{{ retailStore.address }}</div>
                        <div class="text-sm font-medium"><i class="fa fa-phone"></i> {{ retailStore.mobile_no }}</div>
                        <div class="text-sm font-medium"><i class="fa fa-calendar"></i> {{ summary.description }}</div>
                        <div class="inline-block bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 text-xs px-2.5 py-1 rounded font-semibold mt-1">
                            {{ t('commission') }}: {{ retailStore.commission }}%
                        </div>
                    </div>
                </div>

                <!-- Calculation Summary -->
                <div class="border border-slate-200 dark:border-slate-700/80 rounded p-5 bg-slate-50/50 dark:bg-slate-900/30">
                    <h5 class="text-lg font-bold text-green-600 dark:text-green-400 mb-3 flex items-center gap-2">
                        <i class="fa fa-calculator"></i> {{ t('calculation') }}
                    </h5>
                    <div class="space-y-2 text-sm text-slate-750 dark:text-slate-250">
                        <div class="flex justify-between">
                            <span>{{ t('total_product') }}:</span>
                            <span class="font-bold">{{ summary.total_pairs }} {{ t('pair') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ t('total_selling_price') }}:</span>
                            <span class="font-bold">{{ formatTaka(summary.total_sales) }} ৳</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ t('other_expenses') }}:</span>
                            <span class="font-bold text-red-500">-{{ formatTaka(summary.total_expenses) }} ৳</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ t('returns') }} ({{ summary.total_return_pairs }} {{ t('pair') }}):</span>
                            <span class="font-bold text-red-500">-{{ formatTaka(summary.total_returns) }} ৳</span>
                        </div>
                        <hr class="border-slate-200 dark:border-slate-700 my-1" />
                        <div class="flex justify-between">
                            <span>{{ t('paid_amount') }}:</span>
                            <span class="font-bold text-green-600">
                                <span class="bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 text-xs px-1.5 py-0.5 rounded font-semibold mr-1">
                                    {{ formatTaka(paidPercentage) }}%
                                </span>
                                {{ formatTaka(summary.total_payments) }} ৳
                            </span>
                        </div>
                        <div class="flex justify-between text-[16px] font-bold text-red-600 dark:text-red-400 mt-2">
                            <span>{{ t('total_due') }}:</span>
                            <span>{{ formatTaka(summary.description_balance) }} ৳</span>
                        </div>
                    </div>
                </div>

                <!-- Action Button Group -->
                <div class="border border-slate-200 dark:border-slate-700/80 rounded p-5 bg-slate-50/50 dark:bg-slate-900/30 flex flex-col justify-center gap-3">
                    <button
                        @click="toggleFilter"
                        class="border border-blue-500 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 py-2 rounded text-sm font-semibold transition cursor-pointer flex items-center justify-center gap-2"
                    >
                        <i class="fa fa-filter"></i> {{ t('filter') }}
                    </button>
                    <!-- We only render the closing buttons if account book is open -->
                    <template v-if="accountBook.open">
                        <Link
                            :href="route('account-book.closing', accountBook.id)"
                            class="bg-[#28a745] hover:bg-[#218838] text-white py-2 rounded text-sm font-semibold transition text-center shadow-sm cursor-pointer flex items-center justify-center gap-2"
                        >
                            <i class="fa fa-check-circle"></i> {{ t('closing') }}
                        </Link>
                        <Link
                            :href="route('account-book.closing', accountBook.id) + '?dummy=1'"
                            class="border border-[#28a745] text-[#28a745] hover:bg-green-50 dark:hover:bg-green-900/20 py-2 rounded text-sm font-semibold transition text-center shadow-sm cursor-pointer flex items-center justify-center gap-2"
                        >
                            <i class="fa fa-file-invoice"></i> {{ t('dummy_closing') }}
                        </Link>
                    </template>
                </div>
            </div>

            <!-- Expense Form (Rendered only if Ledger Book is Open) -->
            <div v-if="accountBook.open" class="mb-6">
                <form @submit.prevent="submitExpense" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:w-[45%]">
                        <label for="desc" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('other_expenses') }}
                        </label>
                        <input
                            id="desc"
                            v-model="expenseForm.description"
                            type="text"
                            required
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                    <div class="w-full md:w-[22%]">
                        <label for="amt" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('taka') }}
                        </label>
                        <input
                            id="amt"
                            v-model="expenseForm.amount"
                            type="number"
                            step="0.01"
                            required
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                    <div class="w-full md:w-[22%]">
                        <button
                            type="submit"
                            :disabled="expenseForm.processing"
                            class="w-full h-10 bg-blue-600 hover:bg-blue-750 text-white rounded text-sm font-bold transition cursor-pointer disabled:opacity-50"
                        >
                            {{ t('save') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Closed Ledger Alert Box -->
            <div v-else class="border border-slate-200 dark:border-slate-700/85 bg-slate-50/50 dark:bg-slate-900/30 rounded p-5 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="text-sm font-semibold text-slate-750 dark:text-slate-250">
                    <span v-if="summary.description_balance == 0">
                        {{ t('all_dues_paid') }}
                    </span>
                    <span v-else-if="accountBook.balance_carry_forward">
                        {{ t('carry_forward_msg') }}
                    </span>
                    <span v-else>
                        {{ t('not_paid_msg') }}
                    </span>
                </div>
                <button
                    v-if="summary.description_balance > 0"
                    @click="toggleCarryForward"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-xs font-bold transition shadow-sm cursor-pointer"
                >
                    {{ t('change') }}
                </button>
            </div>

            <!-- Collapsible Filters -->
            <div v-show="!isFilterCollapsed" class="p-6 border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30 rounded mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label for="filter-start" class="block text-xs font-semibold text-slate-500 mb-1">{{ t('start_date') }}</label>
                        <input
                            id="filter-start"
                            v-model="filterStartDate"
                            type="date"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label for="filter-end" class="block text-xs font-semibold text-slate-500 mb-1">{{ t('end_date') }}</label>
                        <input
                            id="filter-end"
                            v-model="filterEndDate"
                            type="date"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div class="md:col-span-2">
                        <label for="filter-desc" class="block text-xs font-semibold text-slate-500 mb-1">{{ t('description') }}</label>
                        <input
                            id="filter-desc"
                            v-model="filterDesc"
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

            <!-- Ledger Table -->
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-center text-sm border-collapse border border-slate-200 dark:border-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">#</th>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('date') }}</th>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('memo_no') }}</th>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('pair') }}</th>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('returns') }}</th>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('returned amount') }}</th>
                            <th colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('other_expenses') }}</th>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('total_selling_price') }}</th>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('price excluding commission') }}</th>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('paid_amount') }}</th>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('total_bill') }}</th>
                            <th rowspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle">{{ t('balance') }}</th>
                        </tr>
                        <tr>
                            <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle font-bold">{{ t('expense_description') }}</th>
                            <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 align-middle font-bold">{{ t('taka') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(entry, index) in entries.data"
                            :key="entry.id"
                            class="border-b border-slate-200 dark:border-slate-700 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                {{ (entries.current_page - 1) * entries.per_page + index + 1 }}
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                {{ entry.formatted_created_at }}
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                <span v-if="entry.invoice_id">
                                    <a :href="route().has('invoice.show') ? route('invoice.show', entry.invoice_id) : '#'" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ entry.invoice_id }}
                                    </a>
                                </span>
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-bold">
                                {{ isType(entry, 'Invoice') ? entry.count : '' }}
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                {{ isType(entry, 'Return') ? entry.return_count : '' }}
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-bold text-red-500">
                                <span v-if="isType(entry, 'Return')">{{ formatTaka(entry.paid_amount) }}</span>
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-left">
                                <div class="flex items-center justify-between gap-1">
                                    <span>{{ entry.expense_description || '' }}</span>
                                    <button
                                        v-if="isType(entry, 'Expence') && entry.expense_id && accountBook.open"
                                        @click="deleteExpense(entry.expense_id)"
                                        class="text-red-500 hover:text-red-700 transition cursor-pointer"
                                        title="Delete Expense"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-bold text-red-500">
                                <span v-if="isType(entry, 'Expence')">{{ formatTaka(entry.amount) }}</span>
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-bold">
                                <span v-if="isType(entry, 'Invoice')">{{ formatTaka(entry.total_retail_price) }}</span>
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-bold">
                                <span v-if="isType(entry, 'Invoice')">{{ formatTaka(parseFloat(entry.total_retail_price) - parseFloat(entry.total_commission)) }}</span>
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-bold text-green-600">
                                <span v-if="isType(entry, 'Payment')">{{ formatTaka(entry.paid_amount) }}</span>
                                <span v-else-if="isType(entry, 'Invoice') && entry.paid_amount > 0">{{ formatTaka(entry.paid_amount) }}</span>
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-bold">
                                {{ formatTaka(entry.amount) }}
                            </td>
                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-bold text-slate-850 dark:text-white">
                                {{ formatTaka(entry.running_balance) }}
                            </td>
                        </tr>
                        <tr v-if="entries.data.length === 0">
                            <td colspan="13" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No records found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mb-8">
                <Pagination :pagination="entries" @page-changed="goToPage" />
            </div>

            <!-- Trashed Entries Bin -->
            <div v-if="trashedEntries.data.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
                <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                    <h4 class="text-[17px] font-bold text-slate-800 dark:text-white">
                        {{ t('delete history') }}
                    </h4>
                </div>
                <div class="overflow-x-auto p-6 pt-0">
                    <table class="w-full text-center text-sm border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                            <tr>
                                <th class="px-3 py-2">{{ t('sl') }}</th>
                                <th class="px-3 py-2">{{ t('date') }}</th>
                                <th class="px-3 py-2">{{ t('description') }}</th>
                                <th class="px-3 py-2">{{ t('amount') }}</th>
                                <th class="px-3 py-2 w-56">{{ t('option') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in trashedEntries.data"
                                :key="item.id"
                                class="border-b border-slate-200 dark:border-slate-700 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200 text-center"
                            >
                                <td class="px-3 py-2 font-medium">{{ index + 1 }}</td>
                                <td class="px-3 py-2 font-medium">{{ item.formatted_created_at }}</td>
                                <td class="px-3 py-2 font-medium">{{ item.expense_description || '—' }}</td>
                                <td class="px-3 py-2 font-medium font-bold text-red-500">
                                    {{ formatTaka(parseFloat(item.amount) || parseFloat(item.paid_amount)) }}
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button
                                            @click="restoreEntry(item.expense_id)"
                                            class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm cursor-pointer"
                                        >
                                            <i class="fa fa-undo text-xs"></i>
                                            <span>{{ t('restore') }}</span>
                                        </button>
                                        <button
                                            @click="forceDeleteEntry(item.expense_id)"
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
        </div>
    </AuthenticatedLayout>
</template>
