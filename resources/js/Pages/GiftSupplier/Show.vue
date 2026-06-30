<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    giftSupplier: {
        type: Object,
        required: true,
    },
    accountBooks: {
        type: Array,
        default: () => [],
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
            'gift_supplier_book': 'Gift Supplier Book',
            'name': 'Name',
            'address': 'Address',
            'mobile_no': 'Mobile No',
            'date': 'Date',
            'balance': 'Balance',
            'Are you sure?': 'Are you sure?',
            'edit': 'Edit',
            'delete': 'Delete',
            'save': 'Save',
            'clear': 'Clear',
            'processing': 'Processing...',
            'new gift supplier book': 'New Gift Supplier Book',
            'edit gift supplier profile': 'Edit Gift Supplier Profile',
            'mobile_no_two': 'Mobile No (Alternate)',
        },
        bn: {
            'gift_supplier_book': 'গিফট মহাজন খাতা',
            'name': 'নাম',
            'address': 'ঠিকানা',
            'mobile_no': 'মোবাইল নং',
            'date': 'তারিখ',
            'balance': 'ব্যালেন্স',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'save': 'সংরক্ষণ',
            'clear': 'পরিষ্কার করুন',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে...',
            'new gift supplier book': 'নতুন গিফট মহাজন খাতা',
            'edit gift supplier profile': 'গিফট মহাজন প্রোফাইল সম্পাদন',
            'mobile_no_two': 'মোবাইল নম্বর ২',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Modal profile handling
const isModalOpen = ref(false);

const form = useForm({
    name: props.giftSupplier.name,
    address: props.giftSupplier.address || '',
    mobile_no: props.giftSupplier.mobile_no || '',
    mobile_no_two: props.giftSupplier.mobile_no_two || '',
});

const openEditModal = () => {
    form.name = props.giftSupplier.name;
    form.address = props.giftSupplier.address || '';
    form.mobile_no = props.giftSupplier.mobile_no || '';
    form.mobile_no_two = props.giftSupplier.mobile_no_two || '';
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const submitForm = () => {
    form.put(route('gift-supplier.update', props.giftSupplier.id), {
        onSuccess: () => {
            closeModal();
        }
    });
};

const deleteSupplier = () => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('gift-supplier.destroy', props.giftSupplier.id));
    }
};
</script>

<template>
    <Head :title="t('gift_supplier_book') + ' - ' + giftSupplier.name" />

    <AuthenticatedLayout>
        <!-- Main Card Container wrapping all contents -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8">
            <!-- Page Title -->
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-6">
                {{ t('gift_supplier_book') }}
            </h1>

            <!-- Supplier Details Card -->
            <div class="border border-slate-200 dark:border-slate-700/80 rounded mb-6 p-6 bg-slate-50/50 dark:bg-slate-900/30">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="text-slate-800 dark:text-slate-200 space-y-1 text-[16px]">
                        <div>{{ t('name') }}: <strong>{{ giftSupplier.name }}</strong></div>
                        <div>{{ t('address') }}: <strong>{{ giftSupplier.address }}</strong></div>
                        <div>{{ t('mobile_no') }}: <strong>{{ giftSupplier.mobile_no }}</strong></div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button
                            @click="openEditModal"
                            class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                            title="Edit"
                        >
                            <i class="fa fa-edit text-sm"></i>
                        </button>
                        <button
                            @click="deleteSupplier"
                            class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                            title="Delete"
                        >
                            <i class="fa fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Ledger Table Grid -->
            <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-2/3 border border-slate-200 dark:border-slate-700/80 rounded overflow-hidden bg-slate-50/50 dark:bg-slate-900/30">
                    <div class="overflow-x-auto p-6 pt-6">
                        <table class="w-full text-left text-[15px] border-collapse">
                            <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                                <tr>
                                    <th class="px-4 py-2.5 w-[70%]">{{ t('date') }}</th>
                                    <th class="px-4 py-2.5 w-[30%] text-right">{{ t('balance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="book in accountBooks"
                                    :key="book.id"
                                    class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                                >
                                    <td class="px-4 py-2.5 w-[70%]">
                                        <Link :href="route('account-book.show', book.id)" class="text-blue-600 dark:text-blue-400 hover:underline font-bold">
                                            {{ book.description }}
                                        </Link>
                                    </td>
                                    <td class="px-4 py-2.5 w-[30%] text-right font-bold text-slate-850 dark:text-white">
                                        {{ parseFloat(book.calculated_balance).toFixed(2) }}
                                    </td>
                                </tr>
                                <tr v-if="accountBooks.length === 0">
                                    <td colspan="2" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                        {{ t('No records found.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Modal (Create / Edit supplier profile) -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm overflow-x-hidden overflow-y-auto outline-none">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal"></div>
            <div class="relative w-full max-w-2xl mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ t('edit gift supplier profile') }}
                        </h3>
                        <button
                            @click="closeModal"
                            class="p-1 ml-auto bg-transparent border-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cursor-pointer"
                        >
                            ×
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form @submit.prevent="submitForm">
                        <div class="relative p-6 flex-auto">
                            <!-- Name -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('name') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('address') }}
                                </label>
                                <input
                                    v-model="form.address"
                                    type="text"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.address }}
                                </div>
                            </div>

                            <!-- Mobile No -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('mobile_no') }}
                                </label>
                                <input
                                    v-model="form.mobile_no"
                                    type="text"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="form.errors.mobile_no" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.mobile_no }}
                                </div>
                            </div>

                            <!-- Mobile No Two -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('mobile_no_two') }}
                                </label>
                                <input
                                    v-model="form.mobile_no_two"
                                    type="text"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div v-if="form.errors.mobile_no_two" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.mobile_no_two }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-slate-200 dark:border-slate-700 rounded-b gap-2">
                            <button
                                type="button"
                                @click="closeModal"
                                class="bg-[#6c757d] hover:bg-[#5a6268] text-white px-4 py-2 rounded text-sm font-bold transition duration-150 cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-[#007bff] hover:bg-[#0069d9] text-white px-4 py-2 rounded text-sm font-bold transition duration-150 disabled:opacity-50 cursor-pointer"
                            >
                                {{ form.processing ? t('processing') : t('save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
