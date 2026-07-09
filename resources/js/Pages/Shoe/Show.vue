<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    shoe: {
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
    if (page.props.translations?.menu?.[cleanKey]) {
        return page.props.translations.menu[cleanKey];
    }
    const fallbacks = {
        en: {
            'shoe_details': 'Shoe Details',
            'id': 'ID',
            'suppliers': 'Suppliers',
            'type': 'Type',
            'color': 'Color',
            'selling_price': 'Selling Price',
            'purchase_price': 'Purchase Price',
            'purchase': 'Purchases',
            'sell': 'Sales',
            'supplier_returns': 'Supplier Returns',
            'retail_returns': 'Retail Returns',
            'inventory_check': 'Inventory Check',
            'waste': 'Waste',
            'description': 'Description',
            'date': 'Date',
            'memo_no': 'Memo No',
            'supplier': 'Supplier',
            'party': 'Party',
            'pair': 'Pairs',
            'edit': 'Edit',
            'save': 'Save',
            'close': 'Close',
            'picture': 'Picture',
            'No records found.': 'No records found.',
            'shoe_edit': 'Edit Shoe Details',
            'shoe_type': 'Shoe Type',
            'shoe_color': 'Shoe Color',
            'back': 'Back',
        },
        bn: {
            'shoe_details': 'জুতার বিবরণ',
            'id': 'আইডি',
            'suppliers': 'মহাজন',
            'type': 'ধরণ',
            'color': 'রঙ',
            'selling_price': 'বিক্রয়মূল্য',
            'purchase_price': 'ক্রয়মূল্য',
            'purchase': 'ক্রয় তালিকা',
            'sell': 'বিক্রয় তালিকা',
            'supplier_returns': 'মহাজন ফেরত',
            'retail_returns': 'খুচরা ফেরত',
            'inventory_check': 'সমন্বয় তালিকা',
            'waste': 'অপচয়',
            'description': 'বিবরণ',
            'date': 'তারিখ',
            'memo_no': 'মেমো নম্বর',
            'supplier': 'মহাজন খাতা',
            'party': 'পার্টি',
            'pair': 'জোড়া',
            'edit': 'সম্পাদন',
            'save': 'সংরক্ষণ',
            'close': 'বন্ধ করুন',
            'picture': 'ছবি',
            'No records found.': 'কোনো রেকর্ড পাওয়া যায়নি।',
            'shoe_edit': 'জুতার তথ্য সম্পাদন',
            'shoe_type': 'জুতার ধরণ',
            'shoe_color': 'জুতার রঙ',
            'back': 'ফিরে যান',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Modal open states
const showImageModal = ref(false);
const showEditModal = ref(false);

const editForm = useForm({
    id: props.shoe.id,
    code: props.shoe.code,
    factory_name: props.shoe.factory?.name || '',
    category_id: props.shoe.category_id || '',
    color_id: props.shoe.color_id || '',
    retail_price: props.shoe.retail_price,
    purchase_price: props.shoe.purchase_price,
    image: null
});

const openEditModal = () => {
    editForm.category_id = props.shoe.category_id || '';
    editForm.color_id = props.shoe.color_id || '';
    editForm.retail_price = props.shoe.retail_price;
    editForm.purchase_price = props.shoe.purchase_price;
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

const toFixed = (val) => {
    return parseFloat(val || 0).toFixed(2);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    let hours = date.getHours();
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    const formattedTime = String(hours).padStart(2, '0') + ':' + minutes + ' ' + ampm;
    return `${day}/${month}/${year} ${formattedTime}`;
};
</script>

<template>
    <Head :title="t('pages.shoe_details')" />

    <AuthenticatedLayout>
        <div class="card card-primary card-outline">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">{{ t('pages.shoe_details') }}</h1>
                <Link :href="route('shoe.index')" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> {{ t('pages.back') }}
                </Link>
            </div>
            
            <!-- Details Table -->
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td style="width: 150px; vertical-align: middle;">
                            <a href="#" @click.prevent="showImageModal = true" class="shoe-image-link">
                                <img v-if="shoe.image" :src="`/images/small-thumbnail/${shoe.image}`" height="200" id="shoe-thumbnail" class="object-fit-cover rounded">
                                <img v-else src="/img/shoe.png" height="200" id="shoe-thumbnail" class="object-fit-cover rounded">
                            </a>
                        </td>
                        <td class="text-start" style="vertical-align: middle; line-height: 1.8;">
                            {{ t('pages.id') }}: <span><strong>{{ shoe.code }}</strong></span><br>
                            {{ t('pages.suppliers') }}: <span>{{ shoe.factory?.name || '-' }}</span><br>
                            {{ t('pages.type') }}: <span>{{ shoe.category?.parent ? shoe.category.parent.name + '-' + shoe.category.name : shoe.category?.name || '-' }}</span><br>
                            {{ t('pages.color') }}: <span>{{ shoe.color?.name || '-' }}</span><br>
                            {{ t('pages.selling_price') }}: <span>{{ toFixed(shoe.retail_price) }}</span><br>
                            {{ t('pages.purchase_price') }}: <span>{{ toFixed(shoe.purchase_price) }}</span>
                        </td>
                        <td style="width: 20%; vertical-align: middle; text-align: right;">
                            <button @click="openEditModal" class="btn btn-primary btn-sm btn-edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Historical Tables Layout -->
            <div class="row text-start">
                <!-- Left Column -->
                <div class="col-md-6">
                    <!-- Purchases Table -->
                    <div class="mb-4">
                        <h3>{{ t('pages.purchase') }}</h3>
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th style="width:50%">{{ t('pages.date') }}</th>
                                    <th style="width:25%">{{ t('pages.memo_no') }}</th>
                                    <th style="width:25%">{{ t('pages.supplier') }}</th>
                                    <th style="width:25%">{{ t('pages.pair') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="entry in shoe.purchase_entries" :key="entry.id">
                                    <td>{{ formatDate(entry.created_at) }}</td>
                                    <td>
                                        {{ entry.purchase_id }}
                                    </td>
                                    <td>
                                        <Link v-if="entry.purchase?.account_book?.account" :href="route('factory.show', { factory: entry.purchase?.account_book?.account?.id })" class="text-decoration-none">
                                            {{ entry.purchase.account_book.account.name }}
                                        </Link>
                                        <span v-else>-</span>
                                    </td>
                                    <td>{{ entry.count }}</td>
                                </tr>
                                <tr v-if="!shoe.purchase_entries || shoe.purchase_entries.length === 0">
                                    <td colspan="4" class="text-muted">{{ t('pages.No records found.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Supplier Returns Table -->
                    <div class="mb-4" v-if="shoe.accepted_factory_return_entries && shoe.accepted_factory_return_entries.length > 0">
                        <h3>{{ t('pages.supplier_returns') }}</h3>
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th style="width:25%">{{ t('pages.date') }}</th>
                                    <th style="width:25%">{{ t('pages.supplier') }}</th>
                                    <th style="width:50%">{{ t('pages.pair') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="entry in shoe.accepted_factory_return_entries" :key="entry.id">
                                    <td>{{ formatDate(entry.created_at) }}</td>
                                    <td>
                                        <Link v-if="entry.account_book?.account" :href="route('factory.show', { factory: entry.account_book?.account?.id })" class="text-decoration-none">
                                            {{ entry.account_book.account.name }}
                                        </Link>
                                        <span v-else>-</span>
                                    </td>
                                    <td>{{ entry.count }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Inventory Check / Adjustments Table -->
                    <div class="mb-4" v-if="shoe.adjustment_entries && shoe.adjustment_entries.length > 0">
                        <h3>{{ t('pages.inventory_check') }}</h3>
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th style="width:40%">{{ t('pages.date') }}</th>
                                    <th style="width:30%">জোড়া বাদ</th>
                                    <th style="width:30%">জোড়া যোগ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="entry in shoe.adjustment_entries" :key="entry.id">
                                    <td>{{ formatDate(entry.created_at) }}</td>
                                    <td>{{ entry.type === 'out' ? entry.count : '-' }}</td>
                                    <td>{{ entry.type === 'in' ? entry.count : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <!-- Sales / Sells Table -->
                    <div class="mb-4" v-if="shoe.invoice_entries && shoe.invoice_entries.length > 0">
                        <h3>{{ t('pages.sell') }}</h3>
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th style="width:50%">{{ t('pages.date') }}</th>
                                    <th style="width:25%">{{ t('pages.memo_no') }}</th>
                                    <th style="width:25%">{{ t('pages.party') }}</th>
                                    <th style="width:25%">{{ t('pages.pair') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="entry in shoe.invoice_entries" :key="entry.id">
                                    <td>{{ formatDate(entry.created_at) }}</td>
                                    <td>
                                        {{ entry.invoice_id }}
                                    </td>
                                    <td>
                                        <Link v-if="entry.invoice?.account_book?.retail_account" :href="route('retail-store.show', { retail_store: entry.invoice?.account_book?.retail_account?.id })" class="text-decoration-none">
                                            {{ entry.invoice.account_book.retail_account.name }}-{{ entry.invoice.account_book.retail_account.address }}
                                        </Link>
                                        <span v-else>-</span>
                                    </td>
                                    <td>{{ entry.count }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Retailer Returns Table -->
                    <div class="mb-4" v-if="shoe.accepted_retail_return_entries && shoe.accepted_retail_return_entries.length > 0">
                        <h3>{{ t('pages.retail_returns') }}</h3>
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th style="width:50%">{{ t('pages.date') }}</th>
                                    <th style="width:50%">{{ t('pages.pair') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="entry in shoe.accepted_retail_return_entries" :key="entry.id">
                                    <td>{{ formatDate(entry.created_at) }}</td>
                                    <td>{{ entry.count }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Waste Table -->
                    <div class="mb-4" v-if="shoe.waste_entries && shoe.waste_entries.length > 0">
                        <h3>{{ t('pages.waste') }}</h3>
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th style="width:40%">{{ t('pages.date') }}</th>
                                    <th style="width:20%">{{ t('pages.pair') }}</th>
                                    <th style="width:40%">{{ t('pages.description') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="entry in shoe.waste_entries" :key="entry.id">
                                    <td>{{ formatDate(entry.created_at) }}</td>
                                    <td>{{ entry.count }}</td>
                                    <td>{{ entry.description }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" v-if="showImageModal" @click="showImageModal = false">
            <div class="relative max-w-3xl max-h-[90vh] bg-white rounded-lg p-2 shadow-2xl overflow-hidden" @click.stop>
                <button type="button" class="absolute top-2 right-4 text-3xl font-semibold text-gray-500 hover:text-gray-800" @click="showImageModal = false">&times;</button>
                <img v-if="shoe.image" :src="`/images/small-thumbnail/${shoe.image}`" class="img-fluid rounded max-h-[80vh] max-w-full object-fit-contain">
                <img v-else src="/img/shoe.png" class="img-fluid rounded max-h-[80vh] max-w-full object-fit-contain">
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
                                <div class="col-12 pt-2">
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
    </AuthenticatedLayout>
</template>

<style scoped>
.fw-bold {
    font-weight: 700 !important;
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
.object-fit-contain {
    object-fit: contain !important;
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

/* Bootstrap Grid Emulation */
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}

.col-md-6 {
    position: relative;
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    box-sizing: border-box;
}

@media (min-width: 768px) {
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table td a {
    color: #007bff !important;
    text-decoration: none !important;
}

.table td a:hover {
    color: #0056b3 !important;
    text-decoration: underline !important;
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

.btn-outline-secondary {
    color: #6c757d !important;
    border-color: #6c757d !important;
    background-color: transparent !important;
}

.btn-outline-secondary:hover {
    color: #fff !important;
    background-color: #6c757d !important;
    border-color: #6c757d !important;
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
</style>
