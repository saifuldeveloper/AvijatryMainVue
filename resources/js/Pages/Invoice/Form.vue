<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    invoice: {
        type: Object,
        default: null,
    },
    memoNo: {
        type: Number,
        default: null,
    },
    bankAccounts: {
        type: Array,
        required: true,
    },
    gifts: {
        type: Array,
        required: true,
    },
    retailStores: {
        type: Array,
        required: true,
    },
    sessionRetailStore: {
        type: Object,
        default: null,
    },
    giftTransactions: {
        type: Array,
        default: () => [],
    },
    transactions: {
        type: Array,
        default: () => [],
    }
});

const page = usePage();

const localRetailStores = ref([...props.retailStores]);

const showOnetimeSaleModal = ref(false);
const onetimeSaleForm = ref({
    shop_name: '',
    mobile_no: '',
    address: '',
    company_share: 0,
    onetime_buyer: true,
    secret_key: ''
});
const onetimeErrors = ref({});

const openOnetimeSaleModal = () => {
    let key = '';
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for (let i = 0; i < 32; i++) {
        key += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    
    onetimeSaleForm.value = {
        shop_name: '',
        mobile_no: '',
        address: '',
        company_share: 0,
        onetime_buyer: true,
        secret_key: key
    };
    onetimeErrors.value = {};
    showOnetimeSaleModal.value = true;
};

const submitOnetimeSale = () => {
    onetimeErrors.value = {};
    router.post(route('retail-store.store'), onetimeSaleForm.value, {
        preserveState: true,
        onSuccess: (page) => {
            localRetailStores.value = [...page.props.retailStores];
        },
        onError: (errors) => {
            onetimeErrors.value = errors;
        }
    });
};

watch(() => props.sessionRetailStore, (newVal) => {
    if (newVal) {
        form.retail_store_id = newVal.id;
        form.memo_to_name = newVal.shop_name + (newVal.address ? ' - ' + newVal.address : '');
        
        if (!localRetailStores.value.find(s => s.id === newVal.id)) {
            localRetailStores.value.push({
                id: newVal.id,
                name: newVal.shop_name + (newVal.address ? ' - ' + newVal.address : ''),
                shop_name: newVal.shop_name,
                address: newVal.address,
                mobile_no: newVal.mobile_no,
                commission: newVal.commission || 0,
                return_count: newVal.return_count || 0,
                return_amount: newVal.return_amount || 0,
                other_costs: newVal.other_costs || 0
            });
        }
        showOnetimeSaleModal.value = false;
    }
}, { immediate: true });

// Translation Helper matching the old software Bengali/English UI
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
            'shoe_sales': 'Shoe Sales',
            'one_time_sales': 'One-time Sales',
            'retailers': 'Retailer',
            'memo_no': 'Memo No',
            'id': 'ID',
            'type': 'Type',
            'color': 'Color',
            'selling_price': 'Selling Price',
            'pair_stock': 'Pair Stock',
            'pair': 'Pairs',
            'size': 'Size',
            'total_price': 'Total Price',
            'total': 'Total',
            'discount_products': 'Discount Products',
            'commission': 'Commission',
            'total_commission': 'Total Commission',
            'total_excluding_commission': 'Total Excluding Commission',
            'for_return_of_pair': 'Pairs Return',
            'total_excluding_refund': 'Total Excluding Refund',
            'shipping_costs': 'Shipping Costs',
            'others_expenses': 'Other Expenses',
            'discount': 'Discount',
            'total_amount': 'Total Amount',
            'deposits_details': 'Deposit Details',
            'pament_method': 'Payment Method',
            'check_no': 'Check No',
            'taka': 'Taka',
            'total_deposits': 'Total Deposits',
            'gift_details': 'Gift Details',
            'gift': 'Gift',
            'count': 'Count',
            'preview': 'Preview',
            'submit': 'Submit',
            'retail_store_status': 'Status',
        },
        bn: {
            'shoe_sales': 'জুতা বিক্রয়',
            'one_time_sales': 'খুচরা বিক্রয়',
            'retailers': 'খরিদ্দার',
            'memo_no': 'মেমো নং',
            'id': 'আইডি',
            'type': 'টাইপ',
            'color': 'রং',
            'selling_price': 'গায়ের দাম',
            'pair_stock': 'জোড়া স্টক',
            'pair': 'জোড়া',
            'size': 'সাইজ',
            'total_price': 'মোট দাম',
            'total': 'মোট',
            'discount_products': 'ডিসকাউন্ট পণ্য',
            'commission': 'কমিশন',
            'total_commission': 'মোট কমিশন',
            'total_excluding_commission': 'কমিশন বাদে মোট',
            'for_return_of_pair': 'জোড়া ফেরত বাবদ',
            'total_excluding_refund': 'ফেরত বাদে মোট',
            'shipping_costs': 'পাঠানোর খরচ',
            'others_expenses': 'অন্যান্য খরচ',
            'discount': 'ডিসকাউন্ট',
            'total_amount': 'সর্বমোট',
            'deposits_details': 'জমার বিবরণ',
            'pament_method': 'পেমেন্ট মাধ্যম',
            'check_no': 'চেক নং',
            'taka': 'টাকা',
            'total_deposits': 'মোট জমা',
            'gift_details': 'গিফটের বিবরণ',
            'gift': 'গিফট',
            'count': 'সংখ্যা',
            'preview': 'প্রিভিউ',
            'submit': 'সাবমিট',
            'retail_store_status': 'অবস্থা',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Form instantiation
const isEditMode = computed(() => props.invoice !== null);

const initialSales = () => {
    if (isEditMode.value) {
        return props.invoice.invoice_entries.map(entry => {
            const sizes = entry.sizes_data ? entry.sizes_data.map(sz => ({
                size_id: sz.size_id,
                name: sz.size?.name || '',
                quantity: sz.quantity,
                sorts: sz.sorts ? JSON.parse(sz.sorts) : []
            })) : [];
            return {
                id: entry.id,
                shoe_id: entry.shoe?.code || '',
                category_name: entry.shoe ? (entry.shoe.category?.parent?.name + '-' + entry.shoe.category?.name) : '',
                color_name: entry.shoe?.color?.name || '',
                retail_price: parseFloat(entry.retail_price > 0 ? entry.retail_price : (entry.shoe?.retail_price ?? 0)).toFixed(2),
                available: entry.available || 0,
                count: entry.count,
                sizes: sizes
            };
        });
    } else {
        return [
            {
                shoe_id: '',
                category_name: '',
                color_name: '',
                retail_price: '',
                available: '',
                count: '',
                sizes: []
            }
        ];
    }
};

const initialPayments = () => {
    if (isEditMode.value && props.transactions.length > 0) {
        return props.transactions.map(tx => {
            let pMethod = tx.payment_method_id;
            if (tx.payment_type === 'cheque' || !tx.payment_method_id) {
                pMethod = 'cheque';
            }
            return {
                id: tx.id,
                payment_method: pMethod,
                cheque_no: tx.description ? tx.description.replace('চেক নং ', '') : '',
                amount: parseFloat(tx.amount || 0).toFixed(2),
            };
        });
    } else {
        return [
            {
                payment_method: props.bankAccounts[0]?.id || '',
                cheque_no: '',
                amount: ''
            }
        ];
    }
};

const initialGifts = () => {
    if (isEditMode.value && props.giftTransactions.length > 0) {
        return props.giftTransactions.map(gt => ({
            id: gt.id,
            gift_id: gt.gift_id,
            count: gt.count
        }));
    } else {
        return [
            {
                gift_id: '',
                count: ''
            }
        ];
    }
};

const form = useForm({
    retail_store_id: isEditMode.value ? props.invoice.account_book?.retail_account?.id : (props.sessionRetailStore ? props.sessionRetailStore.id : ''),
    memo_to_name: isEditMode.value ? (props.invoice.account_book?.retail_account?.shop_name + (props.invoice.account_book?.retail_account?.address ? ' - ' + props.invoice.account_book?.retail_account?.address : '')) : (props.sessionRetailStore ? props.sessionRetailStore.name : ''),
    commission: isEditMode.value ? parseFloat(props.invoice.commission || 0).toFixed(2) : (props.sessionRetailStore ? parseFloat(props.sessionRetailStore.commission || 0).toFixed(2) : '0.00'),
    is_discount_product: isEditMode.value ? props.invoice.is_discount_product_sale == 1 : false,
    transport: isEditMode.value ? parseFloat(props.invoice.transport || 0).toFixed(2) : '',
    discount: isEditMode.value ? parseFloat(props.invoice.discount || 0).toFixed(2) : '',
    sales: initialSales(),
    payments: initialPayments(),
    gifts: initialGifts(),
});

// Selected Retailer Details (from prop)
const selectedStoreDetails = ref(null);

const findAndSetStoreDetails = (storeId) => {
    if (!storeId) {
        selectedStoreDetails.value = null;
        return;
    }
    const store = localRetailStores.value.find(s => s.id === parseInt(storeId));
    if (store) {
        selectedStoreDetails.value = store;
    } else if (props.sessionRetailStore && props.sessionRetailStore.id === parseInt(storeId)) {
        selectedStoreDetails.value = props.sessionRetailStore;
    } else {
        selectedStoreDetails.value = null;
    }
};

onMounted(() => {
    if (form.retail_store_id) {
        findAndSetStoreDetails(form.retail_store_id);
    }
});

// Watch Retailer ID to set details & commission
watch(() => form.retail_store_id, (newVal) => {
    findAndSetStoreDetails(newVal);
    if (selectedStoreDetails.value) {
        if (!form.is_discount_product) {
            form.commission = parseFloat(selectedStoreDetails.value.commission || 0).toFixed(2);
        }
    } else {
        form.commission = '0.00';
    }
});

watch(() => form.is_discount_product, (newVal) => {
    if (!newVal) {
        if (selectedStoreDetails.value) {
            form.commission = parseFloat(selectedStoreDetails.value.commission || 0).toFixed(2);
        } else {
            form.commission = '0.00';
        }
    }
});

// Autocomplete Handler for Retailer Select
const handleRetailerInput = () => {
    if (!form.memo_to_name) {
        form.retail_store_id = '';
        return;
    }
    const store = localRetailStores.value.find(s => s.name === form.memo_to_name);
    if (store) {
        form.retail_store_id = store.id;
    } else {
        form.retail_store_id = '';
    }
};

// Available Return count & amount
const returnCount = computed(() => {
    if (isEditMode.value) {
        return props.invoice.return_count_val || 0;
    }
    return selectedStoreDetails.value ? selectedStoreDetails.value.return_count : 0;
});

const returnAmount = computed(() => {
    if (isEditMode.value) {
        return props.invoice.return_amount_val || 0;
    }
    return selectedStoreDetails.value ? selectedStoreDetails.value.return_amount : 0;
});

const otherCosts = computed(() => {
    if (isEditMode.value) {
        return props.invoice.other_costs_val || 0;
    }
    return selectedStoreDetails.value ? selectedStoreDetails.value.other_costs : 0;
});

// Load existing shoe data via Ajax
const loadShoeData = (row) => {
    if (!row.shoe_id) return;
    
    // Check duplicates
    const same = form.sales.filter(s => s.shoe_id === row.shoe_id);
    if (same.length > 1) {
        alert(t('pages.The same shoe has been added multiple times:'));
        row.shoe_id = '';
        return;
    }

    axios.get(route('ajax.shoe.show', { shoe: row.shoe_id }))
        .then(res => {
            if (res.data && res.data.id) {
                const availableStock = res.data.cal ? Object.values(res.data.cal).reduce((sum, item) => sum + (item.total_quantity || 0), 0) : 0;
                
                if (availableStock <= 0) {
                    alert('এই জুতাটি ইনভেন্টরিতে নেই।');
                    row.shoe_id = '';
                    row.category_name = '';
                    row.color_name = '';
                    row.retail_price = '';
                    row.available = '';
                    row.count = '';
                    row.sizes = [];
                    return;
                }

                row.category_name = res.data.category?.parent?.name + '-' + res.data.category?.name;
                row.color_name = res.data.color?.name || '';
                row.retail_price = parseFloat(res.data.retail_price).toFixed(2);
                row.available = availableStock;
                row.sizes = [];

                // Stock clamping on load matching the old software
                if (!row.count || parseInt(row.count) === 0) {
                    row.count = row.available > 0 ? 1 : 0;
                } else if (parseInt(row.count) > row.available) {
                    row.count = row.available;
                }
            } else {
                alert('এই আইডির কোন জুতা নেই।');
                row.shoe_id = '';
            }
        })
        .catch(() => {
            alert('এই আইডির কোন জুতা নেই।');
            row.shoe_id = '';
        });
};

const validateRowStock = (row) => {
    const count = parseInt(row.count) || 0;
    const available = parseInt(row.available) || 0;
    if (count > available) {
        alert('এই পরিমাণ জুতা ইনভেন্টরিতে নেই।');
        row.count = available;
    }
};

// Size Chart Modal states
const showSizeChartModal = ref(false);
const activeRowIndex = ref(null);
const modalSizesList = ref([]);
const activeSortCount = ref(2);

const openSizeChart = (index) => {
    const row = form.sales[index];
    if (!row.count) {
        alert('জোড়া প্রদান করুন');
        return;
    }
    if (!row.shoe_id) {
        alert('জুতা আইডি প্রদান করুন');
        return;
    }

    activeRowIndex.value = index;

    // Fetch sizes for this category/shoe
    axios.get(route('ajax.shoe.show', { shoe: row.shoe_id }))
        .then(res => {
            const shoeData = res.data;
            const dbSizes = shoeData.size || [];
            const calData = shoeData.cal || {};

            modalSizesList.value = dbSizes.map(size => {
                const existingSize = row.sizes.find(s => s.size_id === size.id);
                const stockQty = calData[size.id] ? calData[size.id].total_quantity : 0;
                
                // If edit mode and this entry existed, add the original entry count back to available stock
                let originalQty = 0;
                if (isEditMode.value) {
                    const originalEntry = props.invoice.invoice_entries.find(e => e.shoe_id === shoeData.id);
                    if (originalEntry && originalEntry.sizes_data) {
                        const originalSize = originalEntry.sizes_data.find(sz => sz.size_id === size.id);
                        originalQty = originalSize ? originalSize.quantity : 0;
                    }
                }

                const sorts = existingSize ? [...existingSize.sorts] : Array(activeSortCount.value).fill('');
                
                return {
                    size_id: size.id,
                    name: size.name,
                    quantity: existingSize ? existingSize.quantity : 0,
                    availableStock: stockQty + originalQty,
                    sorts: sorts
                };
            });
            
            showSizeChartModal.value = true;
        })
        .catch(() => {
            alert('সাইজ চার্ট লোড করা যায়নি।');
        });
};

const addSort = () => {
    activeSortCount.value += 2;
    modalSizesList.value.forEach(sz => {
        sz.sorts.push('', '');
    });
};

const deleteSort = () => {
    if (activeSortCount.value > 2) {
        activeSortCount.value -= 2;
        modalSizesList.value.forEach(sz => {
            sz.sorts.splice(-2);
        });
        calculateSizeQuantities();
    }
};

const calculateSizeQuantities = () => {
    modalSizesList.value.forEach(sz => {
        const rowSum = sz.sorts.reduce((sum, val) => sum + (parseInt(val) || 0), 0);
        sz.quantity = rowSum;
    });
};

const totalSizesSum = computed(() => {
    return modalSizesList.value.reduce((sum, sz) => sum + sz.quantity, 0);
});

const saveSizeChart = () => {
    const row = form.sales[activeRowIndex.value];
    if (totalSizesSum.value !== parseInt(row.count)) {
        alert('সাইজ চার্টে যোগ করা সংখ্যা মোট জোড়ার সংখ্যার সমান হতে হবে (' + row.count + ' জোড়া)');
        return;
    }

    // Verify stock constraints for each size
    for (const sz of modalSizesList.value) {
        if (sz.quantity > sz.availableStock) {
            alert(`সাইজ ${sz.name} এর জন্য পর্যাপ্ত স্টক নেই। এভেইলেবল: ${sz.availableStock} জোড়া`);
            return;
        }
    }
    
    row.sizes = modalSizesList.value.map(sz => ({
        size_id: sz.size_id,
        name: sz.name,
        quantity: sz.quantity,
        sorts: sz.sorts.map(v => parseInt(v) || 0)
    }));

    showSizeChartModal.value = false;
};

// Add / Remove rows
const addSalesRow = () => {
    for (let i = 0; i < form.sales.length; i++) {
        const row = form.sales[i];
        if (!row.shoe_id) {
            alert('অনুগ্রহ করে জুতা আইডি প্রদান করুন।');
            return;
        }
        if (!row.count || parseInt(row.count) <= 0) {
            alert('অনুগ্রহ করে জোড়ার সংখ্যা সঠিকভাবে (০ এর বেশি) প্রদান করুন।');
            return;
        }
    }
    form.sales.push({
        shoe_id: '',
        category_name: '',
        color_name: '',
        retail_price: '',
        available: '',
        count: '',
        sizes: []
    });
};

const removeSalesRow = (index) => {
    if (form.sales.length > 1) {
        form.sales.splice(index, 1);
    }
};

const addPaymentRow = () => {
    form.payments.push({
        payment_method: props.bankAccounts[0]?.id || '',
        cheque_no: '',
        amount: ''
    });
};

const removePaymentRow = (index) => {
    if (form.payments.length > 1) {
        form.payments.splice(index, 1);
    }
};

const addGiftRow = () => {
    form.gifts.push({
        gift_id: '',
        count: ''
    });
};

const removeGiftRow = (index) => {
    if (form.gifts.length > 1) {
        form.gifts.splice(index, 1);
    }
};

// Computations
const getRowSubtotal = (row) => {
    const count = parseInt(row.count) || 0;
    const price = parseFloat(row.retail_price) || 0;
    return count * price;
};

const totalAmount = computed(() => {
    return form.sales.reduce((sum, row) => sum + getRowSubtotal(row), 0);
});

const totalCommission = computed(() => {
    const commPct = parseFloat(form.commission) || 0;
    return (totalAmount.value * commPct) / 100;
});

const commissionDeducted = computed(() => {
    return totalAmount.value - totalCommission.value;
});

const refundDeducted = computed(() => {
    return commissionDeducted.value - parseFloat(returnAmount.value || 0);
});

const transportAdded = computed(() => {
    return refundDeducted.value + (parseFloat(form.transport) || 0);
});

const otherCostsDeducted = computed(() => {
    return transportAdded.value - parseFloat(otherCosts.value || 0);
});

const totalReceivable = computed(() => {
    return otherCostsDeducted.value - (parseFloat(form.discount) || 0);
});

const totalPaymentsAmount = computed(() => {
    return form.payments.reduce((sum, pay) => sum + (parseFloat(pay.amount) || 0), 0);
});

// Preview Modal States
const isShowingPreview = ref(false);
const previewData = ref(null);

const togglePreview = () => {
    if (!form.retail_store_id) {
        alert('খরিদ্দার নির্বাচন করুন।');
        return;
    }
    
    // Group sales for preview layout matching show.blade.php
    const groupedSales = {};
    form.sales.forEach(row => {
        if (!row.shoe_id || !row.count) return;
        const catParts = (row.category_name || '').split('-');
        const parentCat = catParts[0] || 'Uncategorized';
        const subCat = catParts[1] || '';
        
        if (!groupedSales[parentCat]) {
            groupedSales[parentCat] = {};
        }
        
        const priceStr = parseFloat(row.retail_price || 0).toFixed(2);
        if (!groupedSales[parentCat][priceStr]) {
            groupedSales[parentCat][priceStr] = {};
        }
        
        if (!groupedSales[parentCat][priceStr][subCat]) {
            groupedSales[parentCat][priceStr][subCat] = 0;
        }
        groupedSales[parentCat][priceStr][subCat] += parseInt(row.count) || 0;
    });

    previewData.value = {
        memoNo: isEditMode.value ? props.invoice.id : props.memoNo,
        shopName: selectedStoreDetails.value?.shop_name || '',
        address: selectedStoreDetails.value?.address || '',
        mobileNo: selectedStoreDetails.value?.mobile_no || '',
        isDiscount: form.is_discount_product,
        groupedSales: groupedSales,
        totalAmount: totalAmount.value,
        commission: form.commission,
        totalCommission: totalCommission.value,
        commissionDeducted: commissionDeducted.value,
        returnCount: returnCount.value,
        returnAmount: returnAmount.value,
        refundDeducted: refundDeducted.value,
        transport: form.transport || 0,
        transportAdded: transportAdded.value,
        otherCosts: otherCosts.value,
        otherCostsDeducted: otherCostsDeducted.value,
        discount: form.discount || 0,
        totalReceivable: totalReceivable.value,
        totalPayments: totalPaymentsAmount.value,
        gifts: form.gifts.map(g => {
            const giftObj = props.gifts.find(gf => gf.id === parseInt(g.gift_id));
            return {
                name: giftObj ? giftObj.name : '',
                count: g.count
    };
}).filter(g => g.name && g.count),
payments: form.payments.map(p => {
    const acc = props.bankAccounts.find(b => b.id === parseInt(p.payment_method));
    return {
        method: p.payment_method === 'cheque' ? 'চেক' : (acc ? acc.name : ''),
        cheque_no: p.cheque_no,
        amount: p.amount
    };
}).filter(p => p.amount)
};

isShowingPreview.value = true;
};

const submitForm = () => {
if (!form.retail_store_id) {
alert('খরিদ্দার নির্বাচন করুন।');
return;
}

// Filter empty rows
form.sales = form.sales.filter(s => s.shoe_id && s.count);
form.payments = form.payments.filter(p => p.amount);
form.gifts = form.gifts.filter(g => g.gift_id && g.count);

if (form.sales.length === 0) {
alert('কমপক্ষে একটি জুতা যোগ করুন।');
return;
}

if (isEditMode.value) {
form.put(route('invoice.update', props.invoice.id));
} else {
form.post(route('invoice.store'));
}
};

const toFixed = (val) => {
return parseFloat(val || 0).toFixed(2);
};
</script>

<template>
    <Head :title="t('shoe_sales')" />

    <AuthenticatedLayout>
        <!-- Dynamic Datalists -->
        <datalist id="retail-store-list">
            <option v-for="store in localRetailStores" :key="store.id" :value="store.name" :data-id="store.id" />
        </datalist>

        <!-- Form Screen -->
        <div v-if="!isShowingPreview" class="bg-white dark:bg-slate-800 rounded border border-[#ced4da] dark:border-slate-700 border-t-[3px] border-t-[#007bff] shadow-sm overflow-hidden w-full max-w-none mx-auto">
            <div class="px-5 py-4 bg-white dark:bg-slate-900 border-b border-[#ced4da]/80 dark:border-slate-700 flex items-center justify-between">
                <h1 class="text-[28px] font-bold text-[#212529] dark:text-white font-nikosh flex items-baseline gap-3">
                    <span>{{ isEditMode ? 'জুতা বিক্রয় সম্পাদন' : 'জুতা বিক্রয়' }}</span>
                    <small v-if="!isEditMode">
                        <button type="button" @click="openOnetimeSaleModal" class="btn-new text-blue-650 dark:text-blue-400 hover:underline text-lg font-normal cursor-pointer">
                            {{ t('one_time_sales') }}
                        </button>
                    </small>
                </h1>
            </div>

            <div class="p-5 font-nikosh">
                <form @submit.prevent="submitForm" autocomplete="off">
                    <!-- Retailer & Memo No -->
                    <div class="grid grid-cols-12 gap-4 items-center mb-6 w-full">
                        <div class="col-span-12 md:col-span-9 flex items-stretch border border-[#ced4da] dark:border-slate-700 rounded h-[38px] bg-white dark:bg-slate-900">
                            <span class="bg-[#e9ecef] dark:bg-slate-950 border-r border-[#ced4da] dark:border-slate-700 px-4 flex items-center text-sm text-[#495057] dark:text-slate-300 font-bold select-none whitespace-nowrap font-nikosh">
                                {{ t('retailers') }}
                            </span>
                            <input
                                v-model="form.memo_to_name"
                                type="text"
                                list="retail-store-list"
                                required
                                :disabled="isEditMode"
                                @input="handleRetailerInput"
                                class="flex-1 border-0 focus:ring-0 px-3 bg-transparent text-slate-850 dark:text-slate-200 text-sm font-bold outline-none cursor-pointer h-full"
                            />
                        </div>

                        <div class="col-span-12 md:col-span-3 flex md:justify-end w-full">
                            <div class="flex items-stretch border border-[#ced4da] dark:border-slate-700 rounded h-[38px] bg-white dark:bg-slate-900">
                                <span class="bg-[#e9ecef] dark:bg-slate-950 border-r border-[#ced4da] dark:border-slate-700 px-3 flex items-center text-sm text-[#495057] dark:text-slate-400 select-none whitespace-nowrap">
                                    {{ t('memo_no') }}
                                </span>
                                <div class="flex items-center justify-center font-bold text-slate-800 dark:text-white text-sm bg-white dark:bg-slate-900 px-4 font-english">
                                    {{ isEditMode ? invoice.id : memoNo }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Grid Fieldset -->
                    <fieldset :disabled="form.retail_store_id === ''" class="border border-slate-200 dark:border-slate-700 rounded overflow-hidden mb-6 disabled:opacity-60 transition">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm memo-input">
                                <thead>
                                    <tr class="border-b border-[#ced4da] dark:border-slate-700 text-[#212529] dark:text-slate-200 font-bold bg-white dark:bg-slate-900 text-center">
                                        <th class="px-2 py-3 w-12 min-w-[48px]"></th>
                                        <th class="px-3 py-3 w-[12%]">{{ t('id') }}</th>
                                        <th class="px-3 py-3 w-[13%]">{{ t('type') }}</th>
                                        <th class="px-3 py-3 w-[13%]">{{ t('color') }}</th>
                                        <th class="px-3 py-3 w-[13%]">{{ t('selling_price') }}</th>
                                        <th class="px-3 py-3 w-[13%]">{{ t('pair_stock') }}</th>
                                        <th class="px-3 py-3 w-[13%]">{{ t('pair') }}</th>
                                        <th class="px-3 py-3 w-[10%]">{{ t('size') }}</th>
                                        <th class="px-3 py-3 w-[13%] text-right">{{ t('total_price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, index) in form.sales" :key="index" class="border-b border-[#dee2e6]/70 dark:border-slate-700/60 bg-white dark:bg-slate-800/40">
                                        <td class="p-2 text-center align-middle">
                                            <button type="button" @click="removeSalesRow(index)" class="btn-danger-custom hover:bg-[#c82333] transition cursor-pointer">
                                                <i class="fas fa-minus text-sm"></i>
                                            </button>
                                        </td>
                                        <td class="p-2 align-middle">
                                            <input
                                                v-model="row.shoe_id"
                                                type="text"
                                                required
                                                @change="loadShoeData(row)"
                                                class="w-full invoice-input-field text-center font-english"
                                            />
                                        </td>
                                        <td class="p-2 align-middle">
                                            <input :value="row.category_name" type="text" disabled class="w-full invoice-input-field text-center invoice-disabled" />
                                        </td>
                                        <td class="p-2 align-middle">
                                            <input :value="row.color_name" type="text" disabled class="w-full invoice-input-field text-center invoice-disabled" />
                                        </td>
                                        <td class="p-2 align-middle">
                                            <input
                                                v-model="row.retail_price"
                                                type="text"
                                                :disabled="!form.is_discount_product"
                                                class="w-full invoice-input-field text-center font-english"
                                                :class="!form.is_discount_product ? 'invoice-disabled' : ''"
                                            />
                                        </td>
                                        <td class="p-2 align-middle">
                                            <input :value="row.available" type="text" disabled class="w-full invoice-input-field text-center font-english invoice-disabled" />
                                        </td>
                                        <td class="p-2 align-middle">
                                            <input
                                                v-model="row.count"
                                                type="number"
                                                required
                                                min="0"
                                                @change="validateRowStock(row)"
                                                class="w-full invoice-input-field text-center font-english"
                                            />
                                        </td>
                                        <td class="p-2 text-center align-middle">
                                            <button type="button" @click="openSizeChart(index)" class="btn-primary-custom hover:bg-[#0069d9] transition shadow-sm cursor-pointer">
                                                <i class="fas fa-ruler"></i>
                                            </button>
                                        </td>
                                        <td class="p-2 align-middle">
                                            <input :value="toFixed(getRowSubtotal(row))" type="text" disabled class="w-full invoice-input-field text-right font-english pr-4 invoice-disabled" />
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-700">
                                        <td class="p-3 text-center align-middle">
                                            <button type="button" @click="addSalesRow" class="btn-success-custom hover:bg-[#218838] transition cursor-pointer">
                                                <i class="fas fa-plus text-sm"></i>
                                            </button>
                                        </td>
                                        <td colspan="7" class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 pr-4 align-middle">
                                            {{ t('total') }}
                                        </td>
                                        <td class="p-2">
                                            <input :value="toFixed(totalAmount)" type="text" disabled class="w-full invoice-input-field text-right font-english pr-4 font-black invoice-disabled" />
                                        </td>
                                    </tr>
                                    
                                    <!-- Discount Products checkbox & Commission -->
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-700">
                                        <td colspan="2" class="p-3"></td>
                                        <td class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 align-middle">
                                            {{ t('discount_products') }}
                                        </td>
                                        <td class="p-3 text-center align-middle">
                                            <input v-model="form.is_discount_product" type="checkbox" class="rounded border-slate-350 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer" />
                                        </td>
                                        <td class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 align-middle">
                                            {{ t('commission') }} (%)
                                        </td>
                                        <td class="p-2">
                                            <input
                                                v-model="form.commission"
                                                type="text"
                                                :disabled="!form.is_discount_product"
                                                class="w-full invoice-input-field text-center font-english"
                                                :class="!form.is_discount_product ? 'invoice-disabled' : ''"
                                            />
                                        </td>
                                        <td class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 align-middle">
                                            {{ t('total_commission') }}
                                        </td>
                                        <td></td>
                                        <td class="p-2">
                                            <input :value="toFixed(totalCommission)" type="text" disabled class="w-full invoice-input-field text-right font-english pr-4 font-bold invoice-disabled" />
                                        </td>
                                    </tr>

                                    <!-- Commission Deducted -->
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-700">
                                        <td colspan="8" class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 pr-4 align-middle">
                                            {{ t('total_excluding_commission') }}
                                        </td>
                                        <td class="p-2">
                                            <input :value="toFixed(commissionDeducted)" type="text" disabled class="w-full invoice-input-field text-right font-english pr-4 font-black invoice-disabled" />
                                        </td>
                                    </tr>

                                    <!-- Refund/Returns Count & Amount -->
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-700">
                                        <td colspan="8" class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 pr-4 align-middle">
                                            <span class="bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 font-bold px-2.5 py-0.5 rounded font-english mr-2">{{ returnCount }}</span>
                                            {{ t('for_return_of_pair') }}
                                        </td>
                                        <td class="p-2">
                                            <input :value="toFixed(returnAmount)" type="text" disabled class="w-full invoice-input-field text-right font-english pr-4 font-bold invoice-disabled" />
                                        </td>
                                    </tr>

                                    <!-- Total Excluding Refund -->
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-700">
                                        <td colspan="8" class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 pr-4 align-middle">
                                            {{ t('total_excluding_refund') }}
                                        </td>
                                        <td class="p-2">
                                            <input :value="toFixed(refundDeducted)" type="text" disabled class="w-full invoice-input-field text-right font-english pr-4 font-black invoice-disabled" />
                                        </td>
                                    </tr>

                                    <!-- Shipping costs -->
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-700">
                                        <td colspan="8" class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 pr-4 align-middle">
                                            {{ t('shipping_costs') }}
                                        </td>
                                        <td class="p-2">
                                            <input v-model="form.transport" type="text" class="w-full invoice-input-field text-right font-english pr-4" />
                                        </td>
                                    </tr>

                                    <!-- Other Expenses -->
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-700">
                                        <td colspan="8" class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 pr-4 align-middle">
                                            {{ t('others_expenses') }}
                                        </td>
                                        <td class="p-2">
                                            <input :value="toFixed(otherCosts)" type="text" disabled class="w-full invoice-input-field text-right font-english pr-4 font-bold invoice-disabled" />
                                        </td>
                                    </tr>

                                    <!-- Discount -->
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-700">
                                        <td colspan="8" class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 pr-4 align-middle">
                                            {{ t('discount') }}
                                        </td>
                                        <td class="p-2">
                                            <input v-model="form.discount" type="text" class="w-full invoice-input-field text-right font-english pr-4" />
                                        </td>
                                    </tr>

                                    <!-- Net Receivable Total -->
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-700">
                                        <td colspan="8" class="p-4 text-right font-extrabold text-[#212529] dark:text-white text-base pr-4 align-middle">
                                            {{ t('total_amount') }}
                                        </td>
                                        <td class="p-2">
                                            <input :value="toFixed(totalReceivable)" type="text" disabled class="w-full invoice-input-field text-right font-english pr-4 font-black text-blue-600 dark:text-blue-400 invoice-disabled" />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </fieldset>

                    <!-- Payments & Deposits -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-[#212529] dark:text-white mb-3">
                            {{ t('deposits_details') }}
                        </h3>
                        <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded mb-4">
                            <table class="w-full text-left border-collapse text-sm payment-table">
                                <thead>
                                    <tr class="border-b border-[#ced4da] dark:border-slate-700 text-[#495057] dark:text-slate-200 font-bold bg-[#f8f9fa] dark:bg-slate-900 text-center">
                                        <th class="px-2 py-2.5 w-12"></th>
                                        <th class="px-3 py-2.5 text-left w-[65%]">{{ t('pament_method') }}</th>
                                        <th class="px-3 py-2.5 w-[15%]">{{ t('check_no') }}</th>
                                        <th class="px-3 py-2.5 text-right w-[15%] pr-4">{{ t('taka') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(pay, pIndex) in form.payments" :key="pIndex" class="border-b border-[#dee2e6]/70 dark:border-slate-750 bg-white dark:bg-slate-800/40">
                                        <td class="p-2 text-center align-middle">
                                            <button type="button" @click="removePaymentRow(pIndex)" class="btn-danger-custom hover:bg-[#c82333] transition cursor-pointer">
                                                <i class="fas fa-minus text-sm"></i>
                                            </button>
                                        </td>
                                        <td class="p-2 align-middle">
                                            <select v-model="pay.payment_method" required class="w-full invoice-input-field font-semibold outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 cursor-pointer">
                                                <option v-for="ac in bankAccounts" :key="ac.id" :value="ac.id">{{ ac.name }}</option>
                                            </select>
                                        </td>
                                        <td class="p-2 align-middle">
                                            <input
                                                v-model="pay.cheque_no"
                                                type="text"
                                                :disabled="pay.payment_method !== 'cheque'"
                                                class="w-full invoice-input-field text-center font-english"
                                                :class="pay.payment_method !== 'cheque' ? 'invoice-disabled' : ''"
                                            />
                                        </td>
                                        <td class="p-2 align-middle">
                                            <input v-model="pay.amount" type="text" required class="w-full invoice-input-field text-right font-english pr-4" />
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-750">
                                        <td class="p-2 text-center align-middle">
                                            <button type="button" @click="addPaymentRow" class="btn-success-custom hover:bg-[#218838] transition cursor-pointer">
                                                <i class="fas fa-plus text-sm"></i>
                                            </button>
                                        </td>
                                        <td colspan="2" class="p-3 text-right font-bold text-slate-700 dark:text-slate-200 pr-4 align-middle">
                                            {{ t('total_deposits') }}
                                        </td>
                                        <td class="p-2">
                                            <input :value="toFixed(totalPaymentsAmount)" type="text" disabled class="w-full invoice-input-field text-right font-english pr-4 font-black invoice-disabled" />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Gifts Details -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-[#212529] dark:text-white mb-3">
                            {{ t('gift_details') }}
                        </h3>
                        <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded mb-4">
                            <table class="w-full text-left border-collapse text-sm gift-table">
                                <thead>
                                    <tr class="border-b border-[#ced4da] dark:border-slate-700 text-[#495057] dark:text-slate-200 font-bold bg-[#f8f9fa] dark:bg-slate-900 text-center">
                                        <th class="px-2 py-2.5 w-12"></th>
                                        <th class="px-3 py-2.5 text-left w-[70%]">{{ t('gift') }}</th>
                                        <th class="px-3 py-2.5 text-right w-[20%] pr-4">{{ t('count') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(gift, gIndex) in form.gifts" :key="gIndex" class="border-b border-[#dee2e6]/70 dark:border-slate-755 bg-white dark:bg-slate-800/40">
                                        <td class="p-2 text-center align-middle">
                                            <button type="button" @click="removeGiftRow(gIndex)" class="btn-danger-custom hover:bg-[#c82333] transition cursor-pointer">
                                                <i class="fas fa-minus text-sm"></i>
                                            </button>
                                        </td>
                                        <td class="p-2 align-middle">
                                            <select v-model="gift.gift_id" class="w-full invoice-input-field font-semibold outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 cursor-pointer">
                                                <option value="">গিফট নির্বাচন করুন</option>
                                                <option v-for="gItem in gifts" :key="gItem.id" :value="gItem.id">{{ gItem.name }}</option>
                                            </select>
                                        </td>
                                        <td class="p-2 align-middle">
                                            <input v-model="gift.count" type="number" min="1" class="w-full invoice-input-field text-right font-english pr-4" />
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-white dark:bg-slate-900 border-t border-[#ced4da] dark:border-slate-750">
                                        <td class="p-2 text-center align-middle">
                                            <button type="button" @click="addGiftRow" class="btn-success-custom hover:bg-[#218838] transition cursor-pointer">
                                                <i class="fas fa-plus text-sm"></i>
                                            </button>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="flex items-center justify-end gap-3 mt-6 border-t border-[#ced4da] dark:border-slate-700 pt-5">
                        <button type="button" @click="togglePreview" class="bg-[#6c757d] hover:bg-[#5a6268] border border-[#6c757d] text-white font-bold px-6 py-2.5 rounded transition shadow-sm text-sm cursor-pointer inline-flex items-center gap-2">
                            {{ t('preview') }}
                        </button>
                        <button type="submit" class="bg-[#007bff] hover:bg-[#0069d9] border border-[#007bff] text-white font-bold px-8 py-2.5 rounded transition shadow-sm text-sm cursor-pointer inline-flex items-center gap-2">
                            {{ t('submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Screen (Matches show.blade.php) -->
        <div v-else class="bg-white dark:bg-slate-800 rounded border border-[#ced4da] dark:border-slate-700 p-6 mb-8 w-full font-nikosh">
            <div class="flex justify-between items-center mb-5 border-b border-slate-200 dark:border-slate-700 pb-3">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-eye text-[#007bff]"></i> মেমো প্রিভিউ (সংরক্ষিত নয়)
                </h2>
                <button type="button" @click="isShowingPreview = false" class="bg-[#6c757d] hover:bg-[#5a6268] text-white font-bold px-4 py-2 rounded text-sm cursor-pointer transition">
                    ফর্মে ফিরে যান
                </button>
            </div>

            <!-- Printable content mock -->
            <div class="border border-[#dee2e6] dark:border-slate-700 p-6 bg-white dark:bg-slate-900 rounded font-nikosh text-[#212529] dark:text-slate-200">
                <table class="w-full border-collapse border border-[#dee2e6] dark:border-slate-700 mb-5 text-sm">
                    <tbody>
                        <tr class="bg-[#f8f9fa] dark:bg-slate-950 border-b border-[#dee2e6] dark:border-slate-700">
                            <td class="p-3 w-[50%] text-left">
                                {{ t('memo_no') }}: <strong class="font-black font-english">{{ previewData.memoNo }}</strong>
                            </td>
                            <td v-if="previewData.isDiscount" class="p-3 w-[20%] text-center text-red-500 font-bold">
                                ডিসকাউন্ট বিক্রয় মেমো
                            </td>
                            <td class="p-3 text-right">
                                {{ t('date') }}: <strong>{{ new Date().toLocaleDateString('bn-BD') }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="p-3 text-left leading-relaxed">
                                {{ t('name') }}: <strong class="font-bold">{{ previewData.shopName }}</strong><br />
                                ঠিকানা: <strong class="font-bold">{{ previewData.address || '-' }}</strong><br />
                                মোবাইল নং: <strong class="font-bold font-english">{{ previewData.mobileNo || '-' }}</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Items list -->
                <table class="w-full text-left border-collapse text-sm mb-5">
                    <thead>
                        <tr class="border-b border-[#dee2e6] dark:border-slate-700 font-bold bg-[#f8f9fa] dark:bg-slate-950">
                            <th class="p-2.5 w-12 text-center">#</th>
                            <th class="p-2.5 w-[55%]">বিবরণ</th>
                            <th class="p-2.5 text-center w-[15%]">{{ t('pair') }}</th>
                            <th class="p-2.5 text-center w-[15%]">দাম</th>
                            <th class="p-2.5 text-right w-[15%] pr-4">{{ t('total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(pg, parentCat, pIdx) in previewData.groupedSales" :key="pIdx">
                            <template v-for="(rpg, retailPrice, rIdx) in pg" :key="rIdx">
                                <tr class="border-b border-[#dee2e6]/70 dark:border-slate-800">
                                    <td class="p-2.5 text-center">{{ pIdx + 1 }}</td>
                                    <td class="p-2.5">
                                        {{ parentCat }}-
                                        <span v-for="(count, subCat, cIdx) in rpg" :key="subCat">
                                            <span v-if="cIdx > 0">+</span>{{ subCat }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 text-center font-english">
                                        {{ Object.values(rpg).reduce((sum, v) => sum + v, 0) }}
                                    </td>
                                    <td class="p-2.5 text-center font-english">
                                        {{ toFixed(retailPrice) }}
                                    </td>
                                    <td class="p-2.5 text-right font-english pr-4 font-bold">
                                        {{ toFixed(Object.values(rpg).reduce((sum, v) => sum + v, 0) * parseFloat(retailPrice)) }}
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>

                <!-- Totals & Balances -->
                <div class="flex justify-end mb-6">
                    <div class="w-full md:w-[450px]">
                        <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm">
                            <span class="font-bold text-slate-600 dark:text-slate-400">মোট দাম</span>
                            <span class="text-right font-bold font-english">{{ toFixed(previewData.totalAmount) }}</span>
                        </div>
                        <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm">
                            <span class="font-bold text-slate-600 dark:text-slate-400">কমিশন ({{ previewData.commission }}%)</span>
                            <span class="text-right font-bold font-english">(-) {{ toFixed(previewData.totalCommission) }}</span>
                        </div>
                        <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm">
                            <span class="font-bold text-slate-600 dark:text-slate-400">কমিশন বাদে মোট</span>
                            <span class="text-right font-black font-english text-slate-800 dark:text-white">{{ toFixed(previewData.commissionDeducted) }}</span>
                        </div>
                        <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm">
                            <span class="font-bold text-slate-600 dark:text-slate-400">{{ previewData.returnCount }} জোড়া ফেরত বাবদ</span>
                            <span class="text-right font-bold font-english">(-) {{ toFixed(previewData.returnAmount) }}</span>
                        </div>
                        <div class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm">
                            <span class="font-bold text-slate-600 dark:text-slate-400">ফেরত বাদে মোট</span>
                            <span class="text-right font-black font-english text-slate-800 dark:text-white">{{ toFixed(previewData.refundDeducted) }}</span>
                        </div>
                        <div v-if="previewData.transport > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm">
                            <span class="font-bold text-slate-600 dark:text-slate-400">পাঠানোর খরচ</span>
                            <span class="text-right font-bold font-english">(+) {{ toFixed(previewData.transport) }}</span>
                        </div>
                        <div v-if="previewData.otherCosts > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm">
                            <span class="font-bold text-slate-600 dark:text-slate-400">অন্যান্য খরচ</span>
                            <span class="text-right font-bold font-english">(-) {{ toFixed(previewData.otherCosts) }}</span>
                        </div>
                        <div v-if="previewData.discount > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm">
                            <span class="font-bold text-slate-600 dark:text-slate-400">ডিসকাউন্ট</span>
                            <span class="text-right font-bold font-english">(-) {{ toFixed(previewData.discount) }}</span>
                        </div>
                        <div class="grid grid-cols-2 py-2 border-b-[2px] border-b-slate-300 dark:border-b-slate-600 text-sm font-extrabold bg-[#f8f9fa] dark:bg-slate-950 px-2 rounded">
                            <span class="text-blue-600 dark:text-blue-400">সর্বমোট</span>
                            <span class="text-right font-black font-english text-blue-600 dark:text-blue-400 text-base">{{ toFixed(previewData.totalReceivable) }}</span>
                        </div>
                        <div v-if="previewData.totalPayments > 0" class="grid grid-cols-2 py-1.5 border-b border-slate-100 dark:border-slate-800 text-sm">
                            <span class="font-bold text-slate-600 dark:text-slate-400">জমা</span>
                            <span class="text-right font-bold font-english">{{ toFixed(previewData.totalPayments) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Gift details -->
                <div v-if="previewData.gifts.length > 0" class="mt-6 w-full md:w-[350px]">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-1.5 mb-2">গিফটের বিবরণ</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700 text-[#495057] dark:text-slate-400 font-bold">
                                <th class="py-1">গিফট</th>
                                <th class="py-1 text-right">সংখ্যা</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(g, gIdx) in previewData.gifts" :key="gIdx" class="border-b border-slate-100 dark:border-slate-800/50">
                                <td class="py-1.5 text-slate-700 dark:text-slate-350">{{ g.name }}</td>
                                <td class="py-1.5 text-right font-bold font-english">{{ g.count }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Size Chart Modal Component -->
        <div v-if="showSizeChartModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto font-nikosh">
            <div class="bg-white dark:bg-slate-850 rounded-lg shadow-xl max-w-2xl w-full border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-5 py-4 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        সাইজ চার্ট
                    </h3>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="addSort" class="bg-[#007bff] hover:bg-[#0069d9] text-white text-xs font-bold px-3 py-1.5 rounded cursor-pointer transition">
                            সর্ট যোগ করুন
                        </button>
                        <button type="button" @click="deleteSort" class="bg-[#dc3545] hover:bg-[#c82333] text-white text-xs font-bold px-3 py-1.5 rounded cursor-pointer transition">
                            সর্ট মুছুন
                        </button>
                    </div>
                </div>
                
                <div class="p-5 overflow-x-auto">
                    <table class="w-full border-collapse border border-slate-200 dark:border-slate-700 text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold text-center">
                                <th class="p-2 border-r border-slate-200 dark:border-slate-700">সাইজ</th>
                                <th v-for="sIdx in activeSortCount" :key="sIdx" class="p-2 border-r border-slate-200 dark:border-slate-700">সর্ট {{ sIdx }}</th>
                                <th class="p-2">পরিমাণ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(sz, szIdx) in modalSizesList" :key="szIdx" class="border-b border-slate-200 dark:border-slate-700/65 bg-white dark:bg-slate-800/40">
                                <td class="p-2 text-center font-bold border-r border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100">
                                    {{ sz.name }}
                                    <div class="text-[10px] text-slate-400 font-semibold font-english">
                                        (স্টক: {{ sz.availableStock }})
                                    </div>
                                </td>
                                <td v-for="(val, colIdx) in sz.sorts" :key="colIdx" class="p-1 border-r border-slate-200 dark:border-slate-700">
                                    <input
                                        v-model="sz.sorts[colIdx]"
                                        type="number"
                                        min="0"
                                        @input="calculateSizeQuantities"
                                        class="w-full border-0 focus:ring-0 px-2 py-1 text-center font-bold text-sm bg-transparent text-slate-800 dark:text-slate-100 font-english"
                                    />
                                </td>
                                <td class="p-2 text-center font-bold font-english text-slate-800 dark:text-slate-200" :class="sz.quantity > sz.availableStock ? 'text-red-500 dark:text-red-400' : ''">
                                    {{ sz.quantity }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-end gap-5 mt-4 text-sm font-bold text-slate-700 dark:text-slate-350 px-2">
                        <div>
                            মোট পরিমাণ: <span class="text-blue-600 dark:text-blue-400 font-black font-english text-base">{{ totalSizesSum }}</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3.5 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showSizeChartModal = false" class="bg-[#6c757d] hover:bg-[#5a6268] text-white font-bold px-4 py-2 rounded text-sm cursor-pointer transition">
                        বন্ধ করুন
                    </button>
                    <button type="button" @click="saveSizeChart" class="bg-[#007bff] hover:bg-[#0069d9] text-white font-bold px-6 py-2 rounded text-sm cursor-pointer transition">
                        সেভ
                    </button>
                </div>
            </div>
        </div>

        <!-- One-time Sale (Retail Store) Creation Modal -->
        <div v-if="showOnetimeSaleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-900 rounded-lg shadow-xl w-full max-w-lg overflow-hidden border border-[#ced4da] dark:border-slate-700">
                <div class="px-5 py-4 bg-[#f8f9fa] dark:bg-slate-950 border-b border-[#ced4da] dark:border-slate-800 flex justify-between items-center">
                    <h5 class="text-lg font-bold text-slate-800 dark:text-white font-nikosh">খুচরা বিক্রয় (নতুন খরিদ্দার)</h5>
                    <button type="button" @click="showOnetimeSaleModal = false" class="text-slate-500 hover:text-slate-700 dark:hover:text-white text-xl leading-none">
                        &times;
                    </button>
                </div>
                <div class="p-5 font-nikosh">
                    <form @submit.prevent="submitOnetimeSale" autocomplete="off" class="space-y-4">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">নাম/দোকানের নাম <span class="text-red-500">*</span></label>
                                <input v-model="onetimeSaleForm.shop_name" type="text" required class="w-full invoice-input-field" />
                                <span v-if="onetimeErrors.shop_name" class="text-red-500 text-xs font-bold">{{ onetimeErrors.shop_name }}</span>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">মোবাইল নম্বর <span class="text-red-500">*</span></label>
                                <input v-model="onetimeSaleForm.mobile_no" type="text" required class="w-full invoice-input-field" />
                                <span v-if="onetimeErrors.mobile_no" class="text-red-500 text-xs font-bold">{{ onetimeErrors.mobile_no }}</span>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ঠিকানা <span class="text-red-500">*</span></label>
                                <input v-model="onetimeSaleForm.address" type="text" required class="w-full invoice-input-field" />
                                <span v-if="onetimeErrors.address" class="text-red-500 text-xs font-bold">{{ onetimeErrors.address }}</span>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">কোম্পানি শেয়ার</label>
                                <select v-model="onetimeSaleForm.company_share" class="w-full invoice-input-field cursor-pointer">
                                    <option :value="0">না</option>
                                    <option :value="1">হ্যাঁ</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-3 border-t border-[#ced4da] dark:border-slate-800">
                            <button type="button" @click="showOnetimeSaleModal = false" class="px-4 py-2 rounded text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-sm font-semibold transition cursor-pointer">
                                বাতিল
                            </button>
                            <button type="submit" class="px-4 py-2 rounded text-white bg-[#007bff] hover:bg-[#0069d9] text-sm font-semibold transition cursor-pointer">
                                সংরক্ষণ করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.invoice-input-field {
    background-color: #ffffff !important;
    border: 1px solid #ced4da !important;
    border-radius: 4px !important;
    color: #495057 !important;
    height: 38px !important;
    font-weight: bold !important;
    width: 100% !important;
    outline: none !important;
    padding: 6px 12px !important;
}

.dark .invoice-input-field {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    color: #f8fafc !important;
}

.invoice-disabled {
    background-color: #e9ecef !important;
    opacity: 1 !important;
    color: #495057 !important;
    cursor: not-allowed !important;
}

.dark .invoice-disabled {
    background-color: #0f172a !important;
    color: #64748b !important;
}

.btn-danger-custom {
    background-color: #e47272 !important;
    color: white !important;
    border-radius: 4px !important;
    width: 38px !important;
    height: 38px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.btn-success-custom {
    background-color: #6ebb84 !important;
    color: white !important;
    border-radius: 4px !important;
    width: 38px !important;
    height: 38px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.btn-primary-custom {
    background-color: #007bff !important;
    color: white !important;
    border-radius: 4px !important;
    width: 38px !important;
    height: 38px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.memo-input table,
.payment-table table,
.gift-table table {
    border-collapse: collapse !important;
}

.memo-input th,
.payment-table th,
.gift-table th {
    font-size: 15px !important;
    font-weight: bold !important;
}
</style>
