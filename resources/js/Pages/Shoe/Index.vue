<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    shoes: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
    totals: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
    colors: {
        type: Array,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
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
            'inventory': 'Inventory',
            'total_pair': 'Total Pairs',
            'selling_price_product': 'Total Selling Price',
            'purchase_price_product': 'Total Purchase Price',
            'id': 'ID',
            'suppliers': 'Suppliers',
            'type': 'Type',
            'color': 'Color',
            'picture': 'Picture',
            'selling_price': 'Selling Price',
            'purchase_price': 'Purchase Price',
            'In stock': 'In stock',
            'size': 'Size',
            'edit': 'Edit',
            'download': 'Download',
            'On this page': 'On this page',
            'total': 'Total',
            'save': 'Save',
            'close': 'Close',
            'No records found.': 'No records found.',
            'please_select': 'Please select at least one item.',
            'download_confirm': 'Do you want to download photos of selected items?',
            'shoe_edit': 'Edit Shoe Details',
            'shoe_type': 'Shoe Type',
            'shoe_color': 'Shoe Color',
            'quantity': 'Quantity',
            'pair': 'Pair',
            'size_details': 'Size Stock Details',
            'all': 'All',
        },
        bn: {
            'inventory': 'স্টক তালিকা',
            'total_pair': 'মোট জোড়া',
            'selling_price_product': 'বিক্রয়মূল্য সামগ্রী',
            'purchase_price_product': 'ক্রয়মূল্য সামগ্রী',
            'id': 'আইডি',
            'suppliers': 'মহাজন',
            'type': 'ধরণ',
            'color': 'রঙ',
            'picture': 'ছবি',
            'selling_price': 'বিক্রয়মূল্য',
            'purchase_price': 'ক্রয়মূল্য',
            'In stock': 'স্টকে আছে',
            'size': 'সাইজ',
            'edit': 'সম্পাদন',
            'download': 'ডাউনলোড',
            'On this page': 'এই পাতায়',
            'total': 'মোট',
            'save': 'সংরক্ষণ',
            'close': 'বন্ধ করুন',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'please_select': 'অনুগ্রহ করে কমপক্ষে একটি জুতা নির্বাচন করুন।',
            'download_confirm': 'আপনি কি নির্বাচিত জুতাগুলোর ছবি ডাউনলোড করতে চান?',
            'shoe_edit': 'জুতার তথ্য সম্পাদন',
            'shoe_type': 'জুতার ধরণ',
            'shoe_color': 'জুতার রঙ',
            'quantity': 'পরিমাণ',
            'pair': 'জোড়া',
            'size_details': 'সাইজ স্টক বিবরণ',
            'all': 'সব',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Filter states
const filterId = ref(props.filters.id || '');
const filterFactory = ref(props.filters.factory || '');
const filterCategory = ref(props.filters.category || '');
const filterColor = ref(props.filters.color || '');
const filterRetailPrice = ref(props.filters.retail_price || '');
const filterPurchasePrice = ref(props.filters.purchase_price || '');
const filterCount = ref(props.filters.count || '');
const filterStockFilter = ref(props.filters.stock_filter === '1');

const perPage = ref(props.filters.per_page || '10');
const isLoading = ref(false);

const orderby = ref(props.filters.orderby || 'id');
const order = ref(props.filters.order || 'desc');

// Select checkboxes state
const selectedIds = ref([]);
const checkAll = ref(false);

const toggleCheckAll = () => {
    if (checkAll.value) {
        selectedIds.value = props.shoes.data.map(shoe => shoe.shoe_id);
    } else {
        selectedIds.value = [];
    }
};

watch(selectedIds, (newVal) => {
    if (newVal.length === props.shoes.data.length && props.shoes.data.length > 0) {
        checkAll.value = true;
    } else {
        checkAll.value = false;
    }
});

// Perform Search/Filter
const applyFilters = () => {
    isLoading.value = true;
    router.get(route('shoe.index'), {
        id: filterId.value,
        factory: filterFactory.value,
        category: filterCategory.value,
        color: filterColor.value,
        retail_price: filterRetailPrice.value,
        purchase_price: filterPurchasePrice.value,
        count: filterCount.value,
        stock_filter: filterStockFilter.value ? '1' : '0',
        orderby: orderby.value,
        order: order.value,
        per_page: perPage.value,
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            isLoading.value = false;
        }
    });
};

