<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
    }
    if (page.props.translations?.pages?.[cleanKey]) {
        return page.props.translations.pages[cleanKey];
    }
    const fallbacks = {
        en: {
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
            'Resume Checking': 'পুনরায় চেকিং এ ফিরে যান',
            'Calculation': 'হিসাব সমীকরণ',
            'complete match': 'সম্পূর্ণ মিল',
            'Under Stock': 'ঘাটতি স্টক',
            'extra match': 'অতিরিক্ত স্টক',
            'Total Count ID': 'মোট গণনা আইডি',
            'remaining count ID': 'অবশিষ্ট গণনা আইডি',
            'under match': 'ঘাটতি স্টক মেলান',
            'id': 'আইডি',
            'supplier': 'মহাজন',
            'type': 'ধরণ',
            'color': 'রঙ',
            'picture': 'ছবি',
            'selling_price': 'বিক্রয়মূল্য',
            'purchase_price': 'ক্রয়মূল্য',
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
    const targetTable = type === 'partial' ? partialTable : extraTable;
    
    targetTable.value.data.forEach(row => {
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
const partialTable = ref({ data: [], current_page: 1, last_page: 1, total: 0 });
const extraTable = ref({ data: [], current_page: 1, last_page: 1, total: 0 });

const partialSearch = ref('');
const extraSearch = ref('');

const partialPerPage = ref(10);
const extraPerPage = ref(10);

const fetchTableData = (type) => {
    let targetRef, searchVal, perPageVal, pageVal;
    if (type === 'partial') {
        targetRef = partialTable;
        searchVal = partialSearch.value;
        perPageVal = partialPerPage.value;
        pageVal = partialTable.value.current_page;
    } else {
        targetRef = extraTable;
        searchVal = extraSearch.value;
        perPageVal = extraPerPage.value;
        pageVal = extraTable.value.current_page;
    }

    axios.get(route('inventory-check.entries-data', { id: props.inventoryCheck.id }), {
        params: {
            type: type,
            search: searchVal,
            per_page: perPageVal,
            page: pageVal
        }
    }).then(res => {
        targetRef.value = res.data;

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

// Image Modal State
const showImageModal = ref(false);
const activeImagePath = ref('');

const openImageModal = (row) => {
    activeImagePath.value = `/images/small-thumbnail/${row.image}`;
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

const toFixed = (val) => {
    return parseFloat(val || 0).toFixed(2);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
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
            <div class="card card-primary card-outline shadow-sm p-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center border-bottom pb-2 mb-4">
                    <h2 class="font-weight-bold text-dark mb-2 mb-sm-0 fs-4">
                        <i class="fas fa-clipboard-check text-primary me-2"></i>
                        {{ t('menu.inventory_check') }}
                        <small class="text-muted">({{ formatDate(inventoryCheck.start_date) }})</small>
                    </h2>
                    <button @click="handleResumeChecking" class="btn btn-primary shadow-sm fw-bold btn-resume-check">
                        <i class="fas fa-undo me-1"></i> {{ t('pages.Resume Checking') }}
                    </button>
                </div>

                <!-- Statistics grid cards -->
                <div class="row g-3 mb-4">
                    <!-- Complete Match -->
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded bg-light border border-success h-100 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted d-block small fw-bold">{{ t('pages.complete match') }}</span>
                                <span class="fs-4 fw-bold text-success">{{ stats.complete_match_pairs }}</span>
                            </div>
                            <div class="fs-2 text-success"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>

                    <!-- Under Stock -->
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded bg-light border border-warning h-100 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted d-block small fw-bold">{{ t('pages.Under Stock') }}</span>
                                <span class="fs-4 fw-bold text-warning">{{ stats.under_stock_pairs }}</span>
                            </div>
                            <div class="fs-2 text-warning"><i class="fas fa-arrow-circle-down"></i></div>
                        </div>
                    </div>

                    <!-- Extra Match -->
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded bg-light border border-danger h-100 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted d-block small fw-bold">{{ t('pages.extra match') }}</span>
                                <span class="fs-4 fw-bold text-danger">{{ stats.extra_match_pairs }}</span>
                            </div>
                            <div class="fs-2 text-danger"><i class="fas fa-arrow-circle-up"></i></div>
                        </div>
                    </div>

                    <!-- Total Scanned -->
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded bg-light border border-info h-100 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted d-block small fw-bold">{{ t('pages.Total Count ID') }}</span>
                                <span class="fs-4 fw-bold text-info">{{ stats.total_count_ids }}</span>
                            </div>
                            <div class="fs-2 text-info"><i class="fas fa-barcode"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Tables: Under Stock, Extra Stock -->
                <form @submit.prevent="handleAdjustInventory">
                    <div v-for="group in [
                        { title: t('pages.under match'), type: 'partial', table: partialTable, search: partialSearch, perPage: partialPerPage },
                        { title: t('pages.extra match'), type: 'extra', table: extraTable, search: extraSearch, perPage: extraPerPage }
                    ]" :key="group.type" class="card my-4 border shadow-sm">
                        <div class="card-header bg-light d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center py-3">
                            <h5 class="mb-2 mb-sm-0 text-dark fw-bold">
                                {{ group.title }}
                            </h5>
                            <div class="d-flex gap-2">
                                <input type="text" v-model="group.search.value" class="form-control form-control-sm" placeholder="Search here..." @keyup.enter="fetchTableData(group.type)" style="max-width: 200px;">
                                <button type="button" @click="fetchTableData(group.type)" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i></button>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover text-center align-middle mb-0 w-100">
                                    <thead class="table-dark">
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
                                                        <input class="form-check-input" type="radio" :name="`global_${group.type}`" :id="`global_fix_${group.type}`" :value="group.type === 'partial' ? 'under_stock' : 'over_stock'" @change="handleSelectAll(group.type, group.type === 'partial' ? 'under_stock' : 'over_stock')">
                                                        <label class="form-check-label fw-bold text-secondary" :for="`global_fix_${group.type}`">{{ t('pages.fix the pair') }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-0">
                                                        <input class="form-check-input" type="radio" :name="`global_${group.type}`" :id="`global_cancel_${group.type}`" value="cancel" @change="handleSelectAll(group.type, 'cancel')">
                                                        <label class="form-check-label fw-bold text-secondary" :for="`global_cancel_${group.type}`">{{ t('pages.cancel') }}</label>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="row in group.table.value.data" :key="row.id">
                                            <td class="fw-bold">{{ row.id }}</td>
                                            <td>{{ row.factory || '-' }}</td>
                                            <td>{{ row.category || '-' }}</td>
                                            <td>{{ row.color || '-' }}</td>
                                            <td>
                                                <button type="button" @click="openImageModal(row)" class="btn btn-link p-0">
                                                    <img :src="`/images/small-thumbnail/${row.image}`" height="50" class="rounded object-fit-cover shadow-sm" style="width: 50px;">
                                                </button>
                                            </td>
                                            <td class="text-success fw-bold">{{ toFixed(row.retail_price) }}</td>
                                            <td class="text-danger fw-bold">{{ toFixed(row.purchase_price) }}</td>
                                            <td>{{ row.current_stock }}</td>
                                            <td>{{ row.count }}</td>
                                            <td class="fw-bold">
                                                {{ group.type === 'partial' ? row.remaining : -row.remaining }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center gap-3">
                                                    <div class="form-check form-check-inline mb-0">
                                                        <input class="form-check-input" type="radio" :name="`row_${row.id}`" :id="`row_fix_${row.id}`" :value="group.type === 'partial' ? 'under_stock' : 'over_stock'" :checked="formState[row.id]?.action === (group.type === 'partial' ? 'under_stock' : 'over_stock')" @change="handleRowAction(row, group.type === 'partial' ? 'under_stock' : 'over_stock', group.type)" required>
                                                        <label class="form-check-label text-muted small" :for="`row_fix_${row.id}`">{{ t('pages.fix the pair') }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-0">
                                                        <input class="form-check-input" type="radio" :name="`row_${row.id}`" :id="`row_cancel_${row.id}`" value="cancel" :checked="formState[row.id]?.action === 'cancel'" @change="handleRowAction(row, 'cancel', group.type)">
                                                        <label class="form-check-label text-muted small" :for="`row_cancel_${row.id}`">{{ t('pages.cancel') }}</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="group.table.value.data.length === 0">
                                            <td colspan="11" class="text-muted p-3">{{ t('pages.No records found.') }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-secondary fw-bold" v-if="group.table.value.data.length > 0">
                                        <tr>
                                            <td colspan="5" class="text-end pe-4">{{ t('pages.total') }} :</td>
                                            <td class="text-success">{{ toFixed(getSum(group.table.value.data, 'retail_price')) }}</td>
                                            <td class="text-danger">{{ toFixed(getSum(group.table.value.data, 'purchase_price')) }}</td>
                                            <td>{{ getSum(group.table.value.data, 'current_stock') }}</td>
                                            <td>{{ getPageCountSum(group.table.value.data) }}</td>
                                            <td>
                                                {{ Math.abs(getRemainingSum(group.table.value.data, group.type)) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Pagination Navigation for Subtable -->
                            <div v-if="group.table.value.last_page > 1" class="d-flex justify-content-center align-items-center gap-2 mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm" :disabled="group.table.value.current_page === 1" @click="group.table.value.current_page--; fetchTableData(group.type)">
                                    &larr; {{ t('pages.previous page') }}
                                </button>
                                <span class="text-muted small">
                                    {{ t('pages.page') }} {{ group.table.value.current_page }} / {{ group.table.value.last_page }}
                                </span>
                                <button type="button" class="btn btn-outline-secondary btn-sm" :disabled="group.table.value.current_page === group.table.value.last_page" @click="group.table.value.current_page++; fetchTableData(group.type)">
                                    {{ t('pages.next page') }} &rarr;
                                </button>
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
.fw-bold {
    font-weight: 700 !important;
}
.fs-2 {
    font-size: 2rem !important;
}
.fs-4 {
    font-size: 1.5rem !important;
}
.fs-5 {
    font-size: 1.25rem !important;
}
.fs-6 {
    font-size: 1rem !important;
}
.object-fit-cover {
    object-fit: cover !important;
}
</style>
