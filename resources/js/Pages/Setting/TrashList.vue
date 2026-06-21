<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router, Link } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const props = defineProps({
    logs: {
        type: Object,
        required: true,
    },
    users: {
        type: Array,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
});

const page = usePage();

// Local filters state
const logName = ref(props.filters.log_name || '');
const causerId = ref(props.filters.causer_id || '');
const perPage = ref(props.filters.per_page || 50);

// Date range parsing helper
const getInitialDates = () => {
    if (props.filters.daterange) {
        const parts = props.filters.daterange.split(' - ');
        if (parts.length === 2) {
            return { start: parts[0], end: parts[1] };
        }
    }
    return { start: '', end: '' };
};

const initialDates = getInitialDates();
const startDate = ref(initialDates.start);
const endDate = ref(initialDates.end);

// Input page state for pagination Form
const inputPage = ref(props.logs.current_page);

// Update input page if pagination changes
watch(() => props.logs.current_page, (newPage) => {
    inputPage.value = newPage;
});

// Translation helper
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
            'activity_log': 'Activity Log',
            'symbol': 'Symbol',
            'user': 'User',
            'ip': 'IP',
            'message': 'Message',
            'device': 'Device',
            'time': 'Time',
            'option': 'Option',
            'filter': 'Filter',
            'clear': 'Clear',
            'select': 'Select',
            'previous page': 'Previous Page',
            'next page': 'Next Page',
            'page': 'Page',
            'show': 'Show',
            'delete list': 'Delete List',
            'No activity logs found.': 'No activity logs found.'
        },
        bn: {
            'activity_log': 'অ্যাক্টিভিটি লগ',
            'symbol': 'প্রতীক',
            'user': 'ব্যবহারকারী',
            'ip': 'আইপি',
            'message': 'বার্তা',
            'device': 'ডিভাইস',
            'time': 'সময়',
            'option': 'অপশন',
            'filter': 'ফিল্টার',
            'clear': 'পরিষ্কার করুন',
            'select': 'নির্বাচন করুন',
            'previous page': 'পূর্ববর্তী পাতা',
            'next page': 'পরবর্তী পাতা',
            'page': 'পাতা',
            'show': 'দেখুন',
            'delete list': 'ডিলিট তালিকা',
            'No activity logs found.': 'কোনো অ্যাক্টিভিটি লগ পাওয়া যায়নি।'
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Compute total showing text
const showingText = computed(() => {
    const start = props.logs.from || 0;
    const end = props.logs.to || 0;
    const total = props.logs.total || 0;
    
    const locale = page.props.locale || 'en';
    if (locale === 'bn') {
        return `সর্বমোট ${total} টির মধ্যে ${start} থেকে ${end} প্রদর্শন করা হচ্ছে`;
    }
    return `Showing ${start} to ${end} of ${total} entries`;
});

// Trigger search
const filterLogs = () => {
    let daterangeVal = '';
    if (startDate.value && endDate.value) {
        daterangeVal = `${startDate.value} - ${endDate.value}`;
    }
    router.get(route('trash.list'), {
        daterange: daterangeVal,
        causer_id: causerId.value,
        log_name: logName.value,
        per_page: perPage.value,
        page: 1
    }, {
        preserveState: true,
        replace: true
    });
};

// Reset search
const clearFilters = () => {
    logName.value = '';
    causerId.value = '';
    startDate.value = '';
    endDate.value = '';
    perPage.value = 50;
    
    router.get(route('trash.list'), {}, {
        preserveState: false
    });
};

// Go to page
const goToPage = (pageNumber) => {
    if (pageNumber >= 1 && pageNumber <= props.logs.last_page) {
        let daterangeVal = '';
        if (startDate.value && endDate.value) {
            daterangeVal = `${startDate.value} - ${endDate.value}`;
        }
        router.get(route('trash.list'), {
            daterange: daterangeVal,
            causer_id: causerId.value,
            log_name: logName.value,
            per_page: perPage.value,
            page: pageNumber
        }, {
            preserveState: true
        });
    }
};

const handlePaginationSubmit = () => {
    const pageNum = parseInt(inputPage.value, 10);
    if (pageNum >= 1 && pageNum <= props.logs.last_page) {
        goToPage(pageNum);
    } else {
        alert(t('pages.zeroRecords'));
        inputPage.value = props.logs.current_page;
    }
};

// Toggle Filter Collapse
const isFilterCollapsed = ref(true);
const toggleFilter = () => {
    isFilterCollapsed.value = !isFilterCollapsed.value;
};
</script>

<template>
    <Head :title="t('pages.delete list')" />

    <AuthenticatedLayout>
        <!-- Card Container styled like AdminLTE card-primary card-outline -->
        <div class="border-t-[3px] border-t-rose-600 border-x border-b border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Card Header -->
            <div class="px-5 py-3 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-[#f8f9fa] dark:bg-[#242434]">
                <h2 class="text-xl font-bold text-slate-850 dark:text-white m-0 leading-tight">
                    <i class="fa fa-trash"></i> {{ t('pages.delete list') }}
                </h2>
                <div class="flex items-center gap-2">
                    <Link
                        :href="route('activity.log')"
                        class="bg-[#007bff] hover:bg-[#0069d9] text-white font-semibold rounded px-3 py-1.5 text-xs transition-colors duration-150 inline-flex items-center gap-1"
                    >
                        <i class="fa fa-list"></i> {{ t('pages.activity_log') }}
                    </Link>
                    <button
                        @click="toggleFilter"
                        class="bg-slate-650 hover:bg-slate-700 text-white font-semibold rounded px-3 py-1.5 text-xs transition-colors duration-150 inline-flex items-center gap-1"
                    >
                        <i class="fa fa-filter"></i> {{ t('pages.filter') }}
                    </button>
                </div>
            </div>

            <!-- Filter Panel -->
            <div v-show="!isFilterCollapsed" class="p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30 transition-all duration-300">
                <form @submit.prevent="filterLogs" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 items-end">
                    <!-- Start Date -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-350 mb-1">
                            Start Date / শুরুর তারিখ
                        </label>
                        <input
                            type="date"
                            v-model="startDate"
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm transition-all"
                        />
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-350 mb-1">
                            End Date / শেষের তারিখ
                        </label>
                        <input
                            type="date"
                            v-model="endDate"
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm transition-all"
                        />
                    </div>

                    <!-- User Selector -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-350 mb-1">
                            {{ t('pages.user') }}
                        </label>
                        <select
                            v-model="causerId"
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm transition-all"
                        >
                            <option value="">{{ t('pages.select') }}</option>
                            <option
                                v-for="user in props.users"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Message Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-350 mb-1">
                            {{ t('pages.message') }}
                        </label>
                        <input
                            type="text"
                            v-model="logName"
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm transition-all"
                            placeholder="Search message..."
                        />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 w-full">
                        <button
                            type="submit"
                            class="flex-1 bg-slate-650 hover:bg-slate-700 text-white font-semibold rounded py-2 text-xs transition duration-150 inline-flex items-center justify-center gap-1"
                        >
                            <i class="fa fa-search"></i> {{ t('pages.show') }}
                        </button>
                        <button
                            type="button"
                            @click="clearFilters"
                            class="flex-1 bg-[#dc3545] hover:bg-[#c82333] text-white font-semibold rounded py-2 text-xs transition duration-150 inline-flex items-center justify-center gap-1"
                        >
                            <i class="fa fa-broom"></i> {{ t('pages.clear') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Body (Table) -->
            <div class="p-5">
                <!-- Limit Selector -->
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Show Entries:</span>
                        <select
                            v-model="perPage"
                            @change="filterLogs"
                            class="rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 p-1 px-1.5 text-xs font-normal text-slate-800 dark:text-slate-300 focus:border-blue-500 outline-none"
                        >
                            <option :value="50">50</option>
                            <option :value="100">100</option>
                            <option :value="200">200</option>
                            <option :value="500">500</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-slate-200 dark:border-slate-700/80">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-[#383854] text-slate-700 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700/80">
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm" style="width: 15%">{{ t('pages.symbol') }}</th>
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm" style="width: 15%">{{ t('pages.user') }}</th>
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm" style="width: 12%">{{ t('pages.ip') }}</th>
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm" style="width: 25%">{{ t('pages.message') }}</th>
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm" style="width: 15%">{{ t('pages.device') }}</th>
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm" style="width: 13%">{{ t('pages.time') }}</th>
                                <th class="p-3 font-bold text-sm text-right" style="width: 5%">{{ t('pages.option') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/60 dark:divide-slate-700/40 text-slate-700 dark:text-slate-200">
                            <tr
                                v-for="log in props.logs.data"
                                :key="log.id"
                                class="hover:bg-slate-50/50 dark:hover:bg-slate-850/40 transition-colors duration-150"
                            >
                                <!-- Symbol Badge -->
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-medium">
                                    <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 px-2 py-0.5 rounded text-xs font-semibold border border-rose-200/55 dark:border-rose-900/30">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                        <svg v-if="log.viewed" class="w-3 h-3 text-slate-500 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </span>
                                </td>
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-semibold text-slate-800 dark:text-slate-100">
                                    {{ log.user_name }}
                                </td>
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-mono text-xs">
                                    {{ log.ip }}
                                </td>
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60 text-slate-700 dark:text-slate-350 leading-relaxed font-semibold">
                                    {{ log.message }}
                                </td>
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60 text-xs">
                                    <div class="font-semibold text-slate-800 dark:text-slate-250">{{ log.os }}</div>
                                    <div class="text-slate-400 dark:text-slate-500 font-medium">{{ log.browser }}</div>
                                </td>
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60 text-xs font-medium text-slate-500 dark:text-slate-400">
                                    {{ log.time }}
                                </td>
                                <td class="p-3 text-right">
                                    <a
                                        v-if="log.view_route !== '#'"
                                        :href="log.view_route"
                                        :target="log.target"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white font-semibold rounded px-2.5 py-1.5 text-xs transition duration-150 inline-flex items-center gap-1.5"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ t('pages.show') }}
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="props.logs.data.length === 0">
                                <td colspan="7" class="p-8 text-center text-slate-400 dark:text-slate-500 font-medium">
                                    {{ t('No activity logs found.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination Wrapper matching the old project's dataTablePagination layout -->
                <div class="mt-5 border-t border-slate-200 dark:border-slate-700/60 pt-4">
                    <div class="text-center text-xs text-slate-500 dark:text-slate-450 font-bold mb-3">
                        {{ showingText }}
                    </div>
                    
                    <nav class="flex justify-center items-center">
                        <form @submit.prevent="handlePaginationSubmit" class="flex flex-flow row wrap items-center justify-center gap-3">
                            <!-- Prev Page Button -->
                            <button
                                type="button"
                                @click="goToPage(props.logs.current_page - 1)"
                                :disabled="props.logs.current_page === 1"
                                class="border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-750 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-3 py-1.5 rounded-[7px] text-xs font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed select-none"
                            >
                                &larr; {{ t('pages.previous page') }}
                            </button>

                            <!-- Jump Page Input Group -->
                            <div class="flex items-center border border-slate-300 dark:border-slate-650 rounded-[7px] overflow-hidden bg-white dark:bg-slate-850 h-8">
                                <span class="bg-slate-50 dark:bg-slate-750 px-3 h-full flex items-center text-xs text-slate-600 dark:text-slate-300 border-r border-slate-300 dark:border-slate-650 font-bold">
                                    {{ t('pages.page') }}
                                </span>
                                <input
                                    type="number"
                                    v-model="inputPage"
                                    class="w-16 h-full text-center bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 outline-none text-xs border-none focus:ring-0 focus:ring-offset-0 font-bold"
                                    :min="1"
                                    :max="props.logs.last_page"
                                />
                                <span class="bg-slate-50 dark:bg-slate-750 px-3 h-full flex items-center text-xs text-slate-600 dark:text-slate-300 border-l border-slate-300 dark:border-slate-650 font-bold">
                                    / {{ props.logs.last_page }}
                                </span>
                            </div>

                            <!-- Show Button -->
                            <button
                                type="submit"
                                class="bg-slate-600 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-650 text-white font-semibold rounded-[7px] px-3.5 py-1.5 text-xs transition duration-150 h-8 flex items-center"
                            >
                                {{ t('pages.show') }}
                            </button>

                            <!-- Next Page Button -->
                            <button
                                type="button"
                                @click="goToPage(props.logs.current_page + 1)"
                                :disabled="props.logs.current_page === props.logs.last_page"
                                class="border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-750 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-3 py-1.5 rounded-[7px] text-xs font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed select-none"
                            >
                                {{ t('pages.next page') }} &rarr;
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
