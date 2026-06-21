<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();

const form = useForm({
    old_password: '',
    new_password: '',
    new_password_confirmation: '',
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
            'Change Password': 'Change Password',
            'change_password': 'Change Password',
            'Old Password': 'Old Password',
            'New Password': 'New Password',
            'Confirm Password': 'Confirm Password',
            'update': 'Update Password',
            'save': 'Save',
            'cancel': 'Cancel'
        },
        bn: {
            'Change Password': 'পাসওয়ার্ড পরিবর্তন করুন',
            'change_password': 'পাসওয়ার্ড পরিবর্তন করুন',
            'Old Password': 'বর্তমান পাসওয়ার্ড',
            'New Password': 'নতুন পাসওয়ার্ড',
            'Confirm Password': 'নিশ্চিত পাসওয়ার্ড',
            'update': 'পাসওয়ার্ড পরিবর্তন করুন',
            'save': 'সংরক্ষণ',
            'cancel': 'বাতিল'
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

const submitForm = () => {
    form.post(route('change.password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head :title="t('pages.Change Password')" />

    <AuthenticatedLayout>
        <!-- Card Container matching roles/users outline design -->
        <div class="border-t-[3px] border-t-blue-600 border-x border-b border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden max-w-xl mx-auto mb-8">
            <!-- Card Header -->
            <div class="px-5 py-3 border-b border-slate-200 dark:border-slate-700 bg-[#f8f9fa] dark:bg-[#242434]">
                <h2 class="text-xl font-bold text-slate-850 dark:text-white m-0 leading-tight">
                    {{ t('pages.Change Password') }}
                </h2>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <form @submit.prevent="submitForm" class="space-y-5">
                    <!-- Old Password -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                            {{ t('Old Password') }} <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.old_password"
                            type="password"
                            required
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm transition-all"
                        />
                        <div v-if="form.errors.old_password" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                            {{ form.errors.old_password }}
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                            {{ t('New Password') }} <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.new_password"
                            type="password"
                            required
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm transition-all"
                        />
                        <div v-if="form.errors.new_password" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                            {{ form.errors.new_password }}
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                            {{ t('Confirm Password') }} <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.new_password_confirmation"
                            type="password"
                            required
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm transition-all"
                        />
                        <div v-if="form.errors.new_password_confirmation" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                            {{ form.errors.new_password_confirmation }}
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-250/60 dark:border-slate-700/60">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-[#007bff] hover:bg-[#0069d9] text-white font-semibold rounded text-sm transition duration-150 inline-flex items-center gap-1.5 shadow-sm"
                        >
                            <i class="fa fa-save"></i> {{ t('update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
