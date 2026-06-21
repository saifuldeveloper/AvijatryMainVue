<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    roles: {
        type: Array,
        required: true,
    },
    permissions: {
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
    if (page.props.translations?.menu?.[cleanKey]) {
        return page.props.translations.menu[cleanKey];
    }
    const fallbacks = {
        en: {
            'User type list': 'User type list',
            'New user type': 'New user type',
            'Edit user type': 'Edit user type',
            'name': 'Name',
            'option': 'Option',
            'edit': 'Edit',
            'delete': 'Delete',
            'save': 'Save',
            'update': 'Update',
            'cancel': 'Cancel',
            'Permissions': 'Permissions',
            'All permissions': 'All permissions',
            'Are you sure?': 'Are you sure?',
            'No user types found.': 'No user types found.',
            'Assigned Permissions': 'Assigned Permissions',
            'Select All': 'Select All',
            'Deselect All': 'Deselect All',
            'Search permissions...': 'Search permissions...',
            'Permissions list grouped by domain.': 'Permissions list grouped by domain.'
        },
        bn: {
            'User type list': 'ব্যবহারকারীর ধরণের তালিকা',
            'New user type': 'নতুন ব্যবহারকারীর ধরণ',
            'Edit user type': 'ব্যবহারকারীর ধরণ সম্পাদন',
            'name': 'নাম',
            'option': 'অপশন',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'save': 'সংরক্ষণ',
            'update': 'আপডেট',
            'cancel': 'বাতিল',
            'Permissions': 'পারমিশন সমূহ',
            'All permissions': 'সকল পারমিশন',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'No user types found.': 'কোনো ব্যবহারকারীর ধরণ পাওয়া যায়নি।',
            'Assigned Permissions': 'নির্ধারিত পারমিশন সংখ্যা',
            'Select All': 'সব সিলেক্ট করুন',
            'Deselect All': 'সব আনসিলেক্ট করুন',
            'Search permissions...': 'পারমিশন খুঁজুন...',
            'Permissions list grouped by domain.': 'ডোমেন অনুযায়ী পারমিশন সমূহের গ্রুপ তালিকা।'
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Translate permission names from lang/bn/permission.php
const translatePermission = (name) => {
    if (page.props.translations?.permission?.[name]) {
        return page.props.translations.permission[name];
    }
    return name;
};

// Group permissions by category
const permissionGroups = computed(() => {
    const groups = {};
    props.permissions.forEach(permission => {
        const name = permission.name.toLowerCase();
        let groupName = 'Other Settings';
        
        if (name.includes('bank account')) groupName = 'Bank Accounts';
        else if (name.includes('categor')) groupName = 'Categories';
        else if (name.includes('retail store') || name.includes('party')) groupName = 'Retail Stores';
        else if (name.includes('factor')) groupName = 'Factories';
        else if (name.includes('gift')) groupName = 'Gifts';
        else if (name.includes('employee') || name.includes('staff')) groupName = 'Employees';
        else if (name.includes('expense')) groupName = 'Expenses';
        else if (name.includes('shoe') || name.includes('size') || name.includes('barcode') || name.includes('inventory')) groupName = 'Inventory / Shoes';
        else if (name.includes('cheque')) groupName = 'Cheques';
        else if (name.includes('sms')) groupName = 'SMS Settings';
        else if (name.includes('invoice')) groupName = 'Invoices';
        else if (name.includes('purchase')) groupName = 'Purchases';
        else if (name.includes('transaction') || name.includes('bikri')) groupName = 'Transactions / Books';
        else if (name.includes('report')) groupName = 'Reports';
        else if (name.includes('loan')) groupName = 'Loans';
        else if (name.includes('waste')) groupName = 'Waste / Jolap';
        else if (name.includes('commitment')) groupName = 'Commitments';
        else if (name.includes('note')) groupName = 'Notes';
        else if (name.includes('asset')) groupName = 'Assets';
        else if (name.includes('liabilit')) groupName = 'Liabilities';
        
        if (!groups[groupName]) {
            groups[groupName] = [];
        }
        groups[groupName].push(permission);
    });
    return groups;
});

// Group titles localized
const getGroupTitle = (groupName) => {
    const locale = page.props.locale || 'en';
    if (locale === 'bn') {
        const bnNames = {
            'Bank Accounts': 'ব্যাংক হিসাব',
            'Categories': 'ক্যাটাগরি সমূহ',
            'Retail Stores': 'খুচরা দোকান / পার্টি',
            'Factories': 'মহাজন / ফ্যাক্টরি',
            'Gifts': 'গিফট / উপহার',
            'Employees': 'কর্মচারী / স্টাফ',
            'Expenses': 'খরচ সমূহ',
            'Inventory / Shoes': 'জুতা ও ইনভেন্টরি',
            'Cheques': 'চেক বই',
            'SMS Settings': 'এসএমএস সেটিংস',
            'Invoices': 'বিক্রয় চালান',
            'Purchases': 'ক্রয় চালান',
            'Transactions / Books': 'লেনদেন ও বুকস',
            'Reports': 'রিপোর্ট সমূহ',
            'Loans': 'হাওলাত খাতা',
            'Waste / Jolap': 'জুতা/গিফট জোলাপ',
            'Commitments': 'কমিটমেন্ট',
            'Notes': 'নোটস',
            'Assets': 'সম্পদ খাতা',
            'Liabilities': 'দেনা খাতা',
            'Other Settings': 'অন্যান্য সেটিংস'
        };
        return bnNames[groupName] || groupName;
    }
    return groupName;
};

// Modal states
const isModalOpen = ref(false);
const isEditing = ref(false);

// Form
const form = useForm({
    id: null,
    name: '',
    permissions: [],
});

// Open modal for adding
const openAddModal = () => {
    isEditing.value = false;
    form.clearErrors();
    form.reset();
    isModalOpen.value = true;
};

// Open modal for editing
const openEditModal = (role) => {
    isEditing.value = true;
    form.clearErrors();
    form.id = role.id;
    form.name = role.name;
    form.permissions = role.permissions ? role.permissions.map(p => p.name) : [];
    isModalOpen.value = true;
};

// Close modal
const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

// Check if all permissions are selected
const isAllSelected = computed(() => {
    return form.permissions.length === props.permissions.length;
});

// Toggle Select All
const toggleAll = () => {
    if (isAllSelected.value) {
        form.permissions = [];
    } else {
        form.permissions = props.permissions.map(p => p.name);
    }
};

// Toggle Group Select
const isGroupAllSelected = (groupPermissionsList) => {
    return groupPermissionsList.every(p => form.permissions.includes(p.name));
};

const toggleGroup = (groupPermissionsList) => {
    const groupNames = groupPermissionsList.map(p => p.name);
    if (isGroupAllSelected(groupPermissionsList)) {
        form.permissions = form.permissions.filter(name => !groupNames.includes(name));
    } else {
        groupNames.forEach(name => {
            if (!form.permissions.includes(name)) {
                form.permissions.push(name);
            }
        });
    }
};

// Submit form
const submitForm = () => {
    if (isEditing.value) {
        form.post(route('setting.role.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('setting.role.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// Delete role
const deleteRole = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        form.delete(route('setting.role.delete', id));
    }
};
</script>

<template>
    <Head :title="t('pages.User type list')" />

    <AuthenticatedLayout>
        <!-- Card container styled like AdminLTE card-primary card-outline -->
        <div class="border-t-[3px] border-t-blue-600 border-x border-b border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Card Header -->
            <div class="px-5 py-3 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-[#f8f9fa] dark:bg-[#242434]">
                <h2 class="text-xl font-bold text-slate-850 dark:text-white m-0 leading-tight">
                    {{ t('pages.User type list') }}
                </h2>
                <button
                    @click="openAddModal"
                    class="bg-[#007bff] hover:bg-[#0069d9] text-white font-semibold rounded px-3 py-1.5 text-xs transition-colors duration-150"
                >
                    {{ t('pages.New user type') }}
                </button>
            </div>

            <!-- Card Body (Table) -->
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-slate-200 dark:border-slate-700/80">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-[#383854] text-slate-700 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700/80">
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm" style="width: 50%">{{ t('pages.name') }}</th>
                                <th class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-bold text-sm" style="width: 30%">{{ t('Assigned Permissions') }}</th>
                                <th class="p-3 font-bold text-sm text-right" style="width: 20%">{{ t('pages.option') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/60 dark:divide-slate-700/40 text-slate-700 dark:text-slate-200">
                            <tr
                                v-for="role in props.roles"
                                :key="role.id"
                                class="hover:bg-slate-50/50 dark:hover:bg-slate-850/40 transition-colors duration-150"
                            >
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60 font-medium">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ role.name }}</span>
                                        <span
                                            v-if="role.name === 'super-admin'"
                                            class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-850 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-250 dark:border-amber-900/30 rounded uppercase"
                                        >
                                            System Default
                                        </span>
                                    </div>
                                </td>
                                <td class="p-3 border-r border-slate-200 dark:border-slate-700/60 text-sm">
                                    <span class="bg-[#007bff] text-white px-2 py-0.5 rounded text-xs font-semibold">
                                        {{ role.permissions ? role.permissions.length : 0 }} / {{ props.permissions.length }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <div v-if="role.name !== 'super-admin'" class="inline-flex items-center gap-2">
                                        <button
                                            @click="openEditModal(role)"
                                            class="bg-[#28a745] hover:bg-[#218838] text-white font-semibold rounded px-2.5 py-1 text-xs transition-colors duration-150"
                                        >
                                            {{ t('pages.edit') }}
                                        </button>
                                        <button
                                            @click="deleteRole(role.id)"
                                            class="bg-[#dc3545] hover:bg-[#c82333] text-white font-semibold rounded px-2.5 py-1 text-xs transition-colors duration-150"
                                        >
                                            {{ t('pages.delete') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="props.roles.length === 0">
                                <td colspan="3" class="p-8 text-center text-slate-400 dark:text-slate-500 font-medium">
                                    {{ t('No user types found.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add / Edit User Type Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 dark:bg-slate-950/70 backdrop-blur-sm transition-opacity duration-300"
            @click.self="closeModal"
        >
            <div
                class="bg-white dark:bg-slate-800 rounded shadow-2xl border border-slate-200 dark:border-slate-700/80 w-full max-w-4xl max-h-[85vh] overflow-hidden transform transition-all duration-300 scale-100 flex flex-col"
            >
                <!-- Modal Header -->
                <div class="p-5 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/40 dark:to-slate-800 border-b border-slate-200 dark:border-slate-700/80 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ isEditing ? t('pages.Edit user type') : t('pages.New user type') }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ t('Permissions list grouped by domain.') }}
                        </p>
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
                <form @submit.prevent="submitForm" class="flex-1 overflow-y-auto p-5 space-y-6">
                    <!-- User Type Name Input -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                            {{ t('pages.name') }} <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            :disabled="isEditing"
                            class="w-full px-3 py-1.5 rounded border border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none disabled:bg-slate-100 dark:disabled:bg-slate-900 transition-all"
                        />
                        <div v-if="form.errors.name" class="text-rose-550 dark:text-rose-455 text-xs font-semibold mt-1">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div class="border border-slate-200 dark:border-slate-700/80 rounded overflow-hidden bg-slate-50/30 dark:bg-slate-800/40">
                        <!-- Permission Selection Toolbar -->
                        <div class="p-4 border-b border-slate-200 dark:border-slate-700/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50 dark:bg-slate-800/80">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                {{ t('Permissions') }} ({{ form.permissions.length }} selected)
                            </span>
                            <button
                                type="button"
                                @click="toggleAll"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-650 text-slate-700 dark:text-slate-250 border border-slate-350 dark:border-slate-600 rounded shadow-sm transition-colors"
                            >
                                <span>⚡</span>
                                <span>{{ isAllSelected ? t('Deselect All') : t('Select All') }}</span>
                            </button>
                        </div>

                        <!-- Grouped Permissions Checkbox Grid -->
                        <div class="p-4 space-y-6">
                            <div
                                v-for="(groupPerms, groupName) in permissionGroups"
                                :key="groupName"
                                class="border border-slate-200/60 dark:border-slate-700/40 rounded bg-white dark:bg-slate-800 p-4 space-y-3 shadow-sm"
                            >
                                <!-- Group Header with toggle -->
                                <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-700/50">
                                    <h4 class="text-sm font-bold text-blue-650 dark:text-blue-400">
                                        {{ getGroupTitle(groupName) }}
                                    </h4>
                                    <button
                                        type="button"
                                        @click="toggleGroup(groupPerms)"
                                        class="text-xs font-bold text-slate-500 hover:text-slate-800 dark:text-slate-450 dark:hover:text-slate-200 transition-colors"
                                    >
                                        {{ isGroupAllSelected(groupPerms) ? t('Deselect All') : t('Select All') }}
                                    </button>
                                </div>

                                <!-- Permission Items Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    <label
                                        v-for="permission in groupPerms"
                                        :key="permission.id"
                                        class="flex items-start gap-2.5 p-2 rounded hover:bg-slate-50 dark:hover:bg-slate-750/30 cursor-pointer select-none group"
                                    >
                                        <input
                                            type="checkbox"
                                            v-model="form.permissions"
                                            :value="permission.name"
                                            class="w-4 h-4 mt-0.5 rounded border-slate-300 dark:border-slate-650 text-blue-650 focus:ring-blue-500 dark:bg-slate-800 transition"
                                        />
                                        <span class="text-xs font-medium text-slate-650 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors leading-tight">
                                            {{ translatePermission(permission.name) }}
                                        </span>
                                    </label>
                                </div>
                            </div>
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