const handleStockFilterCheckboxChange = (e) => {
    filterStockFilter.value = e.target.checked;
    applyFilters();
};

const sortBy = (column) => {
    if (orderby.value === column) {
        order.value = order.value === 'asc' ? 'desc' : 'asc';
    } else {
        orderby.value = column;
        order.value = 'desc';
    }
    applyFilters();
};

// Handle download images zip
const handleDownload = () => {
    if (selectedIds.value.length === 0) {
        alert(t('pages.please_select'));
        return;
    }

    if (!confirm(t('pages.download_confirm'))) {
        return;
    }

    axios.post(route('show.download'), {
        selected_ids: JSON.stringify(selectedIds.value)
    }).then(res => {
        if (res.data.success) {
            let downloadLink = document.createElement('a');
            downloadLink.href = '/' + res.data.file_name;
            downloadLink.download = 'images.zip';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            axios.post(route('show.download.deleted'));
        } else {
            alert(res.data.error || 'Download failed');
        }
    }).catch(err => {
        alert(err.response?.data?.error || 'Download failed. Please try again.');
    });
};

const goToPage = (pageNumber) => {
    isLoading.value = true;
    router.get(route('shoe.index'), {
        id: filterId.value,
        factory: filterFactory.value,
        category: filterCategory.value,
        color: filterColor.value,
        retail_price: filterRetailPrice.value,
        purchase_price: filterPurchasePrice.value,
        count: filterCount.value,
        stock_filter: filterStockFilter.value ? '1' : '0',
        orderby: orderby.value,
        order: order.value,
        per_page: perPage.value,
        page: pageNumber,
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            isLoading.value = false;
        }
    });
};

// Size Modal state
const showSizeModal = ref(false);
const maxSortsCount = ref(0);
const activeSizes = ref([]);
const activeShoeCode = ref('');
const activeShoeCount = ref(0);

const openSizeModal = (shoe) => {
    axios.get(route('ajax.shoe.show', { shoe: shoe.shoe_code })).then(res => {
        activeShoeCode.value = shoe.shoe_code;
        activeShoeCount.value = shoe.count;
        const sizesData = res.data.size || [];
        const calData = res.data.cal || {};
        
        let maxSorts = 0;
        sizesData.forEach(sz => {
            const calculated = calData[sz.id];
            if (calculated && calculated.sorts) {
                const sortsArr = Array.isArray(calculated.sorts) 
                    ? calculated.sorts 
                    : Object.values(calculated.sorts);
                if (sortsArr.length > maxSorts) {
                    maxSorts = sortsArr.length;
                }
            }
        });
        maxSortsCount.value = maxSorts;

        activeSizes.value = sizesData.map(sz => {
            const calculated = calData[sz.id] || {};
            const sortsArr = Array.isArray(calculated.sorts) 
                ? calculated.sorts 
                : Object.values(calculated.sorts || {});
            
            const paddedSorts = [];
            for (let i = 0; i < maxSorts; i++) {
                paddedSorts.push(sortsArr[i] !== undefined ? sortsArr[i] : 0);
            }

            return {
                name: sz.size,
                total_quantity: calculated.total_quantity || 0,
                sorts: paddedSorts
            };
        });
        showSizeModal.value = true;
    });
};

// Edit Modal state
const showEditModal = ref(false);
const editForm = useForm({
    id: null,
    code: '',
    factory_name: '',
    category_id: '',
    color_id: '',
    retail_price: '',
    purchase_price: '',
    image: null
});

const openEditModal = (shoe) => {
    editForm.id = shoe.shoe_id;
    editForm.code = shoe.shoe_code;
    editForm.factory_name = shoe.factory_name || '';
    editForm.category_id = shoe.category_id || '';
    editForm.color_id = shoe.color_id || '';
    editForm.retail_price = shoe.retail_price;
    editForm.purchase_price = shoe.purchase_price;
    editForm.image = null;
    showEditModal.value = true;
};

