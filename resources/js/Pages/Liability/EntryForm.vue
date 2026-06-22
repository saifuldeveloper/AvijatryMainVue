<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    accountBooks: {
        type: Array,
        required: true,
    },
    liability: {
        type: Object,
        default: null,
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
            'liabilites_entry_create': 'Create Liabilities Entry',
            'liabilites_entry_edit': 'Edit Liabilities Entry',
            'liabilities book name': 'Liabilities Account Book',
            'Liabilities Amount': 'Amount (Taka)',
            'date': 'Date',
            'details': 'Details / Note',
            'select': '-- Select --',
            'save': 'Save',
            'update': 'Update',
            'back': 'Back',
        },
        bn: {
            'liabilites_entry_create': 'নতুন দেনা এন্ট্রি',
            'liabilites_entry_edit': 'দেনা এন্ট্রি সম্পাদন',
            'liabilities book name': 'দেনা খাতা হিসাব',
            'Liabilities Amount': 'দেনার পরিমাণ (টাকা)',
            'date': 'তারিখ',
            'details': 'বিস্তারিত বিবরণ',
            'select': '-- নির্বাচন করুন --',
            'save': 'সংরক্ষণ করুন',
            'update': 'হালনাগাদ করুন',
            'back': 'ফিরে যান',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Form instantiation
const form = useForm({
    account_book_id: props.liability ? props.liability.account_book_id : '',
    total_amount: props.liability ? props.liability.total_amount : '',
    created_at: props.liability 
        ? new Date(props.liability.created_at).toISOString().split('T')[0] 
        : new Date().toISOString().split('T')[0],
    note: props.liability ? props.liability.description : '',
});

const submitForm = () => {
    if (props.liability) {
        form.put(route('liabilities.entry.update', props.liability.id));
    } else {
        form.post(route('liabilities.entry.store'));
    }
};

const goBack = () => {
    if (props.liability && props.liability.entry_id) {
        window.location.href = route('liabilities.show', props.liability.entry_id);
    } else {
        window.location.href = route('liabilities.index');
    }
};
</script>

<template>
    <Head :title="liability ? t('liabilites_entry_edit') : t('liabilites_entry_create')" />

    <AuthenticatedLayout>
        <!-- Back Button -->
        <div class="mb-4">
            <button
                @click="goBack"
                class="bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition shadow-sm flex items-center gap-1.5"
            >
                <i class="fa fa-arrow-left"></i>
                <span>{{ t('back') }}</span>
            </button>
        </div>

        <!-- Form Card Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8 max-w-3xl mx-auto">
            <!-- Card Header -->
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h3 class="text-[18px] font-bold text-slate-800 dark:text-white">
                    {{ liability ? t('liabilites_entry_edit') : t('liabilites_entry_create') }}
                </h3>
            </div>

            <!-- Card Body -->
            <form @submit.prevent="submitForm" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Liabilities Book Select -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                            {{ t('liabilities book name') }} <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.account_book_id"
                            required
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">{{ t('select') }}</option>
                            <option
                                v-for="book in accountBooks"
                                :key="book.id"
                                :value="book.id"
                            >
                                {{ book.liability_account?.name || (book.liabilityAccount ? book.liabilityAccount.name : 'Book ' + book.id) }}
                            </option>
                        </select>
                        <div v-if="form.errors.account_book_id" class="text-red-500 text-xs mt-1">
                            {{ form.errors.account_book_id }}
                        </div>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                            {{ t('Liabilities Amount') }} <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.total_amount"
                            type="number"
                            step="0.01"
                            required
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                            :placeholder="t('Liabilities Amount')"
                        />
                        <div v-if="form.errors.total_amount" class="text-red-500 text-xs mt-1">
                            {{ form.errors.total_amount }}
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                            {{ t('date') }}
                        </label>
                        <input
                            v-model="form.created_at"
                            type="date"
                            class="w-full h-10 rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                        <div v-if="form.errors.created_at" class="text-red-500 text-xs mt-1">
                            {{ form.errors.created_at }}
                        </div>
                    </div>

                    <!-- Details Note -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                            {{ t('details') }}
                        </label>
                        <textarea
                            v-model="form.note"
                            rows="4"
                            class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                            :placeholder="t('details')"
                        ></textarea>
                        <div v-if="form.errors.note" class="text-red-500 text-xs mt-1">
                            {{ form.errors.note }}
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        @click="goBack"
                        class="bg-slate-500 hover:bg-slate-600 text-white font-bold rounded px-5 py-2.5 text-sm transition shadow-sm"
                    >
                        {{ t('back') }}
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold rounded px-5 py-2.5 text-sm transition shadow-sm disabled:opacity-50"
                    >
                        {{ liability ? t('update') : t('save') }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
