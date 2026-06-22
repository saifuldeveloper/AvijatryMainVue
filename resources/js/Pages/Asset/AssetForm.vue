<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    asset: {
        type: Object,
        default: null,
    },
    attachments: {
        type: Array,
        default: () => [],
    },
    accountBooks: {
        type: Array,
        required: true,
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
            'asset_entry': 'Asset Entry',
            'edit_asset_entry': 'Edit Asset Entry',
            'asset_type': 'Asset Account Book',
            'asset_price': 'Asset Price / Cost',
            'date': 'Purchase Date',
            'status': 'Status',
            'active': 'Active',
            'inactive': 'Inactive',
            'details': 'Details / Notes',
            'attachments': 'Attachments',
            'select': 'Select Account',
            'save': 'Save Entry',
            'update': 'Update Entry',
            'back': 'Back',
            'remove_selected': 'Remove selected',
            'select_files': 'Select Files',
        },
        bn: {
            'asset_entry': 'অ্যাসেট এন্ট্রি',
            'edit_asset_entry': 'অ্যাসেট এন্ট্রি সম্পাদন',
            'asset_type': 'অ্যাসেট অ্যাকাউন্ট বুক',
            'asset_price': 'অ্যাসেট মূল্য',
            'date': 'ক্রয়ের তারিখ',
            'status': 'অবস্থা',
            'active': 'সক্রিয়',
            'inactive': 'অসক্রিয়',
            'details': 'বিস্তারিত বিবরণ',
            'attachments': 'সংযুক্ত ফাইলসমূহ',
            'select': 'হিসাব নির্বাচন করুন',
            'save': 'সংরক্ষণ করুন',
            'update': 'হালনাগাদ করুন',
            'back': 'পূর্ববর্তী পাতায় যান',
            'remove_selected': 'নির্বাচন সরান',
            'select_files': 'ফাইল নির্বাচন করুন',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Form setup
const form = useForm({
    account_book_id: props.asset?.account_book_id || '',
    asset_cost: props.asset?.asset_cost || '',
    start_date: props.asset?.start_date ? props.asset.start_date.substring(0, 10) : new Date().toISOString().substring(0, 10),
    note: props.asset?.note || '',
    status: props.asset ? (props.asset.status ? 1 : 0) : 1,
    attachments: [],
    remove_media: [],
});

// Selection list for existing files
const existingAttachments = ref(props.attachments || []);

// Handle multiple file upload selection
const handleFileSelect = (event) => {
    form.attachments = Array.from(event.target.files);
};

// Remove file selection helper
const toggleRemoveExisting = (mediaId) => {
    const idx = form.remove_media.indexOf(mediaId);
    if (idx > -1) {
        form.remove_media.splice(idx, 1);
    } else {
        form.remove_media.push(mediaId);
    }
};

// Submit form (workaround POST with _method PUT for file uploads on Laravel update)
const submitForm = () => {
    if (props.asset) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT'
        })).post(route('assets.update', props.asset.id));
    } else {
        form.post(route('assets.store'));
    }
};
</script>

