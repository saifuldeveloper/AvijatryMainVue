<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps({
    giftSupplier: {
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
            'gift_supplier_book': 'Gift Supplier Book',
            'date': 'Date',
            'details': 'Details / Memo',
            'quantity': 'Quantity',
            'unit_price': 'Unit Price',
            'total_price': 'Total Price',
            'payment': 'Payment',
            'running_balance': 'Running Balance',
            'clear': 'Clear Filter',
            'filter': 'Filter',
            'total': 'Total',
            'Are you sure?': 'Are you sure?',
            'No records found.': 'No records found.',
            'back': 'Back to Profile',
            'start_date': 'Start Date',
            'end_date': 'End Date',
            'description': 'Description / Note',
            'amount': 'Amount',
            'save': 'Save',
            'processing': 'Processing...',
            'entry_type': 'Entry Type',
            'purchase': 'Purchase',
            'summary': 'Period Summary',
            'opening_balance': 'Opening Balance',
            'total_purchases': 'Total Purchases',
            'total_payments': 'Total Payments',
            'current_balance': 'Current Balance',
            'calculation': 'Calculation',
            'total_pair': 'Total Quantity',
            'total_product': 'Total Product Worth',
            'total_closing_payment': 'Total Closing Payment',
            'total_debt': 'Total Debt',
            'dummy_closing': 'Dummy Closing',
            'closing': 'Closing',
            'memo': 'Memo',
            'type': 'Type',
            'balance': 'Balance',
            'old': 'Opening Balance',
            'gift': 'Gift',
            'gift_supplier_payment': 'Gift Supplier Payment',
        },
        bn: {
            'gift_supplier_book': 'গিফট মহাজন খাতা',
            'date': 'তারিখ',
            'details': 'বিবরণ / খাত',
            'quantity': 'পরিমাণ',
            'unit_price': 'দর',
            'total_price': 'মোট বিল',
            'payment': 'পরিশোধ',
            'running_balance': 'জের (Running Balance)',
            'clear': 'ফিল্টার পরিষ্কার',
            'filter': 'ফিল্টার',
            'total': 'মোট',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'back': 'মহাজন প্রোফাইলে ফিরুন',
            'start_date': 'শুরুর তারিখ',
            'end_date': 'শেষের তারিখ',
            'description': 'বিবরণ / নোট',
            'amount': 'টাকার পরিমাণ',
            'save': 'সংরক্ষণ',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
            'entry_type': 'এন্ট্রির ধরণ',
            'purchase': 'ক্রয়',
            'summary': 'হিসাব সারসংক্ষেপ',
            'opening_balance': 'সাবেক (Opening Balance)',
            'total_purchases': 'মোট ক্রয়',
            'total_payments': 'মোট পরিশোধ',
            'current_balance': 'বর্তমান জের',
            'calculation': 'হিসাব',
            'total_pair': 'মোট পরিমাণ',
            'total_product': 'মোট মাল',
            'total_closing_payment': 'মোট ক্লোজিং পরিশোধ',
            'total_debt': 'মোট দেনা',
            'dummy_closing': 'ডামি ক্লোজিং',
            'closing': 'ক্লোজিং',
            'memo': 'মেমো',
            'type': 'টাইপ',
            'balance': 'ব্যালেন্স',
            'old': 'সাবেক',
            'gift': 'গিফট',
            'gift_supplier_payment': 'গিফট মহাজন পরিশোধ',
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

// Flatpickr Element References
const startDateRef = ref(null);
const endDateRef = ref(null);

let fpStart, fpEnd;

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
</script>

<template>
    <Head :title="t('gift_supplier_book') + ' - ' + giftSupplier.name" />

    <AuthenticatedLayout>
        <!-- Main Card Container wrapping all contents -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8">
            
            <!-- Breadcrumbs / Title Row -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                        {{ t('gift_supplier_book') }}
                    </h1>
                </div>
                <div>
                    <Link
                        :href="route('gift-supplier.show', giftSupplier.id)"
                        class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer"
                    >
                        {{ t('back') }}
                    </Link>
                </div>
            </div>

            <!-- Calculation & Info Panels -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
                
                <!-- Supplier Info -->
                <div class="md:col-span-4 border border-slate-200 dark:border-slate-700 rounded p-6 bg-slate-50/50 dark:bg-slate-900/30">
                    <h5 class="text-blue-600 dark:text-blue-400 font-bold text-lg mb-4 flex items-center gap-2">
                        <i class="fa fa-user"></i> Profile Info
                    </h5>
                    <div class="space-y-2 text-[15px] text-slate-700 dark:text-slate-350">
                        <div>Name: <strong class="text-slate-900 dark:text-white">{{ giftSupplier.name }}</strong></div>
                        <div>Address: <strong class="text-slate-900 dark:text-white">{{ giftSupplier.address || '-' }}</strong></div>
                        <div>Mobile: <strong class="text-slate-900 dark:text-white">{{ giftSupplier.mobile_no || '-' }}</strong></div>
                        <div>Period: <strong class="text-slate-900 dark:text-white">{{ summary.description }}</strong></div>
                    </div>
                </div>

                <!-- Calculations -->
                <div class="md:col-span-5 border border-slate-200 dark:border-slate-700 rounded p-6 bg-slate-50/50 dark:bg-slate-900/30">
                    <h5 class="text-green-600 dark:text-green-400 font-bold text-lg mb-4 flex items-center gap-2">
                        <i class="fa fa-calculator"></i> {{ t('calculation') }}
                    </h5>
                    <div class="space-y-2 text-[15px] text-slate-700 dark:text-slate-350">
                        <div class="flex justify-between">
                            <span>{{ t('total_pair') }}:</span>
                            <strong class="text-slate-900 dark:text-white">{{ summary.total_pairs }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ t('total_product') }}:</span>
                            <strong class="text-slate-900 dark:text-white">{{ parseFloat(summary.total_purchases).toFixed(2) }} ৳</strong>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>{{ t('payment') }}:</span>
                            <div class="flex items-center gap-1.5">
                                <span :class="['px-2 py-0.5 rounded text-xs font-bold text-white', summary.paid_percentage >= 100 ? 'bg-green-600' : 'bg-yellow-500']">
                                    {{ parseFloat(summary.paid_percentage).toFixed(2) }}%
                                </span>
                                <strong class="text-slate-900 dark:text-white">{{ parseFloat(summary.total_payments).toFixed(2) }} ৳</strong>
                            </div>
                        </div>

                        <!-- Commission / Staff Expense if closed -->
                        <div v-if="!accountBook.open && accountBook.commission > 0" class="flex justify-between items-center">
                            <span>{{ t('commission') }}:</span>
                            <strong class="text-slate-900 dark:text-white">{{ parseFloat(accountBook.commission).toFixed(2) }} ৳</strong>
                        </div>
                        <div v-if="!accountBook.open && accountBook.staff > 0" class="flex justify-between items-center">
                            <span>Staff Expenses:</span>
                            <strong class="text-slate-900 dark:text-white">{{ parseFloat(accountBook.staff).toFixed(2) }} ৳</strong>
                        </div>

                        <div v-if="summary.total_closing_payment > 0" class="flex justify-between">
                            <span>{{ t('total_closing_payment') }}:</span>
                            <strong class="text-slate-900 dark:text-white">{{ parseFloat(summary.total_closing_payment).toFixed(2) }} ৳</strong>
                        </div>
                        <hr class="border-slate-200 dark:border-slate-700" />
                        <div class="flex justify-between text-base">
                            <span>{{ t('total_debt') }}:</span>
                            <strong class="text-red-600 dark:text-red-400 font-bold">{{ parseFloat(summary.current_balance).toFixed(2) }} ৳</strong>
                        </div>
                    </div>
                </div>

                <!-- Actions Block -->
                <div class="md:col-span-3 border border-slate-200 dark:border-slate-700 rounded p-6 bg-slate-50/50 dark:bg-slate-900/30 flex flex-col justify-center gap-3">
                    <button
                        @click="toggleFilter"
                        class="bg-[#007bff] hover:bg-[#0069d9] text-white px-4 py-2 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer w-full text-center"
                    >
                        <i class="fa fa-filter"></i> {{ t('filter') }}
                    </button>
                    
                    <Link
                        v-if="accountBook.open && $page.props.auth.permissions.includes('factory closing')"
                        :href="route('account-book.closing-page', accountBook.id)"
                        class="bg-[#28a745] hover:bg-[#218838] text-white px-4 py-2 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer w-full text-center"
                    >
                        <i class="fa fa-check-circle"></i> {{ t('closing') }}
                    </Link>

                    <Link
                        v-if="accountBook.open && $page.props.auth.permissions.includes('factory dummy closing')"
                        :href="route('account-book.closing-page', accountBook.id) + '?dummy=1'"
                        class="border border-[#28a745] hover:bg-green-50 dark:hover:bg-green-950/20 text-[#28a745] px-4 py-2 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer w-full text-center"
                    >
                        <i class="fa fa-file-invoice"></i> {{ t('dummy_closing') }}
                    </Link>
                </div>
            </div>

            <!-- Collapsible Filters -->
            <div v-show="!isFilterCollapsed" class="p-6 border border-slate-200 dark:border-slate-700 rounded bg-slate-50/50 dark:bg-slate-900/30 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('start_date') }}
                        </label>
                        <input
                            ref="startDateRef"
                            v-model="startDate"
                            type="text"
                            placeholder="Select date"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('end_date') }}
                        </label>
                        <input
                            ref="endDateRef"
                            v-model="endDate"
                            type="text"
                            placeholder="Select date"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ t('description') }}
                        </label>
                        <input
                            v-model="filterDescription"
                            type="text"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div class="flex gap-2">
                        <button
                            @click="clearFilter"
                            class="bg-[#6c757d] hover:bg-[#5a6268] text-white h-10 px-4 rounded text-sm font-semibold transition duration-150 shadow-sm flex items-center justify-center cursor-pointer flex-1"
                        >
                            {{ t('clear') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Ledgers Table -->
            <div class="border border-slate-200 dark:border-slate-700 rounded overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[14px] border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                            <tr>
                                <th class="px-4 py-2.5 w-[12%]">{{ t('date') }}</th>
                                <th class="px-4 py-2.5 w-[12%]">{{ t('type') }}</th>
                                <th class="px-4 py-2.5 w-[20%]">{{ t('details') }}</th>
                                <th class="px-4 py-2.5 w-[10%] text-center">{{ t('quantity') }}</th>
                                <th class="px-4 py-2.5 w-[12%] text-right">{{ t('unit_price') }}</th>
                                <th class="px-4 py-2.5 w-[12%] text-right">{{ t('total_price') }}</th>
                                <th class="px-4 py-2.5 w-[12%] text-right">{{ t('payment') }}</th>
                                <th class="px-4 py-2.5 w-[12%] text-right">{{ t('running_balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Opening Balance Row -->
                            <tr class="bg-slate-100/50 dark:bg-slate-900/60 font-semibold text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700">
                                <td class="px-4 py-3">{{ accountBook.formatted_created_at || new Date(accountBook.created_at).toLocaleDateString('en-GB') }}</td>
                                <td class="px-4 py-3">{{ t('old') }}</td>
                                <td class="px-4 py-3" colspan="5">Jer Forwarded</td>
                                <td class="px-4 py-3 text-right text-slate-950 dark:text-white">{{ parseFloat(summary.opening_balance).toFixed(2) }}</td>
                            </tr>

                            <!-- Data Rows -->
                            <tr
                                v-for="entry in entries.data"
                                :key="entry.id"
                                class="border-b border-slate-200 dark:border-slate-700 odd:bg-white even:bg-slate-50/50 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                            >
                                <td class="px-4 py-3">{{ entry.formatted_created_at }}</td>
                                <td class="px-4 py-3">
                                    <span v-if="entry.entry_type === 0" class="text-blue-600 dark:text-blue-400 font-semibold">{{ t('purchase') }}</span>
                                    <span v-else-if="entry.entry_type === 2" class="text-green-600 dark:text-green-400 font-semibold">{{ t('payment') }}</span>
                                    <span v-else-if="entry.entry_type === 5" class="text-purple-600 dark:text-purple-400 font-semibold">{{ t('closing') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <template v-if="entry.gift_purchase_id">
                                        <Link :href="route('gift-purchase.show', entry.gift_purchase_id)" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                                            Memo #{{ entry.gift_purchase_id }}
                                        </Link>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                            {{ entry.gift_name }}
                                        </div>
                                    </template>
                                    <template v-else>
                                        {{ entry.description || (entry.entry_type === 5 ? t('gift_supplier_payment') : '-') }}
                                        <div v-if="entry.account_name" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                            Source: {{ entry.account_name }}
                                        </div>
                                    </template>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ entry.entry_type === 0 ? entry.count : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    {{ entry.entry_type === 0 ? parseFloat(entry.unit_price).toFixed(2) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    {{ entry.entry_type === 0 ? parseFloat(entry.total_amount).toFixed(2) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-green-700 dark:text-green-400">
                                    {{ entry.payment_amount > 0 ? parseFloat(entry.payment_amount).toFixed(2) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold">
                                    {{ parseFloat(entry.running_balance).toFixed(2) }}
                                </td>
                            </tr>

                            <tr v-if="entries.data.length === 0">
                                <td colspan="8" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                    {{ t('No records found.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/30">
                    <div class="text-sm text-slate-600 dark:text-slate-400">
                        Showing {{ entries.from || 0 }} to {{ entries.to || 0 }} of {{ entries.total || 0 }} entries
                    </div>
                    <Pagination :links="entries.links" @page-change="goToPage" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
