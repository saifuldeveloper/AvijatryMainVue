<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    notes: {
        type: Object,
        required: true,
    },
    trashNotes: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ name: '', reference: '', daterange: '' }),
    },
    defaultDateRange: {
        type: String,
        default: '',
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
            'notes': 'Notes Log',
            'note': 'Note',
            'new_note': 'New Note',
            'note_edit': 'Edit Note',
            'name': 'Title',
            'reference': 'Reference',
            'content': 'Note Content',
            'attachments': 'Attachments',
            'option': 'Option',
            'edit': 'Edit',
            'delete': 'Delete',
            'restore': 'Restore',
            'save': 'Save',
            'update': 'Update',
            'cancel': 'Cancel',
            'clear': 'Clear Filter',
            'filter': 'Filter',
            'Are you sure?': 'Are you sure?',
            'Trash Bin': 'Trash Bin',
            'Active Notes': 'Active Notes',
            'No notes found.': 'No notes found.',
            'No trashed notes found.': 'No trashed notes found.',
            'Search notes...': 'Search by title...',
            'Search reference...': 'Search by reference...',
            'deleted_by': 'Deleted By',
            'date': 'Date',
            'created_by': 'Created By',
            'preview_note': 'Note Details',
            'no_attachments': 'No attached files',
            'select_files': 'Select Files',
            'remove_selected': 'Remove selection',
            'name_label': 'Name',
            'reference_label': 'Reference',
            'details_label': 'Details',
            'attached_files': 'Attached Files',
            'preview': 'Preview',
            'size': 'Size',
            'download': 'Download',
            'image_preview': 'Image Preview',
            'sl': 'S.L.',
            'note_name': 'Note Name',
        },
        bn: {
            'notes': 'নোটসমূহ',
            'note': 'নোট',
            'new_note': 'নতুন নোট',
            'note_edit': 'নোট সম্পাদন',
            'name': 'শিরোনাম',
            'reference': 'রেফারেন্স',
            'content': 'নোটের বিবরণ',
            'attachments': 'সংযুক্ত ফাইল',
            'option': 'অপশন',
            'edit': 'সম্পাদন',
            'delete': 'মুছুন',
            'restore': 'পুনরুদ্ধার',
            'save': 'সংরক্ষণ',
            'update': 'আপডেট',
            'cancel': 'বাতিল',
            'clear': 'ফিল্টার পরিষ্কার',
            'filter': 'ফিল্টার',
            'Are you sure?': 'আপনি কি নিশ্চিত?',
            'Trash Bin': 'মুছে ফেলা নোটসমূহ',
            'Active Notes': 'সক্রিয় নোটসমূহ',
            'No notes found.': 'কোনো নোট পাওয়া যায়নি।',
            'No trashed notes found.': 'মুছে ফেলা কোনো নোট পাওয়া যায়নি।',
            'Search notes...': 'শিরোনাম দিয়ে খুঁজুন...',
            'Search reference...': 'রেফারেন্স দিয়ে খুঁজুন...',
            'deleted_by': 'মুছে ফেলেছেন',
            'date': 'তারিখ',
            'created_by': 'তৈরি করেছেন',
            'preview_note': 'নোট বিস্তারিত',
            'no_attachments': 'কোনো ফাইল নেই',
            'select_files': 'ফাইল নির্বাচন করুন',
            'remove_selected': 'নির্বাচন সরান',
            'name_label': 'নাম',
            'reference_label': 'রেফারেন্স',
            'details_label': 'বিবরণ',
            'attached_files': 'সংযুক্ত ফাইলসমূহ',
            'preview': 'প্রিভিউ',
            'size': 'সাইজ',
            'download': 'ডাউনলোড',
            'image_preview': 'ছবি প্রিভিউ',
            'sl': 'ক্রমিক',
            'note_name': 'নোটের নাম',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const filterSearchName = ref(props.filters?.name || '');
const filterSearchRef = ref(props.filters?.reference || '');
const filterDateRange = ref(props.filters?.daterange || '');

// Server-side filtering with debounce
let filterTimeout;
const runFilters = (debounceTime = 400) => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(route('notes.index'), {
            name: filterSearchName.value,
            reference: filterSearchRef.value,
            daterange: filterDateRange.value,
            page: 1
        }, {
            preserveState: true,
            replace: true
        });
    }, debounceTime);
};

watch(filterSearchName, () => runFilters(400));
watch(filterSearchRef, () => runFilters(400));
watch(filterDateRange, () => runFilters(0));