<template>
    <Head :title="props.asset ? t('edit_asset_entry') : t('asset_entry')" />

    <AuthenticatedLayout>
        <div>
            <!-- Form Card -->
            <div class="border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-6">
                <!-- Card Header -->
                <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-850/50 flex justify-between items-center">
                    <h3 class="text-[20px] font-bold text-slate-800 dark:text-white">
                        {{ props.asset ? t('edit_asset_entry') : t('asset_entry') }}
                    </h3>
                    <a
                        :href="route('assets.index')"
                        class="bg-slate-500 hover:bg-slate-600 text-white font-semibold rounded px-4 py-1.5 text-xs transition duration-150 shadow-sm inline-flex items-center gap-1"
                    >
                        &larr; {{ t('back') }}
                    </a>
                </div>

                <!-- Card Body Form -->
                <form @submit.prevent="submitForm" enctype="multipart/form-data">
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Asset Account Book Select -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1.5">
                                    {{ t('asset_type') }} <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    v-model="form.account_book_id"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">{{ t('select') }}</option>
                                    <option
                                        v-for="book in accountBooks"
                                        :key="book.id"
                                        :value="book.id"
                                    >
                                        {{ book.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.account_book_id" class="text-xs text-rose-600 mt-1">
                                    {{ form.errors.account_book_id }}
                                </div>
                            </div>

                            <!-- Asset Price / Cost -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1.5">
                                    {{ t('asset_price') }} <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.asset_cost"
                                    type="number"
                                    step="0.01"
                                    required
                                    placeholder="0.00"
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-bold"
                                />
                                <div v-if="form.errors.asset_cost" class="text-xs text-rose-600 mt-1">
                                    {{ form.errors.asset_cost }}
                                </div>
                            </div>

                            <!-- Purchase Date -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1.5">
                                    {{ t('date') }} <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.start_date"
                                    type="date"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-semibold"
                                />
                                <div v-if="form.errors.start_date" class="text-xs text-rose-600 mt-1">
                                    {{ form.errors.start_date }}
                                </div>
                            </div>

                            <!-- Status Select (Shown only on edit) -->
                            <div v-if="props.asset">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1.5">
                                    {{ t('status') }} <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    v-model="form.status"
                                    required
                                    class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option :value="1">{{ t('active') }}</option>
                                    <option :value="0">{{ t('inactive') }}</option>
                                </select>
                                <div v-if="form.errors.status" class="text-xs text-rose-600 mt-1">
                                    {{ form.errors.status }}
                                </div>
                            </div>
                        </div>

                        <!-- Notes / details -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1.5">
                                {{ t('details') }}
                            </label>
                            <textarea
                                v-model="form.note"
                                rows="4"
                                class="w-full rounded border-slate-350 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                :placeholder="t('details')"
                            ></textarea>
                            <div v-if="form.errors.note" class="text-xs text-rose-600 mt-1">
                                {{ form.errors.note }}
                            </div>
                        </div>

                        <!-- Attachments Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1.5">
                                {{ t('attachments') }}
                            </label>
                            <input
                                type="file"
                                multiple
                                @change="handleFileSelect"
                                class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-slate-200"
                            />
                            <div v-if="form.errors.attachments" class="text-xs text-rose-600 mt-1">
                                {{ form.errors.attachments }}
                            </div>
                        </div>

                        <!-- Existing Attachments list (Edit mode only) -->
                        <div v-if="props.asset && existingAttachments.length > 0" class="pt-4 border-t border-slate-200 dark:border-slate-700">
                            <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2.5">
                                {{ t('attachments') }} ({{ t('Edit') || 'সম্পাদন' }})
                            </h4>
                            <div class="space-y-2">
                                <div
                                    v-for="attachment in existingAttachments"
                                    :key="attachment.id"
                                    class="flex items-center justify-between bg-slate-50 dark:bg-slate-900 px-3 py-2 rounded text-sm"
                                >
                                    <a
                                        :href="attachment.url"
                                        target="_blank"
                                        class="text-blue-600 dark:text-blue-400 hover:underline font-bold break-all mr-2"
                                    >
                                        {{ attachment.name }}
                                    </a>
                                    <button
                                        type="button"
                                        @click="toggleRemoveExisting(attachment.id)"
                                        class="px-2.5 py-1 text-xs font-semibold rounded shrink-0 transition"
                                        :class="form.remove_media.includes(attachment.id) ? 'bg-rose-500 text-white hover:bg-rose-600' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-350 hover:bg-slate-300 dark:hover:bg-slate-650'"
                                    >
                                        {{ form.remove_media.includes(attachment.id) ? t('remove_selected') : t('delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer buttons -->
                    <div class="border-t border-slate-200 dark:border-slate-700 px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 flex justify-end gap-3.5">
                        <a
                            :href="route('assets.index')"
                            class="rounded border border-slate-300 dark:border-slate-650 px-5 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                        >
                            {{ t('cancel') || 'বাতিল' }}
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded bg-blue-600 hover:bg-blue-700 disabled:opacity-50 px-5 py-2 text-sm font-semibold text-white shadow-sm transition"
                        >
                            {{ props.asset ? t('update') : t('save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
