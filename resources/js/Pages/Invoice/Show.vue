<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    invoice: {
        type: Object,
        required: true,
    },
    transactions: {
        type: Array,
        default: () => [],
    },
    giftTransactions: {
        type: Array,
        default: () => [],
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
            'Shoe sales receipt': 'Shoe Sales Receipt',
            'memo_no': 'Memo No',
            'Discount sales memo': 'Discount Sales Memo',
            'date': 'Date',
            'name': 'Name',
            'address': 'Address',
            'mobile_no': 'Mobile No',
            'View with ID': 'View with ID',
            'Print': 'Print',
            'edit': 'Edit',
            'delete': 'Delete',
            'details': 'Details',
            'pair': 'Pairs',
            'price': 'Price',
            'total': 'Total',
            'commission': 'Commission',
            'total_excluding_commission': 'Total Excluding Commission',
            'for_return_of_pair': 'Pairs Return',
            'total_excluding_refund': 'Total Excluding Refund',
            'shipping_costs': 'Shipping Costs',
            'shipping_costs_added': 'Total with Shipping',
            'others_expenses': 'Other Expenses',
            'others_expenses_deducted': 'Total Excluding Other Expenses',
            'discount': 'Discount',
            'total_amount': 'Grand Total',
            'deposits_details': 'Deposit Details',
            'pament_method': 'Payment Method',
            'check_no': 'Check No',
            'taka': 'Taka',
            'total_deposits': 'Total Deposits',
            'previous_due': 'Previous Due',
            'total_due': 'Total Due',
            'gift_details': 'Gift Details',
            'gift': 'Gift',
            'count': 'Count',
            'confirm_delete': 'Are you sure you want to delete this invoice?',
        },
        bn: {
            'Shoe sales receipt': 'জুতা বিক্রয়ের রশিদ',
            'memo_no': 'মেমো নং',
            'Discount sales memo': 'ডিসকাউন্ট বিক্রয় মেমো',
            'date': 'তারিখ',
            'name': 'নাম',
            'address': 'ঠিকানা',
            'mobile_no': 'মোবাইল নং',
            'View with ID': 'আইডি সহ দেখুন',
            'Print': 'প্রিন্ট',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'details': 'বিবরণ',
            'pair': 'জোড়া',
            'price': 'দাম',
            'total': 'মোট',
            'commission': 'কমিশন',
            'total_excluding_commission': 'কমিশন বাদে মোট',
            'for_return_of_pair': 'জোড়া ফেরত বাবদ',
            'total_excluding_refund': 'ফেরত বাদে মোট',
            'shipping_costs': 'পাঠানোর খরচ',
            'shipping_costs_added': 'পাঠানোর খরচ সহ',
            'others_expenses': 'অন্যান্য খরচ',
            'others_expenses_deducted': 'অন্যান্য খরচ বাদে',
            'discount': 'ডিসকাউন্ট',
            'total_amount': 'সর্বমোট',
            'deposits_details': 'জমার বিবরণ',
            'pament_method': 'পেমেন্ট মাধ্যম',
            'check_no': 'চেক নং',
            'taka': 'টাকা',
            'total_deposits': 'মোট জমা',
            'previous_due': 'সাবেক বাকী',
            'total_due': 'মোট বাকী',
            'gift_details': 'গিফটের বিবরণ',
            'gift': 'গিফট',
            'count': 'সংখ্যা',
            'confirm_delete': 'আপনি কি নিশ্চিত যে আপনি এই মেমোটি মুছতে চান?',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Formatted Date
const formattedDate = computed(() => {
    if (!props.invoice.created_at) return '';
    const date = new Date(props.invoice.created_at);
    return date.toLocaleDateString(page.props.locale === 'bn' ? 'bn-BD' : 'en-US', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
});

// Grouped sales entries calculation matching original show.blade.php
const groupedSales = computed(() => {
    const entries = props.invoice.invoice_entries || [];
    const salesGroup = {};

    entries.forEach(entry => {
        const shoe = entry.shoe;
        if (!shoe) return;
        const parentCat = shoe.category?.parent?.name || 'Uncategorized';
        const subCat = shoe.category?.name || '';
        const price = parseFloat(entry.retail_price > 0 ? entry.retail_price : shoe.retail_price).toFixed(2);

        if (!salesGroup[parentCat]) {
            salesGroup[parentCat] = {};
        }
        if (!salesGroup[parentCat][price]) {
            salesGroup[parentCat][price] = {};
        }
        if (!salesGroup[parentCat][price][subCat]) {
            salesGroup[parentCat][price][subCat] = 0;
        }
        salesGroup[parentCat][price][subCat] += entry.count;
    });

    return salesGroup;
});

const totalPairs = computed(() => {
    return (props.invoice.invoice_entries || []).reduce((sum, entry) => sum + entry.count, 0);
});

const handleDelete = () => {
    if (confirm(t('pages.confirm_delete'))) {
        router.delete(route('invoice.destroy', props.invoice.id));
    }
};

const toFixed = (val) => {
    return parseFloat(val || 0).toFixed(2);
};

const printPage = () => {
    window.print();
};
</script>

<template>
    <Head :title="t('Shoe sales receipt')" />

    <AuthenticatedLayout>
        <!-- Print CSS injection -->
        <component is="style">
            @page {
                margin-top: 1.9in !important;
                margin-left: 0.3in !important;
                margin-right: 0.3in !important;
                margin-bottom: 1in !important;
            }

            @media print {
                body {
                    margin: 0 !important;
                    padding: 0 !important;
                    background: #fff !important;
                }
                
                header, footer, nav, .d-print-none, .hide-on-print {
                    display: none !important;
                }

                .print-card {
                    border: none !important;
                    box-shadow: none !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    width: 100% !important;
                    max-width: none !important;
                    background: #fff !important;
                }

                table {
                    width: 100% !important;
                    border-collapse: collapse !important;
                    background: #fff !important;
                }

                th, td {
                    border: 1px solid #000 !important;
                    color: #000 !important;
                    background: #fff !important;
                    font-size: 18px !important;
                }

                tr {
                    page-break-inside: avoid !important;
                }
            }
        </component>

        <!-- Main Card Container -->
        <div class="border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8 w-full font-nikosh print-card">
            <!-- Memo Header Details -->
            <div class="overflow-x-auto mb-5">
                <table class="w-full border-collapse border border-[#dee2e6] dark:border-slate-700 text-sm">
                    <tbody>
                        <tr class="bg-[#f8f9fa] dark:bg-slate-900/50 border-b border-[#dee2e6] dark:border-slate-700">
                            <td class="p-3 w-[45%] text-left font-bold text-slate-850 dark:text-white">
                                {{ t('memo_no') }}: <strong class="font-black font-english">{{ invoice.id }}</strong>
                            </td>
                            <td v-if="invoice.is_discount_product_sale == 1" class="p-3 w-[20%] text-center text-red-500 font-extrabold">
                                {{ t('Discount sales memo') }}
                            </td>
                            <td class="p-3 text-right font-bold text-slate-850 dark:text-white font-english">
                                {{ t('date') }}: <strong>{{ formattedDate }}</strong>
                            </td>
                        </tr>
                        <tr class="bg-white dark:bg-slate-800">
                            <td class="p-3 text-left align-top leading-relaxed text-slate-700 dark:text-slate-200">
                                {{ t('name') }}: <strong class="font-bold text-slate-900 dark:text-white">{{ invoice.account_book?.retail_account?.shop_name }}</strong><br />
                                {{ t('address') }}: <strong class="font-bold">{{ invoice.account_book?.retail_account?.address || '-' }}</strong><br />
                                {{ t('mobile_no') }}: <strong class="font-bold font-english">{{ invoice.account_book?.retail_account?.mobile_no || '-' }}</strong>
                            </td>
                            <td colspan="2" class="p-3 text-right align-top hide-on-print">
                                <div class="flex items-center justify-end gap-1.5 pt-1.5">
                                    <!-- ID View Link -->
                                    <Link
                                        :href="route('invoice.show', { invoice: invoice.id, view: 'id' })"
                                        class="bg-[#28a745] hover:bg-[#218838] border border-[#28a745] text-white font-bold px-3 py-1.5 rounded text-xs transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer h-[32px]"
                                    >
                                        {{ t('View with ID') }}
                                    </Link>

                                    <!-- Edit Link -->
                                    <Link
                                        :href="route('invoice.edit', invoice.id)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] border border-[#007bff] text-white font-bold w-[32px] h-[32px] rounded transition shadow-sm inline-flex items-center justify-center cursor-pointer"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit text-xs"></i>
                                    </Link>

                                    <!-- Delete Button -->
                                    <button
                                        @click="handleDelete"
                                        class="bg-[#dc3545] hover:bg-[#c82333] border border-[#dc3545] text-white font-bold w-[32px] h-[32px] rounded transition shadow-sm inline-flex items-center justify-center cursor-pointer"
                                        title="Delete"
                                    >
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>

                                    <!-- Print Button -->
                                    <button
                                        @click="printPage"
                                        class="bg-[#17a2b8] hover:bg-[#138496] border border-[#17a2b8] text-white font-bold px-3.5 py-1.5 rounded text-xs transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer h-[32px]"
                                    >
                                        <i class="fas fa-print"></i> {{ t('Print') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto mb-6">
                <table class="w-full border-collapse border border-[#dee2e6] dark:border-slate-700 text-sm">
                    <thead>
                        <tr class="bg-[#f8f9fa] dark:bg-slate-900 border-b border-[#dee2e6] dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold text-center">
                            <th class="p-2 border-r border-[#dee2e6] dark:border-slate-700 w-12">#</th>
                            <th class="p-2 border-r border-[#dee2e6] dark:border-slate-700 text-left w-[55%]">{{ t('details') }}</th>
                            <th class="p-2 border-r border-[#dee2e6] dark:border-slate-700 w-[15%]">{{ t('pair') }}</th>
                            <th class="p-2 border-r border-[#dee2e6] dark:border-slate-700 w-[15%]">{{ t('price') }}</th>
                            <th class="p-2 w-[15%] text-right pr-4">{{ t('total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(pg, parentCat, pIdx) in groupedSales" :key="parentCat">
                            <template v-for="(rpg, retailPrice, rIdx) in pg" :key="retailPrice">
                                <tr class="border-b border-[#dee2e6]/70 dark:border-slate-700/60 text-slate-750 dark:text-slate-200">
                                    <td class="p-2.5 text-center border-r border-[#dee2e6] dark:border-slate-700 font-english">{{ pIdx + 1 }}</td>
                                    <td class="p-2.5 border-r border-[#dee2e6] dark:border-slate-700 font-bold">
                                        {{ parentCat }}-
                                        <span v-for="(count, subCat, cIdx) in rpg" :key="subCat">
                                            <span v-if="cIdx > 0">+</span>{{ subCat }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 text-center border-r border-[#dee2e6] dark:border-slate-700 font-english font-bold">
                                        {{ Object.values(rpg).reduce((sum, v) => sum + v, 0) }}
                                    </td>
                                    <td class="p-2.5 text-center border-r border-[#dee2e6] dark:border-slate-700 font-english font-bold">
                                        {{ toFixed(retailPrice) }}
                                    </td>
                                    <td class="p-2.5 text-right font-english pr-4 font-black">
                                        {{ toFixed(Object.values(rpg).reduce((sum, v) => sum + v, 0) * parseFloat(retailPrice)) }}
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Totals & Due calculations -->
            <div class="flex justify-end mb-6 font-nikosh">
                <div class="w-full md:w-[450px]">
                    <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-300">
                        <span>{{ t('total') }}</span>
                        <span class="text-right font-black font-english">{{ toFixed(invoice.total_amount_val) }}</span>
                    </div>
                    <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-350">
                        <span>কমিশন ({{ invoice.commission }}%)</span>
                        <span class="text-right font-bold font-english">(-) {{ toFixed(invoice.total_commission_val) }}</span>
                    </div>
                    <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-800 dark:text-white font-bold">
                        <span>{{ t('total_excluding_commission') }}</span>
                        <span class="text-right font-black font-english">{{ toFixed(invoice.commission_deducted_val) }}</span>
                    </div>
                    <div v-if="invoice.return_amount_val > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-350">
                        <span>{{ invoice.return_count_val }} জোড়া ফেরত বাবদ</span>
                        <span class="text-right font-bold font-english">(-) {{ toFixed(invoice.return_amount_val) }}</span>
                    </div>
                    <div v-if="invoice.return_amount_val > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-800 dark:text-white font-bold">
                        <span>{{ t('total_excluding_refund') }}</span>
                        <span class="text-right font-black font-english">{{ toFixed(invoice.return_deducted_val) }}</span>
                    </div>
                    <div v-if="invoice.transport > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-350">
                        <span>{{ t('shipping_costs') }}</span>
                        <span class="text-right font-bold font-english">(+) {{ toFixed(invoice.transport) }}</span>
                    </div>
                    <div v-if="invoice.transport > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-800 dark:text-white font-bold">
                        <span>{{ t('shipping_costs_added') }}</span>
                        <span class="text-right font-black font-english">{{ toFixed(invoice.transport_added_val) }}</span>
                    </div>
                    <div v-if="invoice.other_costs_val > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-350">
                        <span>{{ t('others_expenses') }}</span>
                        <span class="text-right font-bold font-english">(-) {{ toFixed(invoice.other_costs_val) }}</span>
                    </div>
                    <div v-if="invoice.other_costs_val > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-800 dark:text-white font-bold">
                        <span>{{ t('others_expenses_deducted') }}</span>
                        <span class="text-right font-black font-english">{{ toFixed(invoice.other_costs_deducted_val) }}</span>
                    </div>
                    <div v-if="invoice.discount > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-350">
                        <span>{{ t('discount') }}</span>
                        <span class="text-right font-bold font-english">(-) {{ toFixed(invoice.discount) }}</span>
                    </div>
                    <div class="grid grid-cols-2 py-2 border-b-[2px] border-b-slate-300 dark:border-b-slate-650 text-sm font-extrabold bg-[#f8f9fa] dark:bg-slate-900 px-2 rounded">
                        <span class="text-blue-600 dark:text-blue-400">সর্বমোট</span>
                        <span class="text-right font-black font-english text-blue-600 dark:text-blue-400 text-base">{{ toFixed(invoice.total_receivable_val) }}</span>
                    </div>
                    <div v-if="invoice.total_payment_val > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-750 dark:text-slate-200">
                        <span>জমা</span>
                        <span class="text-right font-bold font-english">{{ toFixed(invoice.total_payment_val) }}</span>
                    </div>
                    <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-750 dark:text-slate-300">
                        <span>{{ t('previous_due') }}</span>
                        <span class="text-right font-bold font-english">(+) {{ toFixed(invoice.account_book_previous_balance_val) }}</span>
                    </div>
                    <div class="grid grid-cols-2 py-2 border-t border-t-slate-300 dark:border-t-slate-600 text-base font-black text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-950 px-2 rounded">
                        <span>{{ t('total_due') }}</span>
                        <span class="text-right font-black font-english text-lg text-slate-900 dark:text-white">{{ toFixed(invoice.account_book_balance_val) }}</span>
                    </div>
                </div>
            </div>

            <!-- Gift list details -->
            <div v-if="giftTransactions.length > 0" class="mt-8 border-t border-slate-200 dark:border-slate-700 pt-5">
                <h3 class="text-lg font-bold text-slate-850 dark:text-white mb-3">
                    <i class="fas fa-gift text-purple-500 mr-1.5"></i> {{ t('gift_details') }}
                </h3>
                <div class="overflow-x-auto w-full md:w-[450px]">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700 text-[#495057] dark:text-slate-400 font-bold bg-[#f8f9fa] dark:bg-slate-900">
                                <th class="p-2 text-left">#</th>
                                <th class="p-2 text-left w-[70%]">গিফট</th>
                                <th class="p-2 text-right pr-4 w-[25%]">সংখ্যা</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(gt, idx) in giftTransactions" :key="gt.id" class="border-b border-slate-100 dark:border-slate-800/50">
                                <td class="p-2 font-english">{{ idx + 1 }}</td>
                                <td class="p-2 text-slate-750 dark:text-slate-200 font-semibold">{{ gt.gift?.name }}</td>
                                <td class="p-2 text-right font-black font-english pr-4 text-slate-800 dark:text-slate-105">{{ gt.count }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
