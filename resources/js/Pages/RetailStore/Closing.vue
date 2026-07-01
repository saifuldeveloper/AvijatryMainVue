<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
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
    bankAccounts: {
        type: Array,
        required: true,
    },
    isDummy: {
        type: Boolean,
        default: false,
    },
    openingBalance: {
        type: Number,
        required: true,
    },
    calculations: {
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
            'retailer_book_closing': 'Retail Store Closing',
            'name': 'Name',
            'shop_name': 'Shop Name',
            'address': 'Address',
            'mobile_no': 'Mobile No',
            'calculation': 'Calculation',
            'details': 'Details',
            'Product Body Price': 'Product Body Price',
            'Taka excluding commission': 'Taka excluding commission',
            'total_sales': 'Total Sales',
            'total discount on Shoe Sales': 'Total discount on Shoe Sales',
            'total_return': 'Total Return',
            'total': 'Total',
            'payment': 'Payment (-)',
            'shipping_costs': 'Shipping costs (+)',
            'others_expenses': 'Other expenses (-)',
            'discount': 'Discount (-)',
            'old due': 'Old due (+)',
            'total_due': 'Total Due',
            'commission': 'Commission',
            'excluding_commission': 'Excluding Commission',
            'staff': 'Staff',
            'total_paid': 'Total Paid',
            'due': 'Remaining Due',
            'closing_date': 'Closing Date',
            'paid': 'Paid Payments',
            'bank': 'Bank Account / Source',
            'close_it': 'Close Account Book',
            'back': 'Cancel & Go Back',
            'commission_percentage': 'Commission (%)',
            'staff_percentage': 'Staff (%)',
            'dummy_closing': 'Dummy Closing',
            'percentage commission': 'Percentage Commission:',
            'staff percentage commission': 'Staff Percentage Commission:',
            'All_total_due': 'All Total Due',
            'commitment': 'Commitment',
            'commitment due': 'Commitment Due',
            'payment made by commitment': 'Payment made by commitment',
            'payment_date': 'Payment Date',
            'taka': 'Taka',
            'option': 'Option'
        },
        bn: {
            'retailer_book_closing': 'খুচরা বিক্রেতা খাতা ক্লোজিং',
            'name': 'নাম',
            'shop_name': 'দোকানের নাম',
            'address': 'ঠিকানা',
            'mobile_no': 'মোবাইল নং',
            'calculation': 'হিসাব',
            'details': 'বিবরণ',
            'Product Body Price': 'গায়ের দাম',
            'Taka excluding commission': 'কমিশন বাদে মূল্য',
            'total_sales': 'মোট বিক্রয়',
            'total discount on Shoe Sales': 'জুতো বিক্রির উপর মোট ডিসকাউন্ট',
            'total_return': 'মোট ফেরত',
            'total': 'মোট',
            'payment': 'পরিশোধ (-)',
            'shipping_costs': 'পরিবহন খরচ (+)',
            'others_expenses': 'অন্যান্য খরচ (-)',
            'discount': 'ডিসকাউন্ট (-)',
            'old due': 'আগের বাকি (+)',
            'total_due': 'মোট বাকি',
            'commission': 'কমিশন',
            'excluding_commission': 'কমিশন বাদে বাকি',
            'staff': 'স্টাফ খরচ',
            'total_paid': 'ক্লোজিং পরিশোধ',
            'due': 'অবশিষ্ট বাকি',
            'closing_date': 'ক্লোজিং এর তারিখ',
            'paid': 'পরিশোধ',
            'bank': 'ব্যাংক খাতা',
            'close_it': 'ক্লোজ করুন',
            'back': 'বাতিল করুন',
            'commission_percentage': 'কমিশন (%)',
            'staff_percentage': 'স্টাফ (%)',
            'dummy_closing': 'ডামি ক্লোজিং',
            'percentage commission': 'শতকরা কমিশন :',
            'staff percentage commission': 'স্টাফ শতকরা কমিশন :',
            'All_total_due': 'কমিশন এবং স্টাফ বাদে বাকি',
            'commitment': 'অঙ্গীকার',
            'commitment due': 'অঙ্গীকার বাদে বাকি দেনা',
            'payment made by commitment': 'অঙ্গীকার করা জমা',
            'payment_date': 'পরিশোধের তারিখ',
            'taka': 'টাকা',
            'option': 'অপশন'
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Initial Payments
const initialPayments = (() => {
    if (!props.accountBook.open && props.accountBook.closing_transactions?.length > 0) {
        return props.accountBook.closing_transactions.map(tx => ({
            amount: tx.amount,
            method: tx.payment_method_id || tx.bank_account_id || props.bankAccounts[0]?.id || 1
        }));
    }
    return [{ amount: '', method: props.bankAccounts[0]?.id || 1 }];
})();

// Initial Commitments
const initialCommitments = (() => {
    if (props.accountBook.closing_retial_store_commitments?.length > 0) {
        return props.accountBook.closing_retial_store_commitments.map(comm => ({
            amount: comm.amount,
            payment_date: comm.payment_date ? comm.payment_date.split(' ')[0] : ''
        }));
    }
    return props.accountBook.open ? [{ amount: '', payment_date: '' }] : [];
})();

const form = useForm({
    closing_date: props.accountBook.closing_date ? props.accountBook.closing_date.split(' ')[0] : new Date().toISOString().split('T')[0],
    closing_type: 'auto',
    commission_percentage: props.accountBook.commission_percentage || 0,
    commission: props.accountBook.commission || 0,
    staff_percentage: props.accountBook.staff_percentage || 0,
    staff: props.accountBook.staff || 0,
    payment: props.accountBook.open ? [{ amount: '', method: props.bankAccounts[0]?.id || 1 }] : initialPayments,
    commitment: props.accountBook.open ? [{ amount: '', payment_date: '' }] : initialCommitments,
    balance_carry_forward: props.accountBook.balance_carry_forward !== null ? props.accountBook.balance_carry_forward : 1,
});

// Commission Base calculation (excluding commission values of: total_sale + total_discount_product_sale - total_return)
const commissionBase = computed(() => {
    return (parseFloat(props.calculations.total_sale_commission) || 0) + 
           (parseFloat(props.calculations.total_discount_product_sale_commission) || 0) - 
           (parseFloat(props.calculations.total_return_commission) || 0);
});

// Watchers to calculate commissions and staff expenses dynamically
watch(() => form.commission_percentage, (val) => {
    const pct = parseFloat(val) || 0;
    const computedCommission = commissionBase.value * (pct / 100);
    if (Math.abs(form.commission - computedCommission) > 0.01) {
        form.commission = parseFloat(computedCommission.toFixed(2));
    }
});

watch(() => form.commission, (val) => {
    const comm = parseFloat(val) || 0;
    if (commissionBase.value > 0) {
        const pct = (comm / commissionBase.value) * 100;
        if (Math.abs(form.commission_percentage - pct) > 0.01) {
            form.commission_percentage = parseFloat(pct.toFixed(2));
        }
    }
});

watch(() => form.staff_percentage, (val) => {
    const pct = parseFloat(val) || 0;
    const computedStaff = commissionBase.value * (pct / 100);
    if (Math.abs(form.staff - computedStaff) > 0.01) {
        form.staff = parseFloat(computedStaff.toFixed(2));
    }
});

watch(() => form.staff, (val) => {
    const staffAmt = parseFloat(val) || 0;
    if (commissionBase.value > 0) {
        const pct = (staffAmt / commissionBase.value) * 105;
        const computedPct = (staffAmt / commissionBase.value) * 100;
        if (Math.abs(form.staff_percentage - computedPct) > 0.01) {
            form.staff_percentage = parseFloat(computedPct.toFixed(2));
        }
    }
});

const totalPayable = computed(() => {
    return parseFloat(props.calculations.balance_before_closing) || 0;
});

const commissionDeducted = computed(() => {
    return totalPayable.value - (parseFloat(form.commission) || 0);
});

const remainingDue = computed(() => {
    return commissionDeducted.value - (parseFloat(form.staff) || 0);
});

const totalPaid = computed(() => {
    return form.payment.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
});

const totalCommitment = computed(() => {
    return form.commitment.reduce((sum, c) => sum + (parseFloat(c.amount) || 0), 0);
});

const commitmentDue = computed(() => {
    const val = remainingDue.value - totalPaid.value - totalCommitment.value;
    return Math.abs(val) < 0.000001 ? 0 : val;
});

const finalDue = computed(() => {
    const val = remainingDue.value - totalPaid.value;
    return Math.abs(val) < 0.000001 ? 0 : val;
});

// Dynamic rows management
const addPaymentRow = () => {
    form.payment.push({ amount: '', method: props.bankAccounts[0]?.id || 1 });
};

const removePaymentRow = (index) => {
    form.payment.splice(index, 1);
};

const addCommitmentRow = () => {
    form.commitment.push({ amount: '', payment_date: '' });
};

const removeCommitmentRow = (index) => {
    form.commitment.splice(index, 1);
};

const submitClosing = () => {
    form.post(route('account-book.closing-store', props.accountBook.id));
};
</script>

<template>
    <Head :title="t('retailer_book_closing') + ' - ' + retailStore.shop_name" />

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

        <!-- Main Card Wrapper -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8 p-6">
            <h1 class="text-2xl font-bold text-slate-850 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-3">
                {{ isDummy ? t('dummy_closing') : t('retailer_book_closing') }}
            </h1>

            <!-- Store Details Box -->
            <div class="border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 p-4 rounded mb-6 text-sm text-slate-800 dark:text-slate-200 leading-relaxed">
                <div>{{ t('name') }}: <strong>{{ retailStore.name }}</strong></div>
                <div>{{ t('mobile_no') }}: <strong>{{ retailStore.mobile_no || '—' }}</strong></div>
                <div>{{ t('date') }}: <strong>{{ accountBook.description || '-' }}</strong></div>
            </div>

            <!-- Warning if closed -->
            <div v-if="!accountBook.open" class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-950/20 text-yellow-800 dark:text-yellow-350 border border-yellow-250 dark:border-yellow-900 rounded">
                {{ t('This ledger has been closed. Closing information cannot be edited.') }}
            </div>

            <form @submit.prevent="submitClosing">
                <!-- Grid Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-6">
                    <!-- Left: Calculations Table (3 Columns Same to Same) -->
                    <div class="lg:col-span-6">
                        <h2 class="text-lg font-bold text-slate-850 dark:text-white mb-3 pb-1 border-b border-slate-100 dark:border-slate-750">
                            {{ t('calculation') }}
                        </h2>

                        <table class="w-full border-collapse border border-slate-200 dark:border-slate-700 text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                                    <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-left font-bold text-slate-800 dark:text-white">{{ t('details') }}</th>
                                    <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right font-bold text-slate-800 dark:text-white w-1/3">{{ t('Product Body Price') }}</th>
                                    <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right font-bold text-slate-800 dark:text-white w-1/3">{{ t('Taka excluding commission') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-250 dark:divide-slate-700">
                                <!-- Total sell -->
                                <tr>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('total_sales') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="calculations.total_sale.toFixed(2)" />
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="calculations.total_sale_commission.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Discount Product Sale -->
                                <tr>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('total discount on Shoe Sales') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="calculations.total_discount_product_sale.toFixed(2)" />
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="calculations.total_discount_product_sale_commission.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Total return -->
                                <tr>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('total_return') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="calculations.total_return.toFixed(2)" />
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="calculations.total_return_commission.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Total (computed) -->
                                <tr class="bg-slate-50 dark:bg-slate-900 font-bold">
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">{{ t('total') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-bold px-2 text-xs" :value="(calculations.total_sale + calculations.total_discount_product_sale - calculations.total_return).toFixed(2)" />
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-bold px-2 text-xs" :value="(calculations.total_sale_commission + calculations.total_discount_product_sale_commission - calculations.total_return_commission).toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Payment (-) -->
                                <tr>
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('payment') }} (-)</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="calculations.total_payment.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Shipping Costs (+) -->
                                <tr>
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('shipping_costs') }} (+)</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="calculations.total_transport.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Other Expenses (-) -->
                                <tr>
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('others_expenses') }} (-)</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="calculations.total_expense.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Discount (-) -->
                                <tr>
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('discount') }} (-)</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="calculations.total_discount.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Old Due (+) -->
                                <tr>
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('old due') }} (+)</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="openingBalance.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Total Due -->
                                <tr class="bg-slate-50 dark:bg-slate-900 font-bold">
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700">{{ t('total_due') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-bold px-2 text-xs" :value="totalPayable.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Commission -->
                                <tr>
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('commission') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                        <input
                                            v-model="form.commission"
                                            type="number"
                                            step="0.01"
                                            :disabled="!accountBook.open"
                                            class="w-full h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs text-right font-bold focus:border-blue-500"
                                        />
                                    </td>
                                </tr>

                                <!-- Percentage Commission -->
                                <tr>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('percentage commission') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right px-2 text-xs" :value="parseFloat(form.commission || 0).toFixed(2)" />
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                        <div class="flex items-center gap-1">
                                            <input
                                                v-model="form.commission_percentage"
                                                type="number"
                                                step="0.01"
                                                :disabled="!accountBook.open"
                                                class="w-full h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs text-right focus:border-blue-500"
                                            />
                                            <span class="text-xs font-semibold text-slate-500">%</span>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Excluding Commission -->
                                <tr class="bg-slate-50 dark:bg-slate-900 font-bold">
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700">{{ t('excluding_commission') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-bold px-2 text-xs" :value="commissionDeducted.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Staff -->
                                <tr>
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('staff') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                        <input
                                            v-model="form.staff"
                                            type="number"
                                            step="0.01"
                                            :disabled="!accountBook.open"
                                            class="w-full h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs text-right font-bold focus:border-blue-500"
                                        />
                                    </td>
                                </tr>

                                <!-- Staff percentage commission -->
                                <tr>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('staff percentage commission') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right px-2 text-xs" :value="parseFloat(form.staff || 0).toFixed(2)" />
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                        <div class="flex items-center gap-1">
                                            <input
                                                v-model="form.staff_percentage"
                                                type="number"
                                                step="0.01"
                                                :disabled="!accountBook.open"
                                                class="w-full h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs text-right focus:border-blue-500"
                                            />
                                            <span class="text-xs font-semibold text-slate-500">%</span>
                                        </div>
                                    </td>
                                </tr>

                                <!-- All Total Due -->
                                <tr class="bg-slate-50 dark:bg-slate-900 font-black">
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700">{{ t('All_total_due') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-black px-2 text-xs" :value="remainingDue.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Total Paid -->
                                <tr>
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('total_paid') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs text-green-600 dark:text-green-400" :value="totalPaid.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Commitment -->
                                <tr>
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('commitment') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs text-blue-600 dark:text-blue-400" :value="totalCommitment.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Commitment Due -->
                                <tr>
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-medium">{{ t('commitment due') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-55 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-medium px-2 text-xs" :value="commitmentDue.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Due -->
                                <tr class="bg-slate-100/50 dark:bg-slate-900/40 font-black text-red-600 dark:text-red-400">
                                    <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700">{{ t('due') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">
                                        <input type="text" disabled class="w-full bg-slate-150 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded h-8 text-right font-black px-2 text-xs" :value="finalDue.toFixed(2)" />
                                    </td>
                                </tr>

                                <!-- Closing Date -->
                                <tr>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 font-bold">{{ t('closing_date') }}</td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700"></td>
                                    <td class="px-3 py-2 border border-slate-200 dark:border-slate-700">
                                        <input
                                            v-model="form.closing_date"
                                            type="date"
                                            required
                                            :disabled="!accountBook.open"
                                            class="w-full h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs text-center"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Right: Payments & Commitments (Same to Same Columns) -->
                    <div class="lg:col-span-6 space-y-8">
                        <!-- 1. Paid Payments List -->
                        <div>
                            <h2 class="text-lg font-bold text-slate-850 dark:text-white mb-3 pb-1 border-b border-slate-100 dark:border-slate-750 flex items-center justify-between">
                                <span>{{ t('paid') }}</span>
                                <button
                                    v-if="accountBook.open"
                                    type="button"
                                    @click="addPaymentRow"
                                    class="bg-green-600 hover:bg-green-700 text-white rounded p-1 w-6 h-6 inline-flex items-center justify-center text-sm font-bold shadow transition cursor-pointer"
                                >
                                    +
                                </button>
                            </h2>

                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse border border-slate-200 dark:border-slate-700 text-sm">
                                    <thead>
                                        <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                                            <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 w-12">{{ t('option') }}</th>
                                            <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-left font-bold text-slate-800 dark:text-white">{{ t('bank') }}</th>
                                            <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right font-bold text-slate-800 dark:text-white w-1/3">{{ t('taka') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-150 dark:divide-slate-700">
                                        <tr v-for="(pay, index) in form.payment" :key="index">
                                            <td class="px-3 py-1 border border-slate-200 dark:border-slate-700 text-center">
                                                <button
                                                    v-if="accountBook.open && form.payment.length > 1"
                                                    type="button"
                                                    @click="removePaymentRow(index)"
                                                    class="text-red-500 hover:text-red-700 transition cursor-pointer"
                                                >
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </td>
                                            <td class="px-3 py-1 border border-slate-200 dark:border-slate-700">
                                                <select
                                                    v-model="pay.method"
                                                    :disabled="!accountBook.open"
                                                    class="w-full h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500"
                                                >
                                                    <option v-for="bank in bankAccounts" :key="bank.id" :value="bank.id">
                                                        {{ bank.bank }} ({{ bank.account_no }})
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="px-3 py-1 border border-slate-200 dark:border-slate-700 text-right">
                                                <input
                                                    v-model="pay.amount"
                                                    type="number"
                                                    step="0.01"
                                                    required
                                                    :disabled="!accountBook.open"
                                                    class="w-full h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs text-right font-bold focus:border-blue-500"
                                                    placeholder="0.00"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-slate-50 dark:bg-slate-900 font-bold">
                                            <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">{{ t('total_paid') }}</td>
                                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right font-bold text-green-600 dark:text-green-400">
                                                {{ totalPaid.toFixed(2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- 2. Commitments List -->
                        <div>
                            <h2 class="text-lg font-bold text-slate-850 dark:text-white mb-3 pb-1 border-b border-slate-100 dark:border-slate-750 flex items-center justify-between">
                                <span>{{ t('commitment') }}</span>
                                <button
                                    v-if="accountBook.open"
                                    type="button"
                                    @click="addCommitmentRow"
                                    class="bg-green-600 hover:bg-green-700 text-white rounded p-1 w-6 h-6 inline-flex items-center justify-center text-sm font-bold shadow transition cursor-pointer"
                                >
                                    +
                                </button>
                            </h2>

                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse border border-slate-200 dark:border-slate-700 text-sm">
                                    <thead>
                                        <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                                            <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 w-12">{{ t('option') }}</th>
                                            <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-left font-bold text-slate-800 dark:text-white">{{ t('payment_date') }}</th>
                                            <th class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right font-bold text-slate-800 dark:text-white w-1/3">{{ t('taka') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-150 dark:divide-slate-700">
                                        <tr v-for="(comm, index) in form.commitment" :key="index">
                                            <td class="px-3 py-1 border border-slate-200 dark:border-slate-700 text-center">
                                                <button
                                                    v-if="accountBook.open && form.commitment.length > 1"
                                                    type="button"
                                                    @click="removeCommitmentRow(index)"
                                                    class="text-red-500 hover:text-red-700 transition cursor-pointer"
                                                >
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </td>
                                            <td class="px-3 py-1 border border-slate-200 dark:border-slate-700">
                                                <input
                                                    v-model="comm.payment_date"
                                                    type="date"
                                                    required
                                                    :disabled="!accountBook.open"
                                                    class="w-full h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs text-center focus:border-blue-500"
                                                />
                                            </td>
                                            <td class="px-3 py-1 border border-slate-200 dark:border-slate-700 text-right">
                                                <input
                                                    v-model="comm.amount"
                                                    type="number"
                                                    step="0.01"
                                                    required
                                                    :disabled="!accountBook.open"
                                                    class="w-full h-8 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs text-right font-bold focus:border-blue-500"
                                                    placeholder="0.00"
                                                />
                                            </td>
                                        </tr>
                                        <tr v-if="form.commitment.length === 0">
                                            <td colspan="3" class="px-3 py-4 text-center text-slate-400 bg-slate-50/50 dark:bg-slate-900/10">
                                                {{ t('No records found.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-slate-50 dark:bg-slate-900 font-bold">
                                            <td colspan="2" class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right">{{ t('payment made by commitment') }}</td>
                                            <td class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-right font-bold text-blue-600 dark:text-blue-400">
                                                {{ totalCommitment.toFixed(2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carry Forward & Submit Row -->
                <div class="border-t border-slate-200 dark:border-slate-750 pt-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <!-- Left: Carry Forward Radio Options -->
                    <div class="text-sm text-slate-800 dark:text-slate-200">
                        <div class="mb-2">
                            <strong>{{ retailStore.name }}</strong> এর মোট বাকী <strong><span class="text-red-500 font-bold">{{ finalDue.toFixed(2) }}</span> {{ t('taka') }}</strong>।
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-medium">
                                <input
                                    v-model="form.balance_carry_forward"
                                    type="radio"
                                    :value="1"
                                    :disabled="!accountBook.open"
                                    class="text-blue-600 focus:ring-blue-500 bg-slate-100 border-slate-300 dark:bg-slate-900 dark:border-slate-700"
                                />
                                <span>বাকী পরবর্তী খাতায় যুক্ত হবে</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-medium">
                                <input
                                    v-model="form.balance_carry_forward"
                                    type="radio"
                                    :value="0"
                                    :disabled="!accountBook.open"
                                    class="text-blue-600 focus:ring-blue-500 bg-slate-100 border-slate-300 dark:bg-slate-900 dark:border-slate-700"
                                />
                                <span>বাকী পরবর্তী খাতায় যুক্ত হবে না</span>
                            </label>
                        </div>
                    </div>

                    <!-- Right: Submit Button -->
                    <div v-if="accountBook.open">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-[#007bff] hover:bg-[#0069d9] text-white rounded px-8 py-3 text-[15px] font-bold transition shadow disabled:opacity-50 flex items-center gap-1.5 cursor-pointer w-full md:w-auto justify-center"
                        >
                            <i class="fa fa-check-circle"></i>
                            <span>{{ form.processing ? t('processing') : t('close_it') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
