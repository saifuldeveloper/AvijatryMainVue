<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    },
    trashUsers: {
        type: Array,
        default: () => [],
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
    if (page.props.translations?.menu?.[cleanKey]) {
        return page.props.translations.menu[cleanKey];
    }
    const fallbacks = {
        en: {
            'User List': 'User List',
            'New User': 'New User',
            'Edit User': 'Edit User',
            'name': 'Name',
            'email': 'Email',
            'phone': 'Mobile No',
            'User Type': 'User Type',
            'type': 'Type',
            'option': 'Option',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'Deleted User List': 'Deleted User List',
            'Are you sure?': 'Are you sure?',
            'save': 'Save',
            'update': 'Update',
            'cancel': 'Cancel',
            'password': 'Password',
            'date': 'Date',
            'deleted_by': 'Deleted By',
            'Active Users': 'Active Users',
            'Trash Bin': 'Trash Bin',
            'Search users...': 'Search users...',
            'No users found.': 'No users found.',
            'No trashed users found.': 'No trashed users found.',
            'Select User Type': 'Select User Type',
            'Add New User': 'Add New User',
            'Please fill in the details below.': 'Please fill in the details below.',
            'Update User Details': 'Update User Details',
            'Leave blank to keep current password.': 'Leave blank to keep current password.'
        },
        bn: {
            'User List': 'ব্যবহারকারী তালিকা',
            'New User': 'নতুন ব্যবহারকারী',
            'Edit User': 'ব্যবহারকারী সম্পাদন',
            'name': 'নাম',
            'email': 'ইমেইল',
            'phone': 'মোবাইল নম্বর',
            'User Type': 'ব্যবহারকারীর ধরণ',
            'type': 'ধরণ',
            'option': 'অপশন',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'Deleted User List': 'মুছে ফেলা ব্যবহারকারী তালিকা',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'save': 'সংরক্ষণ',
            'update': 'আপডেট',
            'cancel': 'বাতিল',
            'password': 'পাসওয়ার্ড',
            'date': 'তারিখ',
            'deleted_by': 'মুছে ফেলেছেন',
            'Active Users': 'সক্রিয় ব্যবহারকারীগণ',
            'Trash Bin': 'মুছে ফেলা ব্যবহারকারীগণ',
            'Search users...': 'ব্যবহারকারী খুঁজুন...',
            'No users found.': 'কোনো ব্যবহারকারী পাওয়া যায়নি।',
            'No trashed users found.': 'মুছে ফেলা কোনো ব্যবহারকারী পাওয়া যায়নি।',
            'Select User Type': 'ব্যবহারকারীর ধরণ নির্বাচন করুন',
            'Add New User': 'নতুন ব্যবহারকারী যুক্ত করুন',
            'Please fill in the details below.': 'অনুগ্রহ করে নিচের তথ্যগুলো পূরণ করুন।',
            'Update User Details': 'ব্যবহারকারীর তথ্য আপডেট করুন',
            'Leave blank to keep current password.': 'পাসওয়ার্ড পরিবর্তন না করতে চাইলে খালি রাখুন।'
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Search filter state
const searchQuery = ref('');

// Filtered active users
const filteredUsers = computed(() => {
    if (!searchQuery.value) return props.users;
    const query = searchQuery.value.toLowerCase();
    return props.users.filter(user => 
        user.name?.toLowerCase().includes(query) ||
        user.email?.toLowerCase().includes(query) ||
        user.phone?.toLowerCase().includes(query) ||
        user.roles?.some(r => r.name?.toLowerCase().includes(query))
    );
});

// Modal states
const isModalOpen = ref(false);
const isEditing = ref(false);

// Form
const form = useForm({
    id: null,
    name: '',
    email: '',
    phone: '',
    password: '',
    role: '',
});

// Open modal for adding
const openAddModal = () => {
    isEditing.value = false;
    form.clearErrors();
    form.reset();
    isModalOpen.value = true;
};

// Open modal for editing
const openEditModal = (user) => {
    isEditing.value = true;
    form.clearErrors();
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.phone = user.phone || '';
    form.password = '';
    form.role = user.roles?.[0]?.name || '';
    isModalOpen.value = true;
};

// Close modal
const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

// Submit form (Save / Update)
const submitForm = () => {
    if (isEditing.value) {
        form.post(route('setting.user.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('setting.user.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// Delete user
const deleteUser = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('setting.user.delete', id));
    }
};

// Restore user
const restoreUser = (id) => {
    form.post(route('setting.user.restore', id));
};

// Force delete user
const forceDeleteUser = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('setting.user.force-delete', id));
    }
};
</script>

<template>
    <Head :title="t('pages.User List')" />

    <AuthenticatedLayout>
        <!-- Card container styled like AdminLTE card-primary card-outline -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Card Header -->
            <div class="px-5 py-3 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h2 class="text-xl font-bold text-slate-850 dark:text-white m-0 leading-tight">
                    {{ t('pages.User List') }}
                </h2>
                <div class="flex items-center gap-3">
                    <!-- Search Input -->
                    <div class="relative">
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="t('Search users...')"
                            class="pl-3 pr-8 py-1 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all"
                        />
                        <span class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>

                    <button
                        @click="openAddModal"
                        class="bg-[#007bff] hover:bg-[#0069d9] text-white font-semibold rounded px-3 py-1.5 text-xs transition-colors duration-150"
                    >
                        {{ t('pages.New User') }}
                    </button>
                </div>
            </div>

            <!-- Card Body (Table) -->
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-slate-200 dark:border-slate-700/80">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-[#383854] text-slate-700 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700/80">
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm">{{ t('pages.name') }}</th>
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm">{{ t('pages.email') }}</th>
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm">{{ t('pages.phone') }}</th>
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm">{{ t('pages.type') }}</th>
                                <th class="p-3 font-bold text-sm text-right">{{ t('pages.option') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/60 dark:divide-slate-700/40 text-slate-700 dark:text-slate-200">
                            <tr
                                v-for="user in filteredUsers"
                                :key="user.id"
                                class="hover:bg-slate-50/50 dark:hover:bg-slate-850/40 transition-colors duration-150"
                            >
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60">{{ user.name }}</td>
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60">{{ user.email }}</td>
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60">{{ user.phone || '' }}</td>
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60">
                                    <div class="flex flex-wrap gap-1">
                                        <span
                                            v-for="role in user.roles"
                                            :key="role.id"
                                            class="bg-[#007bff] text-white px-2 py-0.5 rounded text-xs font-semibold"
                                        >
                                            {{ role.name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button
                                            @click="openEditModal(user)"
                                            class="bg-[#28a745] hover:bg-[#218838] text-white font-semibold rounded px-2.5 py-1 text-xs transition-colors duration-150"
                                        >
                                            {{ t('pages.edit') }}
                                        </button>
                                        <button
                                            @click="deleteUser(user.id)"
                                            class="bg-[#dc3545] hover:bg-[#c82333] text-white font-semibold rounded px-2.5 py-1 text-xs transition-colors duration-150"
                                        >
                                            {{ t('pages.delete') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredUsers.length === 0">
                                <td colspan="5" class="p-8 text-center text-slate-400 dark:text-slate-500 font-medium">
                                    {{ t('No users found.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Trashed Users section styled like AdminLTE outline card -->
        <div
            v-if="props.trashUsers.length > 0"
            class="border border-rose-100 dark:border-rose-950 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden"
        >
            <div class="px-5 py-3 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50 border-b border-rose-100 dark:border-rose-950">
                <h3 class="text-lg font-bold text-rose-800 dark:text-rose-400 m-0">
                    {{ t('pages.Deleted User List') }}
                </h3>
            </div>

            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-rose-100 dark:border-rose-950">
                        <thead>
                            <tr class="bg-rose-50/10 dark:bg-rose-950/10 text-slate-700 dark:text-slate-200 border-b border-rose-100 dark:border-rose-950">
                                <th class="p-3 border-r border-rose-100 dark:border-rose-950 font-bold text-sm">{{ t('pages.name') }}</th>
                                <th class="p-3 border-r border-rose-100 dark:border-rose-950 font-bold text-sm">{{ t('pages.email') }}</th>
                                <th class="p-3 border-r border-rose-100 dark:border-rose-950 font-bold text-sm">{{ t('pages.type') }}</th>
                                <th class="p-3 border-r border-rose-100 dark:border-rose-950 font-bold text-sm">{{ t('pages.date') }}</th>
                                <th class="p-3 border-r border-rose-100 dark:border-rose-950 font-bold text-sm">{{ t('pages.deleted_by') }}</th>
                                <th class="p-3 font-bold text-sm text-right">{{ t('pages.option') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-100/50 dark:divide-slate-700/40 text-slate-700 dark:text-slate-200">
                            <tr
                                v-for="user in props.trashUsers"
                                :key="user.id"
                                class="hover:bg-rose-50/5 dark:hover:bg-rose-950/5 transition-colors duration-150"
                            >
                                <td class="p-3 border-r border-rose-100 dark:border-rose-950 text-sm">{{ user.name }}</td>
                                <td class="p-3 border-r border-rose-100 dark:border-rose-950 text-sm">{{ user.email }}</td>
                                <td class="p-3 border-r border-rose-100 dark:border-rose-950 text-sm">
                                    <div class="flex flex-wrap gap-1">
                                        <span
                                            v-for="role in user.roles"
                                            :key="role.id"
                                            class="bg-[#007bff] text-white px-2 py-0.5 rounded text-xs font-semibold"
                                        >
                                            {{ role.name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-3 border-r border-rose-100 dark:border-rose-950 text-sm">
                                    {{ user.deleted_at ? new Date(user.deleted_at).toLocaleString() : '—' }}
                                </td>
                                <td class="p-3 border-r border-rose-100 dark:border-rose-950 text-sm">
                                    {{ user.deleted_by || '—' }}
                                </td>
                                <td class="p-3 text-right text-sm">
                                    <div class="inline-flex items-center gap-2">
                                        <button
                                            @click="restoreUser(user.id)"
                                            class="bg-[#28a745] hover:bg-[#218838] text-white font-semibold rounded px-2.5 py-1 text-xs transition-colors duration-150"
                                        >
                                            {{ t('pages.restore') }}
                                        </button>
                                        <button
                                            @click="forceDeleteUser(user.id)"
                                            class="bg-[#dc3545] hover:bg-[#c82333] text-white font-semibold rounded px-2.5 py-1 text-xs transition-colors duration-150"
                                        >
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add / Edit Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 dark:bg-slate-950/70 backdrop-blur-sm transition-opacity duration-300"
            @click.self="closeModal"
        >
            <div
                class="bg-white dark:bg-slate-800 rounded shadow-2xl border border-slate-200 dark:border-slate-700/80 w-full max-w-lg overflow-hidden transform transition-all duration-300 scale-100 flex flex-col"
            >
                <!-- Modal Header -->
                <div class="p-5 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/40 dark:to-slate-800 border-b border-slate-200 dark:border-slate-700/80 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ isEditing ? t('pages.Edit User') : t('pages.New User') }}
                        </h3>
                    </div>
                    <button
                        @click="closeModal"
                        class="p-1 rounded text-slate-450 hover:text-slate-750 dark:hover:text-white hover:bg-slate-150 dark:hover:bg-slate-700 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body (Form) -->
                <form @submit.prevent="submitForm" class="p-5 space-y-4">
                    <!-- Name Input -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                            {{ t('pages.name') }} <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all"
                        />
                        <div v-if="form.errors.name" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                            {{ t('pages.email') }} <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all"
                        />
                        <div v-if="form.errors.email" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Phone Input -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                            {{ t('pages.phone') }}
                        </label>
                        <input
                            v-model="form.phone"
                            type="text"
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all"
                        />
                        <div v-if="form.errors.phone" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                            {{ form.errors.phone }}
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                            {{ t('pages.password') }} <span v-if="!isEditing" class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.password"
                            type="password"
                            :required="!isEditing"
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all"
                        />
                        <p v-if="isEditing" class="text-[11px] text-slate-450 dark:text-slate-400 mt-1">
                            {{ t('Leave blank to keep current password.') }}
                        </p>
                        <div v-if="form.errors.password" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- User Type Dropdown -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                            {{ t('pages.User Type') }} <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.role"
                            required
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer transition-all"
                        >
                            <option value="" disabled>{{ t('Select User Type') }}</option>
                            <option
                                v-for="role in props.roles"
                                :key="role.id"
                                :value="role.name"
                            >
                                {{ role.name }}
                            </option>
                        </select>
                        <div v-if="form.errors.role" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                            {{ form.errors.role }}
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700/80">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors"
                        >
                            {{ t('pages.cancel') }}
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-[#007bff] hover:bg-[#0069d9] text-white font-semibold rounded text-sm transition duration-150 shadow-sm disabled:opacity-50"
                        >
                            {{ isEditing ? t('pages.update') : t('pages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
