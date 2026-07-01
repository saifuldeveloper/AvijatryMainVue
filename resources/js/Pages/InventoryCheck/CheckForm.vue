<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
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
            'Inventory Check': 'Inventory Check',
            'supplier': 'Supplier',
            'type': 'Type',
            'color': 'Color',
            'selling_price': 'Selling Price',
            'purchase_price': 'Purchase Price',
            'id': 'ID',
            'pair': 'Pairs',
            'pair_stock': 'Stock (Pairs)',
            'pair_due': 'Remaining (Pairs)',
            'add': 'Add',
            'finish checking': 'Finish Checking',
            'Calculation': 'Calculations',
            'complete match': 'Complete Match',
            'Under Stock': 'Under Stock',
            'extra match': 'Extra Stock',
            'Total Count ID': 'Total Scanned IDs',
            'Total Count pairs': 'Total Scanned Pairs',
            'remaining count ID': 'Remaining IDs',
            'remaining count pairs': 'Remaining Pairs',
            'low': 'Under Stock',
            'extra': 'Extra',
            'current_stock': 'Current Stock',
            'total': 'Total',
            'previous page': 'Previous Page',
            'next page': 'Next Page',
            'page': 'Page',
            'show': 'Show',
            'showing_from': 'Showing',
            'to': 'to',
            'confirm_finish': 'Are you sure you want to finish the inventory check?',
            'already_counted': 'This ID has already been counted once. Do you want to add it again?',
            'no_shoe_alert': 'No shoe found with this ID.',
        },
        bn: {
            'Inventory Check': 'স্টক চেকিং',
            'supplier': 'মহাজন',
            'type': 'ধরণ',
            'color': 'রঙ',
            'selling_price': 'বিক্রয়মূল্য',
            'purchase_price': 'ক্রয়মূল্য',
            'id': 'আইডি',
            'pair': 'জোড়া',
            'pair_stock': 'স্টক জোড়া',
            'pair_due': 'অবশিষ্ট জোড়া',
            'add': 'যোগ করুন',
            'finish checking': 'চেকিং শেষ করুন',
            'Calculation': 'হিসাব সমীকরণ',
            'complete match': 'সম্পূর্ণ মিল',
            'Under Stock': 'ঘাটতি স্টক',
            'extra match': 'অতিরিক্ত স্টক',
            'Total Count ID': 'মোট গণনা আইডি',
            'Total Count pairs': 'মোট গণনা জোড়া',
            'remaining count ID': 'অবশিষ্ট গণনা আইডি',
            'remaining count pairs': 'অবশিষ্ট গণনা জোড়া',
            'low': 'ঘাটতি',
            'extra': 'অতিরিক্ত',
            'current_stock': 'বর্তমান স্টক',
            'total': 'মোট',
            'previous page': 'পূর্ববর্তী পাতা',
            'next page': 'পরবর্তী পাতা',
            'page': 'পৃষ্ঠা',
            'show': 'দেখান',
            'showing_from': 'দেখানো হচ্ছে',
            'to': 'থেকে',
            'confirm_finish': 'আপনি কি ইনভেন্টরি চেক শেষ করতে চান?',
            'already_counted': 'এই আইডিটি ইতিমধ্যেই একবার গণনা করা হয়েছে। আপনি কি আবার যোগ করতে চান?',
            'no_shoe_alert': 'এই আইডির কোন জুতা নেই।',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Scanner State
const shoeId = ref('');
const checkCount = ref('');
const isAlreadyCounted = ref(false);
const confirmBypass = ref(false);

const scannedShoe = ref(null);
const shoeStock = ref('');
const shoeDue = ref('');

// Loading state
const isSearching = ref(false);

// Lookup Shoe on Scan
const handleShoeLookup = () => {
    const val = shoeId.value.trim();
    if (!val) return;

    isSearching.value = true;
    axios.get(route('ajax.shoe.show', { shoe: val }), {
        params: { inventory_check_id: props.inventoryCheck.id }
    }).then(res => {
        scannedShoe.value = res.data;
        shoeStock.value = res.data.available || 0;
        isAlreadyCounted.value = res.data.already_counted || false;
        confirmBypass.value = false;

        // Check if the shoe is present in loaded tables to determine remaining count
        const partialItem = partialTable.value.data.find(item => item.id === val);
        const extraItem = extraTable.value.data.find(item => item.id === val);
        const fullItem = fullTable.value.data.find(item => item.id === val);
        
        if (partialItem) {
            shoeDue.value = partialItem.remaining;
        } else if (extraItem) {
            shoeDue.value = extraItem.remaining;
        } else if (fullItem) {
            shoeDue.value = 0;
        } else {
            shoeDue.value = res.data.available || 0;
        }

        // Auto focus count input
        document.getElementById('shoe-check-count')?.focus();
    }).catch(() => {
        alert(t('pages.no_shoe_alert'));
        shoeId.value = '';
        scannedShoe.value = null;
        shoeStock.value = '';
        shoeDue.value = '';
        document.getElementById('shoe-check-id')?.focus();
    }).finally(() => {
        isSearching.value = false;
    });
};

// Add Checklist Entry
const submitEntryForm = useForm({
    inventory_check_id: props.inventoryCheck.id,
    shoe_id: '',
    count: '',
});

const handleAddEntry = () => {
    if (isAlreadyCounted.value && !confirmBypass.value) {
        if (confirm(t('pages.already_counted'))) {
            confirmBypass.value = true;
        } else {
            resetScanner();
            return;
        }
    }

    submitEntryForm.shoe_id = shoeId.value.trim();
    submitEntryForm.count = checkCount.value;
    
    submitEntryForm.post(route('inventory-check-entry.store'), {
        onSuccess: () => {
            resetScanner();
            // Refresh tables
            fetchTableData('partial');
            fetchTableData('extra');
            fetchTableData('full');
            // Reload page statistics
            router.reload({ only: ['stats'] });
        }
    });
};

const resetScanner = () => {
    shoeId.value = '';
    checkCount.value = '';
    scannedShoe.value = null;
    shoeStock.value = '';
    shoeDue.value = '';
    isAlreadyCounted.value = false;
    confirmBypass.value = false;
    document.getElementById('shoe-check-id')?.focus();
};

// Finish Checking
const handleFinishChecking = () => {
    if (confirm(t('pages.confirm_finish'))) {
        router.get(route('inventory-check.complete', { inventoryCheck: props.inventoryCheck.id }));
    }
};

// Group Tables State and Fetching
const partialTable = ref({ data: [], current_page: 1, last_page: 1, total: 0 });
const extraTable = ref({ data: [], current_page: 1, last_page: 1, total: 0 });
const fullTable = ref({ data: [], current_page: 1, last_page: 1, total: 0 });

const partialSearch = ref('');
const extraSearch = ref('');
const fullSearch = ref('');

const partialPerPage = ref(10);
const extraPerPage = ref(10);
const fullPerPage = ref(10);

const fetchTableData = (type) => {
    let targetRef, searchVal, perPageVal, pageVal;
    if (type === 'partial') {
        targetRef = partialTable;
        searchVal = partialSearch.value;
        perPageVal = partialPerPage.value;
        pageVal = partialTable.value.current_page;
    } else if (type === 'extra') {
        targetRef = extraTable;
        searchVal = extraSearch.value;
        perPageVal = extraPerPage.value;
        pageVal = extraTable.value.current_page;
    } else {
        targetRef = fullTable;
        searchVal = fullSearch.value;
        perPageVal = fullPerPage.value;
        pageVal = fullTable.value.current_page;
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
    });
};

