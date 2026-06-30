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
            'name': 'Name',
            'address': 'Address',
            'mobile_no': 'Mobile No',
            'calculation': 'Calculation',
            'description': 'Description',
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
        },
        bn: {
            'gift_supplier_book_closing': 'গিফট মহাজন খাতা ক্লোজিং',
            'name': 'নাম',
            'address': 'ঠিকানা',
            'mobile_no': 'মোবাইল নং',
            'calculation': 'হিসাব',
            'description': 'বিবরণ',
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

// Computed properties
const totalPayable = computed(() => {
    const purchase = parseFloat(props.purchaseAmount) || 0;
    const payment = parseFloat(props.paymentAmount) || 0;
    const oldBalance = parseFloat(props.openingBalance) || 0;
    return purchase - payment + oldBalance;
});

const commissionDeducted = computed(() => {
    return totalPayable.value - (parseFloat(form.commission) || 0);
});

const remainingDebt = computed(() => {
    return commissionDeducted.value;
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
    <Head :title="t('gift_supplier_book_closing') + ' - ' + giftSupplier.name" />

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
                {{ t('gift_supplier_book_closing') }}
            </h1>

            <!-- Supplier Detail Box -->
            <div class="border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 p-4 rounded mb-6 text-sm text-slate-800 dark:text-slate-200 leading-relaxed">
                <div>{{ t('name') }}: <strong>{{ giftSupplier.name }}</strong></div>
                <div>{{ t('address') }}: <strong>{{ giftSupplier.address || '—' }}</strong></div>
                <div>{{ t('mobile_no') }}: <strong>{{ giftSupplier.mobile_no || '—' }}</strong></div>
            </div>

            <!-- Warning if closed -->
            <div v-if="!accountBook.open" class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-950/20 text-yellow-800 dark:text-yellow-350 border border-yellow-250 dark:border-yellow-900 rounded">
                This ledger has been closed. Closing information cannot be edited.
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
                                            <input :value="purchaseAmount.toFixed(2)" readonly class="w-full h-9 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border-slate-200 dark:border-slate-700 focus:ring-0 focus:outline-none" />
                                        </td>
                                    </tr>

                                    <!-- সাবেক (Opening Balance) -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('opening_balance') }}</td>
                                        <td class="py-1">
                                            <input :value="openingBalance.toFixed(2)" readonly class="w-full h-9 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border-slate-200 dark:border-slate-700 focus:ring-0 focus:outline-none" />
                                        </td>
                                    </tr>

                                    <!-- তাগাদা (Payment) -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('payment') }}</td>
                                        <td class="py-1">
                                            <input :value="paymentAmount.toFixed(2)" readonly class="w-full h-9 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border-slate-200 dark:border-slate-700 focus:ring-0 focus:outline-none" />
                                        </td>
                                    </tr>

                                    <!-- মোট দেনা (Total Debt) -->
                                    <tr class="bg-red-50/20 dark:bg-red-950/10 font-bold">
                                        <td class="py-2.5 text-sm text-red-700 dark:text-red-400">{{ t('total_debt') }}</td>
                                        <td class="py-1">
                                            <input :value="totalPayable.toFixed(2)" readonly class="w-full h-9 rounded bg-slate-100 dark:bg-slate-900 text-red-600 dark:text-red-400 font-bold text-right text-sm border-slate-200 dark:border-slate-700" />
                                        </td>
                                    </tr>

                                    <!-- কমিশন (Commission) -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('commission') }}</td>
                                        <td class="py-1 flex gap-2">
                                            <div class="w-1/3 relative">
                                                <input v-model.number="form.commission_percentage" type="number" step="0.01" class="w-full h-9 rounded text-right text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500 pr-5" />
                                                <span class="absolute right-1.5 top-2 text-xs text-slate-400">%</span>
                                            </div>
                                            <input v-model.number="form.commission" type="number" step="0.01" class="w-2/3 h-9 rounded text-right text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                        </td>
                                    </tr>

                                    <!-- কমিশন বাদে (Excluding Commission) -->
                                    <tr class="font-bold">
                                        <td class="py-2.5 text-sm text-slate-800 dark:text-white">{{ t('excluding_commission') }}</td>
                                        <td class="py-1">
                                            <input :value="commissionDeducted.toFixed(2)" readonly class="w-full h-9 rounded bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-bold text-right text-sm border-slate-200 dark:border-slate-700" />
                                        </td>
                                    </tr>

                                    <!-- সর্বমোট পরিশোধ (Total Paid) -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('total_paid') }}</td>
                                        <td class="py-1">
                                            <input :value="totalPaid.toFixed(2)" readonly class="w-full h-9 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border-slate-200 dark:border-slate-700" />
                                        </td>
                                    </tr>

                                    <!-- চেকের মাধ্যমে পরিশোধ (Payment by Check) -->
                                    <tr>
                                        <td class="py-2.5 text-sm text-slate-700 dark:text-slate-350 font-medium">{{ t('payment_by_check') }}</td>
                                        <td class="py-1">
                                            <input :value="totalCheque.toFixed(2)" readonly class="w-full h-9 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 text-right text-sm border-slate-200 dark:border-slate-700" />
                                        </td>
                                    </tr>

                                    <!-- বাকি (Due) -->
                                    <tr class="bg-blue-50/20 dark:bg-blue-950/10 font-bold text-lg">
                                        <td class="py-2.5 text-sm text-blue-700 dark:text-blue-450">{{ t('due') }}</td>
                                        <td class="py-1">
                                            <input :value="due.toFixed(2)" readonly class="w-full h-10 rounded bg-slate-100 dark:bg-slate-900 text-blue-700 dark:text-blue-400 font-bold text-right text-base border-slate-250 dark:border-slate-700" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Right Column: Payments and Cheques -->
                    <div class="lg:col-span-5">
                        <h2 class="text-lg font-black text-slate-850 dark:text-white mb-3 pb-1 border-b border-slate-100 dark:border-slate-750 flex items-center justify-between">
                            <span>Payments & Checks</span>
                            <div class="flex gap-2">
                                <button type="button" @click="addPaymentRow" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-2.5 py-1 rounded cursor-pointer font-bold transition shadow-sm">+ Add Cash</button>
                                <button type="button" @click="addChequeRow" class="bg-purple-600 hover:bg-purple-700 text-white text-xs px-2.5 py-1 rounded cursor-pointer font-bold transition shadow-sm">+ Add Cheque</button>
                            </div>
                        </h2>

                        <!-- Cash / Bank Payments list -->
                        <div v-if="form.payment.length > 0" class="space-y-3 mb-6">
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cash & Bank Payments</h3>
                            <div v-for="(p, index) in form.payment" :key="index" class="flex gap-2 items-center bg-slate-50 dark:bg-slate-900/30 p-2.5 rounded border border-slate-150 dark:border-slate-750">
                                <div class="w-1/2">
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Amount</label>
                                    <input v-model.number="p.amount" type="number" required placeholder="0.00" class="w-full h-8 rounded text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Account</label>
                                    <select v-model="p.method" class="w-full h-8 rounded text-[13px] border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500 py-0 px-2 leading-tight">
                                        <option v-for="b in bankAccounts" :key="b.id" :value="b.id">
                                            {{ b.bank }} ({{ b.account_no }})
                                        </option>
                                    </select>
                                </div>
                                <button type="button" @click="removePaymentRow(index)" class="text-red-500 hover:text-red-700 font-bold text-lg mt-5 focus:outline-none cursor-pointer">×</button>
                            </div>
                        </div>

                        <!-- Cheques list -->
                        <div v-if="form.cheque.length > 0" class="space-y-3 mb-6">
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cheque Payments</h3>
                            <div v-for="(c, index) in form.cheque" :key="index" class="bg-slate-50 dark:bg-slate-900/30 p-3 rounded border border-slate-150 dark:border-slate-750 space-y-2 relative">
                                <button type="button" @click="removeChequeRow(index)" class="absolute right-2.5 top-1.5 text-red-500 hover:text-red-700 font-bold text-lg focus:outline-none cursor-pointer">×</button>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-500">Cheque No</label>
                                        <input v-model="c.id" type="text" required class="w-full h-8 rounded text-xs border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-500">Amount</label>
                                        <input v-model.number="c.amount" type="number" required class="w-full h-8 rounded text-xs border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500">Due Date</label>
                                    <input v-model="c.due_date" type="date" required class="w-full h-8 rounded text-xs border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500" />
                                </div>
                            </div>
                        </div>

                        <!-- Date input and Submit -->
                        <div class="mt-6 border-t border-slate-150 dark:border-slate-700/80 pt-6 space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1.5">
                                    {{ t('closing_date') }}
                                </label>
                                <input
                                    ref="closingDateRef"
                                    v-model="form.closing_date"
                                    type="text"
                                    required
                                    class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 text-center"
                                />
                            </div>
                            <button
                                type="submit"
                                :disabled="form.processing || due !== 0"
                                class="bg-[#28a745] hover:bg-[#218838] disabled:opacity-50 text-white font-bold py-2.5 rounded text-sm w-full transition shadow-sm cursor-pointer"
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
