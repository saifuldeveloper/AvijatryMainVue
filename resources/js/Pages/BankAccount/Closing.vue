<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
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
    total_deposit: {
        type: Number,
        required: true,
    },
    total_expense: {
        type: Number,
        required: true,
    },
    currentBalance: {
        type: Number,
        required: true,
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
    const fallbacks = {
        en: {
            'bank_closing': 'Bank Account Closing',
            'name': 'Name',
            'branch': 'Branch',
            'account_no': 'Account No',
            'date': 'Period Date',
            'withdraw': 'Withdrawals (Expense)',
            'deposit': 'Deposits',
            'balance': 'Balance',
            'closing_date': 'Closing Date',
            'close_it': 'Close Account Book',
            'back': 'Cancel & Go Back',
            'processing': 'Processing...',
            'This ledger has been closed. Closing information cannot be edited.': 'This ledger has been closed. Closing information cannot be edited.',
        },
        bn: {
            'bank_closing': 'ব্যাংক হিসাব ক্লোজিং',
            'name': 'নাম',
            'branch': 'শাখা',
            'account_no': 'অ্যাকাউন্ট নং',
            'date': 'তারিখ',
            'withdraw': 'উত্তোলন (খরচ)',
            'deposit': 'জমা',
            'balance': 'ব্যালেন্স',
            'closing_date': 'ক্লোজিং এর তারিখ',
            'close_it': 'হিসাব ক্লোজ করুন',
            'back': 'বাতিল করুন',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
            'This ledger has been closed. Closing information cannot be edited.': 'এই হিসাব খাতাটি বন্ধ করা হয়েছে। ক্লোজিং তথ্য পরিবর্তন করা যাবে না।',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

const closingDateRef = ref(null);
let fpClosingDate;

const form = useForm({
    closing_date: props.accountBook.closing_date ? props.accountBook.closing_date.split(' ')[0] : new Date().toISOString().split('T')[0],
    closing_balance: props.currentBalance,
    balance_carry_forward: 1,
    closing_type: 'normal',
});

onMounted(() => {
    fpClosingDate = flatpickr(closingDateRef.value, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        defaultDate: form.closing_date,
        onChange: (selectedDates, dateStr) => {
            form.closing_date = dateStr;
        }
    });
});

const submitClosing = () => {
    form.post(route('account-book.closing-store', props.accountBook.id));
};
</script>

<template>
    <Head :title="t('bank_closing') + ' - ' + bankAccount.bank" />

    <AuthenticatedLayout>
        <!-- Back Button -->
        <div class="mb-4">
            <Link
                :href="route('account-book.show', accountBook.id)"
                class="bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition shadow-sm flex items-center gap-1.5 cursor-pointer w-fit"
            >
                <i class="fa fa-arrow-left"></i>
                <span>{{ t('back') }}</span>
            </Link>
        </div>

        <!-- Main Outer Card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8 p-6 max-w-4xl mx-auto">
            <!-- Header title -->
            <h1 class="text-2xl font-black text-slate-850 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-3">
                {{ t('bank_closing') }}
            </h1>

            <!-- Account Detail Box -->
            <div class="border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 p-4 rounded mb-6 text-sm text-slate-800 dark:text-slate-200 leading-relaxed">
                <div>{{ t('name') }}: <strong>{{ bankAccount.bank }}</strong></div>
                <div v-if="bankAccount.account_no !== 'cash'">
                    <div>{{ t('branch') }}: <strong>{{ bankAccount.branch }}</strong></div>
                    <div>{{ t('account_no') }}: <strong>{{ bankAccount.account_no }}</strong></div>
                </div>
                <div>{{ t('date') }}: <strong>{{ accountBook.description || '-' }}</strong></div>
            </div>

            <!-- Warning if closed -->
            <div v-if="!accountBook.open" class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-950/20 text-yellow-800 dark:text-yellow-350 border border-yellow-250 dark:border-yellow-900 rounded text-sm">
                {{ t('This ledger has been closed. Closing information cannot be edited.') }}
            </div>

            <form @submit.prevent="submitClosing">
                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    
                    <!-- Left: Withdrawals -->
                    <div class="border border-slate-200 dark:border-slate-700 rounded overflow-hidden">
                        <div class="bg-red-500 text-white px-4 py-3 font-bold text-base flex justify-between items-center">
                            <span>{{ t('withdraw') }}</span>
                            <span>Total</span>
                        </div>
                        <div class="p-6 text-center">
                            <div class="text-3xl font-black text-red-600 dark:text-red-400">
                                {{ parseFloat(total_expense).toFixed(2) }} ৳
                            </div>
                        </div>
                    </div>

                    <!-- Right: Deposits & Closing Balance -->
                    <div class="border border-slate-200 dark:border-slate-700 rounded overflow-hidden">
                        <div class="bg-green-600 text-white px-4 py-3 font-bold text-base flex justify-between items-center">
                            <span>{{ t('deposit') }}</span>
                            <span>Total</span>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center text-sm font-semibold border-b border-slate-100 dark:border-slate-700 pb-2">
                                <span>Total Deposits:</span>
                                <strong class="text-green-600">{{ parseFloat(total_deposit).toFixed(2) }} ৳</strong>
                            </div>
                            <div class="flex justify-between items-center text-base font-bold border-b border-slate-100 dark:border-slate-700 pb-2">
                                <span>{{ t('balance') }}:</span>
                                <strong class="text-slate-900 dark:text-white">{{ parseFloat(currentBalance).toFixed(2) }} ৳</strong>
                            </div>

                            <!-- Closing Date Picker -->
                            <div class="pt-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                    {{ t('closing_date') }}
                                </label>
                                <input
                                    ref="closingDateRef"
                                    v-model="form.closing_date"
                                    type="text"
                                    required
                                    :disabled="!accountBook.open"
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm text-center"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="bg-slate-50 dark:bg-slate-900/50 p-4 border border-slate-200 dark:border-slate-700 rounded flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-slate-700 dark:text-slate-300 font-semibold text-center sm:text-left">
                        Bank Book: <strong>{{ bankAccount.bank }}</strong> - Close this account book period.
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing || !accountBook.open"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded text-sm transition shadow-sm cursor-pointer disabled:opacity-50 w-full sm:w-auto"
                    >
                        {{ form.processing ? t('processing') : t('close_it') }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
