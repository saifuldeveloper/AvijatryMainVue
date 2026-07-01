<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';

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
        default: () => ({ search: '', per_page: 50 }),
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
            'Remaining Inventory check ID': 'অবশিষ্ট স্টক চেকিং তালিকা',
            'Inventory Check ID': 'ইনভেন্টরি চেক আইডি',
            'id': 'আইডি',
            'supplier': 'মহাজন',
            'type': 'ধরণ',
            'color': 'রঙ',
            'purchase_price': 'ক্রয়মূল্য',
            'selling_price': 'বিক্রয়মূল্য',
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
const perPage = ref(parseInt(props.filters.per_page) || 50);

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
    perPage.value = 50;
    applyFilters();
};

const toFixed = (val) => {
    return parseFloat(val || 0).toFixed(2);
};

// Calculate page totals
const pageTotalPurchase = props.entries.data.reduce((sum, item) => sum + parseFloat(item.purchase_price || 0) * parseInt(item.count || 0) / 12, 0);
const pageTotalRetail = props.entries.data.reduce((sum, item) => sum + parseFloat(item.retail_price || 0) * parseInt(item.count || 0), 0);
const pageTotalStock = props.entries.data.reduce((sum, item) => sum + parseInt(item.count || 0), 0);
</script>

<template>
    <Head :title="t('pages.Remaining Inventory check ID')" />

    <AuthenticatedLayout>
        <div class="container-fluid mt-4">
            <!-- Back Button -->
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <Link :href="route('inventory-check.show', { inventory_check: id })" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> {{ t('pages.close') }}
                </Link>
                <span class="btn btn-primary btn-sm fw-bold">
                    {{ t('pages.Inventory Check ID') }}: #{{ id }}
                </span>
            </div>

            <!-- Remaining List Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom p-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                    <h5 class="mb-2 mb-sm-0 text-dark fw-bold">
                        <i class="fas fa-list-alt text-primary me-2"></i> {{ t('pages.Remaining Inventory check ID') }}
                    </h5>
                    <div class="d-flex gap-2">
                        <input type="text" v-model="search" class="form-control form-control-sm" placeholder="Search here..." @keyup.enter="applyFilters" style="max-width: 200px;">
                        <button @click="applyFilters" class="btn btn-secondary btn-sm">
                            <i class="fas fa-search"></i>
                        </button>
                        <button @click="clearFilters" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center align-middle w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 15%">{{ t('pages.id') }}</th>
                                    <th style="width: 20%">{{ t('pages.supplier') }}</th>
                                    <th style="width: 20%">{{ t('pages.type') }}</th>
                                    <th style="width: 15%">{{ t('pages.color') }}</th>
                                    <th style="width: 10%">{{ t('pages.purchase_price') }}</th>
                                    <th style="width: 10%">{{ t('pages.selling_price') }}</th>
                                    <th style="width: 8%">{{ t('pages.stock') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, idx) in entries.data" :key="item.id">
                                    <td>{{ entries.from + idx }}</td>
                                    <td>
                                        <Link :href="route('shoe.show', { shoe: item.code })" class="fw-bold text-decoration-none">
                                            {{ item.code }}
                                        </Link>
                                    </td>
                                    <td>
                                        <Link v-if="item.factory_id" :href="route('factory.show', { factory: item.factory_id })" class="text-decoration-none">
                                            {{ item.factory }}
                                        </Link>
                                        <span v-else>{{ item.factory }}</span>
                                    </td>
                                    <td>{{ item.category }}</td>
                                    <td>{{ item.color }}</td>
                                    <td class="text-danger fw-bold">{{ toFixed(item.purchase_price) }}</td>
                                    <td class="text-success fw-bold">{{ toFixed(item.retail_price) }}</td>
                                    <td class="fw-bold fs-6">{{ item.count }}</td>
                                </tr>
                                <tr v-if="entries.data.length === 0">
                                    <td colspan="8" class="text-muted p-4 fs-5">{{ t('pages.No records found.') }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="table-secondary fw-bold" v-if="entries.data.length > 0">
                                <tr>
                                    <td colspan="5" class="text-end pe-4">{{ t('pages.total') }} :</td>
                                    <td class="text-danger">{{ toFixed(pageTotalPurchase) }}</td>
                                    <td class="text-success">{{ toFixed(pageTotalRetail) }}</td>
                                    <td>{{ pageTotalStock }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 d-flex justify-content-center">
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
.fs-6 {
    font-size: 1rem !important;
}
</style>
