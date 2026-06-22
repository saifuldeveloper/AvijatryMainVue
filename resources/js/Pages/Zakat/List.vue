<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    closingPeriods: {
        type: Array,
        required: true,
    },
    zakatRecords: {
        type: Array,
        required: true,
    },
    trashRecords: {
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
            'Zakat Management': 'Zakat Management',
            'Record List': 'Record List',
            'Fiscal year': 'Fiscal Year',
            'Total Assets': 'Total Assets',
            'Zakat Payable': 'Zakat Payable',
            'option': 'Option',
            'date': 'Date',
            'sl': 'SL',
            'delete list': 'Deleted Zakat calculations',
            'restore': 'Restore',
            'force_delete': 'Delete Permanently',
            'Are you sure?': 'Are you sure?',
            'No records found.': 'No records found.',
            'Zakat Record Details': 'Zakat Record Details',
            'System Assets': 'System Calculated Assets',
            'Custom Assets': 'Custom Assets Detail',
            'Total Combined': 'Total Combined Assets',
            'Zakat Payable (2.5%)': 'Zakat Payable (2.5%)',
            'Close': 'Close',
            'back': 'Zakat Worksheet',
        },
        bn: {
            'Zakat Management': 'যাকাত হিসাব ব্যবস্থাপনা',
            'Record List': 'পূর্ববর্তী যাকাত হিসাব তালিকা',
            'Fiscal year': 'অর্থবছর',
            'Total Assets': 'মোট মূলধন',
            'Zakat Payable': 'প্রদেয় যাকাত (২.৫%)',
            'option': 'অপশন',
            'date': 'তারিখ',
            'sl': 'ক্রমিক',
            'delete list': 'মুছে ফেলা যাকাত হিসাবসমূহ',
            'restore': 'পুনরুদ্ধার',
            'force_delete': 'স্থায়ীভাবে মুছুন',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'Zakat Record Details': 'যাকাত হিসাব বিবরণী',
            'System Assets': 'সিস্টেম হিসাবকৃত সম্পদ',
            'Custom Assets': 'অতিরিক্ত সম্পদ বিবরণী',
            'Total Combined': 'সর্বমোট সম্পদ',
            'Zakat Payable (2.5%)': 'প্রদেয় যাকাত (২.৫%)',
            'Close': 'বন্ধ করুন',
            'back': 'যাকাত ওয়ার্কশিট',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Details Modal State
const isModalOpen = ref(false);
const activeRecord = ref(null);

const showDetails = (record) => {
    activeRecord.value = record;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    activeRecord.value = null;
};

// Actions
const deleteRecord = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('zakat.calculate.destroy', id), {
            preserveScroll: true
        });
    }
};

const restoreRecord = (id) => {
    router.post(route('zakat.calculate.restore', id), {}, {
        preserveScroll: true
    });
};

const forceDeleteRecord = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('zakat.calculate.forceDelete', id), {
            preserveScroll: true
        });
    }
};