const handleUpdate = () => {
    editForm.post(route('shoe.update', { shoe: editForm.code }), {
        forceFormData: true,
        onSuccess: () => {
            showEditModal.value = false;
        }
    });
};

const onImageChange = (e) => {
    editForm.image = e.target.files[0];
};

// Image Hover Preview & Click Modal
const showImageModal = ref(false);
const modalImageSrc = ref('');
const showHoverPreview = ref(false);
const hoverImageSrc = ref('');
const hoverPosition = ref({ top: '0px', left: '0px' });

const openImageModal = (shoe) => {
    if (shoe.image) {
        modalImageSrc.value = `/images/small-thumbnail/${shoe.image}`;
        showImageModal.value = true;
    }
};

const handleMouseEnter = (event, shoe) => {
    if (shoe.image) {
        hoverImageSrc.value = `/images/small-thumbnail/${shoe.image}`;
        showHoverPreview.value = true;
        
        const rect = event.target.getBoundingClientRect();
        const xOffset = window.scrollX || window.pageXOffset;
        const yOffset = window.scrollY || window.pageYOffset;
        
        let leftVal = rect.right + xOffset + 15;
        if (leftVal + 400 > window.innerWidth) {
            leftVal = rect.left + xOffset - 415;
        }
        
        hoverPosition.value = {
            top: (rect.top + yOffset - 50) + 'px',
            left: leftVal + 'px'
        };
    }
};

const handleMouseLeave = () => {
    showHoverPreview.value = false;
};

const toFixed = (val) => {
    return parseFloat(val || 0).toFixed(2);
};
</script>

