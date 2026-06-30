<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';

const props = defineProps({
    giftPurchase: {
        type: Object,
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
            'gift_purchase_receipt': 'Gift purchase receipt',
            'memo_no': 'Memo No',
            'date': 'Date',
            'name': 'Name',
            'address': 'Address',
            'mobile_no': 'Mobile No',
            'details': 'Details',
            'quantity': 'Quantity',
            'price': 'Price',
            'taka': 'Taka',
            'total': 'Total',
            'edit': 'Edit',
            'delete': 'Delete',
            'back': 'Back to Supplier Book',
            'Are you sure?': 'Are you sure you want to delete this memo?',
        },
        bn: {
            'gift_purchase_receipt': 'গিফট ক্রয় রসিদ',
            'memo_no': 'মেমো নং',
            'date': 'তারিখ',
            'name': 'নাম',
            'address': 'ঠিকানা',
            'mobile_no': 'মোবাইল নং',
            'details': 'বিবরণ',
            'quantity': 'পরিমাণ',
            'price': 'দর',
            'taka': 'টাকা',
            'total': 'মোট',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'back': 'মহাজন খাতায় ফিরুন',
            'Are you sure?': 'আপনি কি নিশ্চিত এই মেমোটি মুছতে চান?',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

const deleteMemo = () => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('gift-purchase.destroy', props.giftPurchase.id));
    }
};

const supplier = props.giftPurchase.account_book?.gift_supplier_account || {};
const totalAmt = parseFloat(props.giftPurchase.total_amount) || 0;
</script>

<template>
    <Head :title="t('gift_purchase_receipt') + ' #' + giftPurchase.id" />

    <AuthenticatedLayout>
        <!-- Card container matching the exact layout and width of old Blade project -->
        <div class="border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8 w-full max-w-7xl mx-auto">
            <!-- Header Row with Back and Edit/Delete Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-200 dark:border-slate-700 pb-3 mb-4 gap-4">
                <h1 class="text-3xl font-black text-slate-800 dark:text-white mb-0">
                    {{ t('gift_purchase_receipt') }}
                </h1>
                
                <div class="flex items-center gap-2 select-none">
                    <Link
                        :href="route('account-book.show', giftPurchase.account_book_id)"
                        class="bg-slate-500 hover:bg-slate-600 text-white px-3 py-1.5 text-xs font-semibold rounded transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer"
                    >
                        <i class="fa fa-arrow-left"></i>
                        <span>{{ t('back') }}</span>
                    </Link>
                    <Link
                        v-if="giftPurchase.account_book?.open"
                        :href="route('gift-purchase.edit', giftPurchase.id)"
                        class="bg-[#007bff] hover:bg-[#0069d9] text-white px-3 py-1.5 text-xs font-semibold rounded transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer"
                    >
                        <i class="fa fa-edit"></i>
                        <span>{{ t('edit') }}</span>
                    </Link>
                    <button
                        v-if="giftPurchase.account_book?.open"
                        @click="deleteMemo"
                        class="bg-[#dc3545] hover:bg-[#c82333] text-white px-3 py-1.5 text-xs font-semibold rounded transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer"
                    >
                        <i class="fa fa-trash"></i>
                        <span>{{ t('delete') }}</span>
                    </button>
                </div>
            </div>

            <!-- Memo No, Date and Supplier Info Layout matching show.blade.php -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6 text-sm text-slate-800 dark:text-slate-200">
                <div class="space-y-1.5">
                    <p class="m-0">{{ t('memo_no') }}: <strong class="text-slate-950 dark:text-white">{{ giftPurchase.id }}</strong></p>
                    <p class="m-0">{{ t('date') }}: <strong class="text-slate-955 dark:text-white">{{ new Date(giftPurchase.created_at).toLocaleDateString('en-GB') }}</strong></p>
                </div>
                <div class="space-y-1.5 sm:text-right">
                    <p class="m-0">{{ t('name') }}: <strong class="text-slate-955 dark:text-white">{{ supplier.name || 'N/A' }}</strong></p>
                    <p class="m-0">{{ t('address') }}: <strong class="text-slate-955 dark:text-white">{{ supplier.address || 'N/A' }}</strong></p>
                    <p class="m-0">{{ t('mobile_no') }}: <strong class="text-slate-955 dark:text-white">{{ supplier.mobile_no || 'N/A' }}</strong></p>
                </div>
            </div>

            <!-- Items Table matching the structure and CSS styles of show.blade.php -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-[15px]">
                    <thead>
                        <tr class="border-b-[2px] border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-bold">
                            <th class="px-4 py-2.5 w-16 text-center">#</th>
                            <th class="px-4 py-2.5 w-[52%]">{{ t('details') }}</th>
                            <th class="px-4 py-2.5 w-[16%] text-center">{{ t('quantity') }}</th>
                            <th class="px-4 py-2.5 w-[16%] text-center">{{ t('price') }}</th>
                            <th class="px-4 py-2.5 w-[16%] text-right pr-6">{{ t('taka') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/60 text-slate-800 dark:text-slate-200">
                        <tr
                            v-for="(item, index) in giftPurchase.gift_transactions"
                            :key="item.id"
                            class="odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 transition"
                        >
                            <td class="px-4 py-2.5 text-center font-medium">{{ index + 1 }}</td>
                            <td class="px-4 py-2.5 font-semibold text-blue-600 dark:text-blue-400">
                                {{ item.gift?.name || 'N/A' }}
                            </td>
                            <td class="px-4 py-2.5 text-center">{{ item.count }}</td>
                            <td class="px-4 py-2.5 text-center">
                                {{ parseFloat(item.unit_price) > 0 ? parseFloat(item.unit_price).toFixed(2) : 'পেন্ডিং' }}
                            </td>
                            <td class="px-4 py-2.5 text-right pr-6 font-bold">
                                {{ parseFloat(item.amount).toFixed(2) }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50/20 font-bold">
                            <td colspan="4" class="px-4 py-4 text-right text-slate-800 dark:text-white">
                                {{ t('total') }} = 
                            </td>
                            <td class="px-4 py-4 text-right pr-6 text-slate-950 dark:text-white font-black text-[16px]">
                                {{ totalAmt.toFixed(2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
