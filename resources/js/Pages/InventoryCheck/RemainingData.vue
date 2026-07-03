<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    id: {
        type: [String, Number],
        required: true,
    },
    entries: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '', per_page: 10 }),
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
            'Remaining Inventory check ID': 'Remaining Inventory Check List',
            'Inventory Check ID': 'Inventory Check ID',
            'id': 'ID',
            'supplier': 'Supplier',
            'type': 'Type',
            'color': 'Color',
            'purchase_price': 'Purchase Price',
            'selling_price': 'Selling Price',
            'stock': 'Stock',
            'total': 'Total',
            'close': 'Back',
            'clear': 'Clear',
            'filter': 'Filter',
            'No records found.': 'No records found.',
            'page': 'Page',
            'show': 'Show',
            'Showing :start to :end of :total entries': 'Showing :start to :end of :total entries',
        },
        bn: {
            'Remaining Inventory check ID': 'বাকি ইনভেন্টরি চেক আইডি',
            'Inventory Check ID': 'ইনভেন্টরি চেক আইডি',
            'id': 'আইডি',
            'supplier': 'মহাজন',
            'type': 'টাইপ',
            'color': 'রং',
            'purchase_price': 'ডজন দাম',
            'selling_price': 'গায়ের দাম',
            'stock': 'স্টক',
            'total': 'মোট',
            'close': 'ফিরে যান',
            'clear': 'পরিষ্কার করুন',
            'filter': 'ফিল্টার',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'page': 'পৃষ্ঠা',
            'show': 'দেখান',
            'Showing :start to :end of :total entries': 'দেখানো হচ্ছে :start থেকে :end এর মধ্যে, মোট :total টি',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const search = ref(props.filters.search || '');
const perPage = ref(parseInt(props.filters.per_page) || 10);

const applyFilters = () => {
    router.get(route('inventory-check.remaining-data', { id: props.id }), {
        search: search.value,
        per_page: perPage.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const goToPage = (pageNumber) => {
    router.get(route('inventory-check.remaining-data', { id: props.id }), {
        search: search.value,
        per_page: perPage.value,
        page: pageNumber,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    search.value = '';
    perPage.value = 10;
    applyFilters();
};

const toFixed = (val) => {
    return parseFloat(val || 0).toFixed(2);
};

const formatPrice = (val) => {
    return parseFloat(val || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

// Calculate page totals (same as blade callback sums)
const pageTotalPurchase = computed(() => props.entries.data.reduce((sum, item) => sum + parseFloat(item.purchase_price || 0), 0));
const pageTotalRetail = computed(() => props.entries.data.reduce((sum, item) => sum + parseFloat(item.retail_price || 0), 0));
const pageTotalStock = computed(() => props.entries.data.reduce((sum, item) => sum + parseInt(item.count || 0), 0));
</script>

<template>
    <Head :title="t('menu.inventory_check') || 'Inventory Check'" />

    <AuthenticatedLayout>
        <div class="container-fluid mt-4">
            <!-- Remaining List Card -->
            <div class="card card-primary card-outline p-3">
                <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center border-bottom pb-2 mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2 mb-sm-0">
                        <Link :href="route('inventory-check.show', { inventory_check: id })" class="btn btn-outline-primary btn-sm fw-bold me-2 mr-2">
                            <i class="fas fa-arrow-left"></i> {{ t('pages.close') }}
                        </Link>
                        <h5 class="mb-0 font-weight-bold text-dark">{{ t('pages.Remaining Inventory check ID') }}</h5>
                    </div>
                    <span class="btn btn-primary btn-sm fw-bold">{{ t('pages.Inventory Check ID') }}: #{{ id }}</span>
                </div>

                <div class="card-body">
                    <!-- Length and Search Top Bar (Same to Same position as datatable) -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-12 col-md-6 d-flex justify-content-start align-items-center mb-2 mb-md-0">
                            <label class="show-entries-label mb-0">
                                <template v-if="page.props.locale === 'bn'">
                                    দেখাচ্ছে
                                    <select v-model="perPage" @change="applyFilters" class="custom-select custom-select-sm form-select form-select-sm d-inline-block mx-1" style="width: auto;">
                                        <option :value="10">10</option>
                                        <option :value="25">25</option>
                                        <option :value="50">50</option>
                                        <option :value="100">100</option>
                                        <option :value="200">200</option>
                                    </select>
                                    এন্ট্রি
                                </template>
                                <template v-else>
                                    Show
                                    <select v-model="perPage" @change="applyFilters" class="custom-select custom-select-sm form-select form-select-sm d-inline-block mx-1" style="width: auto;">
                                        <option :value="10">10</option>
                                        <option :value="25">25</option>
                                        <option :value="50">50</option>
                                        <option :value="100">100</option>
                                        <option :value="200">200</option>
                                    </select>
                                    entries
                                </template>
                            </label>
                        </div>
                        <div class="col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-end align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" v-model="search" class="form-control form-control-sm" placeholder="Search here..." @keyup.enter="applyFilters" style="max-width: 200px;">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center align-middle w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ t('pages.id') }}</th>
                                    <th>{{ t('pages.supplier') }}</th>
                                    <th>{{ t('pages.type') }}</th>
                                    <th>{{ t('pages.color') }}</th>
                                    <th>{{ t('pages.purchase_price') }}</th>
                                    <th>{{ t('pages.selling_price') }}</th>
                                    <th>{{ t('pages.stock') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, idx) in entries.data" :key="item.id">
                                    <td>{{ entries.from + idx }}</td>
                                    <td>
                                        <Link :href="route('shoe.show', { shoe: item.code })" class="text-decoration-none text-primary fw-semibold">
                                            {{ item.code }}
                                        </Link>
                                    </td>
                                    <td>
                                        <Link v-if="item.factory_id" :href="route('factory.show', { factory: item.factory_id })" class="text-decoration-none text-primary fw-semibold">
                                            {{ item.factory }}
                                        </Link>
                                        <span v-else>{{ item.factory || '-' }}</span>
                                    </td>
                                    <td>{{ item.category || '-' }}</td>
                                    <td>{{ item.color || '-' }}</td>
                                    <td>{{ toFixed(item.purchase_price) }}</td>
                                    <td>{{ toFixed(item.retail_price) }}</td>
                                    <td class="fw-bold">{{ item.count }}</td>
                                </tr>
                                <tr v-if="entries.data.length === 0">
                                    <td colspan="8" class="text-muted p-4 fs-5">{{ t('pages.No records found.') }}</td>
                                </tr>
                            </tbody>
                            <tfoot v-if="entries.data.length > 0">
                                <tr class="table-secondary fw-bold">
                                    <th colspan="5" class="text-start pe-4">{{ t('pages.total') }}:</th>
                                    <th>{{ formatPrice(pageTotalPurchase) }}</th>
                                    <th>{{ formatPrice(pageTotalRetail) }}</th>
                                    <th><strong>{{ pageTotalStock }}</strong></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Pagination common styling -->
                    <div class="mt-4 px-6 pb-4">
                        <Pagination :pagination="entries" @page-changed="goToPage" />
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
.fs-5 {
    font-size: 1.25rem !important;
}

/* Grid & Layout System */
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
.card-primary.card-outline {
    border-top: 3px solid #007bff;
}
.card-header {
    background-color: transparent;
}
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
.btn-sm {
    padding: .25rem .5rem;
    font-size: .875rem;
    line-height: 1.5;
    border-radius: .2rem;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-outline-primary {
    color: #007bff;
    border-color: #007bff;
}
.btn-outline-primary:hover {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
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

/* Table overrides */
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
.table-bordered {
    border: 1px solid #dee2e6;
}
.table-secondary {
    background-color: #e9ecef;
}

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

/* Alignment utilities */
.d-flex {
    display: flex !important;
}
.flex-column {
    flex-direction: column !important;
}
.justify-content-between {
    justify-content: space-between !important;
}
.justify-content-end {
    justify-content: flex-end !important;
}
@media (min-width: 768px) {
    .justify-content-md-end {
        justify-content: flex-end !important;
    }
}
.align-items-center {
    align-items: center !important;
}
.text-end {
    text-align: right !important;
}
.text-center {
    text-align: center !important;
}

/* Dark Mode overrides */
.dark .card {
    background-color: #1a1a27 !important;
    border-color: #2b2b40 !important;
    color: #f8f9fa !important;
}
.dark .card-header {
    background-color: #1e1e2f !important;
    border-bottom: 1px solid #2b2b40 !important;
    color: #ffffff !important;
}
.dark .text-dark {
    color: #ffffff !important;
}
.dark .table {
    color: #ffffff !important;
    border-color: #2b2b40 !important;
}
.dark .table th, .dark .table td {
    border-color: #2b2b40 !important;
}
.dark .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(255, 255, 255, 0.05) !important;
}
.dark .table-hover tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.085) !important;
}
.dark .table-secondary {
    background-color: #2b2b40 !important;
    color: #ffffff !important;
}
</style>
