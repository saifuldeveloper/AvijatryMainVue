<script setup>
import { ref, watch, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    pagination: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['page-changed']);

const page = usePage();

// Local copy of current page for input bind
const inputPage = ref(props.pagination.current_page);

watch(() => props.pagination.current_page, (newPage) => {
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
            'previous page': 'Previous Page',
            'next page': 'Next Page',
            'page': 'Page',
            'show': 'Show',
            'zeroRecords': 'Page number out of range',
        },
        bn: {
            'previous page': 'পূর্ববর্তী পাতা',
            'next page': 'পরবর্তী পাতা',
            'page': 'পাতা',
            'show': 'দেখুন',
            'zeroRecords': 'পৃষ্ঠা নম্বর সীমার বাইরে',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Compute total showing text
const showingText = computed(() => {
    const start = props.pagination.from || 0;
    const end = props.pagination.to || 0;
    const total = props.pagination.total || 0;
    
    const locale = page.props.locale || 'en';
    if (locale === 'bn') {
        return `সর্বমোট ${total} টির মধ্যে ${start} থেকে ${end} প্রদর্শন করা হচ্ছে`;
    }
    return `Showing ${start} to ${end} of ${total} entries`;
});

const goToPage = (pageNumber) => {
    if (pageNumber >= 1 && pageNumber <= props.pagination.last_page) {
        emit('page-changed', pageNumber);
    }
};

const handlePaginationSubmit = () => {
    const pageNum = parseInt(inputPage.value, 10);
    if (pageNum >= 1 && pageNum <= props.pagination.last_page) {
        goToPage(pageNum);
    } else {
        alert(t('pages.zeroRecords'));
        inputPage.value = props.pagination.current_page;
    }
};
</script>

<template>
    <div v-if="props.pagination.last_page > 1" class="mt-5 border-t border-slate-200 dark:border-slate-700/60 pt-4">
        <div class="text-center text-xs text-slate-500 dark:text-slate-300 font-bold mb-3">
            {{ showingText }}
        </div>
        
        <nav class="flex justify-center items-center">
            <form @submit.prevent="handlePaginationSubmit" class="flex flex-flow row wrap items-center justify-center gap-3">
                <!-- Prev Page Button -->
                <button
                    type="button"
                    @click="goToPage(props.pagination.current_page - 1)"
                    :disabled="props.pagination.current_page === 1"
                    class="border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 px-3 py-1.5 rounded-[7px] text-xs font-semibold transition disabled:opacity-40 disabled:cursor-not-allowed select-none"
                >
                    &larr; {{ t('pages.previous page') }}
                </button>

                <!-- Jump Page Input Group -->
                <div class="flex items-center border border-slate-300 dark:border-slate-600 rounded-[7px] overflow-hidden bg-white dark:bg-slate-800 h-8">
                    <span class="bg-slate-50 dark:bg-slate-700 px-3 h-full flex items-center text-xs text-slate-600 dark:text-slate-200 border-r border-slate-300 dark:border-slate-600 font-bold">
                        {{ t('pages.page') }}
                    </span>
                    <input
                        type="number"
                        v-model="inputPage"
                        class="w-16 h-full text-center bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none text-xs border-none focus:ring-0 focus:ring-offset-0 font-bold"
                        :min="1"
                        :max="props.pagination.last_page"
                    />
                    <span class="bg-slate-50 dark:bg-slate-700 px-3 h-full flex items-center text-xs text-slate-600 dark:text-slate-200 border-l border-slate-300 dark:border-slate-600 font-bold">
                        / {{ props.pagination.last_page }}
                    </span>
                </div>

                <!-- Show Button -->
                <button
                    type="submit"
                    class="bg-slate-600 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-semibold rounded-[7px] px-3.5 py-1.5 text-xs transition duration-150 h-8 flex items-center"
                >
                    {{ t('pages.show') }}
                </button>

                <!-- Next Page Button -->
                <button
                    type="button"
                    @click="goToPage(props.pagination.current_page + 1)"
                    :disabled="props.pagination.current_page === props.pagination.last_page"
                    class="border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 px-3 py-1.5 rounded-[7px] text-xs font-semibold transition disabled:opacity-40 disabled:cursor-not-allowed select-none"
                >
                    {{ t('pages.next page') }} &rarr;
                </button>
            </form>
        </nav>
    </div>
</template>
