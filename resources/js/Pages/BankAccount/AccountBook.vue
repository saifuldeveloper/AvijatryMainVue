<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps({
    bankAccount: {
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
    filters: {
        type: Object,
        default: () => ({ start_date: '', end_date: '', description: '', deposit: '', withdraw: '' }),
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
            'bank_book': 'Bank Statement / Ledger',
            'date': 'Date',
            'expense_description': 'Description',
            'withdraw': 'Withdraw (Debit)',
            'deposit': 'Deposit (Credit)',
            'balance': 'Running Balance',
            'clear': 'Clear Filter',
            'filter': 'Filter',
            'total': 'Total',
            'Are you sure?': 'This action corrects the transaction link. Are you sure you want to proceed?',
            'No records found.': 'No records found.',
            'back': 'Back to closing periods',
            'start_date': 'Start Date',
            'end_date': 'End Date',
            'description': 'Description / Note',
            'amount': 'Amount',
            'save': 'Save',
            'processing': 'Processing...',
            'summary': 'Period Summary',
            'opening_balance': 'Opening Balance',
            'closing': 'Closing',
            'old': 'Opening Balance',
        },
        bn: {
            'bank_book': 'ব্যাংক হিসাব বিবরণী / লেজার',
            'date': 'তারিখ',
            'expense_description': 'বিবরণ',
            'withdraw': 'উত্তোলন (ডেবিট)',
            'deposit': 'জমা (ক্রেডিট)',
            'balance': 'জের (Running Balance)',
            'clear': 'ফিল্টার পরিষ্কার',
            'filter': 'ফিল্টার',
            'total': 'মোট',
            'Are you sure?': 'এই কাজটি ট্রানজেকশন লিংক সংশোধন করবে। আপনি কি নিশ্চিত?',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'back': 'পূর্ববর্তী পেইজে যান',
            'start_date': 'শুরুর তারিখ',
            'end_date': 'শেষের তারিখ',
            'description': 'বিবরণ / নোট',
            'amount': 'টাকার পরিমাণ',
            'save': 'সংরক্ষণ',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
            'summary': 'সারসংক্ষেপ',
            'opening_balance': 'সাবেক (Opening Balance)',
            'closing': 'ক্লোজিং',
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
const filterDeposit = ref(props.filters?.deposit || '');
const filterWithdraw = ref(props.filters?.withdraw || '');
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
            deposit: filterDeposit.value,
            withdraw: filterWithdraw.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch([filterDescription, filterDeposit, filterWithdraw], () => runFilters(400));
watch([startDate, endDate], () => {
    if (startDate.value && endDate.value) {
        runFilters(0);
    }
});

const clearFilter = () => {
    startDate.value = '';
    endDate.value = '';
    filterDescription.value = '';
    filterDeposit.value = '';
    filterWithdraw.value = '';
    
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
        deposit: filterDeposit.value,
        withdraw: filterWithdraw.value,
        page: pageNumber
    }, {
        preserveState: true
    });
};

// Flatpickr References
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
    <Head :title="t('bank_book') + ' - ' + bankAccount.bank" />

    <AuthenticatedLayout>
        <!-- Main Card Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8">
            
            <!-- Breadcrumbs / Title Row -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                        {{ t('bank_book') }}
                    </h1>
                </div>
                <div>
                    <Link
                        :href="route('bank-account.show', bankAccount.id)"
                        class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer"
                    >
                        {{ t('back') }}
                    </Link>
                </div>
            </div>

            <!-- Calculation & Info Panels -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
                
                <!-- Account Info -->
                <div class="md:col-span-4 border border-slate-200 dark:border-slate-700 rounded p-6 bg-slate-50/50 dark:bg-slate-900/30">
                    <h5 class="text-blue-600 dark:text-blue-400 font-bold text-lg mb-4 flex items-center gap-2">
                        <i class="fa fa-university"></i> Account Info
                    </h5>
                    <div class="space-y-2 text-[15px] text-slate-700 dark:text-slate-200">
                        <div v-if="bankAccount.account_no === 'cash'">
                            Name: <strong class="text-slate-900 dark:text-white">{{ bankAccount.bank }}</strong>
                        </div>
                        <div v-else>
                            <div>Bank: <strong class="text-slate-900 dark:text-white">{{ bankAccount.bank }}</strong></div>
                            <div>Branch: <strong class="text-slate-900 dark:text-white">{{ bankAccount.branch }}</strong></div>
                            <div>Account No: <strong class="text-slate-900 dark:text-white">{{ bankAccount.account_no }}</strong></div>
                        </div>
                        <div>Period: <strong class="text-slate-900 dark:text-white">{{ summary.description }}</strong></div>
                    </div>
                </div>

                <!-- Calculations -->
                <div class="md:col-span-5 border border-slate-200 dark:border-slate-700 rounded p-6 bg-slate-50/50 dark:bg-slate-900/30">
                    <h5 class="text-green-600 dark:text-green-400 font-bold text-lg mb-4 flex items-center gap-2">
                        <i class="fa fa-calculator"></i> {{ t('summary') }}
                    </h5>
                    <div class="space-y-2 text-[15px] text-slate-700 dark:text-slate-200">
                        <div class="flex justify-between">
                            <span>{{ t('opening_balance') }}:</span>
                            <strong class="text-slate-900 dark:text-white">{{ parseFloat(summary.opening_balance).toFixed(2) }} ৳</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Deposits:</span>
                            <strong class="text-green-600 dark:text-green-400 font-bold">+{{ parseFloat(summary.total_deposit).toFixed(2) }} ৳</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Withdraws:</span>
                            <strong class="text-red-600 dark:text-red-400 font-bold">-{{ parseFloat(summary.total_expense).toFixed(2) }} ৳</strong>
                        </div>
                        <hr class="border-slate-200 dark:border-slate-700" />
                        <div class="flex justify-between text-base">
                            <span>Current Balance:</span>
                            <strong class="text-slate-950 dark:text-white font-black">{{ parseFloat(summary.current_balance).toFixed(2) }} ৳</strong>
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
                        v-if="accountBook.open"
                        :href="route('account-book.closing', accountBook.id)"
                        class="bg-[#28a745] hover:bg-[#218838] text-white px-4 py-2 rounded text-sm font-semibold transition duration-150 shadow-sm cursor-pointer w-full text-center flex items-center justify-center gap-1.5"
                    >
                        <i class="fa fa-check-circle"></i> {{ t('closing') }}
                    </Link>
                </div>
            </div>

            <!-- Collapsible Filters -->
            <div v-show="!isFilterCollapsed" class="p-6 border border-slate-200 dark:border-slate-700 rounded bg-slate-50/50 dark:bg-slate-900/30 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            {{ t('start_date') }}
                        </label>
                        <input
                            ref="startDateRef"
                            v-model="startDate"
                            type="text"
                            placeholder="Select date"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            {{ t('end_date') }}
                        </label>
                        <input
                            ref="endDateRef"
                            v-model="endDate"
                            type="text"
                            placeholder="Select date"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            {{ t('description') }}
                        </label>
                        <input
                            v-model="filterDescription"
                            type="text"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            {{ t('deposit') }}
                        </label>
                        <input
                            v-model="filterDeposit"
                            type="text"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                        />
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                                {{ t('withdraw') }}
                            </label>
                            <input
                                v-model="filterWithdraw"
                                type="text"
                                class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center"
                            />
                        </div>
                        <button
                            @click="clearFilter"
                            class="bg-[#6c757d] hover:bg-[#5a6268] text-white h-10 px-3 mt-auto rounded text-sm font-semibold transition duration-150 shadow-sm flex items-center justify-center cursor-pointer"
                        >
                            {{ t('clear') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statement Table -->
            <div class="border border-slate-200 dark:border-slate-700 rounded overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-center text-[14px] border-collapse border border-slate-300 dark:border-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                            <tr>
                                <th class="px-4 py-2.5 w-[5%] border border-slate-300 dark:border-slate-700 text-center">#</th>
                                <th class="px-4 py-2.5 w-[12%] border border-slate-300 dark:border-slate-700 text-center">{{ t('date') }}</th>
                                <th class="px-4 py-2.5 w-[43%] border border-slate-300 dark:border-slate-700 text-center">{{ t('expense_description') }}</th>
                                <th class="px-4 py-2.5 w-[13%] border border-slate-300 dark:border-slate-700 text-center">{{ t('withdraw') }}</th>
                                <th class="px-4 py-2.5 w-[13%] border border-slate-300 dark:border-slate-700 text-center">{{ t('deposit') }}</th>
                                <th class="px-4 py-2.5 w-[14%] border border-slate-300 dark:border-slate-700 text-center">{{ t('balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Opening Balance Row (Shown on last page if ordered desc, or first page if ordered asc) -->
                            <tr class="bg-slate-100/50 dark:bg-slate-900/60 font-semibold text-slate-750 dark:text-slate-300 border-b border-slate-300 dark:border-slate-700">
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700">-</td>
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700">{{ accountBook.formatted_created_at || new Date(accountBook.created_at).toLocaleDateString('en-GB') }}</td>
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700">{{ t('old') }}</td>
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700">-</td>
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700">-</td>
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-bold text-slate-950 dark:text-white">{{ parseFloat(summary.opening_balance).toFixed(2) }}</td>
                            </tr>

                            <!-- Data Rows -->
                            <tr
                                v-for="(entry, index) in entries.data"
                                :key="entry.id"
                                class="border-b border-slate-300 dark:border-slate-700 odd:bg-white even:bg-slate-50/50 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200 animate-fade-in"
                            >
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700">
                                    {{ (entries.current_page - 1) * entries.per_page + index + 1 }}
                                </td>
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700">{{ entry.formatted_created_at }}</td>
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700">
                                    {{ entry.rendered_description }}
                                </td>
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-semibold text-red-600 dark:text-red-400">
                                    {{ ['withdraw', 'expense'].includes(entry.type) ? parseFloat(entry.total_amount).toFixed(2) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-semibold text-green-700 dark:text-green-400">
                                    {{ ['deposit', 'income'].includes(entry.type) ? parseFloat(entry.total_amount).toFixed(2) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center border border-slate-300 dark:border-slate-700 font-bold text-slate-950 dark:text-white">
                                    {{ parseFloat(entry.running_balance).toFixed(2) }}
                                </td>
                            </tr>

                            <tr v-if="entries.data.length === 0">
                                <td colspan="6" class="px-6 py-10 text-center border border-slate-300 dark:border-slate-700 text-slate-500 dark:text-slate-400">
                                    {{ t('No records found.') }}
                                </td>
                            </tr>
                        </tbody>
                        <!-- Totals Footer -->
                        <tfoot class="bg-slate-50 dark:bg-slate-900/60 font-black border-t border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white">
                            <tr>
                                <td colspan="3" class="px-4 py-3.5 text-center border border-slate-300 dark:border-slate-700 font-bold">Total:</td>
                                <td class="px-4 py-3.5 text-center border border-slate-300 dark:border-slate-700 text-red-600 dark:text-red-400 font-bold">{{ parseFloat(summary.total_expense).toFixed(2) }}</td>
                                <td class="px-4 py-3.5 text-center border border-slate-300 dark:border-slate-700 text-green-600 dark:text-green-400 font-bold">{{ parseFloat(summary.total_deposit).toFixed(2) }}</td>
                                <td class="border border-slate-300 dark:border-slate-700"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <Pagination :pagination="entries" @page-changed="goToPage" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
