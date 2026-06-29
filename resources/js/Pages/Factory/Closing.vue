<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
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
    purchaseAmount: {
        type: Number,
        required: true,
    },
    returnAmount: {
        type: Number,
        required: true,
    },
    paymentAmount: {
        type: Number,
        required: true,
    },
    openingBalance: {
        type: Number,
        required: true,
    },
    bankAccounts: {
        type: Array,
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
    if (page.props.translations?.menu?.[cleanKey]) {
        return page.props.translations.menu[cleanKey];
    }
    const fallbacks = {
        en: {
            'supplier_book_closing': 'Supplier Book Closing',
            'name': 'Name',
            'address': 'Address',
            'mobile_no': 'Mobile No',
            'calculation': 'Calculation',
            'description': 'Description',
            'taka': 'Taka',
            'product': 'Product',
            'return': 'Return (-)',
            'payment': 'Payment (-)',
            'staff_shoe': 'Staff Shoe',
            'total_debt': 'Total Debt',
            'commission': 'Commission',
            'percentage_commission': 'Percentage Commission',
            'excluding_commission': 'Excluding Commission',
            'staff': 'Staff',
            'staff_percentage_commission': 'Staff Percentage Commission',
            'total_paid': 'Total Paid',
            'payment_by_check': 'Payment by Check',
            'due': 'Due',
            'closing_date': 'Closing Date',
            'paid': 'Paid',
            'bank': 'Bank',
            'check_payment': 'Cheque Payment',
            'check_no': 'Cheque No',
            'payment_date': 'Payment Date',
            'supplier': 'Supplier',
            'as_there_are_no_dues_remaining_the_supplier_account_has_been_closed': ' - As there are no dues remaining, the supplier\'s account has been closed',
            'close_it': 'Close It',
            'back': 'Cancel & Go Back',
        },
        bn: {
            'supplier_book_closing': 'মহাজন খাতা ক্লোজিং',
            'name': 'নাম',
            'address': 'ঠিকানা',
            'mobile_no': 'মোবাইল নং',
            'calculation': 'হিসাব',
            'description': 'বিবরণ',
            'taka': 'টাকা',
            'product': 'মাল',
            'return': 'ফেরত (-)',
            'payment': 'তাগাদা (-)',
            'staff_shoe': 'স্টাফ জুতা',
            'total_debt': 'মোট দেনা',
            'commission': 'কমিশন',
            'percentage_commission': 'শতকরা কমিশন',
            'excluding_commission': 'কমিশন বাদে',
            'staff': 'স্টাফ',
            'staff_percentage_commission': 'স্টাফ শতকরা কমিশন',
            'total_paid': 'সর্বমোট পরিশোধ',
            'payment_by_check': 'চেকের মাধ্যমে পরিশোধ',
            'due': 'বাকি',
            'closing_date': 'ক্লোজিং এর তারিখ',
            'paid': 'পরিশোধ',
            'bank': 'ব্যাংক',
            'check_payment': 'চেক প্রদান',
            'check_no': 'চেক নং',
            'payment_date': 'পরিশোধের তারিখ',
            'supplier': 'মহাজন',
            'as_there_are_no_dues_remaining_the_supplier_account_has_been_closed': ' - As there are no dues remaining, the supplier\'s account has been closed',
            'close_it': 'ক্লোজ করুন',
            'back': 'বাতিল করুন',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

const closingDateRef = ref(null);
let fpClosingDate;

const form = useForm({
    closing_date: props.accountBook.closing_date ? props.accountBook.closing_date.split(' ')[0] : new Date().toISOString().split('T')[0],
    closing_type: props.accountBook.closing_type || 'normal',
    commission_percentage: props.accountBook.commission_percentage || 0,
    commission: props.accountBook.commission || 0,
    staff_percentage: props.accountBook.staff_percentage || 0,
    staff: props.accountBook.staff || 0,
    staff_shoe_amount: props.accountBook.staff_shoe_amount || 0,
    payment: props.accountBook.open ? [{ amount: '', method: props.bankAccounts[0]?.id || 1 }] : [],
    cheque: [],
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

// Dynamic calculations based on percentages and inputs
watch(() => form.commission_percentage, (val) => {
    const percentage = parseFloat(val) || 0;
    const calculated = props.purchaseAmount * (percentage / 100);
    if (form.commission !== calculated) {
        form.commission = parseFloat(calculated.toFixed(2));
    }
});

watch(() => form.commission, (val) => {
    const commissionVal = parseFloat(val) || 0;
    if (props.purchaseAmount > 0) {
        const percentage = (commissionVal / props.purchaseAmount) * 100;
        if (form.commission_percentage !== percentage) {
            form.commission_percentage = parseFloat(percentage.toFixed(2));
        }
    }
});

watch(() => form.staff_percentage, (val) => {
    const percentage = parseFloat(val) || 0;
    const calculated = props.purchaseAmount * (percentage / 100);
    if (form.staff !== calculated) {
        form.staff = parseFloat(calculated.toFixed(2));
    }
});

watch(() => form.staff, (val) => {
    const staffVal = parseFloat(val) || 0;
    if (props.purchaseAmount > 0) {
        const percentage = (staffVal / props.purchaseAmount) * 100;
        if (form.staff_percentage !== percentage) {
            form.staff_percentage = parseFloat(percentage.toFixed(2));
        }
    }
});

// Computed properties matching closing.js
const totalPayable = computed(() => {
    const purchase = parseFloat(props.purchaseAmount) || 0;
    const returnAmt = parseFloat(props.returnAmount) || 0;
    const payment = parseFloat(props.paymentAmount) || 0;
    const staffShoe = parseFloat(form.staff_shoe_amount) || 0;
    const oldBalance = parseFloat(props.openingBalance) || 0;
    return purchase - returnAmt - payment - staffShoe + oldBalance;
});

const commissionDeducted = computed(() => {
    return totalPayable.value - (parseFloat(form.commission) || 0);
});

const remainingDebt = computed(() => {
    return commissionDeducted.value - (parseFloat(form.staff) || 0);
});

const totalPaid = computed(() => {
    return form.payment.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
});

const totalCheque = computed(() => {
    return form.cheque.reduce((sum, c) => sum + (parseFloat(c.amount) || 0), 0);
});

const due = computed(() => {
    const val = remainingDebt.value - totalPaid.value - totalCheque.value;
    return Math.abs(val) < 0.000001 ? 0 : val;
});

// Add/Remove rows
const addPaymentRow = () => {
    form.payment.push({ amount: '', method: props.bankAccounts[0]?.id || 1 });
};

const removePaymentRow = (index) => {
    form.payment.splice(index, 1);
};

const addChequeRow = () => {
    form.cheque.push({ id: '', due_date: new Date().toISOString().split('T')[0], amount: '' });
};

const removeChequeRow = (index) => {
    form.cheque.splice(index, 1);
};

const submitClosing = () => {
    if (due.value !== 0) {
        alert(page.props.locale === 'bn' ? 'কারখানাদারের পাওনা বাকী আছে।' : 'The supplier still has remaining dues.');
        return;
    }
    form.post(route('account-book.closing-store', props.accountBook.id));
};
</script>

<template>
    <Head :title="t('supplier_book_closing') + ' - ' + factory.name" />

    <AuthenticatedLayout>
        <!-- Back Button -->
        <div class="mb-4 flex items-center justify-between">
            <Link
                :href="route('account-book.show', accountBook.id)"
                class="bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition shadow-sm flex items-center gap-1.5 cursor-pointer"
            >
                <i class="fa fa-arrow-left"></i>
                <span>{{ t('back') }}</span>
            </Link>
        </div>

        <!-- Main Outer Card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8 p-6">
            <!-- Header title -->
            <h1 class="text-2xl font-black text-slate-850 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-3">
                {{ t('supplier_book_closing') }}
            </h1>

            <!-- Supplier Detail Box -->
            <div class="border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 p-4 rounded mb-6 text-sm text-slate-800 dark:text-slate-200 leading-relaxed">
                <div>{{ t('name') }}: <strong>{{ factory.name }}</strong></div>
                <div>{{ t('address') }}: <strong>{{ factory.address || '—' }}</strong></div>
                <div>{{ t('mobile_no') }}: <strong>{{ factory.mobile_no || '—' }}</strong></div>
            </div>

            <!-- Warning if closed -->
            <div v-if="!accountBook.open" class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-950/20 text-yellow-800 dark:text-yellow-350 border border-yellow-250 dark:border-yellow-900 rounded">
                {{ t('This ledger has been closed. Closing information cannot be edited.') || 'This ledger has been closed. Closing information cannot be edited.' }}
            </div>

            <form @submit.prevent="submitClosing">
                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-6">
                    
                    <!-- Left Column: Calculations -->
                    <div class="lg:col-span-7">
                        <h2 class="text-lg font-black text-slate-850 dark:text-white mb-3 pb-1 border-b border-slate-100 dark:border-slate-750">
                            {{ t('calculation') }}
                        </h2>
                        
                        <div class="table-responsive">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-200 dark:border-slate-700">
                                        <th class="text-left py-2 font-bold text-slate-800 dark:text-white text-sm">{{ t('description') }}</th>
                                        <th class="text-right py-2 font-bold text-slate-800 dark:text-white text-sm w-1/3">{{ t('taka') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                    <!-- মাল (Product) -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('product') }}</td>
                                        <td class="py-1">
                                            <input type="text" disabled :value="purchaseAmount.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 dark:border-slate-750 bg-slate-50 dark:bg-slate-900/50 text-right font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed" />
                                        </td>
                                    </tr>
                                    <!-- ফেরত (-) -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('return') }}</td>
                                        <td class="py-1">
                                            <input type="text" disabled :value="returnAmount.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 dark:border-slate-750 bg-slate-50 dark:bg-slate-900/50 text-right font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed" />
                                        </td>
                                    </tr>
                                    <!-- তাগাদা (-) (Payment) -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('payment') }}</td>
                                        <td class="py-1">
                                            <input type="text" disabled :value="paymentAmount.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 dark:border-slate-750 bg-slate-50 dark:bg-slate-900/50 text-right font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed" />
                                        </td>
                                    </tr>
                                    <!-- স্টাফ জুতা -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('staff_shoe') }}</td>
                                        <td class="py-1">
                                            <input type="number" step="0.01" :disabled="!accountBook.open" v-model="form.staff_shoe_amount" class="w-full h-9 px-3 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-right font-bold text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500" />
                                        </td>
                                    </tr>
                                    <!-- ওল্ড ব্যালেন্স (যদি থাকে) -->
                                    <tr v-if="openingBalance > 0">
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('old') || 'পুরাতন' }} (+)</td>
                                        <td class="py-1">
                                            <input type="text" disabled :value="openingBalance.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 dark:border-slate-750 bg-slate-50 dark:bg-slate-900/50 text-right font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed" />
                                        </td>
                                    </tr>
                                    <!-- মোট দেনা -->
                                    <tr class="bg-slate-50/50 dark:bg-slate-900/10">
                                        <td class="py-2.5 text-sm font-bold text-slate-850 dark:text-white">{{ t('total_debt') }}</td>
                                        <td class="py-1">
                                            <input type="text" disabled :value="totalPayable.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 dark:border-slate-750 bg-slate-50 dark:bg-slate-900/50 text-right font-black text-slate-800 dark:text-white cursor-not-allowed" />
                                        </td>
                                    </tr>
                                    <!-- কমিশন -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('commission') }}</td>
                                        <td class="py-1">
                                            <input type="number" step="0.01" :disabled="!accountBook.open" v-model="form.commission" class="w-full h-9 px-3 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-right font-bold text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500" />
                                        </td>
                                    </tr>
                                    <!-- শতকরা কমিশন -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">
                                            {{ t('percentage_commission') }} : <strong class="text-blue-600 dark:text-blue-400 font-bold ml-1">{{ (form.commission || 0).toFixed(2) }}</strong>
                                        </td>
                                        <td class="py-1">
                                            <div class="flex items-center">
                                                <input type="number" step="0.01" :disabled="!accountBook.open" v-model="form.commission_percentage" class="w-full h-9 px-3 rounded-l border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-right font-bold text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500 border-r-0" />
                                                <span class="h-9 px-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-700 rounded-r text-slate-600 dark:text-slate-300 flex items-center justify-center text-sm font-bold">%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- কমিশন বাদে -->
                                    <tr class="bg-slate-50/50 dark:bg-slate-900/10">
                                        <td class="py-2.5 text-sm font-bold text-slate-850 dark:text-white">{{ t('excluding_commission') }}</td>
                                        <td class="py-1">
                                            <input type="text" disabled :value="commissionDeducted.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 dark:border-slate-750 bg-slate-50 dark:bg-slate-900/50 text-right font-black text-slate-800 dark:text-white cursor-not-allowed" />
                                        </td>
                                    </tr>
                                    <!-- স্টাফ -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('staff') }}</td>
                                        <td class="py-1">
                                            <input type="number" step="0.01" :disabled="!accountBook.open" v-model="form.staff" class="w-full h-9 px-3 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-right font-bold text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500" />
                                        </td>
                                    </tr>
                                    <!-- স্টাফ শতকরা কমিশন -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">
                                            {{ t('staff_percentage_commission') }} : <strong class="text-blue-600 dark:text-blue-400 font-bold ml-1">{{ (form.staff || 0).toFixed(2) }}</strong>
                                        </td>
                                        <td class="py-1">
                                            <div class="flex items-center">
                                                <input type="number" step="0.01" :disabled="!accountBook.open" v-model="form.staff_percentage" class="w-full h-9 px-3 rounded-l border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-right font-bold text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500 border-r-0" />
                                                <span class="h-9 px-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-700 rounded-r text-slate-600 dark:text-slate-300 flex items-center justify-center text-sm font-bold">%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- সর্বমোট দেনা (২) -->
                                    <tr class="bg-slate-50/50 dark:bg-slate-900/10">
                                        <td class="py-2.5 text-sm font-bold text-slate-850 dark:text-white">{{ t('total_debt') }} (২)</td>
                                        <td class="py-1">
                                            <input type="text" disabled :value="remainingDebt.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 dark:border-slate-750 bg-slate-50 dark:bg-slate-900/50 text-right font-black text-slate-800 dark:text-white cursor-not-allowed" />
                                        </td>
                                    </tr>
                                    <!-- সর্বমোট পরিশোধ -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('total_paid') }}</td>
                                        <td class="py-1">
                                            <input type="text" disabled :value="totalPaid.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 dark:border-slate-750 bg-slate-50 dark:bg-slate-900/50 text-right font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed" />
                                        </td>
                                    </tr>
                                    <!-- চেকের মাধ্যমে পরিশোধ -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('payment_by_check') }}</td>
                                        <td class="py-1">
                                            <input type="text" disabled :value="totalCheque.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 dark:border-slate-750 bg-slate-50 dark:bg-slate-900/50 text-right font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed" />
                                        </td>
                                    </tr>
                                    <!-- বাকি (Due) -->
                                    <tr class="bg-slate-100/70 dark:bg-slate-900">
                                        <td class="py-2.5 text-sm font-black text-red-500 dark:text-red-400">{{ t('due') }}</td>
                                        <td class="py-1">
                                            <input type="text" disabled :value="due.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-900 text-right font-black text-red-500 dark:text-red-400 cursor-not-allowed" />
                                        </td>
                                    </tr>
                                    <!-- ক্লোজিং এর তারিখ -->
                                    <tr>
                                        <td class="py-2.5 text-sm font-bold text-slate-750 dark:text-slate-350">{{ t('closing_date') }}</td>
                                        <td class="py-1">
                                            <input
                                                ref="closingDateRef"
                                                type="text"
                                                :disabled="!accountBook.open"
                                                class="w-full h-9 px-3 rounded border border-slate-300 dark:border-slate-750 bg-white dark:bg-slate-900 text-right font-bold text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Right Column: Payments (Cash/Bank & Cheque) -->
                    <div class="lg:col-span-5">
                        
                        <!-- পরিশোধ (Cash/Bank Payments) Table -->
                        <div class="mb-8">
                            <h2 class="text-lg font-black text-slate-850 dark:text-white mb-3 pb-1 border-b border-slate-100 dark:border-slate-750">
                                {{ t('paid') }}
                            </h2>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-200 dark:border-slate-750">
                                            <th class="py-2 text-left w-10"></th>
                                            <th class="py-2 text-left font-bold text-slate-700 dark:text-slate-350 text-sm">{{ t('bank') }}</th>
                                            <th class="py-2 text-right font-bold text-slate-700 dark:text-slate-350 text-sm w-1/3">{{ t('taka') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-750/50">
                                        <tr v-for="(pmt, index) in form.payment" :key="index" class="align-middle">
                                            <td class="py-2 text-center w-10">
                                                <button type="button" :disabled="!accountBook.open" @click="removePaymentRow(index)" class="w-9 h-9 bg-[#dc3545] hover:bg-[#c82333] disabled:opacity-50 text-white rounded flex items-center justify-center cursor-pointer">
                                                    <i class="fa fa-minus text-sm"></i>
                                                </button>
                                            </td>
                                            <td class="py-2">
                                                <select :disabled="!accountBook.open" v-model="pmt.method" class="w-full h-9 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                                    <option v-for="bank in bankAccounts" :key="bank.id" :value="bank.id">
                                                        <span v-if="bank.account_no === 'cash'">{{ bank.bank }}</span>
                                                        <span v-else>{{ bank.account_no }}-{{ bank.bank }}-{{ bank.branch }}</span>
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="py-2">
                                                <input type="number" step="0.01" :disabled="!accountBook.open" v-model="pmt.amount" class="w-full h-9 px-3 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-right font-bold text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500" />
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t border-slate-200 dark:border-slate-750">
                                            <td class="py-3 text-center w-10">
                                                <button type="button" :disabled="!accountBook.open" @click="addPaymentRow" class="w-9 h-9 bg-[#28a745] hover:bg-[#218838] disabled:opacity-50 text-white rounded flex items-center justify-center cursor-pointer">
                                                    <i class="fa fa-plus text-sm"></i>
                                                </button>
                                            </td>
                                            <td class="py-3 text-right text-sm font-bold text-slate-700 dark:text-slate-350">{{ t('total_paid') }}</td>
                                            <td class="py-3">
                                                <input type="text" disabled :value="totalPaid.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 bg-slate-50 dark:bg-slate-900/50 dark:border-slate-800 text-right font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed" />
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- চেক প্রদান (Cheque Payments) Table -->
                        <div>
                            <h2 class="text-lg font-black text-slate-850 dark:text-white mb-3 pb-1 border-b border-slate-100 dark:border-slate-750">
                                {{ t('check_payment') }}
                            </h2>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-200 dark:border-slate-750">
                                            <th class="py-2 text-left w-10"></th>
                                            <th class="py-2 text-left font-bold text-slate-700 dark:text-slate-350 text-sm w-[25%]">{{ t('check_no') }}</th>
                                            <th class="py-2 text-left font-bold text-slate-700 dark:text-slate-350 text-sm w-[40%]">{{ t('payment_date') }}</th>
                                            <th class="py-2 text-right font-bold text-slate-700 dark:text-slate-350 text-sm w-[35%]">{{ t('taka') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-750/50">
                                        <tr v-for="(chq, index) in form.cheque" :key="index" class="align-middle">
                                            <td class="py-2 text-center w-10">
                                                <button type="button" :disabled="!accountBook.open" @click="removeChequeRow(index)" class="w-9 h-9 bg-[#dc3545] hover:bg-[#c82333] disabled:opacity-50 text-white rounded flex items-center justify-center cursor-pointer">
                                                    <i class="fa fa-minus text-sm"></i>
                                                </button>
                                            </td>
                                            <td class="py-2">
                                                <input type="text" :disabled="!accountBook.open" v-model="chq.id" class="w-full h-9 px-2 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500" />
                                            </td>
                                            <td class="py-2">
                                                <input type="date" :disabled="!accountBook.open" v-model="chq.due_date" class="w-full h-9 px-2 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500" />
                                            </td>
                                            <td class="py-2">
                                                <input type="number" step="0.01" :disabled="!accountBook.open" v-model="chq.amount" class="w-full h-9 px-3 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-right font-bold text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500" />
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t border-slate-200 dark:border-slate-750">
                                            <td class="py-3 text-center w-10">
                                                <button type="button" :disabled="!accountBook.open" @click="addChequeRow" class="w-9 h-9 bg-[#28a745] hover:bg-[#218838] disabled:opacity-50 text-white rounded flex items-center justify-center cursor-pointer">
                                                    <i class="fa fa-plus text-sm"></i>
                                                </button>
                                            </td>
                                            <td colspan="2" class="py-3 text-right text-sm font-bold text-slate-700 dark:text-slate-350">{{ t('payment_by_check') }}</td>
                                            <td class="py-3">
                                                <input type="text" disabled :value="totalCheque.toFixed(2)" class="w-full h-9 px-3 rounded border border-slate-200 bg-slate-50 dark:bg-slate-900/50 dark:border-slate-800 text-right font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed" />
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Bottom Footer Row -->
                <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mt-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-50 dark:bg-slate-900/30 p-4 rounded border border-slate-200 dark:border-slate-750">
                        <div class="text-sm font-bold text-slate-750 dark:text-slate-300">
                            {{ t('supplier') }} <strong>{{ factory.name }}</strong>{{ t('as_there_are_no_dues_remaining_the_supplier_account_has_been_closed') }}
                        </div>
                        <div class="w-full md:w-auto">
                            <button
                                type="submit"
                                :disabled="due !== 0 || !accountBook.open || form.processing"
                                class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 disabled:bg-blue-500/50 disabled:opacity-60 text-white font-extrabold px-6 py-2.5 rounded shadow transition cursor-pointer disabled:cursor-not-allowed text-center"
                            >
                                {{ form.processing ? t('processing') : t('close_it') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
