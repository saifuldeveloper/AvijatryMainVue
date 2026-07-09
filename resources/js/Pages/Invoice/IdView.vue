<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    invoice: {
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
    const fallbacks = {
        en: {
            'shoe_sales_id_receipt': 'Shoe Sales ID Receipt',
            'memo_no': 'Memo No',
            'date': 'Date',
            'name': 'Name',
            'mobile_no': 'Mobile No',
            'View without ID': 'View without ID',
            'Print': 'Print',
            'edit': 'Edit',
            'delete': 'Delete',
            'image': 'Image',
            'id': 'ID',
            'type': 'Type',
            'color': 'Color',
            'selling_price': 'Selling Price',
            'pair': 'Pairs',
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
            'deposits': 'Deposit',
            'previous_due': 'Previous Due',
            'total_due': 'Total Due',
            'gift_details': 'Gift Details',
            'gift': 'Gift',
            'count': 'Count',
            'confirm_delete': 'Are you sure you want to delete this invoice?',
        },
        bn: {
            'shoe_sales_id_receipt': 'জুতা বিক্রয়ের রশিদ (আইডি সহ)',
            'memo_no': 'মেমো নং',
            'date': 'তারিখ',
            'name': 'নাম',
            'mobile_no': 'মোবাইল নং',
            'View without ID': 'আইডি ছাড়া দেখুন',
            'Print': 'প্রিন্ট',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'image': 'ছবি',
            'id': 'আইডি',
            'type': 'টাইপ',
            'color': 'রং',
            'selling_price': 'গায়ের দাম',
            'pair': 'জোড়া',
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
            'deposits': 'জমা',
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
    <Head :title="t('shoe_sales_id_receipt')" />

    <AuthenticatedLayout>
        <!-- Print CSS injection -->
        <component is="style">
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
                    font-size: 16px !important;
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
                            <td class="p-3 w-[70%] text-left font-bold text-slate-850 dark:text-white">
                                মেমো নং: <strong class="font-black font-english">{{ invoice.id }}</strong>
                            </td>
                            <td class="p-3 w-[30%] text-right font-bold text-slate-850 dark:text-white font-english">
                                তারিখ: <strong>{{ formattedDate }}</strong>
                            </td>
                        </tr>
                        <tr class="bg-white dark:bg-slate-800">
                            <td class="p-3 text-left align-top leading-relaxed text-slate-700 dark:text-slate-200">
                                নাম: <strong class="font-bold text-slate-900 dark:text-white">{{ invoice.account_book?.retail_account?.shop_name }}</strong><br />
                                মোবাইল নং: <strong class="font-bold font-english">{{ invoice.account_book?.retail_account?.mobile_no || '-' }}</strong>
                            </td>
                            <td class="p-3 text-right align-top hide-on-print">
                                <div class="flex items-center justify-end gap-1.5 pt-1.5">
                                    <!-- Regular View Link -->
                                    <Link
                                        :href="route('invoice.show', { invoice: invoice.id })"
                                        class="bg-[#28a745] hover:bg-[#218838] border border-[#28a745] text-white font-bold px-3 py-1.5 rounded text-xs transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer h-[32px]"
                                    >
                                        {{ t('View without ID') }}
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

            <!-- Detailed Itemized Table -->
            <div class="overflow-x-auto mb-6">
                <table class="w-full border-collapse border border-[#dee2e6] dark:border-slate-700 text-sm">
                    <thead>
                        <tr class="bg-[#f8f9fa] dark:bg-slate-900 border-b border-[#dee2e6] dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold text-center">
                            <th class="p-2 border-r border-[#dee2e6] dark:border-slate-700 w-16">{{ t('image') }}</th>
                            <th class="p-2 border-r border-[#dee2e6] dark:border-slate-700 w-[15%]">{{ t('id') }}</th>
                            <th class="p-2 border-r border-[#dee2e6] dark:border-slate-700 w-[20%]">{{ t('type') }}</th>
                            <th class="p-2 border-r border-[#dee2e6] dark:border-slate-700 w-[15%]">{{ t('color') }}</th>
                            <th class="p-2 border-r border-[#dee2e6] dark:border-slate-700 w-[20%]">{{ t('selling_price') }}</th>
                            <th class="p-2 border-r border-[#dee2e6] dark:border-slate-700 w-[15%]">{{ t('pair') }}</th>
                            <th class="p-2 w-[15%] text-right pr-4">{{ t('total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="entry in invoice.invoice_entries" :key="entry.id" class="border-b border-[#dee2e6]/70 dark:border-slate-700/60 text-slate-750 dark:text-slate-200 text-center">
                            <td class="p-1 border-r border-[#dee2e6] dark:border-slate-700">
                                <img
                                    v-if="entry.shoe?.image"
                                    :src="`/images/small-thumbnail/${entry.shoe.image}`"
                                    alt="Shoe Thumbnail"
                                    class="w-12 h-12 object-cover rounded mx-auto border dark:border-slate-700"
                                />
                                <img
                                    v-else
                                    src="/img/shoe.png"
                                    alt="Default Shoe"
                                    class="w-12 h-12 object-cover rounded mx-auto border dark:border-slate-700"
                                />
                            </td>
                            <td class="p-2 border-r border-[#dee2e6] dark:border-slate-700 font-english font-bold">
                                {{ entry.shoe?.code || entry.shoe_id }}
                            </td>
                            <td class="p-2 border-r border-[#dee2e6] dark:border-slate-700 font-bold">
                                {{ entry.shoe?.category?.parent?.name }}-{{ entry.shoe?.category?.name }}
                            </td>
                            <td class="p-2 border-r border-[#dee2e6] dark:border-slate-700 font-semibold">
                                {{ entry.shoe?.color?.name }}
                            </td>
                            <td class="p-2 border-r border-[#dee2e6] dark:border-slate-700 font-english font-bold">
                                {{ toFixed(entry.retail_price > 0 ? entry.retail_price : entry.shoe?.retail_price) }}
                            </td>
                            <td class="p-2 border-r border-[#dee2e6] dark:border-slate-700 font-english font-black">
                                {{ entry.count }}
                            </td>
                            <td class="p-2 text-right font-english pr-4 font-black">
                                {{ toFixed((entry.retail_price > 0 ? entry.retail_price : entry.shoe?.retail_price) * entry.count) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Totals & Balances -->
            <div class="flex justify-end mb-6 font-nikosh">
                <div class="w-full md:w-[450px]">
                    <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-300">
                        <span>মোট</span>
                        <span class="text-right font-black font-english">{{ toFixed(invoice.total_amount_val) }}</span>
                    </div>
                    <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-350 hide-on-print">
                        <span>কমিশন ({{ invoice.commission }}%)</span>
                        <span class="text-right font-bold font-english">(-) {{ toFixed(invoice.total_commission_val) }}</span>
                    </div>
                    <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-800 dark:text-white font-bold">
                        <span>কমিশন বাদে মোট</span>
                        <span class="text-right font-black font-english">{{ toFixed(invoice.commission_deducted_val) }}</span>
                    </div>
                    <div v-if="invoice.return_amount_val > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-350">
                        <span>{{ invoice.return_count_val }} জোড়া ফেরত বাবদ</span>
                        <span class="text-right font-bold font-english">(-) {{ toFixed(invoice.return_amount_val) }}</span>
                    </div>
                    <div v-if="invoice.return_amount_val > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-800 dark:text-white font-bold">
                        <span>ফেরত বাদে মোট</span>
                        <span class="text-right font-black font-english">{{ toFixed(invoice.return_deducted_val) }}</span>
                    </div>
                    <div v-if="invoice.transport > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-350">
                        <span>পাঠানোর খরচ</span>
                        <span class="text-right font-bold font-english">(+) {{ toFixed(invoice.transport) }}</span>
                    </div>
                    <div v-if="invoice.transport > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-800 dark:text-white font-bold">
                        <span>পাঠানোর খরচ সহ</span>
                        <span class="text-right font-black font-english">{{ toFixed(invoice.transport_added_val) }}</span>
                    </div>
                    <div v-if="invoice.other_costs_val > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-350">
                        <span>অন্যান্য খরচ</span>
                        <span class="text-right font-bold font-english">(-) {{ toFixed(invoice.other_costs_val) }}</span>
                    </div>
                    <div v-if="invoice.other_costs_val > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-800 dark:text-white font-bold">
                        <span>অন্যান্য খরচ বাদে</span>
                        <span class="text-right font-black font-english">{{ toFixed(invoice.other_costs_deducted_val) }}</span>
                    </div>
                    <div v-if="invoice.discount > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-350">
                        <span>ডিসকাউন্ট</span>
                        <span class="text-right font-bold font-english">(-) {{ toFixed(invoice.discount) }}</span>
                    </div>
                    <div class="grid grid-cols-2 py-2 border-b-[2px] border-b-slate-300 dark:border-b-slate-600 text-sm font-extrabold bg-[#f8f9fa] dark:bg-slate-900 px-2 rounded">
                        <span class="text-blue-600 dark:text-blue-400">সর্বমোট</span>
                        <span class="text-right font-black font-english text-blue-600 dark:text-blue-400 text-base">{{ toFixed(invoice.total_receivable_val) }}</span>
                    </div>
                    <div v-if="invoice.total_payment_val > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-750 dark:text-slate-200">
                        <span>জমা</span>
                        <span class="text-right font-bold font-english">{{ toFixed(invoice.total_payment_val) }}</span>
                    </div>
                    <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm text-slate-750 dark:text-slate-300">
                        <span>সাবেক বাকী</span>
                        <span class="text-right font-bold font-english">(+) {{ toFixed(invoice.account_book_previous_balance_val) }}</span>
                    </div>
                    <div class="grid grid-cols-2 py-2 border-t border-t-slate-300 dark:border-t-slate-600 text-base font-black text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-950 px-2 rounded">
                        <span>মোট বাকী</span>
                        <span class="text-right font-black font-english text-lg text-slate-900 dark:text-white">{{ toFixed(invoice.account_book_balance_val) }}</span>
                    </div>
                </div>
            </div>

            <!-- Gift Details -->
            <div v-if="invoice.gift_transactions && invoice.gift_transactions.length > 0" class="mt-8 border-t border-slate-200 dark:border-slate-700 pt-5">
                <h3 class="text-lg font-bold text-slate-850 dark:text-white mb-3">
                    <i class="fas fa-gift text-purple-500 mr-1.5"></i> গিফটের বিবরণ
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
                            <tr v-for="(gt, idx) in invoice.gift_transactions" :key="gt.id" class="border-b border-slate-100 dark:border-slate-800/50">
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
