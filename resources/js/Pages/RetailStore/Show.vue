<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    retailStore: {
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
            'due_books': 'Retailer Book',
            'name': 'Name',
            'shop_name': 'Shop Name',
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
            'new_party': 'New Retailer',
            'edit_party': 'Edit Retailer Profile',
            'assign': 'Assign',
        },
        bn: {
            'due_books': 'বাকি খাতা',
            'name': 'নাম',
            'shop_name': 'দোকানের নাম',
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
            'new_party': 'নতুন পার্টি',
            'edit_party': 'পার্টি প্রোফাইল সম্পাদন',
            'assign': 'অ্যাসাইন',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Modal profile handling
const isModalOpen = ref(false);

const form = useForm({
    shop_name: props.retailStore.shop_name,
    address: props.retailStore.address || '',
    mobile_no: props.retailStore.mobile_no || '',
    commission: props.retailStore.commission || 0,
    onetime_buyer: !!props.retailStore.onetime_buyer,
    company_share: !!props.retailStore.company_share,
    secret_key: props.retailStore.secret_key || '',
});

const openEditModal = () => {
    form.shop_name = props.retailStore.shop_name;
    form.address = props.retailStore.address || '';
    form.mobile_no = props.retailStore.mobile_no || '';
    form.commission = props.retailStore.commission || 0;
    form.onetime_buyer = !!props.retailStore.onetime_buyer;
    form.company_share = !!props.retailStore.company_share;
    form.secret_key = props.retailStore.secret_key || '';
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const submitForm = () => {
    form.put(route('retail-store.update', props.retailStore.id), {
        onSuccess: () => {
            closeModal();
        }
    });
};

const deleteStore = () => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('retail-store.destroy', props.retailStore.id));
    }
};
</script>

<template>
    <Head :title="t('due_books') + ' - ' + retailStore.shop_name" />

    <AuthenticatedLayout>
        <!-- Main Card Container -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8">
            <!-- Page Title -->
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-6">
                {{ t('due_books') }}
            </h1>

            <!-- Store Details Card -->
            <div class="border border-slate-200 dark:border-slate-700/80 rounded mb-6 p-6 bg-slate-50/50 dark:bg-slate-900/30">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="text-slate-800 dark:text-slate-200 space-y-1 text-[16px]">
                        <div>{{ t('shop_name') }}: <strong>{{ retailStore.shop_name }}</strong></div>
                        <div>{{ t('address') }}: <strong>{{ retailStore.address }}</strong></div>
                        <div>{{ t('mobile_no') }}: <strong>{{ retailStore.mobile_no }}</strong></div>
                        <div v-if="retailStore.commission">
                            {{ t('commission') }}: <strong>{{ retailStore.commission }}%</strong>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- We only render the assign button if the route exists -->
                        <Link
                            v-if="route().has('retail-store.shoes.assign')"
                            :href="route('retail-store.shoes.assign', retailStore.id)"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-sm font-semibold transition shadow-sm"
                        >
                            {{ t('assign') }}
                        </Link>
                        <button
                            @click="openEditModal"
                            class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center cursor-pointer"
                            title="Edit"
                        >
                            <i class="fa fa-edit text-sm"></i>
                        </button>
                        <button
                            @click="deleteStore"
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

        <!-- Form Modal (Edit store profile) -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm overflow-x-hidden overflow-y-auto outline-none">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal"></div>
            <div class="relative w-full max-w-2xl mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ t('edit_party') }}
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
                            <!-- Shop Name -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('shop_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.shop_name"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('shop_name')"
                                />
                                <div v-if="form.errors.shop_name" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.shop_name }}
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('address') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.address"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('address')"
                                />
                                <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.address }}
                                </div>
                            </div>

                            <!-- Mobile No -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('mobile_no') }} <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.mobile_no"
                                    type="text"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('mobile_no')"
                                />
                                <div v-if="form.errors.mobile_no" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.mobile_no }}
                                </div>
                            </div>

                            <!-- Commission -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('commission') }} (%)
                                </label>
                                <input
                                    v-model="form.commission"
                                    type="number"
                                    step="0.01"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('commission')"
                                />
                                <div v-if="form.errors.commission" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.commission }}
                                </div>
                            </div>

                            <!-- Secret Key -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                    {{ t('secret_key') }}
                                </label>
                                <input
                                    v-model="form.secret_key"
                                    type="text"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    :placeholder="t('secret_key')"
                                />
                                <div v-if="form.errors.secret_key" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.secret_key }}
                                </div>
                            </div>

                            <!-- Checkboxes (One-Time and Company Share) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                                <div class="flex items-center gap-2">
                                    <input
                                        id="onetime_buyer"
                                        v-model="form.onetime_buyer"
                                        type="checkbox"
                                        class="rounded border-slate-350 dark:border-slate-650 text-blue-600 focus:ring-blue-500"
                                    />
                                    <label for="onetime_buyer" class="text-sm font-bold text-slate-700 dark:text-slate-300 cursor-pointer">
                                        {{ t('onetime_buyer') }}
                                    </label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input
                                        id="company_share"
                                        v-model="form.company_share"
                                        type="checkbox"
                                        class="rounded border-slate-350 dark:border-slate-650 text-blue-600 focus:ring-blue-500"
                                    />
                                    <label for="company_share" class="text-sm font-bold text-slate-700 dark:text-slate-300 cursor-pointer">
                                        {{ t('company_share') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-slate-200 dark:border-slate-700 rounded-b gap-2">
                            <button
                                type="button"
                                @click="closeModal"
                                class="bg-slate-500 hover:bg-slate-600 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm cursor-pointer"
                            >
                                {{ t('clear') }}
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2 text-sm font-bold transition shadow-sm disabled:opacity-50 cursor-pointer"
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
