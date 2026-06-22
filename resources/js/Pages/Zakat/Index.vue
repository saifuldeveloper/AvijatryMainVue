<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch, onMounted, computed } from 'vue';

const props = defineProps({
    closingPeriods: {
        type: Array,
        required: true,
    },
    selectedYear: {
        type: Number,
        default: null,
    },
    netCapital: {
        type: Number,
        default: 0,
    },
    totalAssets: {
        type: Number,
        default: 0,
    },
    totalLiabilities: {
        type: Number,
        default: 0,
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
            'year': 'Year',
            'filter': 'Filter',
            'Add Custom Asset': 'Add Custom Asset',
            'Zakat Calculation Worksheet': 'Zakat Calculation Worksheet',
            'System Calculated Assets': 'System Calculated Assets',
            'Total Assets': 'Total Assets (Net Capital)',
            'Custom Asset / Addition': 'Custom Asset / Addition',
            'e.g. Cash in Hand, Gold, Debtors': 'e.g. Cash in Hand, Gold, Debtors',
            'Total Combined Assets': 'Total Combined Assets',
            'Zakat Payable': 'Zakat Payable',
            'Re-Calculate': 'Re-Calculate',
            'Save Record': 'Save Record',
            'processing': 'Processing...',
        },
        bn: {
            'Zakat Management': 'যাকাত হিসাব ও ব্যবস্থাপনা',
            'Record List': 'পূর্ববর্তী যাকাত হিসাবসমূহ',
            'Fiscal year': 'অর্থবছর',
            'year': 'বছর',
            'filter': 'ফিল্টার',
            'Add Custom Asset': 'নতুন অ্যাসেট যোগ করুন',
            'Zakat Calculation Worksheet': 'যাকাত হিসাব ওয়ার্কশিট',
            'System Calculated Assets': 'সিস্টেম হিসাবকৃত মূলধন',
            'Total Assets': 'মোট মূলধন (নেট ক্যাপিটাল)',
            'Custom Asset / Addition': 'অতিরিক্ত অ্যাসেট বিবরণ',
            'e.g. Cash in Hand, Gold, Debtors': 'যেমন: নগদ টাকা, স্বর্ণ, ইত্যাদি',
            'Total Combined Assets': 'সর্বমোট যাকাতযোগ্য সম্পদ',
            'Zakat Payable': 'প্রদেয় যাকাত',
            'Re-Calculate': 'পুনরায় হিসাব করুন',
            'Save Record': 'হিসাব সংরক্ষণ করুন',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Selected year state
const selectedYearState = ref(props.selectedYear || (props.closingPeriods[0]?.id || ''));

// Filter change triggers year reload
const onYearChange = () => {
    router.get(route('zakat.page'), {
        year: selectedYearState.value
    }, {
        preserveState: false
    });
};

// Custom dynamic assets
const customAssets = ref([]);

const addCustomField = () => {
    customAssets.value.push({ label: '', amount: 0 });
};

const removeCustomField = (index) => {
    customAssets.value.splice(index, 1);
};

// Form instantiation
const form = useForm({
    year: selectedYearState.value,
    netcapital_value: props.netCapital || 0,
    custom_label: [],
    custom_value: [],
});

// Sync form values on prop updates
watch(() => props.netCapital, (newVal) => {
    form.netcapital_value = newVal;
});

watch(selectedYearState, (newVal) => {
    form.year = newVal;
});

// Interactive calculation values
const totalCombinedAssets = computed(() => {
    let base = parseFloat(form.netcapital_value) || 0;
    customAssets.value.forEach(asset => {
        base += parseFloat(asset.amount) || 0;
    });
    return base;
});

const zakatPayableAmount = computed(() => {
    return totalCombinedAssets.value * 0.025;
});

const submitForm = () => {
    if (!selectedYearState.value) {
        alert('Please select a Fiscal Year first!');
        return;
    }
    // Populate form array fields from customAssets state
    form.custom_label = customAssets.value.map(a => a.label);
    form.custom_value = customAssets.value.map(a => a.amount);
    form.year = selectedYearState.value;

    form.post(route('zakat.store'));
};
</script>

<template>
    <Head :title="t('Zakat Management')" />

    <AuthenticatedLayout>
        <!-- Main Card Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-[20px] font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    🕌 {{ t('Zakat Management') }}
                </h3>
                <a
                    :href="route('zakat.calculate.list')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition duration-150 shadow-sm flex items-center gap-1.5"
                >
                    <i class="fa fa-list"></i>
                    <span>{{ t('Record List') }}</span>
                </a>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <!-- Year Selector Form -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 border-b border-slate-100 dark:border-slate-700 pb-6">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <span class="font-bold text-slate-700 dark:text-slate-300 text-sm whitespace-nowrap">{{ t('Fiscal year') }}:</span>
                        <select
                            v-model="selectedYearState"
                            @change="onYearChange"
                            class="h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 w-48 font-semibold"
                        >
                            <option value="">({{ t('year') }})</option>
                            <option
                                v-for="period in closingPeriods"
                                :key="period.id"
                                :value="period.id"
                            >
                                {{ period.yr }} - {{ new Date(period.end_date).getFullYear() }}
                            </option>
                        </select>
                    </div>

                    <button
                        type="button"
                        @click="addCustomField"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold rounded px-4 py-2 text-xs transition shadow-sm flex items-center gap-1.5 w-full sm:w-auto justify-center"
                    >
                        <i class="fa fa-plus-circle"></i>
                        <span>{{ t('Add Custom Asset') }}</span>
                    </button>
                </div>

                <!-- Worksheet Container -->
                <div class="max-w-2xl mx-auto border border-slate-200 dark:border-slate-700 rounded-lg p-6 bg-slate-50/50 dark:bg-slate-900/30">
                    <h5 class="text-center mb-6 text-slate-600 dark:text-slate-300 font-bold border-b border-slate-200 dark:border-slate-700 pb-3">
                        {{ t('Zakat Calculation Worksheet') }}
                    </h5>

                    <form @submit.prevent="submitForm">
                        <!-- System calculated assets -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-blue-600 dark:text-blue-400 mb-2">
                                {{ t('System Calculated Assets') }}
                            </label>
                            <div class="flex gap-2">
                                <input
                                    type="text"
                                    :value="t('Total Assets')"
                                    disabled
                                    class="w-1/2 h-10 rounded border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-850 text-slate-500 dark:text-slate-400 text-sm font-semibold"
                                />
                                <input
                                    v-model="form.netcapital_value"
                                    type="number"
                                    step="0.01"
                                    class="w-1/2 h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm font-bold focus:border-blue-500 focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <!-- Dynamic custom assets -->
                        <div
                            v-for="(asset, index) in customAssets"
                            :key="index"
                            class="mb-4 border-t border-slate-200 dark:border-slate-700 pt-3"
                        >
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                                {{ t('Custom Asset / Addition') }}
                            </label>
                            <div class="flex gap-2 items-center">
                                <input
                                    v-model="asset.label"
                                    type="text"
                                    required
                                    :placeholder="t('e.g. Cash in Hand, Gold, Debtors')"
                                    class="w-1/2 h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500"
                                />
                                <input
                                    v-model="asset.amount"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="w-5/12 h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500 font-bold"
                                />
                                <button
                                    type="button"
                                    @click="removeCustomField(index)"
                                    class="bg-red-600 hover:bg-red-700 text-white rounded p-2.5 text-xs transition shadow-sm inline-flex items-center justify-center shrink-0"
                                >
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Combined Result Box -->
                        <div class="mt-6 p-4 rounded-lg bg-slate-100 dark:bg-slate-900 border-l-4 border-blue-500 flex justify-between items-center">
                            <div>
                                <p class="text-xs uppercase font-extrabold text-slate-500 dark:text-slate-450 mb-1">
                                    {{ t('Total Combined Assets') }}
                                </p>
                                <h4 class="text-lg font-black text-slate-800 dark:text-white">
                                    {{ totalCombinedAssets.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                </h4>
                            </div>
                            <div class="text-right">
                                <p class="text-xs uppercase font-extrabold text-green-600 dark:text-green-450 mb-1">
                                    {{ t('Zakat Payable') }} (2.5%)
                                </p>
                                <h4 class="text-lg font-black text-green-600 dark:text-green-400">
                                    {{ zakatPayableAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                </h4>
                            </div>
                        </div>

                        <!-- Actions footer -->
                        <div class="mt-6 flex justify-center gap-2">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold rounded px-6 py-2 text-sm transition shadow-sm disabled:opacity-50 flex items-center gap-1.5"
                            >
                                <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status"></span>
                                <i v-else class="fa fa-save"></i>
                                <span>{{ form.processing ? t('processing') : t('Save Record') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
