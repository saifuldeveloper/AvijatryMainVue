<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    gift: {
        type: Object,
        required: true,
    },
    giftTransactions: {
        type: Object,
        required: true,
    },
    dates: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ daterange: '', type: '' }),
    },
});

const page = usePage();

// Translation helper
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
            'gift_details': 'Gift Details',
            'gift_name': 'Gift Name',
            'type': 'Type',
            'count': 'Count',
            'unit_price': 'Unit Price',
            'total_amount': 'Total Amount',
            'description': 'Description',
            'date': 'Date',
            'no_transactions': 'No transactions found.',
            'back': 'Back',
            'all_types': 'All Types',
            'purchase': 'Purchase',
            'sale': 'Sale',
            'waste': 'Waste',
            'use': 'Use',
        },
        bn: {
            'gift_details': 'গিফটের বিবরণ',
            'gift_name': 'গিফটের নাম',
            'type': 'ধরণ',
            'count': 'সংখ্যা',
            'unit_price': 'একক মূল্য',
            'total_amount': 'মোট টাকা',
            'description': 'মন্তব্য',
            'date': 'তারিখ',
            'no_transactions': 'কোনো লেনদেন পাওয়া যায়নি।',
            'back': 'ফিরে যান',
            'all_types': 'সকল ধরণ',
            'purchase': 'ক্রয়',
            'sale': 'বিক্রয়',
            'waste': 'অপচয়',
            'use': 'ব্যবহার',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

const filterType = ref(props.filters?.type || '');
const daterange = ref(props.filters?.daterange || '');

// Filtering with debounce
let filterTimeout;
watch([filterType, daterange], () => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('gift.transaction', props.gift.id), {
            type: filterType.value,
            daterange: daterange.value,
        }, {
            preserveState: true,
            replace: true
        });
    }, 400);
});
</script>

<template>
    <Head :title="gift.name" />

    <AuthenticatedLayout>
        <div class="mb-4">
            <a :href="route('gift.index')" class="inline-flex items-center text-[15px] font-semibold text-blue-600 hover:underline gap-1">
                &larr; {{ t('back') }}
            </a>
        </div>

        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-[20px] font-bold text-slate-800 dark:text-white">
                    {{ gift.name }} - {{ t('gift_details') }}
                </h3>

                <div class="flex items-center gap-3">
                    <select
                        v-model="filterType"
                        class="rounded border-slate-300 bg-white px-3 py-1.5 text-[15px] text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-slate-650 dark:bg-slate-900 dark:text-slate-200"
                    >
                        <option value="">{{ t('all_types') }}</option>
                        <option value="purchase">{{ t('purchase') }}</option>
                        <option value="sale">{{ t('sale') }}</option>
                        <option value="waste">{{ t('waste') }}</option>
                        <option value="use">{{ t('use') }}</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-750 text-[14px] text-slate-500 dark:text-slate-400 font-semibold">
                            <th class="px-6 py-3 w-16">#</th>
                            <th class="px-6 py-3">{{ t('date') }}</th>
                            <th class="px-6 py-3">{{ t('type') }}</th>
                            <th class="px-6 py-3">{{ t('count') }}</th>
                            <th class="px-6 py-3">{{ t('unit_price') }}</th>
                            <th class="px-6 py-3">{{ t('total_amount') }}</th>
                            <th class="px-6 py-3">{{ t('description') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-[15px] text-slate-700 dark:text-slate-300">
                        <tr v-if="giftTransactions.data.length === 0">
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400 dark:text-slate-500">
                                {{ t('no_transactions') }}
                            </td>
                        </tr>
                        <tr v-for="(tr, index) in giftTransactions.data" :key="tr.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition">
                            <td class="px-6 py-3.5">{{ index + 1 }}</td>
                            <td class="px-6 py-3.5">{{ new Date(tr.created_at).toLocaleDateString() }}</td>
                            <td class="px-6 py-3.5">
                                <span class="capitalize px-2 py-0.5 rounded text-[13px] font-semibold"
                                    :class="{
                                        'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': tr.type === 'purchase',
                                        'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400': tr.type === 'sale',
                                        'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400': tr.type === 'waste',
                                        'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400': tr.type === 'use',
                                    }"
                                >
                                    {{ t(tr.type) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 font-semibold">{{ tr.count }}</td>
                            <td class="px-6 py-3.5">{{ tr.unit_price > 0 ? tr.unit_price + ' ৳' : '-' }}</td>
                            <td class="px-6 py-3.5 font-bold">{{ tr.amount > 0 ? tr.amount + ' ৳' : '-' }}</td>
                            <td class="px-6 py-3.5 text-slate-500 dark:text-slate-400 text-[14px]">{{ tr.description || '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700" v-if="giftTransactions.last_page > 1">
                <Pagination :links="giftTransactions.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