// Format date helper
const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<template>
    <Head :title="t('Record List')" />

    <AuthenticatedLayout>
        <!-- Back Button -->
        <div class="mb-4">
            <a
                :href="route('zakat.page')"
                class="bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition shadow-sm flex items-center gap-1.5 w-fit"
            >
                <i class="fa fa-calculator"></i>
                <span>{{ t('back') }}</span>
            </a>
        </div>

        <!-- Active Records Card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-[20px] font-bold text-slate-800 dark:text-white">
                    {{ t('Record List') }}
                </h3>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse text-center">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 w-16">#</th>
                            <th class="px-4 py-2.5 text-left">{{ t('date') }}</th>
                            <th class="px-4 py-2.5">{{ t('Fiscal year') }}</th>
                            <th class="px-4 py-2.5">{{ t('Total Assets') }}</th>
                            <th class="px-4 py-2.5 text-green-600 dark:text-green-450">{{ t('Zakat Payable') }}</th>
                            <th class="px-4 py-2.5 w-28">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(record, index) in zakatRecords"
                            :key="record.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5">{{ index + 1 }}</td>
                            <td class="px-4 py-2.5 text-left">{{ formatDate(record.created_at) }}</td>
                            <td class="px-4 py-2.5 font-bold">
                                {{ record.fiscal_year?.yr ?? (record.fiscal_year_id ? 'Year ' + record.fiscal_year_id : 'N/A') }}
                            </td>
                            <td class="px-4 py-2.5 font-extrabold">
                                {{ parseFloat(record.total_assets).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                            </td>
                            <td class="px-4 py-2.5 text-green-600 dark:text-green-400 font-black">
                                {{ parseFloat(record.zakat_payable).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="showDetails(record)"
                                        class="bg-[#17a2b8] hover:bg-[#138496] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="View Details"
                                    >
                                        <i class="fa fa-eye text-xs"></i>
                                    </button>
                                    <button
                                        @click="deleteRecord(record.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Delete"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="zakatRecords.length === 0">
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No records found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Trashed Records Card -->
        <div v-if="trashRecords.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-850 dark:text-white">
                    {{ t('delete list') }}
                </h4>
            </div>

            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse text-center">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 w-16">#</th>
                            <th class="px-4 py-2.5 text-left">{{ t('date') }}</th>
                            <th class="px-4 py-2.5">{{ t('Fiscal year') }}</th>
                            <th class="px-4 py-2.5">{{ t('Total Assets') }}</th>
                            <th class="px-4 py-2.5 text-green-600 dark:text-green-450">{{ t('Zakat Payable') }}</th>
                            <th class="px-4 py-2.5 w-56">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(record, index) in trashRecords"
                            :key="record.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5">{{ index + 1 }}</td>
                            <td class="px-4 py-2.5 text-left">{{ formatDate(record.created_at) }}</td>
                            <td class="px-4 py-2.5">{{ record.fiscal_year?.yr ?? (record.fiscal_year_id ? 'Year ' + record.fiscal_year_id : 'N/A') }}</td>
                            <td class="px-4 py-2.5 font-bold">{{ parseFloat(record.total_assets).toLocaleString() }}</td>
                            <td class="px-4 py-2.5 text-green-600 dark:text-green-450 font-bold">{{ parseFloat(record.zakat_payable).toLocaleString() }}</td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="restoreRecord(record.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm"
                                    >
                                        <i class="fa fa-undo text-xs"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteRecord(record.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center gap-1.5 transition duration-150 shadow-sm"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                        <span>{{ t('force_delete') }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Details Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal"></div>
            <div class="relative w-full max-w-lg mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t bg-info/10">
                        <h3 class="text-lg font-bold text-slate-850 dark:text-white flex items-center gap-2">
                            🕌 {{ t('Zakat Record Details') }}
                        </h3>
                        <button
                            @click="closeModal"
                            class="p-1 ml-auto bg-transparent border-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                        >
                            ×
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6">
                        <table class="w-full text-sm border border-slate-200 dark:border-slate-700 border-collapse text-left">
                            <tbody>
                                <tr class="border-b border-slate-200 dark:border-slate-700">
                                    <th class="px-4 py-2.5 bg-slate-50 dark:bg-slate-900 font-bold text-slate-600 dark:text-slate-400 w-1/2">
                                        {{ t('System Assets') }}
                                    </th>
                                    <td class="px-4 py-2.5 font-semibold text-slate-800 dark:text-slate-200">
                                        {{ parseFloat(activeRecord?.system_assets || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                                    </td>
                                </tr>
                                <tr class="border-b border-slate-200 dark:border-slate-700" v-if="activeRecord?.custom_assets && activeRecord.custom_assets.length > 0">
                                    <th class="px-4 py-2.5 bg-slate-50 dark:bg-slate-900 font-bold text-slate-600 dark:text-slate-400">
                                        {{ t('Custom Assets') }}
                                    </th>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">
                                        <div v-for="(asset, idx) in activeRecord.custom_assets" :key="idx" class="text-xs mb-1">
                                            <strong>{{ asset.label }}:</strong> {{ parseFloat(asset.amount).toLocaleString() }}
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-b border-slate-200 dark:border-slate-700 bg-blue-50/50 dark:bg-blue-900/10 font-bold">
                                    <th class="px-4 py-2.5 text-blue-600 dark:text-blue-400">
                                        {{ t('Total Combined') }}
                                    </th>
                                    <td class="px-4 py-2.5 text-blue-600 dark:text-blue-400 font-extrabold text-base">
                                        {{ parseFloat(activeRecord?.total_assets || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                                    </td>
                                </tr>
                                <tr class="bg-green-50/50 dark:bg-green-900/10 font-bold">
                                    <th class="px-4 py-2.5 text-green-600 dark:text-green-400">
                                        {{ t('Zakat Payable (2.5%)') }}
                                    </th>
                                    <td class="px-4 py-2.5 text-green-600 dark:text-green-400 font-extrabold text-base">
                                        {{ parseFloat(activeRecord?.zakat_payable || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end p-5 border-t border-solid border-slate-200 dark:border-slate-700 rounded-b gap-2">
                        <button
                            type="button"
                            @click="closeModal"
                            class="bg-slate-500 hover:bg-slate-600 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm"
                        >
                            {{ t('Close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
