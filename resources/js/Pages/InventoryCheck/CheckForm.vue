<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
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
        if (page.props.translations?.pages?.[cleanKey]) {
            return page.props.translations.pages[cleanKey];
        }
    } else if (key.startsWith('menu.')) {
        cleanKey = key.replace('menu.', '');
        if (page.props.translations?.menu?.[cleanKey]) {
            return page.props.translations.menu[cleanKey];
        }
    } else {
        if (page.props.translations?.pages?.[cleanKey]) {
            return page.props.translations.pages[cleanKey];
        }
        if (page.props.translations?.menu?.[cleanKey]) {
            return page.props.translations.menu[cleanKey];
        }
    }
    const fallbacks = {
        en: {
            'inventory_check': 'Inventory Check',
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
            'inventory_check': '\u0987\u09a8\u09ad\u09c7\u09a8\u09cd\u099f\u09b0\u09bf \u099a\u09c7\u0995',
            'Inventory Check': '\u0987\u09a8\u09ad\u09c7\u09a8\u09cd\u099f\u09b0\u09bf \u099a\u09c7\u0995',
            'supplier': 'মহাজন',
            'type': 'টাইপ',
            'color': 'রং',
            'selling_price': 'গায়ের দাম',
            'purchase_price': 'ডজন দাম',
            'id': 'আইডি',
            'pair': 'জোড়া',
            'pair_stock': 'জোড়া আছে',
            'pair_due': 'জোড়া বাকি',
            'add': 'যোগ করুন',
            'finish checking': 'চেক শেষ করুন',
            'Calculation': 'হিসাব',
            'complete match': 'সম্পূর্ণ মিল',
            'Under Stock': 'আন্ডার স্টক',
            'extra match': 'অতিরিক্ত স্টক',
            'Total Count ID': 'মোট চেক করা আইডি',
            'Total Count pairs': 'মোট চেক জোড়া',
            'remaining count ID': 'অবশিষ্ট বাকি আইডি',
            'remaining count pairs': 'অবশিষ্ট বাকি জোড়া',
            'low': 'কম',
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
        const partialItem = tables.value.partial.data.find(item => item.id === val);
        const extraItem = tables.value.extra.data.find(item => item.id === val);
        const fullItem = tables.value.full.data.find(item => item.id === val);
        
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
const tables = ref({
    partial: {
        title: t('pages.Under Stock'),
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
    },
    full: {
        title: t('pages.complete match'),
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
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h1 class="font-weight-bold text-dark mb-0 fs-3">
                        {{ t('menu.inventory_check') }}
                        <small class="text-muted" style="font-size: 60%;">({{ formatDate(inventoryCheck.start_date) }})</small>
                    </h1>
                </div>

                <div class="card">
                    <!-- Checking scanner form (Only show if not resolved) -->
                    <div v-if="!inventoryCheck.resolved" class="p-3">
                    <form @submit.prevent="handleAddEntry" class="mb-4">
                        <div class="row">
                            <!-- Left form details -->
                            <div class="col-lg-8 col-md-7 mb-4">
                                <div class="card h-100 shadow-sm border mb-0">
                                    <div class="card-body p-4">
                                        <h5 class="font-weight-bold mb-3 border-bottom pb-2 text-center text-muted">
                                            <i class="fas fa-clipboard-check mr-1 me-1"></i> {{ t('pages.Inventory Check') }}
                                            ({{ formatDate(inventoryCheck.start_date) }})
                                        </h5>

                                        <!-- Product Detail Box -->
                                        <div class="product-info-box p-3 bg-light rounded border my-4">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-sm-8 mb-3 mb-sm-0 line-height-lg">
                                                    <div class="d-flex flex-wrap">
                                                        <div class="w-50 mb-2">
                                                            <span class="text-muted d-block">{{ t('pages.supplier') }}</span>
                                                            <span class="font-weight-bold text-dark shoe-check-span">{{ scannedShoe?.factory?.name || '-' }}</span>
                                                        </div>
                                                        <div class="w-50 mb-2">
                                                            <span class="text-muted d-block">{{ t('pages.type') }}</span>
                                                            <span class="font-weight-bold text-dark shoe-check-span">{{ scannedShoe?.category?.full_name || '-' }}</span>
                                                        </div>
                                                        <div class="w-50 mb-2">
                                                            <span class="text-muted d-block">{{ t('pages.color') }}</span>
                                                            <span class="font-weight-bold text-dark shoe-check-span">{{ scannedShoe?.color?.name || '-' }}</span>
                                                        </div>
                                                        <div class="w-50 mb-2">
                                                            <span class="text-muted d-block">{{ t('pages.selling_price') }}</span>
                                                            <span class="font-weight-bold text-dark shoe-check-span">{{ scannedShoe ? toFixed(scannedShoe.retail_price) : '-' }}</span>
                                                        </div>
                                                        <div class="w-50">
                                                            <span class="text-muted d-block">{{ t('pages.purchase_price') }}</span>
                                                            <span class="font-weight-bold text-dark shoe-check-span">{{ scannedShoe ? toFixed(scannedShoe.purchase_price) : '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4 text-center">
                                                    <div class="d-inline-block bg-white p-1 border rounded shadow-sm">
                                                        <img v-if="scannedShoe?.image" :src="`/images/small-thumbnail/${scannedShoe.image}`" class="img-fluid rounded object-fit-cover" style="height: 140px; width: 140px; object-fit: cover; border-radius: 4px;">
                                                        <img v-else src="/img/shoe.png" class="img-fluid rounded object-fit-cover" style="height: 140px; width: 140px; object-fit: cover; border-radius: 4px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Form Input Rows -->
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label font-weight-bold" for="shoe-check-id">{{ t('pages.id') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" v-model="shoeId" id="shoe-check-id" class="form-control" @change="handleShoeLookup" required autofocus>
                                                        <span v-if="isSearching" class="input-group-text bg-white border-left-0"><i class="fas fa-spinner fa-spin"></i></span>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3 mb-sm-0">
                                                    <label class="form-label font-weight-bold" for="shoe-check-count">{{ t('pages.pair') }}</label>
                                                    <input type="number" v-model="checkCount" id="shoe-check-count" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label font-weight-bold" for="shoe-check-available">{{ t('pages.pair_stock') }}</label>
                                                    <input type="text" :value="shoeStock" id="shoe-check-available" class="form-control bg-light" disabled>
                                                </div>

                                                <div class="form-group mb-0">
                                                    <label class="form-label font-weight-bold" for="shoe-check-remaining">{{ t('pages.pair_due') }}</label>
                                                    <input type="text" :value="shoeDue" id="shoe-check-remaining" class="form-control bg-light" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Buttons Row -->
                                        <div class="row mt-3">
                                            <div class="col-sm-6 mb-2 mb-sm-0">
                                                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2" :disabled="submitEntryForm.processing">
                                                    <i class="fas fa-plus-circle mr-1 me-1"></i> {{ t('pages.add') }}
                                                </button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" @click="handleFinishChecking" class="btn btn-danger w-100 fw-bold shadow-sm py-2" id="finish-checking-btn">
                                                    <i class="fas fa-check-double mr-1 me-1"></i> {{ t('pages.finish checking') }}
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
                                        <h5 class="font-weight-bold mb-3 border-bottom pb-2 text-center text-muted">
                                            <i class="fas fa-calculator mr-1 me-1"></i> {{ t('pages.Calculation') }}
                                        </h5>

                                        <!-- Complete Match stat -->
                                        <div class="stat-card stat-card-success">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="stat-label">{{ t('pages.complete match') }}</div>
                                                    <div class="stat-value text-success">{{ stats.complete_match_pairs }}</div>
                                                </div>
                                                <div class="stat-icon text-success"><i class="fas fa-check-circle"></i></div>
                                            </div>
                                        </div>

                                        <!-- Under Stock stat -->
                                        <div class="stat-card stat-card-warning">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="stat-label">{{ t('pages.Under Stock') }}</div>
                                                    <div class="stat-value text-warning">{{ stats.under_stock_pairs }}</div>
                                                </div>
                                                <div class="stat-icon text-warning"><i class="fas fa-arrow-circle-down"></i></div>
                                            </div>
                                        </div>

                                        <!-- Extra Match stat -->
                                        <div class="stat-card stat-card-danger">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="stat-label">{{ t('pages.extra match') }}</div>
                                                    <div class="stat-value text-danger">{{ stats.extra_match_pairs }}</div>
                                                </div>
                                                <div class="stat-icon text-danger"><i class="fas fa-arrow-circle-up"></i></div>
                                            </div>
                                        </div>

                                        <!-- Total Scanned stat -->
                                        <div class="stat-card stat-card-info">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="stat-label">{{ t('pages.Total Count ID') }}</div>
                                                    <div class="stat-value text-info">{{ stats.total_count_ids }}</div>
                                                </div>
                                                <div class="mr-5">
                                                    <div class="stat-label">{{ t('pages.Total Count pairs') }}</div>
                                                    <div class="stat-value text-info">{{ stats.total_count_pairs }}</div>
                                                </div>
                                                <div class="stat-icon text-info ml-2"><i class="fas fa-barcode"></i></div>
                                            </div>
                                        </div>

                                        <!-- Remaining stat Link -->
                                        <Link :href="route('inventory-check.remaining-data', { id: inventoryCheck.id })" id="reset-check-form" class="text-decoration-none d-block">
                                            <div class="stat-card stat-card-dark mb-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="stat-label text-primary font-weight-bold">
                                                            <i class="fas fa-list-alt mr-1 me-1"></i> {{ t('pages.remaining count ID') }}
                                                        </div>
                                                        <div class="stat-value text-dark">{{ stats.remaining_count_ids }}</div>
                                                    </div>
                                                    <div class="mr-5">
                                                        <div class="stat-label text-primary">{{ t('pages.remaining count pairs') }}</div>
                                                        <div class="stat-value text-dark">{{ stats.remaining_count_pairs }}</div>
                                                    </div>
                                                    <div class="stat-icon text-secondary"><i class="fas fa-hourglass-half"></i></div>
                                                </div>
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
                                        <th>{{ t('pages.selling_price') }}</th>
                                        <th>{{ t('pages.purchase_price') }}</th>
                                        <th>{{ t('pages.current_stock') }}</th>
                                        <th>{{ t('pages.pair') }}</th>
                                        <th v-if="type !== 'full'">
                                            {{ type === 'partial' ? t('pages.low') : t('pages.extra') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="row in group.data" :key="row.id">
                                        <td class="fw-bold">{{ row.id }}</td>
                                        <td>{{ row.factory || '-' }}</td>
                                        <td>{{ row.category || '-' }}</td>
                                        <td>{{ row.color || '-' }}</td>
                                        <td>{{ toFixed(row.retail_price) }}</td>
                                        <td>{{ toFixed(row.purchase_price) }}</td>
                                        <td>{{ row.current_stock }}</td>
                                        <td>
                                            <span class="text-muted me-2">({{ row.total_count_breakdown || '0' }})</span>
                                            <strong>{{ row.count }}</strong>
                                        </td>
                                        <td v-if="type !== 'full'" class="fw-bold">
                                            {{ type === 'partial' ? row.remaining : -row.remaining }}
                                        </td>
                                    </tr>
                                    <tr v-if="group.data.length === 0">
                                        <td :colspan="type === 'full' ? 8 : 9" class="text-muted p-3">{{ t('pages.No records found.') }}</td>
                                    </tr>
                                </tbody>
                                <tfoot class="font-weight-bold" v-if="group.data.length > 0">
                                    <tr>
                                        <td colspan="4" class="text-end pe-4">{{ t('pages.total') }} :</td>
                                        <td>{{ toFixed(getSum(group.data, 'retail_price')) }}</td>
                                        <td>{{ toFixed(getSum(group.data, 'purchase_price')) }}</td>
                                        <td>{{ getSum(group.data, 'current_stock') }}</td>
                                        <td>{{ getPageCountSum(group.data) }}</td>
                                        <td v-if="type !== 'full'">
                                            {{ Math.abs(getRemainingSum(group.data, type)) }}
                                        </td>
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
.col-12, .col-sm-4, .col-sm-6, .col-sm-8, .col-sm-12, .col-md-5, .col-md-6, .col-md-7, .col-lg-4, .col-lg-8 {
    position: relative;
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    box-sizing: border-box;
}
@media (min-width: 576px) {
    .col-sm-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
    .col-sm-6 { flex: 0 0 50%; max-width: 50%; }
    .col-sm-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
    .col-sm-12 { flex: 0 0 100%; max-width: 100%; }
}
@media (min-width: 768px) {
    .col-md-5 { flex: 0 0 41.666667%; max-width: 41.666667%; }
    .col-md-6 { flex: 0 0 50%; max-width: 50%; }
    .col-md-7 { flex: 0 0 58.333333%; max-width: 58.333333%; }
}
@media (min-width: 992px) {
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
.form-control:disabled, .form-control[readonly] {
    background-color: #e9ecef;
    opacity: 1;
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
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-danger:hover {
    color: #fff;
    background-color: #c82333;
    border-color: #bd2130;
}
.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}
.btn-secondary:hover {
    color: #fff;
    background-color: #5a6268;
    border-color: #545b62;
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
.pb-4 {
    padding-bottom: 1.5rem !important;
}
.p-3 {
    padding: 1rem !important;
}
.p-4 {
    padding: 1.5rem !important;
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
.product-info-box {
    box-sizing: border-box;
}
.shoe-check-span {
    font-weight: bold;
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
</style>
