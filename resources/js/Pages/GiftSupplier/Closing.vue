<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
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
    isDummy: {
        type: Boolean,
        default: false,
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
            'gift_supplier_book_closing': 'Gift Supplier Book Closing',
            'gift_supplier_book_dummy_closing': 'Gift Supplier Book Dummy Closing',
            'name': 'Name',
            'address': 'Address',
            'mobile_no': 'Mobile No',
            'calculation': 'Calculation',
            'details': 'Details',
            'taka': 'Taka',
            'product': 'Product',
            'payment': 'Payment (-)',
            'total_debt': 'Total Debt',
            'commission': 'Commission',
            'percentage_commission': 'Percentage Commission',
            'excluding_commission': 'Excluding Commission',
            'total_paid': 'Total Paid',
            'payment_by_check': 'Payment by Check',
            'due': 'Due',
            'closing_date': 'Closing Date',
            'paid': 'Paid',
            'bank': 'Bank',
            'check_payment': 'Cheque Payment',
            'check_no': 'Cheque No',
            'payment_date': 'Payment Date',
            'as_there_are_no_dues_remaining_the_supplier_account_has_been_closed': ' - As there are no dues remaining, the supplier\'s account has been closed',
            'close_it': 'Close It',
            'back': 'Cancel & Go Back',
            'old': 'Opening Balance',
            'returns': 'Returns (-)',
            'staff': 'Staff Expenses',
            'staff_percentage_commission': 'Staff Percentage Commission',
            'gift_supplier': 'Gift Supplier',
            'close_it_now': 'Close It Now',
        },
        bn: {
            'gift_supplier_book_closing': 'গিফট মহাজন খাতা ক্লোজিং',
            'gift_supplier_book_dummy_closing': 'গিফট মহাজন খাতা ডামি ক্লোজিং',
            'name': 'নাম',
            'address': 'ঠিকানা',
            'mobile_no': 'মোবাইল নং',
            'calculation': 'হিসাব',
            'details': 'বিবরণ',
            'taka': 'টাকা',
            'product': 'মাল',
            'payment': 'তাগাদা (-)',
            'total_debt': 'মোট দেনা',
            'commission': 'কমিশন',
            'percentage_commission': 'শতকরা কমিশন',
            'excluding_commission': 'কমিশন বাদে',
            'total_paid': 'সর্বমোট পরিশোধ',
            'payment_by_check': 'চেকের মাধ্যমে পরিশোধ',
            'due': 'বাকি',
            'closing_date': 'ক্লোজিং এর তারিখ',
            'paid': 'পরিশোধ',
            'bank': 'ব্যাংক',
            'check_payment': 'চেক প্রদান',
            'check_no': 'চেক নং',
            'payment_date': 'পরিশোধের তারিখ',
            'as_there_are_no_dues_remaining_the_supplier_account_has_been_closed': ' মহাজনের হিসাব ক্লোজ করুন যেহেতু আর কোনো দেনা বাকি নেই',
            'close_it': 'ক্লোজ করুন',
            'back': 'বাতিল করুন',
            'old': 'সাবেক (+)',
            'returns': 'ফেরত (-)',
            'staff': 'স্টাফ খরচ',
            'staff_percentage_commission': 'শতকরা স্টাফ খরচ',
            'gift_supplier': 'গিফট মহাজন',
            'close_it_now': 'ক্লোজ করুন',
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
    // Editable dummy fields
    purchase_amount: props.isDummy ? props.purchaseAmount : 0,
    return_amount: props.isDummy ? props.returnAmount : 0,
    payment_amount: props.isDummy ? props.paymentAmount : 0,
    opening_balance: props.isDummy ? props.openingBalance : 0,
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

// Dynamic calculations based on percentages and inputs (Commission)
watch(() => form.commission_percentage, (val) => {
    const percentage = parseFloat(val) || 0;
    const purchase = parseFloat(props.isDummy ? form.purchase_amount : props.purchaseAmount) || 0;
    const calculated = purchase * (percentage / 100);
    if (form.commission !== calculated) {
        form.commission = parseFloat(calculated.toFixed(2));
    }
});

watch(() => form.commission, (val) => {
    const commissionVal = parseFloat(val) || 0;
    const purchase = parseFloat(props.isDummy ? form.purchase_amount : props.purchaseAmount) || 0;
    if (purchase > 0) {
        const percentage = (commissionVal / purchase) * 100;
        if (form.commission_percentage !== percentage) {
            form.commission_percentage = parseFloat(percentage.toFixed(2));
        }
    }
});

// Dynamic calculations based on percentages and inputs (Staff)
watch(() => form.staff_percentage, (val) => {
    const percentage = parseFloat(val) || 0;
    const purchase = parseFloat(props.isDummy ? form.purchase_amount : props.purchaseAmount) || 0;
    const calculated = purchase * (percentage / 100);
    if (form.staff !== calculated) {
        form.staff = parseFloat(calculated.toFixed(2));
    }
});

watch(() => form.staff, (val) => {
    const staffVal = parseFloat(val) || 0;
    const purchase = parseFloat(props.isDummy ? form.purchase_amount : props.purchaseAmount) || 0;
    if (purchase > 0) {
        const percentage = (staffVal / purchase) * 100;
        if (form.staff_percentage !== percentage) {
            form.staff_percentage = parseFloat(percentage.toFixed(2));
        }
    }
});

// Recompute when dummy purchase amount changes
watch(() => form.purchase_amount, (val) => {
    if (props.isDummy) {
        const purchase = parseFloat(val) || 0;
        form.commission = parseFloat((purchase * (form.commission_percentage / 100)).toFixed(2));
        form.staff = parseFloat((purchase * (form.staff_percentage / 100)).toFixed(2));
    }
});

// Computed properties
const totalPayable = computed(() => {
    const purchase = parseFloat(props.isDummy ? form.purchase_amount : props.purchaseAmount) || 0;
    const payment = parseFloat(props.isDummy ? form.payment_amount : props.paymentAmount) || 0;
    const oldBalance = parseFloat(props.isDummy ? form.opening_balance : props.openingBalance) || 0;
    const returns = parseFloat(props.isDummy ? form.return_amount : props.returnAmount) || 0;
    return purchase - payment - returns + oldBalance;
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
        alert(page.props.locale === 'bn' ? 'মহাজনের পাওনা বাকী আছে।' : 'The supplier still has remaining dues.');
        return;
    }
    form.post(route('account-book.closing-store', props.accountBook.id));
};
</script>

<template>
    <Head :title="(isDummy ? t('gift_supplier_book_dummy_closing') : t('gift_supplier_book_closing')) + ' - ' + giftSupplier.name" />

    <AuthenticatedLayout>
        <!-- Back Button -->
        <div class="mb-4">
            <Link
                :href="route('account-book.show', accountBook.id)"
                class="bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer"
            >
                <i class="fa fa-arrow-left"></i>
                <span>{{ t('back') }}</span>
            </Link>
        </div>

        <!-- Main Outer Card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8">
            <!-- Header title -->
            <h1 class="text-2xl font-black text-slate-850 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-3">
                {{ isDummy ? t('gift_supplier_book_dummy_closing') : t('gift_supplier_book_closing') }}
            </h1>

            <!-- Supplier Detail Box -->
            <div class="border border-slate-200 dark:border-slate-750 bg-slate-50 dark:bg-slate-900/50 p-4 rounded mb-6 text-sm text-slate-800 dark:text-slate-200 leading-relaxed">
                <div>{{ t('name') }}: <strong>{{ giftSupplier.name }}</strong></div>
                <div>{{ t('address') }}: <strong>{{ giftSupplier.address || '—' }}</strong></div>
                <div>{{ t('mobile_no') }}: <strong>{{ giftSupplier.mobile_no || '—' }}</strong></div>
            </div>

            <!-- Warning if closed -->
            <div v-if="!accountBook.open" class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-950/20 text-yellow-800 dark:text-yellow-355 border border-yellow-200 dark:border-yellow-900 rounded">
                This ledger has been closed. Closing information cannot be edited.
            </div>

            <form @submit.prevent="submitClosing">
                <fieldset :disabled="!accountBook.open" class="row">
                    <!-- Two Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-6">
                        
                        <!-- Left Column: Calculations -->
                        <div class="lg:col-span-6">
                            <h2 class="text-lg font-black text-slate-850 dark:text-white mb-4 pb-1 border-b border-slate-100 dark:border-slate-750">
                                {{ t('calculation') }}
                            </h2>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-center border-collapse border border-slate-300 dark:border-slate-700">
                                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-bold border-b border-slate-300 dark:border-slate-700">
                                        <tr>
                                            <th class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-left w-[60%]">{{ t('details') }}</th>
                                            <th class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-right w-[40%]">{{ t('taka') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                        <!-- মাল (Product) -->
                                        <tr>
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm font-medium text-slate-700 dark:text-slate-300">{{ t('product') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input
                                                    v-if="isDummy"
                                                    v-model.number="form.purchase_amount"
                                                    type="number"
                                                    step="0.01"
                                                    class="w-full h-8 px-2 rounded text-right text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500"
                                                />
                                                <input
                                                    v-else
                                                    :value="purchaseAmount.toFixed(2)"
                                                    disabled
                                                    class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none"
                                                />
                                            </td>
                                        </tr>

                                        <!-- ফেরত (Returns) -->
                                        <tr v-if="isDummy || returnAmount > 0">
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm font-medium text-slate-700 dark:text-slate-300">{{ t('returns') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input
                                                    v-if="isDummy"
                                                    v-model.number="form.return_amount"
                                                    type="number"
                                                    step="0.01"
                                                    class="w-full h-8 px-2 rounded text-right text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500"
                                                />
                                                <input
                                                    v-else
                                                    :value="returnAmount.toFixed(2)"
                                                    disabled
                                                    class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none"
                                                />
                                            </td>
                                        </tr>

                                        <!-- তাগাদা (Payment) -->
                                        <tr>
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm font-medium text-slate-700 dark:text-slate-300">{{ t('payment') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input
                                                    v-if="isDummy"
                                                    v-model.number="form.payment_amount"
                                                    type="number"
                                                    step="0.01"
                                                    class="w-full h-8 px-2 rounded text-right text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500"
                                                />
                                                <input
                                                    v-else
                                                    :value="paymentAmount.toFixed(2)"
                                                    disabled
                                                    class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none"
                                                />
                                            </td>
                                        </tr>

                                        <!-- সাবেক (Opening Balance) -->
                                        <tr v-if="isDummy || openingBalance > 0">
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm font-medium text-slate-700 dark:text-slate-300">{{ t('old') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input
                                                    v-if="isDummy"
                                                    v-model.number="form.opening_balance"
                                                    type="number"
                                                    step="0.01"
                                                    class="w-full h-8 px-2 rounded text-right text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500"
                                                />
                                                <input
                                                    v-else
                                                    :value="openingBalance.toFixed(2)"
                                                    disabled
                                                    class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none"
                                                />
                                            </td>
                                        </tr>

                                        <!-- মোট দেনা (Total Debt) -->
                                        <tr class="bg-slate-50 dark:bg-slate-900 font-bold">
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm text-slate-800 dark:text-white">{{ t('total_debt') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input :value="totalPayable.toFixed(2)" disabled class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none" />
                                            </td>
                                        </tr>

                                        <!-- কমিশন (Commission) -->
                                        <tr>
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm font-medium text-slate-700 dark:text-slate-300">{{ t('commission') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input v-model.number="form.commission" type="number" step="0.01" class="w-full h-8 px-2 rounded text-right text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                            </td>
                                        </tr>

                                        <!-- শতকরা কমিশন (Percentage Commission) -->
                                        <tr>
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm font-medium text-slate-700 dark:text-slate-300">
                                                {{ t('percentage_commission') }} : <strong class="text-blue-600 dark:text-blue-400">{{ form.commission_percentage }}%</strong>
                                            </td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <div class="flex items-center">
                                                    <input v-model.number="form.commission_percentage" type="number" step="0.01" class="w-full h-8 px-2 rounded-l text-right text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500 border-r-0" />
                                                    <span class="h-8 px-2.5 flex items-center justify-center bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs rounded-r font-bold">%</span>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- কমিশন বাদে (Excluding Commission) -->
                                        <tr class="bg-slate-50 dark:bg-slate-900 font-bold">
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm text-slate-800 dark:text-white">{{ t('excluding_commission') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input :value="commissionDeducted.toFixed(2)" disabled class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none" />
                                            </td>
                                        </tr>

                                        <!-- স্টাফ (Staff) -->
                                        <tr>
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm font-medium text-slate-700 dark:text-slate-300">{{ t('staff') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input v-model.number="form.staff" type="number" step="0.01" class="w-full h-8 px-2 rounded text-right text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                            </td>
                                        </tr>

                                        <!-- শতকরা স্টাফ (Staff Percentage Commission) -->
                                        <tr>
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm font-medium text-slate-700 dark:text-slate-300">
                                                {{ t('staff_percentage_commission') }} : <strong class="text-blue-600 dark:text-blue-400">{{ form.staff_percentage }}%</strong>
                                            </td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <div class="flex items-center">
                                                    <input v-model.number="form.staff_percentage" type="number" step="0.01" class="w-full h-8 px-2 rounded-l text-right text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500 border-r-0" />
                                                    <span class="h-8 px-2.5 flex items-center justify-center bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs rounded-r font-bold">%</span>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- সর্বমোট দেনা (Remaining Debt) -->
                                        <tr class="bg-slate-50 dark:bg-slate-900 font-bold">
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm text-slate-800 dark:text-white">{{ t('total_debt') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input :value="remainingDebt.toFixed(2)" disabled class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none" />
                                            </td>
                                        </tr>

                                        <!-- মোট পরিশোধ (Total Paid) -->
                                        <tr>
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm font-medium text-slate-700 dark:text-slate-300">{{ t('total_paid') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input :value="totalPaid.toFixed(2)" disabled class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none" />
                                            </td>
                                        </tr>

                                        <!-- চেকের মাধ্যমে পরিশোধ (Payment by Check) -->
                                        <tr>
                                            <td class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-left text-sm font-medium text-slate-700 dark:text-slate-300">{{ t('payment_by_check') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input :value="totalCheque.toFixed(2)" disabled class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none" />
                                            </td>
                                        </tr>

                                        <!-- বাকি (Due) -->
                                        <tr class="bg-red-50/20 dark:bg-red-950/10 font-bold">
                                            <td class="px-4 py-3 border border-slate-300 dark:border-slate-700 text-left text-sm text-red-700 dark:text-red-400 font-extrabold">{{ t('due') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input :value="due.toFixed(2)" disabled class="w-full h-9 px-2 rounded bg-slate-100 dark:bg-slate-900 text-red-600 dark:text-red-400 font-extrabold text-right text-base border border-slate-300 dark:border-slate-700 focus:outline-none" />
                                            </td>
                                        </tr>

                                        <!-- ক্লোজিং এর তারিখ (Closing Date) -->
                                        <tr class="font-bold">
                                            <td class="px-4 py-3 border border-slate-300 dark:border-slate-700 text-left text-sm text-slate-800 dark:text-white">{{ t('closing_date') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input ref="closingDateRef" v-model="form.closing_date" type="text" readonly class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-center text-sm border border-slate-300 dark:border-slate-700 cursor-not-allowed focus:outline-none" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Right Column: Payments and Cheques -->
                        <div class="lg:col-span-6">
                            <!-- 1. Cash / Bank Payments Table -->
                            <h2 class="text-lg font-black text-slate-850 dark:text-white mb-4 pb-1 border-b border-slate-100 dark:border-slate-750 flex items-center justify-between">
                                <span>{{ t('paid') }}</span>
                            </h2>
                            <div class="overflow-x-auto mb-6">
                                <table id="closing-payment-table" class="w-full text-center border-collapse border border-slate-300 dark:border-slate-700">
                                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-bold border-b border-slate-300 dark:border-slate-700">
                                        <tr>
                                            <th class="px-4 py-2 border border-slate-300 dark:border-slate-700 w-12"></th>
                                            <th class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-left">{{ t('bank') }}</th>
                                            <th class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-right w-[35%]">{{ t('taka') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                        <tr v-for="(p, index) in form.payment" :key="index">
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <button type="button" @click="removePaymentRow(index)" class="bg-[#dc3545] hover:bg-[#c82333] text-white text-xs px-2.5 py-1.5 rounded cursor-pointer transition shadow-sm font-bold flex items-center justify-center mx-auto">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <select v-model="p.method" class="w-full h-8 px-2 rounded text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500 py-0">
                                                    <option v-for="b in bankAccounts" :key="b.id" :value="b.id">
                                                        {{ b.bank }} ({{ b.account_no }})
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input v-model.number="p.amount" type="number" step="0.01" required placeholder="0.00" class="w-full h-8 px-2 rounded text-right text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-slate-50 dark:bg-slate-900 font-bold border-t border-slate-300 dark:border-slate-700">
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <button type="button" @click="addPaymentRow" class="bg-[#28a745] hover:bg-[#218838] text-white text-xs px-2.5 py-1.5 rounded cursor-pointer transition shadow-sm font-bold flex items-center justify-center mx-auto">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </td>
                                            <td class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-right">{{ t('total_paid') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input type="text" disabled class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none" :value="totalPaid.toFixed(2)" />
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- 2. Cheque Payments Table -->
                            <h2 class="text-lg font-black text-slate-850 dark:text-white mb-4 pb-1 border-b border-slate-100 dark:border-slate-750 flex items-center justify-between">
                                <span>{{ t('check_payment') }}</span>
                            </h2>
                            <div class="overflow-x-auto">
                                <table id="closing-cheque-table" class="w-full text-center border-collapse border border-slate-300 dark:border-slate-700">
                                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-bold border-b border-slate-300 dark:border-slate-700">
                                        <tr>
                                            <th class="px-4 py-2 border border-slate-300 dark:border-slate-700 w-12"></th>
                                            <th class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-left w-[25%]">{{ t('check_no') }}</th>
                                            <th class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-left w-[40%]">{{ t('payment_date') }}</th>
                                            <th class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-right w-[35%]">{{ t('taka') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                        <tr v-for="(c, index) in form.cheque" :key="index">
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <button type="button" @click="removeChequeRow(index)" class="bg-[#dc3545] hover:bg-[#c82333] text-white text-xs px-2.5 py-1.5 rounded cursor-pointer transition shadow-sm font-bold flex items-center justify-center mx-auto">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input v-model="c.id" type="text" required class="w-full h-8 px-2 rounded text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                            </td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input v-model="c.due_date" type="date" required class="w-full h-8 px-2 rounded text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                            </td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input v-model.number="c.amount" type="number" step="0.01" required placeholder="0.00" class="w-full h-8 px-2 rounded text-right text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-slate-50 dark:bg-slate-900 font-bold border-t border-slate-300 dark:border-slate-700">
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <button type="button" @click="addChequeRow" class="bg-[#28a745] hover:bg-[#218838] text-white text-xs px-2.5 py-1.5 rounded cursor-pointer transition shadow-sm font-bold flex items-center justify-center mx-auto">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </td>
                                            <td colspan="2" class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-right">{{ t('payment_by_check') }}</td>
                                            <td class="px-2 py-1.5 border border-slate-300 dark:border-slate-700">
                                                <input type="text" disabled class="w-full h-8 px-2 rounded bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-right text-sm border border-slate-300 dark:border-slate-700 focus:outline-none" :value="totalCheque.toFixed(2)" />
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Row: closing bar description & submission -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mt-6 w-full col-span-12">
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse border border-slate-300 dark:border-slate-700">
                                <tbody>
                                    <tr class="bg-slate-50 dark:bg-slate-900">
                                        <td class="px-4 py-4 text-left border border-slate-300 dark:border-slate-700 text-sm font-semibold text-slate-700 dark:text-slate-300 w-[80%]">
                                            {{ t('gift_supplier') }} <strong>{{ giftSupplier.name }}</strong> - {{ t('as_there_are_no_dues_remaining_the_supplier_account_has_been_closed') }}
                                        </td>
                                        <td class="px-4 py-4 border border-slate-300 dark:border-slate-700 w-[20%] text-center">
                                            <button
                                                type="submit"
                                                :disabled="form.processing || due !== 0"
                                                class="bg-[#007bff] hover:bg-[#0069d9] disabled:opacity-50 text-white font-bold h-10 px-4 rounded text-sm w-full transition shadow-sm cursor-pointer flex items-center justify-center"
                                            >
                                                {{ form.processing ? t('processing') : t('close_it') }}
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
