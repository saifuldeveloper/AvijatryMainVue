<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    authUser: {
        type: Object,
        required: true,
    },
});

const page = usePage();

// Form state
const form = useForm({
    name: props.authUser.name || '',
    email: props.authUser.email || '',
    phone: props.authUser.phone || '',
    image: null,
});

// Image preview state
const imagePreview = ref(null);

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

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
            'Profile': 'Profile',
            'Profile information': 'Profile Information',
            'name': 'Name',
            'email': 'Email',
            'phone': 'Phone',
            'picture': 'Picture',
            'update': 'Update',
            'Select file': 'Select File'
        },
        bn: {
            'Profile': 'প্রোফাইল',
            'Profile information': 'প্রোফাইল তথ্য',
            'name': 'নাম',
            'email': 'ইমেইল',
            'phone': 'মোবাইল নম্বর',
            'picture': 'ছবি',
            'update': 'আপডেট করুন',
            'Select file': 'ফাইল নির্বাচন করুন'
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Current Profile Image path compute
const currentProfileImage = computed(() => {
    if (imagePreview.value) {
        return imagePreview.value;
    }
    if (props.authUser.image) {
        if (props.authUser.image.startsWith('http://') || props.authUser.image.startsWith('https://')) {
            return props.authUser.image;
        }
        return props.authUser.image.startsWith('/') ? props.authUser.image : '/' + props.authUser.image;
    }
    return '/images/profile/no_image.png';
});

const handleProfileImageError = (e) => {
    e.target.src = 'https://api.dicebear.com/7.x/initials/svg?seed=' + encodeURIComponent(props.authUser.name || 'User');
};

const submitForm = () => {
    // We post using standard POST route
    form.post(route('user.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            imagePreview.value = null;
            form.image = null;
        }
    });
};
</script>

<template>
    <Head :title="t('pages.Profile')" />

    <AuthenticatedLayout>
        <!-- Card Container matching roles/users outline design -->
        <div class="border-t-[3px] border-t-blue-600 border-x border-b border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden max-w-4xl mx-auto mb-8">
            <!-- Card Header -->
            <div class="px-5 py-3 border-b border-slate-200 dark:border-slate-700 bg-[#f8f9fa] dark:bg-[#242434]">
                <h2 class="text-xl font-bold text-slate-850 dark:text-white m-0 leading-tight">
                    {{ t('pages.Profile information') }}
                </h2>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                        
                        <!-- Profile Image Section -->
                        <div class="flex flex-col items-center gap-3">
                            <div class="relative w-36 h-36 rounded-full overflow-hidden border-2 border-slate-200 dark:border-slate-700 shadow-md">
                                <img
                                    :src="currentProfileImage"
                                    alt="Profile Image"
                                    @error="handleProfileImageError"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                {{ t('pages.picture') }}
                            </span>
                        </div>

                        <!-- Profile Info Inputs -->
                        <div class="flex-1 w-full space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                        {{ t('pages.name') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        required
                                        class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm transition-all"
                                    />
                                    <div v-if="form.errors.name" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                        {{ t('pages.email') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        required
                                        class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm transition-all"
                                    />
                                    <div v-if="form.errors.email" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                                        {{ form.errors.email }}
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                        {{ t('pages.phone') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.phone"
                                        type="text"
                                        required
                                        class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm transition-all"
                                    />
                                    <div v-if="form.errors.phone" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                                        {{ form.errors.phone }}
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                        {{ t('pages.picture') }} (Upload)
                                    </label>
                                    <input
                                        type="file"
                                        @change="onFileChange"
                                        accept="image/png, image/jpeg, image/jpg"
                                        class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-slate-200 cursor-pointer"
                                    />
                                    <div v-if="form.errors.image" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                                        {{ form.errors.image }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer Action -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200/60 dark:border-slate-700/60">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-[#007bff] hover:bg-[#0069d9] text-white font-semibold rounded text-sm transition duration-150 inline-flex items-center gap-1.5 shadow-sm"
                        >
                            <i class="fa fa-save"></i> {{ t('pages.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