const clearFilter = () => {
    filterSearchName.value = '';
    filterSearchRef.value = '';
    filterDateRange.value = '';
    router.get(route('notes.index'), {}, {
        preserveState: false
    });
};

const goToPage = (pageNumber) => {
    router.get(route('notes.index'), {
        name: filterSearchName.value,
        reference: filterSearchRef.value,
        daterange: filterDateRange.value,
        page: pageNumber
    }, {
        preserveState: true
    });
};

// Toggle Filter Collapse
const isFilterCollapsed = ref(true);
const toggleFilter = () => {
    isFilterCollapsed.value = !isFilterCollapsed.value;
};

// Add/Edit Modal states
const isModalOpen = ref(false);
const isEditing = ref(false);

const form = useForm({
    id: null,
    name: '',
    reference: '',
    content: '',
    attachments: [],
    remove_media: [],
});

// Attachments preview state for edit mode
const existingAttachments = ref([]);

// File Input selection helper
const handleFileSelect = (event) => {
    form.attachments = Array.from(event.target.files);
};

// Open modal for adding
const openAddModal = () => {
    isEditing.value = false;
    existingAttachments.value = [];
    form.clearErrors();
    form.reset();
    isModalOpen.value = true;
};

// Open modal for editing
const openEditModal = (note) => {
    isEditing.value = true;
    existingAttachments.value = [];
    form.clearErrors();
    form.reset();
    
    form.id = note.id;
    form.name = note.name;
    form.reference = note.reference || '';
    form.content = note.content || '';
    
    // Fetch detailed media using AJAX
    axios.get(route('notes.show', note.id))
        .then(response => {
            existingAttachments.value = response.data.attachments || [];
        });

    isModalOpen.value = true;
};

// Close modal
const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    existingAttachments.value = [];
};

// Remove existing attachment helper
const toggleRemoveExisting = (mediaId) => {
    const idx = form.remove_media.indexOf(mediaId);
    if (idx > -1) {
        form.remove_media.splice(idx, 1);
    } else {
        form.remove_media.push(mediaId);
    }
};

