<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps({
    gifts: {
        type: Array,
        required: true,
    },
    giftSuppliers: {
        type: Array,
        required: true,
    },
    memoNo: {
        type: Number,
        required: true,
    },
    bankAccounts: {
        type: Array,
        required: true,
    },
    giftPurchase: {
        type: Object,
        default: null,
    },
    isEditing: {
        type: Boolean,
        default: false,
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
    const fallbacks = {
        en: {
            'gift_purchase': 'Gift Purchase',
            'gift_supplier': 'Gift Supplier',
            'memo_no': 'Memo No',
            'gift': 'Gift',
            'quantity': 'Quantity',
            'price': 'Price',
            'taka': 'Taka',
            'total': 'Total',
            'payment': 'Payment',
            'check_no': 'Check No',
            'payment_date': 'Payment Date',
            'payment_amount': 'Payment Amount',
            'submit': 'Submit',
            'save': 'Save',
            'processing': 'Processing...',
            'add_row': 'Add Row',
            'select_supplier': 'Select Gift Supplier',
            'select_gift': 'Select Gift',
        },
        bn: {
            'gift_purchase': 'গিফট ক্রয়',
            'gift_supplier': 'গিফট মহাজন',
            'memo_no': 'মেমো নং',
            'gift': 'গিফট',
            'quantity': 'পরিমাণ',
            'price': 'দর',
            'taka': 'টাকা',
            'total': 'মোট',
            'payment': 'পরিশোধের মাধ্যম',
            'check_no': 'চেক নং',
            'payment_date': 'চেক প্রদানের তারিখ',
            'payment_amount': 'পরিশোধের পরিমাণ',
            'submit': 'দাখিল করুন',
            'save': 'সংরক্ষণ',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
            'add_row': 'রো যোগ করুন',
            'select_supplier': 'গিফট মহাজন নির্বাচন করুন',
            'select_gift': 'গিফট নির্বাচন করুন',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

const dateRef = ref(null);
const chequeDateRef = ref(null);
let fpDate, fpChequeDate;

const form = useForm({
    gift_supplier_id: props.giftPurchase?.account_book?.account_id || '',
    memo_to_name: props.giftPurchase?.account_book?.gift_supplier_account?.name || '',
    created_at: props.giftPurchase?.created_at ? props.giftPurchase.created_at.split(' ')[0] : new Date().toISOString().split('T')[0],
    gift_purchases: props.giftPurchase?.gift_transactions?.map(gt => ({
        id: gt.id,
        gift_id: gt.gift_id,
        count: gt.count,
        unit_price: gt.unit_price,
    })) || [{ gift_id: '', count: '', unit_price: '' }],
    payment_method: props.giftPurchase ? (props.giftPurchase.supplier_account_entry?.payment_method || 0) : props.bankAccounts[0]?.id || '',
    cheque_no: '',
    cheque_date: '',
    payment_amount: props.giftPurchase ? (props.giftPurchase.supplier_account_entry?.payment_amount || 0) : 0,
});

onMounted(() => {
    fpDate = flatpickr(dateRef.value, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd-m-Y',
        defaultDate: form.created_at,
        onChange: (selectedDates, dateStr) => {
            form.created_at = dateStr;
        }
    });

    initChequePicker();
});

const initChequePicker = () => {
    if (chequeDateRef.value && isCheque.value) {
        fpChequeDate = flatpickr(chequeDateRef.value, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd-m-Y',
            defaultDate: form.cheque_date,
            onChange: (selectedDates, dateStr) => {
                form.cheque_date = dateStr;
            }
        });
    }
};

// Update supplier name when supplier ID changes
watch(() => form.gift_supplier_id, (newId) => {
    const supplier = props.giftSuppliers.find(s => s.id === parseInt(newId));
    if (supplier) {
        form.memo_to_name = supplier.name;
    } else {
        form.memo_to_name = '';
    }
});

// Add purchase line
const addRow = () => {
    form.gift_purchases.push({ gift_id: '', count: '', unit_price: '' });
};

// Remove purchase line
const removeRow = (index) => {
    form.gift_purchases.splice(index, 1);
};

// Calculate total sum of the invoice
const totalAmount = computed(() => {
    return form.gift_purchases.reduce((sum, item) => {
        const qty = parseFloat(item.count) || 0;
        const price = parseFloat(item.unit_price) || 0;
        return sum + (qty * price);
    }, 0);
});

// Watch payment method to set cheque datepicker if needed
const isCheque = computed(() => form.payment_method === 'cheque');

watch(isCheque, (newVal) => {
    if (newVal) {
        setTimeout(() => {
            initChequePicker();
        }, 100);
    } else {
        form.cheque_no = '';
        form.cheque_date = '';
        if (fpChequeDate) {
            fpChequeDate.destroy();
            fpChequeDate = null;
        }
    }
});

const submitForm = () => {
    if (props.isEditing) {
        form.put(route('gift-purchase.update', props.giftPurchase.id));
    } else {
        form.post(route('gift-purchase.store'));
    }
};
</script>

<template>
    <Head :title="t('gift_purchase')" />

    <AuthenticatedLayout>
        <div class="border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8 w-full">
            <!-- Back Arrow / Header -->
            <div class="flex items-center gap-3 mb-6">
                <Link
                    :href="route('gift-supplier.index')"
                    class="bg-slate-500 hover:bg-slate-655 text-white p-2 rounded transition shadow-sm inline-flex items-center justify-center cursor-pointer"
                    title="Back"
                >
                    <i class="fa fa-arrow-left text-sm"></i>
                </Link>
                <h1 class="text-3xl font-black text-slate-850 dark:text-white">
                    {{ t('gift_purchase') }}
                </h1>
            </div>

            <form @submit.prevent="submitForm" autocomplete="off">
                <!-- Header Fields (Supplier, Date, Memo No) matching old project inputs style -->
                <div class="flex flex-col md:flex-row gap-4 items-center mb-6">
                    <!-- Gift Supplier Input Group -->
                    <div class="flex-1 w-full flex items-stretch border border-slate-300 dark:border-slate-700 rounded overflow-hidden">
                        <span class="bg-slate-50 dark:bg-slate-900 border-r border-slate-300 dark:border-slate-700 px-4 py-2 text-sm text-slate-650 dark:text-slate-400 font-semibold flex items-center gap-2 select-none whitespace-nowrap">
                            <i class="fas fa-user-tag text-xs"></i> {{ t('gift_supplier') }}
                        </span>
                        <select
                            v-model="form.gift_supplier_id"
                            required
                            :disabled="isEditing"
                            class="flex-1 border-0 focus:ring-0 px-3 py-2 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-sm cursor-pointer outline-none"
                        >
                            <option value="" disabled>{{ t('select_supplier') }}</option>
                            <option v-for="s in giftSuppliers" :key="s.id" :value="s.id">
                                {{ s.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Date Input Group -->
                    <div class="w-full md:w-[220px] flex items-stretch border border-slate-300 dark:border-slate-700 rounded overflow-hidden">
                        <span class="bg-slate-50 dark:bg-slate-900 border-r border-slate-300 dark:border-slate-700 px-3.5 py-2 text-sm text-slate-600 dark:text-slate-400 flex items-center justify-center select-none">
                            <i class="fas fa-calendar-alt text-xs"></i>
                        </span>
                        <input
                            ref="dateRef"
                            v-model="form.created_at"
                            type="text"
                            required
                            class="w-full border-0 focus:ring-0 px-3 py-2 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-sm text-center font-bold outline-none"
                            placeholder="DD-MM-YYYY"
                        />
                    </div>

                    <!-- Memo No Group -->
                    <div class="w-full md:w-[200px] flex items-stretch border border-slate-300 dark:border-slate-700 rounded overflow-hidden bg-slate-50 dark:bg-slate-900/50">
                        <span class="bg-slate-50 dark:bg-slate-900 border-r border-slate-300 dark:border-slate-700 px-3 py-2 text-sm text-slate-600 dark:text-slate-400 flex items-center gap-1.5 select-none whitespace-nowrap">
                            <i class="fas fa-receipt text-xs"></i> {{ t('memo_no') }}
                        </span>
                        <div class="flex-1 flex items-center justify-center font-bold text-slate-900 dark:text-white text-sm py-2">
                            {{ isEditing ? giftPurchase.id : memoNo }}
                        </div>
                    </div>
                </div>

                <!-- Purchase Item Lines table -->
                <fieldset :disabled="form.gift_supplier_id === ''" class="border border-slate-200 dark:border-slate-700 rounded overflow-hidden mb-6 disabled:opacity-60">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-[15px]">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 font-bold bg-slate-50/50 dark:bg-slate-900/30">
                                    <th class="px-4 py-2.5 w-12"></th> <!-- Column for Row Deletion -->
                                    <th class="px-4 py-2.5 w-[45%]">{{ t('gift') }}</th>
                                    <th class="px-4 py-2.5 w-[18%] text-center">{{ t('quantity') }}</th>
                                    <th class="px-4 py-2.5 w-[18%] text-center">{{ t('price') }}</th>
                                    <th class="px-4 py-2.5 w-[20%] text-right pr-6">{{ t('taka') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, idx) in form.gift_purchases" :key="idx" class="border-b border-slate-200 dark:border-slate-700 hover:bg-slate-50/20">
                                    <!-- Delete Button (Left side) -->
                                    <td class="px-4 py-2 text-center">
                                        <button
                                            v-if="form.gift_purchases.length > 1"
                                            type="button"
                                            @click="removeRow(idx)"
                                            class="bg-[#dc3545] hover:bg-[#bd2130] text-white w-8 h-8 rounded flex items-center justify-center font-black text-lg focus:outline-none cursor-pointer shadow-sm transition"
                                        >
                                            -
                                        </button>
                                        <div v-else class="w-8 h-8"></div>
                                    </td>
                                    <!-- Gift select -->
                                    <td class="px-4 py-2">
                                        <select
                                            v-model="row.gift_id"
                                            required
                                            class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm py-1.5 cursor-pointer focus:border-blue-500"
                                        >
                                            <option value="" disabled>{{ t('select_gift') }}</option>
                                            <option v-for="g in gifts" :key="g.id" :value="g.id">
                                                {{ g.name }}
                                            </option>
                                        </select>
                                    </td>
                                    <!-- Quantity -->
                                    <td class="px-4 py-2">
                                        <input
                                            v-model.number="row.count"
                                            type="number"
                                            required
                                            min="1"
                                            class="w-full h-9 text-center rounded border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white text-sm focus:border-blue-500"
                                        />
                                    </td>
                                    <!-- Unit price -->
                                    <td class="px-4 py-2">
                                        <input
                                            v-model.number="row.unit_price"
                                            type="number"
                                            step="0.01"
                                            required
                                            min="0"
                                            class="w-full h-9 text-center rounded border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white text-sm focus:border-blue-500"
                                        />
                                    </td>
                                    <!-- Taka / Subtotal -->
                                    <td class="px-4 py-2 text-right pr-6">
                                        <div class="h-9 px-3 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 text-slate-700 dark:text-slate-300 rounded flex items-center justify-end font-bold text-sm select-none">
                                            {{ ((parseFloat(row.count) || 0) * (parseFloat(row.unit_price) || 0)).toFixed(2) }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <!-- Total row with left-sided add row button -->
                                <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/10">
                                    <td class="px-4 py-3.5 text-center">
                                        <button
                                            type="button"
                                            @click="addRow"
                                            class="bg-[#28a745] hover:bg-[#218838] text-white w-8 h-8 rounded flex items-center justify-center font-black text-lg focus:outline-none cursor-pointer shadow-sm transition"
                                        >
                                            +
                                        </button>
                                    </td>
                                    <td colspan="3" class="px-4 py-3.5 text-right font-bold text-slate-800 dark:text-white">
                                        Total
                                    </td>
                                    <td class="px-4 py-3.5 text-right pr-6">
                                        <div class="h-9 px-3 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 text-slate-800 dark:text-white rounded flex items-center justify-end font-black text-sm select-none">
                                            {{ totalAmount.toFixed(2) }}
                                        </div>
                                    </td>
                                </tr>

                                <!-- Payment details row (Inline alignment matching old layout) -->
                                <tr>
                                    <td></td>
                                    <td colspan="3" class="px-4 py-5">
                                        <div class="flex flex-wrap items-center justify-end gap-x-6 gap-y-3 text-sm font-bold">
                                            <!-- Payment Type -->
                                            <div class="flex items-center gap-2">
                                                <label class="text-slate-655 dark:text-slate-300 whitespace-nowrap">{{ t('payment') }}</label>
                                                <select
                                                    v-model="form.payment_method"
                                                    required
                                                    class="h-9 rounded border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-blue-500 text-sm py-1.5 cursor-pointer"
                                                    style="width: 200px"
                                                >
                                                    <option v-for="b in bankAccounts" :key="b.id" :value="b.id">
                                                        {{ b.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <!-- Cheque No -->
                                            <div class="flex items-center gap-2">
                                                <label class="text-slate-655 dark:text-slate-300 whitespace-nowrap">{{ t('check_no') }}</label>
                                                <input
                                                    v-model="form.cheque_no"
                                                    type="text"
                                                    :disabled="!isCheque"
                                                    required
                                                    class="h-9 rounded border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white disabled:bg-slate-100/80 dark:disabled:bg-slate-950 text-sm w-36 px-2.5 focus:border-blue-500"
                                                />
                                            </div>

                                            <!-- Cheque Due Date -->
                                            <div class="flex items-center gap-2">
                                                <label class="text-slate-655 dark:text-slate-300 whitespace-nowrap">{{ t('payment_date') }}</label>
                                                <div class="flex items-stretch border border-slate-300 dark:border-slate-700 rounded overflow-hidden w-40 h-9 bg-white dark:bg-slate-900">
                                                    <span class="bg-slate-50 dark:bg-slate-850 px-2 flex items-center text-slate-400 border-r border-slate-300 dark:border-slate-700 select-none">
                                                        <i class="fas fa-calendar-alt text-xs"></i>
                                                    </span>
                                                    <input
                                                        ref="chequeDateRef"
                                                        v-model="form.cheque_date"
                                                        type="text"
                                                        :disabled="!isCheque"
                                                        required
                                                        class="w-full border-0 focus:ring-0 text-center text-slate-800 dark:text-white bg-transparent text-xs"
                                                        placeholder="DD-MM-YYYY"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Payment Amount -->
                                    <td class="px-4 py-5 text-right pr-6">
                                        <input
                                            v-model.number="form.payment_amount"
                                            type="number"
                                            step="0.01"
                                            required
                                            min="0"
                                            class="w-full h-9 text-right font-black rounded border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-905 dark:text-white focus:border-blue-500 text-sm pr-3"
                                        />
                                    </td>
                                </tr>

                                <!-- Action Buttons row -->
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="px-4 py-3 text-right pr-6">
                                        <button
                                            type="submit"
                                            :disabled="form.processing"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-2 rounded text-sm transition shadow-sm cursor-pointer disabled:opacity-50 inline-flex items-center justify-center gap-1.5 w-full"
                                        >
                                            <i class="fas fa-save"></i> Submit
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </fieldset>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