// Helper total sums
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
    fetchTableData('full');
    document.getElementById('shoe-check-id')?.focus();
});
</script>

<template>
    <Head :title="t('menu.inventory_check') || 'Inventory Check'" />

    <AuthenticatedLayout>
        <div class="container-fluid mt-4">
            <div class="card card-primary card-outline shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
                    <h2 class="font-weight-bold text-dark mb-0 fs-4">
                        <i class="fas fa-clipboard-check text-primary me-2"></i>
                        {{ t('menu.inventory_check') }}
                        <small class="text-muted">({{ formatDate(inventoryCheck.start_date) }})</small>
                    </h2>
                </div>

                <!-- Checking scanner form (Only show if not resolved) -->
                <div v-if="!inventoryCheck.resolved">
                    <form @submit.prevent="handleAddEntry" class="mb-4">
                        <div class="row">
                            <!-- Left form details -->
                            <div class="col-lg-8 col-md-7 mb-4">
                                <div class="card h-100 shadow-sm border mb-0">
                                    <div class="card-body p-4">
                                        <h5 class="fw-bold mb-3 border-bottom pb-2 text-center text-muted">
                                            <i class="fas fa-barcode me-2"></i> {{ t('pages.Inventory Check') }}
                                        </h5>

                                        <!-- Product Detail Box -->
                                        <div class="product-info-box p-3 bg-light rounded border my-4">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-sm-8 mb-3 mb-sm-0 line-height-lg">
                                                    <div class="d-flex flex-wrap fs-6">
                                                        <div class="w-50 mb-3">
                                                            <span class="text-muted d-block small">{{ t('pages.supplier') }}</span>
                                                            <strong class="text-dark">{{ scannedShoe?.factory?.name || '-' }}</strong>
                                                        </div>
                                                        <div class="w-50 mb-3">
                                                            <span class="text-muted d-block small">{{ t('pages.type') }}</span>
                                                            <strong class="text-dark">{{ scannedShoe?.category?.full_name || '-' }}</strong>
                                                        </div>
                                                        <div class="w-50 mb-3">
                                                            <span class="text-muted d-block small">{{ t('pages.color') }}</span>
                                                            <strong class="text-dark">{{ scannedShoe?.color?.name || '-' }}</strong>
                                                        </div>
                                                        <div class="w-50 mb-3">
                                                            <span class="text-muted d-block small">{{ t('pages.selling_price') }}</span>
                                                            <strong class="text-success">{{ scannedShoe ? toFixed(scannedShoe.retail_price) : '-' }}</strong>
                                                        </div>
                                                        <div class="w-50">
                                                            <span class="text-muted d-block small">{{ t('pages.purchase_price') }}</span>
                                                            <strong class="text-danger">{{ scannedShoe ? toFixed(scannedShoe.purchase_price) : '-' }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4 text-center">
                                                    <div class="d-inline-block bg-white p-1 border rounded shadow-sm">
                                                        <img v-if="scannedShoe?.image" :src="`/images/small-thumbnail/${scannedShoe.image}`" class="img-fluid rounded object-fit-cover" style="height: 120px; width: 120px;">
                                                        <img v-else src="/img/shoe.png" class="img-fluid rounded object-fit-cover" style="height: 120px; width: 120px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Form Input Rows -->
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold text-secondary" for="shoe-check-id">{{ t('pages.id') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" v-model="shoeId" id="shoe-check-id" class="form-control" @change="handleShoeLookup" required autofocus>
                                                        <span v-if="isSearching" class="input-group-text bg-white border-left-0"><i class="fas fa-spinner fa-spin"></i></span>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3 mb-sm-0">
                                                    <label class="form-label fw-bold text-secondary" for="shoe-check-count">{{ t('pages.pair') }}</label>
                                                    <input type="number" v-model="checkCount" id="shoe-check-count" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold text-secondary" for="shoe-check-available">{{ t('pages.pair_stock') }}</label>
                                                    <input type="text" :value="shoeStock" id="shoe-check-available" class="form-control bg-light" disabled>
                                                </div>

                                                <div class="form-group mb-0">
                                                    <label class="form-label fw-bold text-secondary" for="shoe-check-remaining">{{ t('pages.pair_due') }}</label>
                                                    <input type="text" :value="shoeDue" id="shoe-check-remaining" class="form-control bg-light" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Form Buttons -->
                                        <div class="row mt-4">
                                            <div class="col-sm-6 mb-2 mb-sm-0">
                                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm" :disabled="submitEntryForm.processing">
                                                    <i class="fas fa-plus-circle me-1"></i> {{ t('pages.add') }}
                                                </button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" @click="handleFinishChecking" class="btn btn-danger w-100 fw-bold py-2 shadow-sm" id="finish-checking-btn">
                                                    <i class="fas fa-check-double me-1"></i> {{ t('pages.finish checking') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Calculation stats panel -->
                            <div class="col-lg-4 col-md-5 mb-4">
                                <div class="card h-100 shadow-sm border mb-0">
                                    <div class="card-body p-3 bg-light rounded">
                                        <h5 class="fw-bold mb-3 border-bottom pb-2 text-center text-muted">
                                            <i class="fas fa-calculator me-1"></i> {{ t('pages.Calculation') }}
                                        </h5>

                                        <!-- Complete Match stat -->
                                        <div class="p-3 mb-2 rounded bg-white border border-success d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-muted d-block small fw-bold">{{ t('pages.complete match') }}</span>
                                                <span class="fs-4 fw-bold text-success">{{ stats.complete_match_pairs }}</span>
                                            </div>
                                            <div class="fs-2 text-success"><i class="fas fa-check-circle"></i></div>
                                        </div>

                                        <!-- Under Stock stat -->
                                        <div class="p-3 mb-2 rounded bg-white border border-warning d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-muted d-block small fw-bold">{{ t('pages.Under Stock') }}</span>
                                                <span class="fs-4 fw-bold text-warning">{{ stats.under_stock_pairs }}</span>
                                            </div>
                                            <div class="fs-2 text-warning"><i class="fas fa-arrow-circle-down"></i></div>
                                        </div>

                                        <!-- Extra Match stat -->
                                        <div class="p-3 mb-2 rounded bg-white border border-danger d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-muted d-block small fw-bold">{{ t('pages.extra match') }}</span>
                                                <span class="fs-4 fw-bold text-danger">{{ stats.extra_match_pairs }}</span>
                                            </div>
                                            <div class="fs-2 text-danger"><i class="fas fa-arrow-circle-up"></i></div>
                                        </div>

                                        <!-- Total Scanned stat -->
                                        <div class="p-3 mb-2 rounded bg-white border border-info d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-muted d-block small fw-bold">{{ t('pages.Total Count ID') }}</span>
                                                <span class="fs-4 fw-bold text-info">{{ stats.total_count_ids }}</span>
                                            </div>
                                            <div class="text-end">
                                                <span class="text-muted d-block small fw-bold">{{ t('pages.Total Count pairs') }}</span>
                                                <span class="fs-4 fw-bold text-info">{{ stats.total_count_pairs }}</span>
                                            </div>
                                            <div class="fs-2 text-info"><i class="fas fa-barcode"></i></div>
                                        </div>

                                        <!-- Remaining stat Link -->
                                        <Link :href="route('inventory-check.remaining-data', { id: inventoryCheck.id })" class="text-decoration-none d-block">
                                            <div class="p-3 rounded bg-white border border-dark d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="text-primary d-block small fw-bold"><i class="fas fa-list-alt me-1"></i> {{ t('pages.remaining count ID') }}</span>
                                                    <span class="fs-4 fw-bold text-dark">{{ stats.remaining_count_ids }}</span>
                                                </div>
                                                <div class="text-end">
                                                    <span class="text-primary d-block small fw-bold">{{ t('pages.remaining count pairs') }}</span>
                                                    <span class="fs-4 fw-bold text-dark">{{ stats.remaining_count_pairs }}</span>
                                                </div>
                                                <div class="fs-2 text-secondary"><i class="fas fa-hourglass-half"></i></div>
                                            </div>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="my-4">
                </div>

                <!-- 3 Tables: Under Stock, Extra Stock, Complete Match -->
                <div v-for="group in [
                    { title: t('pages.Under Stock'), type: 'partial', table: partialTable, search: partialSearch, perPage: partialPerPage },
                    { title: t('pages.extra match'), type: 'extra', table: extraTable, search: extraSearch, perPage: extraPerPage },
                    { title: t('pages.complete match'), type: 'full', table: fullTable, search: fullSearch, perPage: fullPerPage }
                ]" :key="group.type" class="card my-4 border shadow-sm">
                    <div class="card-header bg-light d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center py-3">
                        <h5 class="mb-2 mb-sm-0 text-dark fw-bold">
                            {{ group.title }}
                        </h5>
                        <div class="d-flex gap-2">
                            <input type="text" v-model="group.search.value" class="form-control form-control-sm" placeholder="Search here..." @keyup.enter="fetchTableData(group.type)" style="max-width: 200px;">
                            <button @click="fetchTableData(group.type)" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i></button>
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
                                        <th>{{ t('pages.selling_price') }}</th>
                                        <th>{{ t('pages.purchase_price') }}</th>
                                        <th>{{ t('pages.current_stock') }}</th>
                                        <th>{{ t('pages.pair') }}</th>
                                        <th v-if="group.type !== 'full'">
                                            {{ group.type === 'partial' ? t('pages.low') : t('pages.extra') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="row in group.table.value.data" :key="row.id">
                                        <td class="fw-bold">{{ row.id }}</td>
                                        <td>{{ row.factory || '-' }}</td>
                                        <td>{{ row.category || '-' }}</td>
                                        <td>{{ row.color || '-' }}</td>
                                        <td class="text-success fw-bold">{{ toFixed(row.retail_price) }}</td>
                                        <td class="text-danger fw-bold">{{ toFixed(row.purchase_price) }}</td>
                                        <td>{{ row.current_stock }}</td>
                                        <td>
                                            <span class="text-muted me-2">({{ row.total_count_breakdown || '0' }})</span>
                                            <strong class="text-info">{{ row.count }}</strong>
                                        </td>
                                        <td v-if="group.type !== 'full'" class="fw-bold">
                                            {{ group.type === 'partial' ? row.remaining : -row.remaining }}
                                        </td>
                                    </tr>
                                    <tr v-if="group.table.value.data.length === 0">
                                        <td :colspan="group.type === 'full' ? 8 : 9" class="text-muted p-3">{{ t('pages.No records found.') }}</td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-secondary fw-bold" v-if="group.table.value.data.length > 0">
                                    <tr>
                                        <td colspan="4" class="text-end pe-4">{{ t('pages.total') }} :</td>
                                        <td class="text-success">{{ toFixed(getSum(group.table.value.data, 'retail_price')) }}</td>
                                        <td class="text-danger">{{ toFixed(getSum(group.table.value.data, 'purchase_price')) }}</td>
                                        <td>{{ getSum(group.table.value.data, 'current_stock') }}</td>
                                        <td>{{ getPageCountSum(group.table.value.data) }}</td>
                                        <td v-if="group.type !== 'full'">
                                            {{ Math.abs(getRemainingSum(group.table.value.data, group.type)) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Pagination Navigation for Subtable -->
                        <div v-if="group.table.value.last_page > 1" class="d-flex justify-content-center align-items-center gap-2 mt-3">
                            <button class="btn btn-outline-secondary btn-sm" :disabled="group.table.value.current_page === 1" @click="group.table.value.current_page--; fetchTableData(group.type)">
                                &larr; {{ t('pages.previous page') }}
                            </button>
                            <span class="text-muted small">
                                {{ t('pages.page') }} {{ group.table.value.current_page }} / {{ group.table.value.last_page }}
                            </span>
                            <button class="btn btn-outline-secondary btn-sm" :disabled="group.table.value.current_page === group.table.value.last_page" @click="group.table.value.current_page++; fetchTableData(group.type)">
                                {{ t('pages.next page') }} &rarr;
                            </button>
                        </div>
                    </div>
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
.product-info-box {
    line-height: 1.5;
}
</style>