// Submit form
const submitForm = () => {
    if (isEditing.value) {
        // Use POST with _method = PUT workaround for multipart files in updates
        form.transform((data) => ({
            ...data,
            _method: 'PUT'
        })).post(route('notes.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('notes.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// Delete note
const deleteNote = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('notes.destroy', id));
    }
};

// Restore note
const restoreNote = (id) => {
    router.post(route('notes.restore', id));
};

// Force delete note
const forceDeleteNote = (id) => {
    if (confirm(t('pages.Are you sure?'))) {
        router.delete(route('notes.forceDelete', id));
    }
};

// Preview Note Modal states
const isPreviewOpen = ref(false);
const previewData = ref(null);

const openPreview = (noteId) => {
    axios.get(route('notes.show', noteId))
        .then(response => {
            previewData.value = response.data;
            isPreviewOpen.value = true;
        });
};

const closePreview = () => {
    isPreviewOpen.value = false;
    previewData.value = null;
};

// Image Preview Modal states
const isImagePreviewOpen = ref(false);
const imagePreviewUrl = ref('');

const showImagePreview = (url) => {
    imagePreviewUrl.value = url;
    isImagePreviewOpen.value = true;
};

const closeImagePreview = () => {
    imagePreviewUrl.value = '';
    isImagePreviewOpen.value = false;
};

// File size formatter helper
const formatFileSize = (bytes) => {
    const units = ['bytes', 'KB', 'MB', 'GB'];
    let b = parseFloat(bytes);
    if (isNaN(b) || b <= 0) return '0 bytes';
    let i = 0;
    while (b >= 1024 && i < units.length - 1) {
        b /= 1024;
        i++;
    }
    return `${b.toFixed(1)} ${units[i]}`;
};
</script>

<template>
    <Head :title="t('notes')" />

    <AuthenticatedLayout>
        <!-- Notes Layout Card -->
        <div class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <div class="flex items-baseline gap-2.5">
                    <h3 class="text-[20px] font-bold text-slate-880 dark:text-white">
                        {{ t('notes') }}
                    </h3>
                    <button
                        @click="openAddModal"
                        class="text-blue-600 dark:text-blue-400 hover:underline font-bold text-sm"
                    >
                        {{ t('new_note') }}
                    </button>
                </div>
                
                <button
                    @click="toggleFilter"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-4 py-1.5 text-xs transition duration-150 shadow-sm flex items-center gap-1.5"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>{{ t('filter') }}</span>
                </button>
            </div>

            <!-- Collapsible Filter Panel inside Card -->
            <div v-show="!isFilterCollapsed" class="p-6 border-b border-slate-200 dark:border-slate-700 bg-[#fcfcfc] dark:bg-slate-900/50 transition-all duration-300">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('date') }}
                        </label>
                        <input
                            v-model="filterDateRange"
                            type="text"
                            placeholder="YYYY-MM-DD - YYYY-MM-DD"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-center font-semibold"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                            {{ t('name') }}
                        </label>
                        <input
                            v-model="filterSearchName"
                            type="text"
                            :placeholder="t('Search notes...')"
                            class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                                {{ t('reference') }}
                            </label>
                            <input
                                v-model="filterSearchRef"
                                type="text"
                                :placeholder="t('Search reference...')"
                                class="w-full h-10 rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <button
                            @click="clearFilter"
                            class="h-10 rounded bg-[#dc3545] hover:bg-[#c82333] text-white px-5 text-sm font-semibold transition shadow-sm flex items-center justify-center shrink-0"
                        >
                            {{ t('clear') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notes Table -->
            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-16">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5">{{ t('note_name') }}</th>
                            <th class="px-4 py-2.5">{{ t('reference') }}</th>
                            <th class="px-4 py-2.5">{{ t('created_by') }}</th>
                            <th class="px-4 py-2.5">{{ t('date') }}</th>
                            <th class="px-4 py-2.5 text-center w-28 whitespace-nowrap">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(note, index) in notes.data"
                            :key="note.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-800 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5 text-center">
                                {{ (notes.current_page - 1) * notes.per_page + index + 1 }}
                            </td>
                            <td class="px-4 py-2.5 text-blue-600 dark:text-blue-400 font-bold">
                                <button @click="openPreview(note.id)" class="hover:underline text-left">
                                    {{ note.name }}
                                </button>
                            </td>
                            <td class="px-4 py-2.5">
                                {{ note.reference || '—' }}
                            </td>
                            <td class="px-4 py-2.5">
                                {{ note.user?.name ?? '—' }}
                            </td>
                            <td class="px-4 py-2.5">
                                {{ note.formatted_created_at }}
                            </td>
                            <td class="px-4 py-2.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="openPreview(note.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="View"
                                    >
                                        <i class="fa fa-eye text-xs"></i>
                                    </button>
                                    <button
                                        @click="openEditModal(note)"
                                        class="bg-[#007bff] hover:bg-[#0069d9] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Edit"
                                    >
                                        <i class="fa fa-edit text-xs"></i>
                                    </button>
                                    <button
                                        @click="deleteNote(note.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white p-1.5 rounded transition duration-150 shadow-sm inline-flex items-center justify-center"
                                        title="Delete"
                                    >
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="notes.data.length === 0">
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                {{ t('No notes found.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 pb-4">
                <Pagination :pagination="notes" @page-changed="goToPage" />
            </div>
        </div>

        <!-- Trash Bin -->
        <div v-if="trashNotes.length > 0" class="border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800 rounded shadow-sm overflow-hidden mb-8">
            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-[rgba(0,0,0,0.03)] dark:bg-slate-700/50">
                <h4 class="text-[17px] font-bold text-slate-850 dark:text-white">
                    {{ t('Trash Bin') }}
                </h4>
            </div>

            <div class="overflow-x-auto p-6 pt-0">
                <table class="w-full text-left text-[15px] border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900 border-b-[2px] border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-16">{{ t('sl') }}</th>
                            <th class="px-4 py-2.5">{{ t('note_name') }}</th>
                            <th class="px-4 py-2.5">{{ t('date') }}</th>
                            <th class="px-4 py-2.5">{{ t('reference') }}</th>
                            <th class="px-4 py-2.5">{{ t('deleted_by') }}</th>
                            <th class="px-4 py-2.5 text-center w-56 whitespace-nowrap">{{ t('option') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(note, index) in trashNotes"
                            :key="note.id"
                            class="border-b border-slate-200 dark:border-slate-700/80 odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 hover:bg-slate-100/40 dark:hover:bg-slate-700/30 text-slate-855 dark:text-slate-200"
                        >
                            <td class="px-4 py-2.5 text-center">
                                {{ index + 1 }}
                            </td>
                            <td class="px-4 py-2.5 font-medium">{{ note.name }}</td>
                            <td class="px-4 py-2.5">{{ note.formatted_deleted_at }}</td>
                            <td class="px-4 py-2.5">{{ note.reference || '—' }}</td>
                            <td class="px-4 py-2.5">{{ note.deleted_by?.name ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        @click="restoreNote(note.id)"
                                        class="bg-[#28a745] hover:bg-[#218838] text-white px-2.5 py-1.5 rounded text-xs font-semibold shadow-sm transition inline-flex items-center gap-1"
                                    >
                                        <i class="fa fa-undo text-[10px]"></i>
                                        <span>{{ t('restore') }}</span>
                                    </button>
                                    <button
                                        @click="forceDeleteNote(note.id)"
                                        class="bg-[#dc3545] hover:bg-[#c82333] text-white px-2.5 py-1.5 rounded text-xs font-semibold shadow-sm transition inline-flex items-center gap-1"
                                    >
                                        <i class="fa fa-trash text-[10px]"></i>
                                        <span>{{ t('Delete Permanently') || 'স্থায়ীভাবে মুছুন' }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Note Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm"
        >
            <div class="w-full max-w-xl rounded-lg bg-white dark:bg-slate-800 shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                <!-- Modal Header -->
                <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                        {{ isEditing ? t('note_edit') : t('new_note') }}
                    </h3>
                </div>

                <!-- Modal Body -->
                <form @submit.prevent="submitForm" enctype="multipart/form-data">
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                {{ t('name') }} <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500"
                                :placeholder="t('name')"
                            />
                            <div v-if="form.errors.name" class="text-xs text-rose-600 mt-1">{{ form.errors.name }}</div>
                        </div>

                        <!-- Reference -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                {{ t('reference') }}
                            </label>
                            <input
                                v-model="form.reference"
                                type="text"
                                class="w-full rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500"
                                :placeholder="t('reference')"
                            />
                            <div v-if="form.errors.reference" class="text-xs text-rose-600 mt-1">{{ form.errors.reference }}</div>
                        </div>

                        <!-- Note Content -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                {{ t('content') }}
                            </label>
                            <textarea
                                v-model="form.content"
                                rows="5"
                                class="w-full rounded border-slate-300 dark:border-slate-650 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500"
                                :placeholder="t('content')"
                            ></textarea>
                            <div v-if="form.errors.content" class="text-xs text-rose-600 mt-1">{{ form.errors.content }}</div>
                        </div>

                        <!-- Upload Attachments -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-white mb-1">
                                {{ t('attachments') }}
                            </label>
                            <input
                                type="file"
                                multiple
                                @change="handleFileSelect"
                                class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-slate-200"
                            />
                            <div v-if="form.errors.attachments" class="text-xs text-rose-600 mt-1">{{ form.errors.attachments }}</div>
                        </div>

                        <!-- Existing Attachments (Edit mode only) -->
                        <div v-if="isEditing && existingAttachments.length > 0" class="pt-2 border-t border-slate-200 dark:border-slate-700">
                            <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2">
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
                                        class="text-blue-600 dark:text-blue-400 hover:underline break-all"
                                    >
                                        {{ attachment.name }}
                                    </a>
                                    <button
                                        type="button"
                                        @click="toggleRemoveExisting(attachment.id)"
                                        class="px-2 py-1 text-xs font-semibold rounded"
                                        :class="form.remove_media.includes(attachment.id) ? 'bg-rose-500 text-white' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-350'"
                                    >
                                        {{ form.remove_media.includes(attachment.id) ? t('remove_selected') : t('delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="border-t border-slate-200 dark:border-slate-700 px-6 py-3 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="closeModal"
                            class="rounded border border-slate-300 dark:border-slate-650 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                        >
                            {{ t('cancel') }}
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded bg-blue-600 hover:bg-blue-700 disabled:opacity-50 px-4 py-2 text-sm font-semibold text-white shadow transition"
                        >
                            {{ isEditing ? t('update') : t('save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Note Modal -->
        <div
            v-if="isPreviewOpen && previewData"
            class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm"
        >
            <div class="w-full max-w-2xl rounded-lg bg-white dark:bg-slate-800 shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                <!-- Header -->
                <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-850 dark:text-white">
                        {{ t('preview_note') }}
                    </h3>
                    <button @click="closePreview" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-2xl font-semibold leading-none">&times;</button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto text-slate-850 dark:text-slate-200">
                    <p v-if="previewData.name" class="text-sm">
                        <strong>{{ t('name_label') }}:</strong> {{ previewData.name }}
                    </p>
                    <p v-if="previewData.reference" class="text-sm">
                        <strong>{{ t('reference_label') }}:</strong> {{ previewData.reference }}
                    </p>
                    <p v-if="previewData.content" class="text-sm">
                        <strong>{{ t('details_label') }}:</strong><br>
                        <span class="whitespace-pre-wrap block mt-1.5 pl-1">{{ previewData.content }}</span>
                    </p>

                    <!-- Attachments Table (Legacy style) -->
                    <div class="pt-2">
                        <h6 class="text-sm font-bold text-slate-800 dark:text-slate-300 mb-2">
                            {{ t('attached_files') }}:
                        </h6>
                        <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded shadow-sm">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold">
                                    <tr>
                                        <th class="px-4 py-2.5 text-center border-r border-slate-200 dark:border-slate-700 w-24">{{ t('preview') }}</th>
                                        <th class="px-4 py-2.5 border-r border-slate-200 dark:border-slate-700">{{ t('name') }}</th>
                                        <th class="px-4 py-2.5 border-r border-slate-200 dark:border-slate-700 w-24">{{ t('size') }}</th>
                                        <th class="px-4 py-2.5 w-24 text-center">{{ t('download') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="file in previewData.attachments"
                                        :key="file.id"
                                        class="border-b border-slate-150 dark:border-slate-800/80 last:border-0 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 text-slate-800 dark:text-slate-200"
                                    >
                                        <td class="px-4 py-2 text-center border-r border-slate-200 dark:border-slate-700">
                                            <a
                                                v-if="file.mime_type.startsWith('image/')"
                                                href="javascript:void(0);"
                                                @click="showImagePreview(file.url)"
                                                class="inline-block"
                                            >
                                                <img :src="file.url" :alt="file.name" class="h-10 mx-auto object-cover rounded shadow-sm hover:scale-105 transition" />
                                            </a>
                                            <img
                                                v-else-if="file.mime_type === 'application/pdf'"
                                                src="https://img.icons8.com/color/48/000000/pdf.png"
                                                alt="PDF"
                                                class="h-10 mx-auto"
                                            />
                                            <img
                                                v-else
                                                src="https://thumbs.dreamstime.com/b/blue-file-folder-documents-icon-isolated-white-34337927.jpg"
                                                alt="File"
                                                class="h-10 mx-auto"
                                            />
                                        </td>
                                        <td class="px-4 py-2 border-r border-slate-200 dark:border-slate-700 font-medium break-all">{{ file.name }}</td>
                                        <td class="px-4 py-2 border-r border-slate-200 dark:border-slate-700 font-medium whitespace-nowrap">{{ formatFileSize(file.size) }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <a
                                                :href="file.url"
                                                download
                                                class="inline-block border border-blue-600 dark:border-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 text-blue-600 dark:text-blue-400 px-3 py-1 rounded text-xs font-semibold transition"
                                            >
                                                {{ t('download') }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr v-if="previewData.attachments.length === 0">
                                        <td colspan="4" class="px-4 py-6 text-center text-slate-400 dark:text-slate-500 italic">
                                            কোনো ফাইল নেই
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-slate-200 dark:border-slate-700 px-6 py-3 bg-slate-50 dark:bg-slate-900/50 flex justify-end">
                    <button
                        type="button"
                        @click="closePreview"
                        class="rounded bg-slate-600 hover:bg-slate-700 text-white px-5 py-2 text-sm font-semibold transition"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Image Preview Modal -->
        <div
            v-if="isImagePreviewOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
            @click.self="closeImagePreview"
        >
            <div class="relative max-w-4xl w-full bg-white dark:bg-slate-800 rounded-lg shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700">
                <!-- Header -->
                <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                    <h5 class="text-sm font-bold text-slate-855 dark:text-white">{{ t('image_preview') }}</h5>
                    <button @click="closeImagePreview" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-2xl font-bold leading-none">&times;</button>
                </div>
                <!-- Body -->
                <div class="p-6 text-center bg-slate-100 dark:bg-slate-900 flex justify-center items-center max-h-[80vh] overflow-auto">
                    <img :src="imagePreviewUrl" alt="Preview" class="max-w-full max-h-[70vh] object-contain rounded shadow-md" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
