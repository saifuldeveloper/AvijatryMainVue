<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    inventoryCheck: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
});

const page = usePage();

// Translation Helper
const t = (key) => {
    let cleanKey = key;
    if (key.startsWith('pages.')) {
        cleanKey = key.replace('pages.', '');
    } else if (key.startsWith('menu.')) {
        cleanKey = key.replace('menu.', '');
    }
    if (page.props.translations?.pages?.[cleanKey]) {
        return page.props.translations.pages[cleanKey];
    }
    if (page.props.translations?.menu?.[cleanKey]) {
        return page.props.translations.menu[cleanKey];
    }
    const fallbacks = {
        en: {
            'inventory_check': 'Inventory Check',
            'Resume Checking': 'Resume Checking',
            'Calculation': 'Calculations',
            'complete match': 'Complete Match',
            'Under Stock': 'Under Stock',
            'extra match': 'Extra Stock',
            'Total Count ID': 'Total Scanned IDs',
            'remaining count ID': 'Remaining IDs',
            'under match': 'Under Stock Match',
            'id': 'ID',
            'supplier': 'Supplier',
            'type': 'Type',
            'color': 'Color',
            'picture': 'Picture',
            'selling_price': 'Selling Price',
            'purchase_price': 'Purchase Price',
            'current_stock': 'Current Stock',
            'counted': 'Counted',
            'diff': 'Difference',
            'fix the pair': 'Fix Pair',
            'cancel': 'Cancel',
            'total': 'Total',
            'adjust inventory': 'Adjust Stock Inventory',
            'previous page': 'Previous Page',
            'next page': 'Next Page',
            'page': 'Page',
            'confirm_resume': 'Do you want to resume the checking?',
            'please_select_adjustments': 'No adjustments have been selected.',
            'processing': 'Processing',
        },
        bn: {
            'inventory_check': '\u0987\u09a8\u09ad\u09c7\u09a8\u09cd\u099f\u09b0\u09bf \u099a\u09c7\u0995',
            'Resume Checking': 'চেকিং এ ফিরে যান',
            'Calculation': 'হিসাব',
            'complete match': 'সম্পূর্ণ মিল',
            'Under Stock': 'আন্ডার স্টক',
            'extra match': 'অতিরিক্ত স্টক',
            'Total Count ID': 'মোট চেক করা আইডি',
            'remaining count ID': 'অবশিষ্ট বাকি আইডি',
            'under match': 'আন্ডার স্টক',
            'id': 'আইডি',
            'supplier': 'মহাজন',
            'type': 'টাইপ',
            'color': 'রং',
            'picture': 'ছবি',
            'selling_price': 'গায়ের দাম',
            'purchase_price': 'ডজন দাম',
            'current_stock': 'বর্তমান স্টক',
            'counted': 'গণনাকৃত',
            'diff': 'পার্থক্য',
            'fix the pair': 'জোড়া মেলান',
            'cancel': 'বাতিল',
            'total': 'মোট',
            'adjust inventory': 'স্টক সমন্বয় করুন',
            'previous page': 'পূর্ববর্তী পাতা',
            'next page': 'পরবর্তী পাতা',
            'page': 'পৃষ্ঠা',
            'confirm_resume': 'আপনি কি পুনরায় চেকিং এ ফিরে যেতে চান?',
            'please_select_adjustments': 'কোনো সমন্বয় নির্বাচন করা হয়নি।',
            'processing': 'প্রক্রিয়াকরণ হচ্ছে',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Resume Confirmation
const handleResumeChecking = () => {
    if (confirm(t('pages.confirm_resume'))) {
        router.get(route('inventory-check.resume', { inventoryCheck: props.inventoryCheck.id }));
    }
};

// Form State and Submission
const formState = ref({});
const globalState = ref({ partial: '', extra: '' });
const isSubmitting = ref(false);
const submitProgress = ref(0);

const handleRowAction = (row, action, type) => {
    formState.value[row.id] = {
        id: row.id,
        shoe_id: row.shoe_id || row.id,
        count: row.count || 0,
        action: action
    };
};

const handleSelectAll = (type, action) => {
    globalState.value[type] = action;
    const targetTable = tables.value[type];
    
    targetTable.data.forEach(row => {
        handleRowAction(row, action, type);
    });
};

const sendChunk = (index, chunks) => {
    if (index >= chunks.length) {
        // Redirect to inventory index page upon success
        router.get(route('inventory-check.index'));
        return;
    }

    axios.post(route('inventory-check.resolve', { inventoryCheck: props.inventoryCheck.id }), {
        resolved_batch: chunks[index],
        is_final_chunk: (index === chunks.length - 1) ? 1 : 0
    }).then(() => {
        submitProgress.value = Math.round(((index + 1) / chunks.length) * 100);
        sendChunk(index + 1, chunks);
    }).catch(err => {
        isSubmitting.value = false;
        alert('Error adjusting batch. See console.');
        console.error(err);
    });
};

const handleAdjustInventory = () => {
    const allData = Object.values(formState.value);

    if (allData.length === 0) {
        alert(t('pages.please_select_adjustments'));
        return;
    }

    // Split data into chunks of 100
    const chunkSize = 100;
    const chunks = [];
    for (let i = 0; i < allData.length; i += chunkSize) {
        chunks.push(allData.slice(i, i + chunkSize));
    }

    isSubmitting.value = true;
    submitProgress.value = 0;

    sendChunk(0, chunks);
};

// Group Tables State and Fetching
const tables = ref({
    partial: {
        title: t('pages.under match'),
        data: [],
        current_page: 1,
        last_page: 1,
        total: 0,
        from: 0,
        to: 0,
        search: '',
        perPage: 10,
        pageInput: 1
    },
    extra: {
        title: t('pages.extra match'),
        data: [],
        current_page: 1,
        last_page: 1,
        total: 0,
        from: 0,
        to: 0,
        search: '',
        perPage: 10,
        pageInput: 1
    }
});

const fetchTableData = (type) => {
    const group = tables.value[type];
    axios.get(route('inventory-check.entries-data', { id: props.inventoryCheck.id }), {
        params: {
            type: type,
            search: group.search,
            per_page: group.perPage,
            page: group.current_page
        }
    }).then(res => {
        group.data = res.data.data;
        group.current_page = res.data.current_page;
        group.last_page = res.data.last_page;
        group.total = res.data.total;
        group.from = res.data.from;
        group.to = res.data.to;
        group.pageInput = res.data.current_page;

        // Auto check global selection if set
        if (globalState.value[type]) {
            res.data.data.forEach(row => {
                if (!formState.value[row.id]) {
                    handleRowAction(row, globalState.value[type], type);
                }
            });
        }
    });
};

const getImagePath = (row, type) => {
    if (type === 'partial') {
        return `/images/small-thumbnail/${row.image}`;
    }
    return row.image_url || `/images/small-thumbnail/${row.image}`;
};

// Image Modal State
const showImageModal = ref(false);
const activeImagePath = ref('');

const openImageModal = (row, type) => {
    activeImagePath.value = getImagePath(row, type);
    showImageModal.value = true;
};

// Helper sums
const getSum = (data, col) => {
    return data.reduce((sum, item) => sum + parseFloat(item[col] || 0), 0);
};

const getPageCountSum = (data) => {
    return data.reduce((sum, item) => sum + parseInt(item.count || 0), 0);
};

const getRemainingSum = (data, type) => {
    return data.reduce((sum, item) => {
        const remainingVal = parseFloat(item.remaining || 0);
        return sum + (type === 'partial' ? remainingVal : -remainingVal);
    }, 0);
};

const formatStatValue = (val, key) => {
    if (key === 'complete_match_pairs' || key === 'under_stock_pairs' || key === 'extra_match_pairs') {
        const floatVal = parseFloat(val || 0);
        return floatVal > 0 ? floatVal.toFixed(2) : '0';
    }
    return val;
};

const toFixed = (val) => {
    return parseFloat(val || 0).toFixed(2);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' }).replace(/\//g, '-');
};

onMounted(() => {
    fetchTableData('partial');
    fetchTableData('extra');
});
</script>

<template>
    <Head :title="t('menu.inventory_check') || 'Inventory Check'" />

    <AuthenticatedLayout>
        <div class="container-fluid mt-4">
            <div class="card card-primary card-outline p-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center border-bottom pb-2 mb-3">
                    <h1 class="font-weight-bold text-dark mb-2 mb-sm-0 fs-3">
                        {{ t('menu.inventory_check') }} <span class="fw-normal text-muted fs-5 ms-2">{{ inventoryCheck.start_date }}</span>
                    </h1>
                    <button @click="handleResumeChecking" class="btn btn-primary shadow-sm fw-bold btn-resume-check">
                        <i class="fas fa-undo mr-1 me-1"></i> {{ t('pages.Resume Checking') }}
                    </button>
                </div>

                <!-- Statistics Grid Row -->
                <div class="row mb-2">
                    <!-- Complete Match -->
                    <div class="col-6 col-md-4 col-lg mb-3">
                        <div class="stat-card stat-card-success h-100 mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">{{ t('pages.complete match') }}</div>
                                    <div class="stat-value text-success">{{ formatStatValue(stats.complete_match_pairs, 'complete_match_pairs') }}</div>
                                </div>
                                <div class="stat-icon text-success"><i class="fas fa-check-circle"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Under Stock -->
                    <div class="col-6 col-md-4 col-lg mb-3">
                        <div class="stat-card stat-card-warning h-100 mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">{{ t('pages.Under Stock') }}</div>
                                    <div class="stat-value text-warning">{{ formatStatValue(stats.under_stock_pairs, 'under_stock_pairs') }}</div>
                                </div>
                                <div class="stat-icon text-warning"><i class="fas fa-arrow-circle-down"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Extra Match -->
                    <div class="col-6 col-md-4 col-lg mb-3">
                        <div class="stat-card stat-card-danger h-100 mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">{{ t('pages.extra match') }}</div>
                                    <div class="stat-value text-danger">{{ formatStatValue(stats.extra_match_pairs, 'extra_match_pairs') }}</div>
                                </div>
                                <div class="stat-icon text-danger"><i class="fas fa-arrow-circle-up"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Count ID -->
                    <div class="col-6 col-md-4 col-lg mb-3">
                        <div class="stat-card stat-card-info h-100 mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">{{ t('pages.Total Count ID') }}</div>
                                    <div class="stat-value text-info">{{ stats.total_count_ids }}</div>
                                </div>
                                <div class="stat-icon text-info"><i class="fas fa-barcode"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Remaining count ID (Link) -->
                    <div class="col-6 col-md-4 col-lg mb-3">
                        <Link :href="route('inventory-check.remaining-data', { id: inventoryCheck.id })" id="reset-check-form" class="text-decoration-none d-block h-100">
                            <div class="stat-card stat-card-dark h-100 mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-label text-primary font-weight-bold">
                                            <i class="fas fa-list-alt mr-1 me-1"></i> {{ t('pages.remaining count ID') }}
                                        </div>
                                        <div class="stat-value text-dark">{{ stats.remaining_count_ids }}</div>
                                    </div>
                                    <div class="stat-icon text-secondary"><i class="fas fa-hourglass-half"></i></div>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Tables: Under Stock, Extra Stock -->
                <form @submit.prevent="handleAdjustInventory">
                    <div v-for="(group, type) in tables" :key="type" class="card my-4 border">
                        <div class="card-header bg-light d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                            <h4 class="mb-2 mb-sm-0 text-dark mb-0">
                                <strong>{{ group.title }}</strong>
                            </h4>
                        </div>

                        <div class="card-body p-3">
                            <!-- DataTables controls row -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-sm-12 col-md-6 d-flex align-items-center mb-2 mb-md-0">
                                    <label class="d-inline-flex align-items-center gap-1 mb-0 text-muted show-entries-label">
                                        Show
                                        <select v-model="group.perPage" class="form-select form-select-sm d-inline-block mx-1" style="width: auto;" @change="group.current_page = 1; fetchTableData(type)">
                                            <option :value="5">5</option>
                                            <option :value="10">10</option>
                                            <option :value="25">25</option>
                                            <option :value="50">50</option>
                                            <option :value="100">100</option>
                                            <option :value="-1">All</option>
                                        </select>
                                        entries
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-6 d-flex justify-content-md-end align-items-center justify-content-end">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" v-model="group.search" class="form-control form-control-sm" placeholder="Searching..." @keyup.enter="group.current_page = 1; fetchTableData(type)" style="max-width: 200px;">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover text-center inventory-check-table mb-0 w-100">
                                    <thead>
                                        <tr>
                                            <th>{{ t('pages.id') }}</th>
                                            <th>{{ t('pages.supplier') }}</th>
                                            <th>{{ t('pages.type') }}</th>
                                            <th>{{ t('pages.color') }}</th>
                                            <th>{{ t('pages.picture') }}</th>
                                            <th>{{ t('pages.selling_price') }}</th>
                                            <th>{{ t('pages.purchase_price') }}</th>
                                            <th>{{ t('pages.current_stock') }}</th>
                                            <th>{{ t('pages.counted') }}</th>
                                            <th>{{ t('pages.diff') }}</th>
                                            <th>
                                                <div class="d-flex align-items-center justify-content-center gap-3">
                                                    <div class="form-check form-check-inline mb-0">
                                                        <input class="form-check-input" type="radio" :name="`global_${type}`" :id="`global_fix_${type}`" :value="type === 'partial' ? 'under_stock' : 'over_stock'" @change="handleSelectAll(type, type === 'partial' ? 'under_stock' : 'over_stock')">
                                                        <label class="form-check-label text-secondary resolve-action-label-header" :for="`global_fix_${type}`">{{ t('pages.fix the pair') }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-0">
                                                        <input class="form-check-input" type="radio" :name="`global_${type}`" :id="`global_cancel_${type}`" value="cancel" @change="handleSelectAll(type, 'cancel')">
                                                        <label class="form-check-label text-secondary resolve-action-label-header" :for="`global_cancel_${type}`">{{ t('pages.cancel') }}</label>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="row in group.data" :key="row.id">
                                            <td class="fw-bold">{{ row.id }}</td>
                                            <td>{{ row.factory || '-' }}</td>
                                            <td>{{ row.category || '-' }}</td>
                                            <td>{{ row.color || '-' }}</td>
                                            <td>
                                                <button type="button" @click="openImageModal(row, type)" class="btn btn-link p-0">
                                                    <img :src="getImagePath(row, type)" height="50" class="rounded object-fit-cover shadow-sm" style="width: 50px;">
                                                </button>
                                            </td>
                                            <td>{{ toFixed(row.retail_price) }}</td>
                                            <td>{{ toFixed(row.purchase_price) }}</td>
                                            <td>{{ row.current_stock }}</td>
                                            <td>{{ row.count }}</td>
                                            <td class="fw-bold">
                                                {{ type === 'partial' ? row.remaining : -row.remaining }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center gap-3">
                                                    <div class="form-check form-check-inline mb-0">
                                                        <input class="form-check-input" type="radio" :name="`row_${row.id}`" :id="`row_fix_${row.id}`" :value="type === 'partial' ? 'under_stock' : 'over_stock'" :checked="formState[row.id]?.action === (type === 'partial' ? 'under_stock' : 'over_stock')" @change="handleRowAction(row, type === 'partial' ? 'under_stock' : 'over_stock', type)" required>
                                                        <label class="form-check-label text-muted resolve-action-label" :for="`row_fix_${row.id}`">{{ t('pages.fix the pair') }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-0">
                                                        <input class="form-check-input" type="radio" :name="`row_${row.id}`" :id="`row_cancel_${row.id}`" value="cancel" :checked="formState[row.id]?.action === 'cancel'" @change="handleRowAction(row, 'cancel', type)">
                                                        <label class="form-check-label text-muted resolve-action-label" :for="`row_cancel_${row.id}`">{{ t('pages.cancel') }}</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="group.data.length === 0">
                                            <td colspan="11" class="text-muted p-3">{{ t('pages.No records found.') }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="font-weight-bold" v-if="group.data.length > 0">
                                        <tr>
                                            <td colspan="5" class="text-end pe-4">{{ t('pages.total') }} :</td>
                                            <td>{{ toFixed(getSum(group.data, 'retail_price')) }}</td>
                                            <td>{{ toFixed(getSum(group.data, 'purchase_price')) }}</td>
                                            <td>{{ getSum(group.data, 'current_stock') }}</td>
                                            <td>{{ getPageCountSum(group.data) }}</td>
                                            <td>
                                                {{ Math.abs(getRemainingSum(group.data, type)) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="px-6 pb-4">
                                <Pagination :pagination="group" @page-changed="(page) => { group.current_page = page; fetchTableData(type); }" />
                            </div>
                        </div>
                    </div>

                    <!-- Submit Actions Button -->
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm" :disabled="isSubmitting">
                            <span v-if="isSubmitting">
                                <i class="fas fa-spinner fa-spin me-2"></i> {{ t('pages.processing') }} ({{ submitProgress }}%)...
                            </span>
                            <span v-else>
                                <i class="fas fa-check-circle me-1"></i> {{ t('pages.adjust inventory') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Image Popup Modal -->
        <div class="modal fade show d-block" v-if="showImageModal" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.75);" @click="showImageModal = false">
            <div class="modal-dialog modal-dialog-centered text-center" style="max-width: 90%; width: auto;">
                <div class="modal-content bg-transparent border-0">
                    <img :src="activeImagePath" class="img-fluid rounded shadow-lg mx-auto" style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Bootstrap 4 Grid System Emulation */
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.col-6, .col-12, .col-sm-4, .col-sm-6, .col-sm-8, .col-sm-12, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-lg, .col-lg-4, .col-lg-8 {
    position: relative;
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    box-sizing: border-box;
}

/* Mobile defaults */
.col-6 {
    flex: 0 0 50%;
    max-width: 50%;
}
.col-12 {
    flex: 0 0 100%;
    max-width: 100%;
}

/* Screen overrides */
@media (min-width: 576px) {
    .col-sm-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
    .col-sm-6 { flex: 0 0 50%; max-width: 50%; }
    .col-sm-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
    .col-sm-12 { flex: 0 0 100%; max-width: 100%; }
}
@media (min-width: 768px) {
    .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
    .col-md-5 { flex: 0 0 41.666667%; max-width: 41.666667%; }
    .col-md-6 { flex: 0 0 50%; max-width: 50%; }
    .col-md-7 { flex: 0 0 58.333333%; max-width: 58.333333%; }
}
@media (min-width: 992px) {
    .col-lg { flex-basis: 0; flex-grow: 1; max-width: 100%; }
    .col-lg-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
    .col-lg-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
}

/* Card Styling */
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
    box-sizing: border-box;
}
.card-body {
    flex: 1 1 auto;
    padding: 1.25rem;
}
.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
}
.card-primary.card-outline {
    border-top: 3px solid #007bff;
}
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
}
.border {
    border: 1px solid #dee2e6 !important;
}
.h-100 {
    height: 100% !important;
}

/* Form inputs & controls styling */
.form-group {
    margin-bottom: 1rem;
}
.form-label {
    display: inline-block;
    margin-bottom: .5rem;
}
.form-control {
    display: block;
    width: 100%;
    height: calc(1.5em + .75rem + 2px);
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    box-sizing: border-box;
}
.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
}

/* Buttons Styling */
.btn {
    display: inline-block;
    font-weight: 400;
    color: #212529;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    box-sizing: border-box;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-primary:hover {
    color: #fff;
    background-color: #0069d9;
    border-color: #0062cc;
}
.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}
.btn-outline-secondary:hover {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}
.btn:disabled, .btn.disabled {
    opacity: .65;
    cursor: not-allowed;
}
.w-100 {
    width: 100% !important;
}
.py-2 {
    padding-top: .5rem !important;
    padding-bottom: .5rem !important;
}
.px-4 {
    padding-left: 1.5rem !important;
    padding-right: 1.5rem !important;
}

/* Input Groups prepends and appends */
.input-group {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
    width: 100%;
}
.input-group > .form-control {
    position: relative;
    flex: 1 1 auto;
    width: 1%;
    min-width: 0;
    margin-bottom: 0;
}
.input-group > .form-control:not(:first-child) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.input-group > .form-control:not(:last-child) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.input-group-prepend, .input-group-append {
    display: flex;
}
.input-group-prepend {
    margin-right: -1px;
}
.input-group-append {
    margin-left: -1px;
}
.input-group-text {
    display: flex;
    align-items: center;
    padding: .375rem .75rem;
    margin-bottom: 0;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    text-align: center;
    white-space: nowrap;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: .25rem;
}
.input-group-prepend .input-group-text {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.input-group-append .input-group-text {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.border-secondary {
    border-color: #6c757d !important;
}
.border-right-0 {
    border-right: 0 !important;
}
.border-left-0 {
    border-left: 0 !important;
}

/* Form Inline and Pagination layout elements */
.form-inline {
    display: flex;
    flex-flow: row wrap;
    align-items: center;
}

/* Tables styling */
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    vertical-align: top;
    border-color: #dee2e6;
    border-collapse: collapse;
}
.table th, .table td {
    padding: .75rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.05);
}
.table-hover tbody tr:hover {
    color: #212529;
    background-color: rgba(0,0,0,.075);
}
.table-bordered {
    border: 1px solid #dee2e6;
}

/* Alignment and helper utilities */
.d-flex {
    display: flex !important;
}
.flex-wrap {
    flex-wrap: wrap !important;
}
.flex-column {
    flex-direction: column !important;
}
.justify-content-between {
    justify-content: space-between !important;
}
.justify-content-center {
    justify-content: center !important;
}
.align-items-center {
    align-items: center !important;
}
.flex-sm-row {
    @media (min-width: 576px) {
        flex-direction: row !important;
    }
}
.align-items-sm-center {
    @media (min-width: 576px) {
        align-items: center !important;
    }
}
.align-items-start {
    align-items: flex-start !important;
}
.text-center {
    text-align: center !important;
}
.text-end {
    text-align: right !important;
}
.text-muted {
    color: #6c757d !important;
}
.text-dark {
    color: #343a40 !important;
}
.font-weight-bold {
    font-weight: 700 !important;
}
.border-bottom {
    border-bottom: 1px solid #dee2e6 !important;
}
.pb-2 {
    padding-bottom: .5rem !important;
}
.p-3 {
    padding: 1rem !important;
}
.mb-0 {
    margin-bottom: 0 !important;
}
.mb-2 {
    margin-bottom: .5rem !important;
}
.mb-3 {
    margin-bottom: 1rem !important;
}
.mb-4 {
    margin-bottom: 1.5rem !important;
}
.mt-2 {
    margin-top: .5rem !important;
}
.mt-3 {
    margin-top: 1rem !important;
}
.mt-4 {
    margin-top: 1.5rem !important;
}
.my-4 {
    margin-top: 1.5rem !important;
    margin-bottom: 1.5rem !important;
}
.mx-2 {
    margin-left: .5rem !important;
    margin-right: .5rem !important;
}
.mr-2 {
    margin-right: .5rem !important;
}
.w-50 {
    width: 50% !important;
}
.rounded {
    border-radius: .25rem !important;
}
.bg-light {
    background-color: #f8f9fa !important;
}
.object-fit-cover {
    object-fit: cover !important;
}

/* Dark Mode overrides */
.dark .card {
    background-color: #1e1e2e !important;
    border-color: #2d2d3f !important;
    color: #f8f9fa !important;
}
.dark .card-header {
    background-color: #252535 !important;
    border-bottom: 1px solid #2d2d3f !important;
    color: #f8f9fa !important;
}
.dark .bg-light {
    background-color: #151521 !important;
    color: #f8f9fa !important;
}
.dark .product-info-box {
    background-color: #151521 !important;
    border-color: #2d2d3f !important;
    color: #f8f9fa !important;
}
.dark .shoe-check-span {
    color: #ffffff !important;
}
.dark .text-dark {
    color: #ffffff !important;
}
.dark .text-muted {
    color: #a0a0b0 !important;
}
.dark .border {
    border-color: #2d2d3f !important;
}
.dark hr {
    border-color: #2d2d3f !important;
}
.dark .form-control {
    background-color: #151521 !important;
    border-color: #2d2d3f !important;
    color: #ffffff !important;
}
.dark .form-control:focus {
    background-color: #151521 !important;
    border-color: #3b82f6 !important;
}
.dark .form-control:disabled, .dark .form-control[readonly] {
    background-color: #1b1b29 !important;
    color: #a0a0b0 !important;
    opacity: 0.8;
}
.dark .input-group-text {
    background-color: #151521 !important;
    border-color: #2d2d3f !important;
    color: #ffffff !important;
}
.dark label {
    color: #e2e8f0 !important;
}
.dark .table {
    color: #f8f9fa !important;
    border-color: #2d2d3f !important;
}
.dark .table th, .dark .table td {
    border-color: #2d2d3f !important;
    color: #e2e8f0 !important;
}
.dark .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(255, 255, 255, 0.02) !important;
}
.dark .table-hover tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.05) !important;
}
.dark .table-bordered {
    border-color: #2d2d3f !important;
}
.dark .table-secondary {
    background-color: #252535 !important;
    color: #ffffff !important;
}
.dark tfoot.font-weight-bold {
    background-color: #151521 !important;
    color: #ffffff !important;
}
.dark tfoot td {
    color: #ffffff !important;
}

/* Custom alignment and sizing */
.justify-content-end {
    justify-content: flex-end !important;
}
@media (min-width: 768px) {
    .justify-content-md-end {
        justify-content: flex-end !important;
    }
}
.show-entries-label {
    font-size: 15px !important;
    font-weight: 600 !important;
}
.show-entries-label select {
    font-size: 15px !important;
    height: auto !important;
    padding: 0.25rem 1.75rem 0.25rem 0.75rem !important;
}
.search-label {
    font-size: 15px !important;
    font-weight: 600 !important;
}
.resolve-action-label {
    font-size: 15px !important;
    font-weight: 600 !important;
    cursor: pointer;
}
.resolve-action-label-header {
    font-size: 15px !important;
    font-weight: 700 !important;
    cursor: pointer;
}
.form-check-input {
    width: 1.15em !important;
    height: 1.15em !important;
    cursor: pointer;
}
</style>