<template>
    <Head :title="t('menu.inventory')" />

    <AuthenticatedLayout>
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h1>{{ t('menu.inventory') }}</h1>
            </div>
            
            <div class="card card-body">
                <!-- Totals Table section -->
                <div class="row align-items-center mb-3">
                    <div class="col-md-9 mb-2 mb-md-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <td style="width:10%">{{ t('pages.total_pair') }}</td>
                                        <td class="text-center" style="width:15%">
                                            <strong id="stock">{{ totals.stock }}</strong>
                                        </td>
                                        <td style="width:15%">{{ t('pages.selling_price_product') }}</td>
                                        <td class="text-center" style="width:20%">
                                            <strong id="stock-purchase-price">{{ toFixed(totals.total_retail_price) }}</strong>
                                        </td>
                                        <td style="width:15%">{{ t('pages.purchase_price_product') }}</td>
                                        <td class="text-center" style="width:30%">
                                            <strong id="stock-purchase-price">{{ toFixed(totals.stock_purchase_price) }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex justify-content-md-end justify-content-start align-items-center">
                        <div class="d-flex align-items-center" style="display: flex !important; flex-direction: row !important; align-items: center !important;">
                            <span class="me-2" style="white-space: nowrap; margin-right: 8px;">Show:</span>
                            <select v-model="perPage" @change="applyFilters" class="form-control form-control-sm text-center" style="width: 80px; display: inline-block !important;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Main Inventory Table -->
                <div class="table-responsive" style="position: relative;">
                    <!-- Loading Overlay -->
                    <div v-if="isLoading" class="loading-overlay">
                        <div class="spinner"></div>
                    </div>

                    <table id="inventory-table" class="table table-striped text-center align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center text-primary" style="vertical-align: middle;">
                                    <button @click="handleDownload" class="btn btn-primary btn-sm mb-1 d-block mx-auto">
                                        {{ t('pages.download') }}
                                    </button>
                                    <input type="checkbox" class="form-check-input" v-model="checkAll" @change="toggleCheckAll">
                                </th>
                                <th @click="sortBy('id')" class="text-center text-primary" style="width: 10%; vertical-align: middle; cursor: pointer;">
                                    {{ t('pages.id') }}
                                    <i :class="orderby === 'id' ? (order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down') : 'fas fa-sort text-muted'" class="ms-1"></i>
                                </th>
                                <th @click="sortBy('factory')" class="text-center text-primary" style="width: 12.5%; vertical-align: middle; cursor: pointer;">
                                    {{ t('pages.suppliers') }}
                                    <i :class="orderby === 'factory' ? (order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down') : 'fas fa-sort text-muted'" class="ms-1"></i>
                                </th>
                                <th @click="sortBy('category')" class="text-center text-primary" style="width: 12.5%; vertical-align: middle; cursor: pointer;">
                                    {{ t('pages.type') }}
                                    <i :class="orderby === 'category' ? (order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down') : 'fas fa-sort text-muted'" class="ms-1"></i>
                                </th>
                                <th @click="sortBy('color')" class="text-center text-primary" style="width: 10%; vertical-align: middle; cursor: pointer;">
                                    {{ t('pages.color') }}
                                    <i :class="orderby === 'color' ? (order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down') : 'fas fa-sort text-muted'" class="ms-1"></i>
                                </th>
                                <th class="text-center text-primary" style="vertical-align: middle;">{{ t('pages.picture') }}</th>
                                <th @click="sortBy('retail_price')" class="text-center text-primary" style="width: 12.5%; vertical-align: middle; cursor: pointer;">
                                    {{ t('pages.selling_price') }}
                                    <i :class="orderby === 'retail_price' ? (order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down') : 'fas fa-sort text-muted'" class="ms-1"></i>
                                </th>
                                <th @click="sortBy('purchase_price')" class="text-center text-primary" style="width: 12.5%; vertical-align: middle; cursor: pointer;">
                                    {{ t('pages.purchase_price') }}
                                    <i :class="orderby === 'purchase_price' ? (order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down') : 'fas fa-sort text-muted'" class="ms-1"></i>
                                </th>
                                <th @click="sortBy('count')" class="text-center text-primary" style="vertical-align: middle; cursor: pointer;">
                                    <input type="checkbox" id="stock-filter-checkbox" :checked="filterStockFilter" @change="handleStockFilterCheckboxChange" @click.stop />
                                    {{ t('pages.In stock') }}
                                    <i :class="orderby === 'count' ? (order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down') : 'fas fa-sort text-muted'" class="ms-1"></i>
                                </th>
                                <th class="text-center text-primary" style="vertical-align: middle;">{{ t('pages.size') }}</th>
                                <th class="text-center text-primary" style="vertical-align: middle;">{{ t('pages.edit') }}</th>
                            </tr>
                            <!-- Search Inputs -->
                            <tr>
                                <th></th>
                                <th>
                                    <input type="text" v-model="filterId" class="form-control text-center search-id" @keyup.enter="applyFilters">
                                </th>
                                <th>
                                    <input type="text" v-model="filterFactory" class="form-control text-center search-factory" @keyup.enter="applyFilters">
                                </th>
                                <th>
                                    <input type="text" v-model="filterCategory" class="form-control text-center search-category" @keyup.enter="applyFilters">
                                </th>
                                <th>
                                    <input type="text" v-model="filterColor" class="form-control text-center search-color" @keyup.enter="applyFilters">
                                </th>
                                <th></th>
                                <th>
                                    <input type="text" v-model="filterRetailPrice" class="form-control text-center search-retail-price number" @keyup.enter="applyFilters">
                                </th>
                                <th>
                                    <input type="text" v-model="filterPurchasePrice" class="form-control text-center search-purchase-price number" @keyup.enter="applyFilters">
                                </th>
                                <th>
                                    <input type="text" v-model="filterCount" class="form-control text-center search-count number" @keyup.enter="applyFilters">
                                </th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="shoe in shoes.data" :key="shoe.shoe_id" v-memo="[shoe.shoe_id, shoe.count, shoe.retail_price, shoe.purchase_price, shoe.image, selectedIds.includes(shoe.shoe_id)]">
                                <td>
                                    <input type="checkbox" class="form-check-input select-checkbox" :value="shoe.shoe_id" v-model="selectedIds">
                                </td>
                                <td>
                                    <Link :href="route('shoe.show', { shoe: shoe.shoe_code })" class="text-decoration-none">
                                        {{ shoe.shoe_code }}
                                    </Link>
                                </td>
                                <td>
                                    <Link v-if="shoe.factory_id" :href="route('factory.show', { factory: shoe.factory_id })" class="text-decoration-none">
                                        {{ shoe.factory_name || '-' }}
                                    </Link>
                                    <span v-else>{{ shoe.factory_name || '-' }}</span>
                                </td>
                                <td>{{ shoe.category_name || '-' }}</td>
                                <td>{{ shoe.color_name || '-' }}</td>
                                <td>
                                    <img v-if="shoe.image" 
                                         :src="`/images/small-thumbnail/${shoe.image}`" 
                                         height="50" 
                                         width="50" 
                                         class="rounded object-fit-cover shadow-sm mx-auto transition-transform duration-200 hover:scale-105" 
                                         style="cursor: pointer;" 
                                         @click="openImageModal(shoe)" 
                                         @mouseenter="handleMouseEnter($event, shoe)"
                                         @mouseleave="handleMouseLeave"
                                         loading="lazy">
                                    <img v-else src="/img/shoe.png" height="50" width="50" class="rounded object-fit-cover shadow-sm mx-auto" loading="lazy">
                                </td>
                                <td class="text-success">{{ toFixed(shoe.retail_price) }}</td>
                                <td class="text-danger">{{ toFixed(shoe.purchase_price) }}</td>
                                <td class="fs-6">{{ shoe.count }}</td>
                                <td>
                                    <button @click="openSizeModal(shoe)" class="btn btn-primary btn-sm btn-show-size">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                <td>
                                    <button @click="openEditModal(shoe)" class="btn btn-primary btn-sm btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="shoes.data.length === 0">
                                <td colspan="11" class="text-muted p-4 fs-5">{{ t('pages.No records found.') }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-end" style="text-align: right !important"> {{ t('pages.On this page') }}:</th>
                                <th class="text-center" id="total-purchase-price">{{ toFixed(stats.page_total_purchase_price) }}</th>
                                <th class="text-center" id="total-stock-count">{{ stats.page_count }}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <hr>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    <Pagination :pagination="shoes" @page-changed="goToPage" />
                </div>
            </div>
        </div>

        <!-- Size Stock Details Modal -->
        <div class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm overflow-x-hidden overflow-y-auto outline-none" v-if="showSizeModal">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="showSizeModal = false"></div>
            <div class="relative w-full max-w-2xl mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h5 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ t('pages.size') }} - {{ activeShoeCount }} {{ t('pages.pair') }}
                        </h5>
                        <button type="button" class="p-1 ml-auto bg-transparent border-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cursor-pointer" @click="showSizeModal = false">×</button>
                    </div>
                    <div class="relative p-6 flex-auto">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ t('pages.size') }}</th>
                                        <th>{{ t('pages.quantity') }}</th>
                                        <th v-for="n in maxSortsCount" :key="n">{{ n }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sz in activeSizes" :key="sz.name">
                                        <td>{{ sz.name }}</td>
                                        <td>{{ sz.total_quantity }}</td>
                                        <td v-for="(val, idx) in sz.sorts" :key="idx">{{ val }}</td>
                                    </tr>
                                    <tr v-if="activeSizes.length === 0">
                                        <td :colspan="2 + maxSortsCount" class="text-center">{{ t('pages.No records found.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form Modal -->
        <div class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm overflow-x-hidden overflow-y-auto outline-none" v-if="showEditModal">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="showEditModal = false"></div>
            <div class="relative w-full max-w-2xl mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h5 class="text-lg font-bold text-slate-850 dark:text-white">{{ t('pages.shoe_edit') }}</h5>
                        <button type="button" class="p-1 ml-auto bg-transparent border-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cursor-pointer" @click="showEditModal = false">×</button>
                    </div>
                    <form @submit.prevent="handleUpdate" enctype="multipart/form-data">
                        <div class="relative p-6 flex-auto text-start">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">{{ t('pages.suppliers') }}</label>
                                    <input type="text" v-model="editForm.factory_name" class="form-control" readonly>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">{{ t('pages.shoe_type') }}</label>
                                        <select v-model="editForm.category_id" class="form-select form-control" required>
                                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                                {{ cat.parent ? cat.parent.name + '-' + cat.name : cat.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">{{ t('pages.shoe_color') }}</label>
                                        <select v-model="editForm.color_id" class="form-select form-control" required>
                                            <option v-for="col in colors" :key="col.id" :value="col.id">
                                                {{ col.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">{{ t('pages.picture') }}</label>
                                    <input type="file" @change="onImageChange" class="form-control">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">{{ t('pages.selling_price') }}</label>
                                        <input type="number" step="0.01" v-model="editForm.retail_price" class="form-control" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">{{ t('pages.purchase_price') }}</label>
                                        <input type="number" step="0.01" v-model="editForm.purchase_price" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 pt-2 text-start">
                                    <button type="submit" class="btn btn-primary btn-form-save" :disabled="editForm.processing">
                                        {{ t('pages.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Image Preview Modal -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-x-hidden overflow-y-auto outline-none" v-if="showImageModal" @click="showImageModal = false">
            <div class="fixed inset-0 bg-black/50 transition-opacity"></div>
            <div class="relative w-full max-w-3xl mx-auto z-50 p-4" @click.stop>
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <div class="flex items-center justify-between p-4 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h5 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ t('pages.picture') }}
                        </h5>
                        <button type="button" class="p-1 ml-auto bg-transparent border-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cursor-pointer" @click="showImageModal = false">×</button>
                    </div>
                    <div class="relative p-4 flex-auto text-center bg-slate-50 dark:bg-slate-900 rounded-b-lg">
                        <img :src="modalImageSrc" alt="Shoe Image" class="max-h-[70vh] max-w-full mx-auto rounded-lg object-contain shadow-md" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Hover Preview Popup -->
        <Teleport to="body">
            <div 
                v-if="showHoverPreview" 
                :style="{ 
                    position: 'absolute', 
                    top: hoverPosition.top, 
                    left: hoverPosition.left, 
                    zIndex: 9999,
                    pointerEvents: 'none'
                }"
                class="p-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-xl"
            >
                <img :src="hoverImageSrc" alt="Preview" class="max-w-[400px] max-h-[300px] rounded object-contain" />
            </div>
        </Teleport>
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
.object-fit-cover {
    object-fit: cover !important;
}

/* Bootstrap Link Emulation */
.table td a {
    color: #007bff !important;
    text-decoration: none !important;
}

.table td a:hover {
    color: #0056b3 !important;
    text-decoration: underline !important;
}

/* Bootstrap Table Emulation */
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    vertical-align: top;
    border-color: #dee2e6;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
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
    margin-bottom: 1.5rem;
    padding: 1.25rem;
}

.card-primary.card-outline {
    border-top: 3px solid #007bff;
}

.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.card-header h1 {
    font-size: 1.8rem;
    margin: 0;
    font-weight: 500;
}

.text-primary {
    color: #007bff !important;
}

.form-control {
    display: block;
    width: 100%;
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
}

/* Bootstrap Buttons Emulation */
.btn {
    display: inline-block;
    font-weight: 400;
    color: #212529;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    cursor: pointer;
    text-decoration: none;
}

.btn-sm {
    padding: .25rem .5rem;
    font-size: .875rem;
    line-height: 1.5;
    border-radius: .2rem;
}

.btn-primary {
    color: #fff !important;
    background-color: #007bff !important;
    border-color: #007bff !important;
}

.btn-primary:hover {
    color: #fff !important;
    background-color: #0069d9 !important;
    border-color: #0062cc !important;
}

.btn-secondary {
    color: #fff !important;
    background-color: #6c757d !important;
    border-color: #6c757d !important;
}

.btn-secondary:hover {
    color: #fff !important;
    background-color: #5a6268 !important;
    border-color: #545b62 !important;
}

.close {
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
    border: 0;
    background: transparent;
    cursor: pointer;
}

.close:hover {
    color: #000;
    text-decoration: none;
    opacity: .75;
}

/* Loading Overlay */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: transparent;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
}

.spinner {
    width: 45px;
    height: 45px;
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-top: 4px solid #007bff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
