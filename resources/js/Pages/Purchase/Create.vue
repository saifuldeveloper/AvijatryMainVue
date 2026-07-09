<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    memoNo: {
        type: Number,
        required: true,
    },
    nextShoe: {
        type: Number,
        required: true,
    },
    bankAccounts: {
        type: Array,
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
    boxes: {
        type: Array,
        required: true,
    },
    bags: {
        type: Array,
        required: true,
    },
    factories: {
        type: Array,
        required: true,
    }
});

const page = usePage();

// Translation Helper matching the old software Bengali UI
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
            'shoe_purchase': 'Shoe Purchase',
            'suppliers': 'Supplier',
            'memo_no': 'Memo No',
            'new': 'New',
            'id': 'ID',
            'type': 'Type',
            'color': 'Color',
            'picture': 'Picture',
            'pair': 'Pairs',
            'size': 'Size Chart',
            'selling_price': 'Selling Price',
            'purchase_price': 'Purchase Price',
            'total_price': 'Total Price',
            'total': 'Total',
            'payment': 'Payment',
            'check_no': 'Check No',
            'payment_date': 'Payment Date',
            'submit': 'Submit',
            'preview': 'Preview',
            'box': 'Box',
            'bag': 'Bag',
            'shoe_purchase_receipt': 'Shoe Purchase Receipt',
        },
        bn: {
            'shoe_purchase': 'জুতা ক্রয়',
            'suppliers': 'মহাজন',
            'memo_no': 'মেমো নং',
            'new': 'নতুন?',
            'id': 'আইডি',
            'type': 'টাইপ',
            'color': 'রং',
            'picture': 'ছবি',
            'pair': 'জোড়া',
            'size': 'সাইজ',
            'selling_price': 'গায়ের দাম',
            'purchase_price': 'ভজন দাম',
            'total_price': 'মোট দাম',
            'total': 'মোট',
            'payment': 'তাগাদা',
            'check_no': 'চেক নং',
            'payment_date': 'পরিশোধের তারিখ',
            'submit': 'সাবমিট',
            'preview': 'প্রিভিউ',
            'box': 'বক্স',
            'bag': 'ব্যাগ',
            'shoe_purchase_receipt': 'জুতা ক্রয়ের রশিদ',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

const form = useForm({
    factory_id: '',
    memo_to_name: '',
    purchases: [
        {
            new: false,
            shoe_id: '',
            category_id: '',
            category_name: '',
            color_id: '',
            color_name: '',
            image: null,
            image_preview: null,
            count: '',
            retail_price: '',
            purchase_price: '',
            box_id: '',
            bag_id: '',
            sizes: []
        }
    ],
    payment_method: props.bankAccounts[0]?.id || '',
    cheque_no: '',
    cheque_date: '',
    payment_amount: '',
});

// Computed selected factory name for preview and layout
const selectedFactoryName = computed(() => {
    const f = props.factories.find(fac => fac.id === parseInt(form.factory_id));
    return f ? f.name : '';
});

const selectedFactoryAddress = computed(() => {
    const f = props.factories.find(fac => fac.id === parseInt(form.factory_id));
    return f ? f.address : '';
});

const selectedFactoryMobile = computed(() => {
    const f = props.factories.find(fac => fac.id === parseInt(form.factory_id));
    return f ? f.mobile_no : '';
});

const currentDateFormatted = computed(() => {
    const date = new Date();
    return date.toLocaleDateString(page.props.locale === 'bn' ? 'bn-BD' : 'en-US', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
});

// Watch Factory ID to remove old shoe rows when changed
watch(() => form.factory_id, (newVal, oldVal) => {
    if (newVal && newVal !== oldVal) {
        // Remove all old shoes
        form.purchases = form.purchases.filter(row => row.new);
        
        // If no rows remain, add a default row
        if (form.purchases.length === 0) {
            form.purchases.push({
                new: false,
                shoe_id: '',
                category_id: '',
                category_name: '',
                color_id: '',
                color_name: '',
                image: null,
                image_preview: null,
                count: '',
                retail_price: '',
                purchase_price: '',
                box_id: '',
                bag_id: '',
                sizes: []
            });
        }
        recalculateNewShoeIds();
    }
});

// Autocomplete Handler for Category/Type
const handleCategoryInput = (row) => {
    if (!row.category_name) {
        row.category_id = '';
        return;
    }
    const cat = props.categories.find(c => c.full_name === row.category_name);
    if (cat) {
        row.category_id = cat.id;
    } else {
        alert('এই নামে কোন জুতার টাইপ নেই।');
        row.category_name = '';
        row.category_id = '';
    }
};

// Autocomplete Handler for Color
const handleColorInput = (row) => {
    if (!row.color_name) {
        row.color_id = '';
        return;
    }
    const col = props.colors.find(c => c.name === row.color_name);
    if (col) {
        row.color_id = col.id;
    } else {
        alert('এই নামে কোন রং নেই।');
        row.color_name = '';
        row.color_id = '';
    }
};

// Autocomplete Handler for Factory/Supplier
const handleFactorySelect = () => {
    if (!form.memo_to_name) {
        form.factory_id = '';
        return;
    }
    const f = props.factories.find(fac => fac.name === form.memo_to_name);
    if (f) {
        form.factory_id = f.id;
    } else {
        alert('এই নামে কোন মহাজন নেই।');
        form.memo_to_name = '';
        form.factory_id = '';
    }
};

// Real-time Bengali to English digits converters and validators
const handleIntegerInput = (event, obj, key) => {
    let val = event.target.value;
    const banglaDigits = {'০':'0', '১':'1', '২':'2', '৩':'3', '৪':'4', '৫':'5', '৬':'6', '৭':'7', '৮':'8', '৯':'9'};
    val = val.replace(/[০-৯]/g, (match) => banglaDigits[match]);
    val = val.replace(/[^0-9]/g, '');
    obj[key] = val;
    event.target.value = val;
};

const handleDecimalInput = (event, obj, key) => {
    let val = event.target.value;
    const banglaDigits = {'০':'0', '১':'1', '২':'2', '৩':'3', '৪':'4', '৫':'5', '৬':'6', '৭':'7', '৮':'8', '৯':'9'};
    val = val.replace(/[০-৯]/g, (match) => banglaDigits[match]);
    val = val.replace(/[^0-9.]/g, '');
    const parts = val.split('.');
    if (parts.length > 2) {
        val = parts[0] + '.' + parts.slice(1).join('');
    }
    obj[key] = val;
    event.target.value = val;
};

const handleAlphanumericInput = (event, obj, key) => {
    let val = event.target.value;
    const banglaDigits = {'০':'0', '১':'1', '২':'2', '৩':'3', '৪':'4', '৫':'5', '৬':'6', '৭':'7', '৮':'8', '৯':'9'};
    val = val.replace(/[০-৯]/g, (match) => banglaDigits[match]);
    obj[key] = val;
    event.target.value = val;
};

const handleIntegerInputArray = (event, arr, index) => {
    let val = event.target.value;
    const banglaDigits = {'০':'0', '১':'1', '২':'2', '৩':'3', '৪':'4', '৫':'5', '৬':'6', '৭':'7', '৮':'8', '৯':'9'};
    val = val.replace(/[০-৯]/g, (match) => banglaDigits[match]);
    val = val.replace(/[^0-9]/g, '');
    arr[index] = val;
    event.target.value = val;
};

// Watch "New" shoe checkbox to auto-assign new Shoe IDs
const recalculateNewShoeIds = () => {
    let offset = 0;
    const startVal = parseInt(props.nextShoe, 16) || 0;
    form.purchases.forEach((row) => {
        if (row.new) {
            row.shoe_id = (startVal + offset).toString(16);
            offset++;
        }
    });
};

// Validation function matching old project rules
const validatePurchases = () => {
    if (!form.factory_id) {
        alert('অনুগ্রহ করে মহাজন সিলেক্ট করুন!');
        return false;
    }

    for (let i = 0; i < form.purchases.length; i++) {
        const row = form.purchases[i];
        
        // 1. Check ID (for old shoes)
        if (!row.new && !row.shoe_id) {
            alert('আইডি প্রদান করুন।');
            return false;
        }

        // 2. Check Category / Type
        if (row.new && !row.category_id) {
            alert('টাইপ প্রদান করুন।');
            return false;
        }

        // 3. Check Color
        if (row.new && !row.color_id) {
            alert('রং প্রদান করুন।');
            return false;
        }

        // 4. Check Image (only for new shoe)
        if (row.new && !row.image && !row.image_preview) {
            alert('ছবি প্রদান করুন।');
            return false;
        }

        // 5. Check Pairs (Count)
        if (!row.count || parseInt(row.count) <= 0) {
            alert('জোড়া প্রদান করুন।');
            return false;
        }

        // 6. Check Size chart set (only if sizeChartEnabled is true)
        if (page.props.sizeChartEnabled) {
            const sizeSum = row.sizes.reduce((sum, sz) => sum + sz.quantity, 0);
            if (sizeSum === 0) {
                alert('সাইজ চার্ট সেট করুন।');
                return false;
            }
            if (sizeSum !== parseInt(row.count)) {
                alert('সাইজ চার্টে যোগ করা সংখ্যা মোট জোড়ার সংখ্যার সমান হতে হবে (' + row.count + ' জোড়া)');
                return false;
            }
        }

        // 7. Check Selling Price / Retail Price
        if (row.new && (!row.retail_price || parseFloat(row.retail_price) <= 0)) {
            alert('গায়ের দাম প্রদান করুন।');
            return false;
        }

        // 8. Check Purchase Price
        if (row.new && (!row.purchase_price || parseFloat(row.purchase_price) <= 0)) {
            alert('ভজন দাম প্রদান করুন।');
            return false;
        }

        // 9. Check Box selection
        if (row.new && props.boxes.length > 0 && !row.box_id) {
            alert('বক্স বাছাই করুন।');
            return false;
        }

        // 10. Check Bag selection
        if (row.new && props.bags.length > 0 && !row.bag_id) {
            alert('ব্যাগ বাছাই করুন।');
            return false;
        }
    }
    return true;
};

// Add purchase line
const addRow = () => {
    if (!validatePurchases()) {
        return;
    }
    form.purchases.push({
        new: false,
        shoe_id: '',
        category_id: '',
        category_name: '',
        color_id: '',
        color_name: '',
        image: null,
        image_preview: null,
        count: '',
        retail_price: '',
        purchase_price: '',
        box_id: '',
        bag_id: '',
        sizes: []
    });
    recalculateNewShoeIds();
};

// Remove purchase line
const removeRow = (index) => {
    if (form.purchases.length > 1) {
        form.purchases.splice(index, 1);
        recalculateNewShoeIds();
    }
};

// Handle New Checkbox change
const handleNewChange = (row) => {
    if (row.new) {
        row.category_id = '';
        row.category_name = '';
        row.color_id = '';
        row.color_name = '';
        row.image = null;
        row.image_preview = null;
        row.retail_price = '';
        row.purchase_price = '';
        row.box_id = '';
        row.bag_id = '';
        row.sizes = [];
        recalculateNewShoeIds();
        
        // Default pair count
        if (row.count === '') {
            row.count = 12;
        }
    } else {
        row.shoe_id = '';
        row.category_id = '';
        row.category_name = '';
        row.color_id = '';
        row.color_name = '';
        row.image = null;
        row.image_preview = null;
        row.retail_price = '';
        row.purchase_price = '';
        row.box_id = '';
        row.bag_id = '';
        row.sizes = [];
    }
};

// Load existing shoe data via Ajax
const loadShoeData = (row) => {
    if (!row.shoe_id || row.new) return;
    
    // Check if ID is already listed in another row to avoid duplicate inputs
    const same = form.purchases.filter(p => p.shoe_id === row.shoe_id);
    if (same.length > 1) {
        if (confirm('এই আইডির আরো জুতা কিনতে চান?')) {
            const firstRow = same[0];
            firstRow.count = (parseInt(firstRow.count) || 0) + 12;
        }
        row.shoe_id = '';
        return;
    }

    axios.get(route('ajax.shoe.show', { shoe: row.shoe_id }))
        .then(res => {
            if (res.data && res.data.id) {
                if (res.data.factory_id !== form.factory_id) {
                    alert('জুতাটি এই মহাজনের নয়।');
                    row.shoe_id = '';
                    return;
                }
                row.category_id = res.data.category_id;
                row.category_name = res.data.category_name;
                row.color_id = res.data.color_id;
                row.color_name = res.data.color_name;
                row.retail_price = parseFloat(res.data.retail_price).toFixed(2);
                row.purchase_price = parseFloat(res.data.purchase_price).toFixed(2);
                row.box_id = res.data.box_id;
                row.bag_id = res.data.bag_id;
                row.image_preview = res.data.image ? `/images/small-thumbnail/${res.data.image}` : null;
                if (row.count === '') {
                    row.count = 12;
                }
            } else {
                alert('এই আইডির কোন জুতা নেই।');
                row.shoe_id = '';
            }
        })
        .catch(err => {
            alert('এই আইডির কোন জুতা নেই।');
            row.shoe_id = '';
        });
};

// Handle row file upload
const handleImageUpload = (event, row) => {
    const file = event.target.files[0];
    if (file) {
        row.image = file;
        row.image_preview = URL.createObjectURL(file);
    }
};

// Size Chart Modal states
const showSizeChartModal = ref(false);
const activeRowIndex = ref(null);
const modalSizesList = ref([]);
const activeSortCount = ref(2);

const openSizeChart = (index) => {
    const row = form.purchases[index];
    if (!row.count) {
        alert('জোড়া প্রদান করুন');
        return;
    }
    if (!row.category_id) {
        alert('ক্যাটাগরি প্রদান করুন');
        return;
    }

    activeRowIndex.value = index;

    // Fetch sizes for this category
    axios.get(route('get.category-sizes', { category_id: row.category_id }))
        .then(res => {
            const dbSizes = res.data || [];
            
            // Map existing sizes state if previously configured
            modalSizesList.value = dbSizes.map(size => {
                const existingSize = row.sizes.find(s => s.size_id === size.id);
                const sorts = existingSize ? [...existingSize.sorts] : Array(activeSortCount.value).fill('');
                return {
                    size_id: size.id,
                    name: size.name,
                    quantity: existingSize ? existingSize.quantity : 0,
                    sorts: sorts
                };
            });
            
            showSizeChartModal.value = true;
        })
        .catch(err => {
            alert('সাইজ চার্ট লোড করা যায়নি।');
        });
};

// Add Sort Column
const addSort = () => {
    activeSortCount.value += 2;
    modalSizesList.value.forEach(sz => {
        sz.sorts.push('', '');
    });
};

// Delete Sort Column
const deleteSort = () => {
    if (activeSortCount.value > 2) {
        activeSortCount.value -= 2;
        modalSizesList.value.forEach(sz => {
            sz.sorts.splice(-2);
        });
        calculateSizeQuantities();
    }
};

// Calculate size row quantities
const calculateSizeQuantities = () => {
    modalSizesList.value.forEach(sz => {
        const rowSum = sz.sorts.reduce((sum, val) => sum + (parseInt(val) || 0), 0);
        sz.quantity = rowSum;
    });
};

// Total size quantities sum
const totalSizesSum = computed(() => {
    return modalSizesList.value.reduce((sum, sz) => sum + sz.quantity, 0);
});

// Save Size Chart details to row state
const saveSizeChart = () => {
    const row = form.purchases[activeRowIndex.value];
    if (totalSizesSum.value !== parseInt(row.count)) {
        alert('সাইজ চার্টে যোগ করা সংখ্যা মোট জোড়ার সংখ্যার সমান হতে হবে (' + row.count + ' জোড়া)');
        return;
    }
    
    // Save to the row's sizes array
    row.sizes = modalSizesList.value.map(sz => ({
        size_id: sz.size_id,
        name: sz.name,
        quantity: sz.quantity,
        sorts: sz.sorts.map(v => parseInt(v) || 0)
    }));

    showSizeChartModal.value = false;
};

// Calculate subtotal of a row
const getRowSubtotal = (row) => {
    const count = parseInt(row.count) || 0;
    const price = parseFloat(row.purchase_price) || 0;
    return (count * price) / 12;
};

// Total Payable computation
const totalPayable = computed(() => {
    return form.purchases.reduce((sum, row) => sum + getRowSubtotal(row), 0);
});

// Watch payment method to set cheque field states
const isCheque = computed(() => form.payment_method === 'cheque');

watch(isCheque, (newVal) => {
    if (!newVal) {
        form.cheque_no = '';
        form.cheque_date = '';
    }
});

const isShowingPreview = ref(false);

const getCategoryFullName = (id) => {
    const cat = props.categories.find(c => c.id === parseInt(id));
    return cat ? cat.full_name : '';
};

const getColorName = (id) => {
    const col = props.colors.find(c => c.id === parseInt(id));
    return col ? col.name : '';
};

const toFixed = (val) => {
    return parseFloat(val || 0).toFixed(2);
};

const togglePreview = () => {
    if (validatePurchases()) {
        isShowingPreview.value = true;
    }
};

const submitForm = () => {
    if (validatePurchases()) {
        form.post(route('purchase.store'));
    }
};
</script>

<template>
    <Head :title="t('shoe_purchase')" />

    <AuthenticatedLayout>
        <!-- Dynamic Datalists -->
        <datalist id="category-list">
            <option v-for="cat in categories" :key="cat.id" :value="cat.full_name" />
        </datalist>
        <datalist id="color-list">
            <option v-for="col in colors" :key="col.id" :value="col.name" />
        </datalist>
        <datalist id="factory-list">
            <option v-for="fac in factories" :key="fac.id" :value="fac.name" />
        </datalist>

        <!-- Form Screen -->
        <div v-if="!isShowingPreview" class="bg-white dark:bg-slate-800 rounded border border-[#ced4da] dark:border-slate-700 border-t-[3px] border-t-[#007bff] shadow-sm overflow-hidden w-full max-w-none mx-auto">
            <!-- Card Header -->
            <div class="px-5 py-4 bg-white dark:bg-slate-900 border-b border-[#ced4da]/80 dark:border-slate-700 flex items-center justify-between">
                <h1 class="text-[28px] font-bold text-[#212529] dark:text-white font-nikosh">
                    {{ t('shoe_purchase') }}
                </h1>
            </div>

            <!-- Card Body -->
            <div class="p-5 font-nikosh">
                <form @submit.prevent="submitForm" autocomplete="off">
                     <!-- Mahajon & Memo No Bar -->
                    <div class="grid grid-cols-12 gap-4 items-center mb-6 w-full">
                        <!-- Mahajon Input Group -->
                        <div class="col-span-12 md:col-span-9 flex items-stretch border border-[#ced4da] dark:border-slate-700 rounded h-[38px] bg-white dark:bg-slate-900">
                            <span class="bg-[#e9ecef] dark:bg-slate-950 border-r border-[#ced4da] dark:border-slate-700 px-4 flex items-center text-sm text-[#495057] dark:text-slate-350 font-bold select-none whitespace-nowrap">
                                {{ t('suppliers') }}
                            </span>
                            <input
                                v-model="form.memo_to_name"
                                type="text"
                                list="factory-list"
                                required
                                @blur="handleFactorySelect"
                                placeholder="মহাজন নির্বাচন করুন"
                                class="flex-1 border-0 focus:ring-0 px-3 bg-transparent text-slate-850 dark:text-slate-200 text-sm font-bold outline-none cursor-pointer h-full"
                            />
                        </div>

                        <!-- Memo No Group -->
                        <div class="col-span-12 md:col-span-3 flex md:justify-end w-full">
                            <div class="flex items-stretch border border-[#ced4da] dark:border-slate-700 rounded h-[38px] bg-white dark:bg-slate-900">
                                <span class="bg-[#e9ecef] dark:bg-slate-950 border-r border-[#ced4da] dark:border-slate-700 px-3 flex items-center text-sm text-[#495057] dark:text-slate-400 select-none whitespace-nowrap">
                                    {{ t('memo_no') }}
                                </span>
                                <div class="flex items-center justify-center font-bold text-slate-800 dark:text-white text-sm bg-white dark:bg-slate-900 px-4 font-english">
                                    {{ memoNo }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Grid Fieldset -->
                    <fieldset :disabled="form.factory_id === ''" class="border border-slate-200 dark:border-slate-700 rounded overflow-hidden mb-6 disabled:opacity-60 transition">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="border-b border-[#ced4da] dark:border-slate-700 text-[#212529] dark:text-slate-300 font-bold bg-white dark:bg-slate-900">
                                        <th class="px-2 py-3 w-12 min-w-[48px] text-center"></th>
                                        <th class="px-2 py-3 w-[66px] min-w-[66px] text-center"></th>
                                        <th class="px-2 py-3 w-16 text-center select-none font-bold text-[#212529] dark:text-slate-300">{{ t('new') }}</th>
                                        <th class="px-2 py-3 w-[10%] text-center select-none font-bold text-[#212529] dark:text-slate-300">{{ t('id') }}</th>
                                        <th class="px-2 py-3 w-[16%] text-center select-none font-bold text-[#212529] dark:text-slate-300">{{ t('type') }}</th>
                                        <th class="px-2 py-3 w-[16%] text-center select-none font-bold text-[#212529] dark:text-slate-300">{{ t('color') }}</th>
                                        <th class="px-2 py-3 w-14 text-center select-none font-bold text-[#212529] dark:text-slate-300">{{ t('picture') }}</th>
                                        <th class="px-2 py-3 w-[10%] text-center select-none font-bold text-[#212529] dark:text-slate-300">{{ t('pair') }}</th>
                                        <th class="px-2 py-3 w-12 text-center select-none font-bold text-[#212529] dark:text-slate-300">{{ t('size') }}</th>
                                        <th class="px-2 py-3 w-[10%] text-center select-none font-bold text-[#212529] dark:text-slate-300">{{ t('selling_price') }}</th>
                                        <th class="px-2 py-3 w-[10%] text-center select-none font-bold text-[#212529] dark:text-slate-300">{{ t('purchase_price') }}</th>
                                        <th class="px-2 py-3 w-[12%] text-right pr-4 select-none font-bold text-[#212529] dark:text-slate-300">{{ t('total_price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(row, idx) in form.purchases" :key="idx">
                                        <!-- Main Row -->
                                        <tr class="border-b border-[#ced4da]/60 dark:border-slate-700 hover:bg-slate-50/20 align-middle">
                                            <!-- Remove Row Button (Bootstrap Red btn-danger style) -->
                                            <td class="px-2 py-3 text-center w-12 min-w-[48px]">
                                                <button
                                                    type="button"
                                                    @click="removeRow(idx)"
                                                    class="bg-[#dc3545] border border-[#dc3545] text-white w-[38px] h-[38px] rounded flex items-center justify-center font-bold focus:outline-none cursor-pointer transition shadow hover:bg-[#c82333] mx-auto"
                                                    :class="form.purchases.length <= 1 ? 'opacity-55 cursor-not-allowed' : ''"
                                                    :disabled="form.purchases.length <= 1"
                                                >
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                            </td>
                                            <!-- Thumbnail Image Preview (Square-ish border-radius bootstrap style) -->
                                            <td class="px-2 py-3 text-center w-[66px] min-w-[66px]">
                                                <img :src="row.image_preview || '/img/shoe.png'" class="w-[50px] h-[50px] object-cover rounded border border-[#ced4da] dark:border-slate-700 mx-auto bg-slate-50" />
                                            </td>
                                            <!-- New Checkbox -->
                                            <td class="px-2 py-3 text-center">
                                                <input 
                                                    type="checkbox" 
                                                    v-model="row.new" 
                                                    @change="handleNewChange(row)"
                                                    class="form-checkbox h-4 w-4 text-blue-600 rounded border-slate-350 focus:ring-blue-500 cursor-pointer"
                                                />
                                            </td>
                                            <!-- Shoe ID (Form-control style) -->
                                            <td class="px-2 py-3">
                                                <input
                                                    v-model="row.shoe_id"
                                                    type="text"
                                                    required
                                                    :readonly="row.new"
                                                    @blur="loadShoeData(row)"
                                                    @input="handleAlphanumericInput($event, row, 'shoe_id')"
                                                    placeholder=""
                                                    class="w-full h-[38px] text-center rounded border border-[#ced4da] dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white text-sm focus:border-[#80bdff] focus:ring-1 focus:ring-[#80bdff] font-bold outline-none font-english"
                                                    :class="row.new ? 'bg-[#e9ecef] dark:bg-slate-950 text-gray-500' : ''"
                                                 />
                                            </td>
                                            <!-- Category -->
                                            <td class="px-2 py-3">
                                                <input
                                                    v-model="row.category_name"
                                                    type="text"
                                                    list="category-list"
                                                    required
                                                    :disabled="!row.new"
                                                    @blur="handleCategoryInput(row)"
                                                    placeholder=""
                                                    class="w-full h-[38px] rounded border border-[#ced4da] dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm px-2 text-center focus:border-[#80bdff] focus:ring-1 focus:ring-[#80bdff] disabled:bg-[#e9ecef] dark:disabled:bg-slate-950 disabled:text-gray-500 font-medium outline-none"
                                                />
                                            </td>
                                            <!-- Color -->
                                            <td class="px-2 py-3">
                                                <input
                                                    v-model="row.color_name"
                                                    type="text"
                                                    list="color-list"
                                                    required
                                                    :disabled="!row.new"
                                                    @blur="handleColorInput(row)"
                                                    placeholder=""
                                                    class="w-full h-[38px] rounded border border-[#ced4da] dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-sm px-2 text-center focus:border-[#80bdff] focus:ring-1 focus:ring-[#80bdff] disabled:bg-[#e9ecef] dark:disabled:bg-slate-950 disabled:text-gray-500 font-medium outline-none"
                                                />
                                            </td>
                                            <!-- Picture File Input camera button -->
                                            <td class="px-2 py-3 text-center">
                                                <label 
                                                    v-if="row.new"
                                                    :for="`input-image-${idx}`" 
                                                    class="text-white w-[38px] h-[38px] rounded flex items-center justify-center cursor-pointer transition shadow text-xs border mx-auto"
                                                    :class="row.image || row.image_preview ? 'bg-[#28a745] hover:bg-[#218838] border-[#28a745]' : 'bg-[#007bff] hover:bg-[#0069d9] border-[#007bff]'"
                                                >
                                                    <i class="fas fa-camera"></i>
                                                </label>
                                                <div 
                                                    v-else
                                                    class="text-white w-[38px] h-[38px] rounded flex items-center justify-center transition shadow bg-[#007bff] hover:bg-[#0069d9] text-xs border border-[#007bff] cursor-not-allowed opacity-50 mx-auto"
                                                >
                                                    <i class="fas fa-camera"></i>
                                                </div>
                                                <input 
                                                    v-if="row.new"
                                                    :id="`input-image-${idx}`"
                                                    type="file" 
                                                    accept="image/*"
                                                    @change="handleImageUpload($event, row)"
                                                    class="hidden" 
                                                 />
                                            </td>
                                            <!-- Pairs Count -->
                                            <td class="px-2 py-3">
                                                <input
                                                    v-model="row.count"
                                                    type="text"
                                                    required
                                                    @input="handleIntegerInput($event, row, 'count')"
                                                    class="w-full h-[38px] text-center rounded border border-[#ced4da] dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white text-sm focus:border-[#80bdff] focus:ring-1 focus:ring-[#80bdff] font-bold outline-none font-english"
                                                />
                                            </td>
                                             <!-- Size trigger -->
                                             <td class="px-2 py-3 text-center text-sm select-none">
                                                 <template v-if="$page.props.sizeChartEnabled">
                                                     <button
                                                         type="button"
                                                         @click="openSizeChart(idx)"
                                                         class="h-[38px] w-[38px] rounded flex items-center justify-center cursor-pointer transition shadow-sm border mx-auto"
                                                         :class="row.sizes.length > 0 ? 'bg-[#28a745] border-[#28a745] text-white hover:bg-[#218838]' : 'bg-[#007bff] border-[#007bff] text-white hover:bg-[#0069d9]'"
                                                     >
                                                         <i class="fas fa-ruler"></i>
                                                     </button>
                                                 </template>
                                                 <template v-else>
                                                     <span class="text-slate-850 dark:text-slate-200 font-medium">বন্ধ</span>
                                                 </template>
                                             </td>
                                             <!-- Selling Price -->
                                             <td class="px-2 py-3">
                                                 <input
                                                     v-model="row.retail_price"
                                                     type="text"
                                                     required
                                                     :disabled="!row.new"
                                                     @input="handleDecimalInput($event, row, 'retail_price')"
                                                     class="w-full h-[38px] text-center rounded border border-[#ced4da] dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white text-sm focus:border-[#80bdff] focus:ring-1 focus:ring-[#80bdff] disabled:bg-[#e9ecef] dark:disabled:bg-slate-950 disabled:text-gray-500 font-bold outline-none font-english"
                                                 />
                                             </td>
                                             <!-- Purchase Price -->
                                             <td class="px-2 py-3">
                                                 <input
                                                     v-model="row.purchase_price"
                                                     type="text"
                                                     required
                                                     :disabled="!row.new"
                                                     @input="handleDecimalInput($event, row, 'purchase_price')"
                                                     class="w-full h-[38px] text-center rounded border border-[#ced4da] dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white text-sm focus:border-[#80bdff] focus:ring-1 focus:ring-[#80bdff] disabled:bg-[#e9ecef] dark:disabled:bg-slate-950 disabled:text-gray-500 font-bold outline-none font-english"
                                                 />
                                             </td>
                                             <!-- Subtotal -->
                                             <td class="px-2 py-3 text-right pr-4">
                                                 <div class="h-[38px] px-2 border border-[#ced4da] dark:border-slate-700 bg-[#e9ecef] dark:bg-slate-950 text-[#495057] dark:text-gray-400 rounded flex items-center justify-end font-bold text-sm select-none font-english">
                                                     {{ getRowSubtotal(row).toFixed(2) }}
                                                 </div>
                                             </td>
                                         </tr>

                                        <!-- Sub-row for Box/Bag radios -->
                                        <tr class="bg-white dark:bg-slate-800 border-b border-[#ced4da]/60 dark:border-slate-700 text-[13px]">
                                            <td colspan="4"></td>
                                            <td colspan="8" class="px-3 py-2 text-start">
                                                <div class="flex flex-col gap-1 text-slate-700 dark:text-white">
                                                    <!-- Boxes -->
                                                    <div class="flex items-center gap-1 flex-wrap">
                                                        <span class="font-bold mr-1">{{ t('box') }}:</span>
                                                        <label v-for="b in boxes" :key="b.id" class="inline-flex items-center gap-1.5 cursor-pointer select-none mr-3">
                                                            <input 
                                                                type="radio" 
                                                                :name="`box_id_${idx}`" 
                                                                :value="b.id" 
                                                                v-model="row.box_id"
                                                                :disabled="!row.new"
                                                                class="form-radio h-3.5 w-3.5 text-blue-600 focus:ring-0 focus:ring-offset-0 disabled:opacity-60"
                                                            />
                                                            <span>{{ b.name }}</span>
                                                        </label>
                                                    </div>
                                                    <!-- Bags -->
                                                    <div class="flex items-center gap-1 flex-wrap">
                                                        <span class="font-bold mr-1">{{ t('bag') }}:</span>
                                                        <label v-for="bg in bags" :key="bg.id" class="inline-flex items-center gap-1.5 cursor-pointer select-none mr-3">
                                                            <input 
                                                                type="radio" 
                                                                :name="`bag_id_${idx}`" 
                                                                :value="bg.id" 
                                                                v-model="row.bag_id"
                                                                :disabled="!row.new"
                                                                class="form-radio h-3.5 w-3.5 text-blue-600 focus:ring-0 focus:ring-offset-0 disabled:opacity-60"
                                                            />
                                                            <span>{{ bg.name }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <!-- Add Row, Total sum -->
                                    <tr class="border-b border-[#ced4da] dark:border-slate-700 bg-white dark:bg-slate-900">
                                        <td class="px-2 py-3.5 text-center">
                                            <button
                                                type="button"
                                                @click="addRow"
                                                class="bg-[#28a745] border border-[#28a745] text-white w-[38px] h-[38px] rounded flex items-center justify-center font-bold text-lg focus:outline-none cursor-pointer transition shadow hover:bg-[#218838] mx-auto"
                                            >
                                                <i class="fas fa-plus text-sm"></i>
                                            </button>
                                        </td>
                                        <td colspan="10" class="px-2 py-3.5 text-right font-bold text-[#212529] dark:text-white">
                                            {{ t('total') }}
                                        </td>
                                        <td class="px-2 py-3.5 text-right pr-4">
                                            <div class="h-[38px] px-3 border border-[#ced4da] dark:border-slate-700 bg-[#e9ecef] dark:bg-slate-950 text-[#495057] dark:text-white rounded flex items-center justify-end font-bold text-sm select-none font-english">
                                                {{ totalPayable.toFixed(2) }}
                                            </div>
                                        </td>
                                    </tr>
 
                                    <!-- Payment options -->
                                    <tr>
                                        <td></td>
                                        <td colspan="10" class="px-3 py-5">
                                            <div class="flex flex-wrap items-center justify-end gap-x-6 gap-y-3 text-sm font-bold">
                                                <!-- Payment Type -->
                                                <div class="flex items-center gap-2">
                                                    <label class="text-slate-650 dark:text-slate-300 whitespace-nowrap">{{ t('payment') }}</label>
                                                    <select
                                                        v-model="form.payment_method"
                                                        required
                                                        class="h-[38px] rounded border border-[#ced4da] dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-[#80bdff] focus:ring focus:ring-[#80bdff]/25 text-sm py-1.5 cursor-pointer"
                                                        style="width: 200px"
                                                    >
                                                        <option v-for="b in bankAccounts" :key="b.id" :value="b.id">
                                                            {{ b.name }}
                                                        </option>
                                                    </select>
                                                </div>
 
                                                <!-- Cheque No -->
                                                <div class="flex items-center gap-2">
                                                    <label class="text-slate-650 dark:text-slate-300 whitespace-nowrap">{{ t('check_no') }}</label>
                                                    <input
                                                        v-model="form.cheque_no"
                                                        type="text"
                                                        :disabled="!isCheque"
                                                        required
                                                        class="h-[38px] rounded border border-[#ced4da] dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white disabled:bg-[#e9ecef] dark:disabled:bg-slate-950 focus:border-[#80bdff] focus:ring focus:ring-[#80bdff]/25 text-sm w-36 px-2.5"
                                                    />
                                                </div>
 
                                                <!-- Cheque Due Date -->
                                                <div class="flex items-center gap-2">
                                                    <label class="text-slate-650 dark:text-slate-300 whitespace-nowrap">{{ t('payment_date') }}</label>
                                                    <input
                                                        v-model="form.cheque_date"
                                                        type="date"
                                                        :disabled="!isCheque"
                                                        required
                                                        class="h-[38px] rounded border border-[#ced4da] dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-white disabled:bg-[#e9ecef] dark:disabled:bg-slate-950 focus:border-[#80bdff] focus:ring focus:ring-[#80bdff]/25 text-sm w-44 px-2.5 font-english"
                                                    />
                                                </div>
                                            </div>
                                        </td>
                                        <!-- Payment Amount -->
                                        <td class="px-3 py-5 text-right pr-4">
                                            <input
                                                v-model.number="form.payment_amount"
                                                type="text"
                                                required
                                                @input="handleDecimalInput($event, form, 'payment_amount')"
                                                class="w-full h-[38px] text-right font-bold rounded border border-[#ced4da] dark:border-slate-700 dark:bg-slate-900 text-slate-850 dark:text-white focus:border-[#80bdff] focus:ring focus:ring-[#80bdff]/25 text-sm pr-3 outline-none font-english"
                                            />
                                        </td>
                                    </tr>
 
                                    <!-- Action Buttons -->
                                    <tr>
                                        <td colspan="10"></td>
                                        <td class="px-2 py-3 text-right pr-2">
                                            <button
                                                type="button"
                                                @click="togglePreview"
                                                class="bg-[#6c757d] hover:bg-[#5a6268] border border-[#6c757d] text-white font-bold px-6 py-2 h-[38px] rounded text-sm transition shadow-sm cursor-pointer w-full text-center flex items-center justify-center"
                                            >
                                                {{ t('preview') }}
                                            </button>
                                        </td>
                                        <td class="px-2 py-3 text-right pr-4">
                                            <button
                                                type="submit"
                                                :disabled="form.processing"
                                                class="bg-[#007bff] hover:bg-[#0069d9] border border-[#007bff] text-white font-bold px-6 py-2 h-[38px] rounded text-sm transition shadow-sm cursor-pointer disabled:opacity-50 inline-flex items-center justify-center gap-1.5 w-full text-center"
                                            >
                                                সাবমিট
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

        <!-- Preview UI -->
        <div v-else class="border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8 w-full font-nikosh">
            <!-- Header Title -->
            <h1 class="text-[28px] font-bold text-[#212529] dark:text-white font-nikosh mb-3">
                {{ t('shoe_purchase_receipt') }}
            </h1>

            <!-- Receipt Metadata Table -->
            <table class="w-full border-collapse border border-[#dee2e6] dark:border-slate-700 mb-5 text-[#212529] dark:text-slate-200 text-sm font-nikosh">
                <tbody>
                    <tr class="bg-[#f8f9fa] dark:bg-slate-900 border-b border-[#dee2e6] dark:border-slate-700">
                        <td class="p-3 w-[70%] text-left align-middle border-r border-[#dee2e6] dark:border-slate-700">
                            {{ t('memo_no') }}: <strong class="font-black font-english">{{ memoNo }}</strong>
                        </td>
                        <td class="p-3 w-[30%] text-right align-middle font-english">
                            {{ t('date') }}: <strong>{{ currentDateFormatted }}</strong>
                        </td>
                    </tr>
                    <tr class="bg-white dark:bg-slate-800">
                        <td class="p-3 w-[70%] text-left align-top leading-relaxed border-r border-[#dee2e6] dark:border-slate-700">
                            {{ t('name') }}: <strong class="font-bold">{{ selectedFactoryName }}</strong><br />
                            {{ t('address') }}: <strong class="font-bold">{{ selectedFactoryAddress || '-' }}</strong><br />
                            {{ t('mobile_no') }}: <strong class="font-bold font-english">{{ selectedFactoryMobile || '-' }}</strong>
                        </td>
                        <td class="p-3 w-[30%] text-right align-top">
                            <div class="flex items-center justify-end gap-1.5 pt-1.5">
                                <button
                                    type="button"
                                    @click="isShowingPreview = false"
                                    class="bg-[#6c757d] hover:bg-[#5a6268] border border-[#6c757d] text-white font-bold px-3 py-1.5 rounded text-xs transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer h-[32px]"
                                >
                                    ফিরে যান (Back)
                                </button>
                                <button
                                    type="button"
                                    @click="submitForm"
                                    :disabled="form.processing"
                                    class="bg-[#007bff] hover:bg-[#0069d9] border border-[#007bff] text-white font-bold px-3 py-1.5 rounded text-xs transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer disabled:opacity-50 h-[32px]"
                                >
                                    দাখিল করুন (Submit)
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Items Table -->
            <div class="border border-slate-200 dark:border-slate-700 rounded overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 font-black bg-[#f7f7f7]/60 dark:bg-slate-900/30">
                                <th class="px-4 py-3 w-12 text-center">#</th>
                                <th class="px-4 py-3 w-16 text-center">{{ t('picture') }}</th>
                                <th class="px-4 py-3 w-[12%] text-center">{{ t('id') }}</th>
                                <th class="px-4 py-3 w-[18%] text-center">{{ t('type') }}</th>
                                <th class="px-4 py-3 w-[15%] text-center">{{ t('color') }}</th>
                                <th class="px-4 py-3 w-[12%] text-center">{{ t('selling_price') }}</th>
                                <th class="px-4 py-3 w-[12%] text-center">{{ t('purchase_price') }}</th>
                                <th class="px-4 py-3 w-[10%] text-center">{{ t('pair') }}</th>
                                <th class="px-4 py-3 w-[12%] text-right pr-6">{{ t('total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(pe, index) in form.purchases" :key="index">
                                <tr class="border-b border-slate-200 dark:border-slate-700 align-middle">
                                    <td class="px-4 py-3 text-center font-bold text-slate-400 font-english">{{ index + 1 }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <img 
                                            :src="pe.image_preview || '/img/shoe.png'" 
                                            class="w-12 h-12 object-cover rounded border border-slate-200 dark:border-slate-700 mx-auto"
                                        />
                                    </td>
                                    <td class="px-4 py-3 text-center font-black text-blue-650 dark:text-blue-400 font-english">
                                        {{ pe.shoe_id }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-semibold text-slate-800 dark:text-slate-200">
                                        {{ pe.category_name || getCategoryFullName(pe.category_id) }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-slate-700 dark:text-slate-350">
                                        {{ pe.color_name || getColorName(pe.color_id) }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-success font-semibold font-english">
                                        {{ toFixed(pe.retail_price) }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-danger font-semibold font-english">
                                        {{ toFixed(pe.purchase_price) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-800 dark:text-white font-english">
                                        {{ pe.count }}
                                    </td>
                                    <td class="px-4 py-3 text-right pr-6 font-black text-slate-900 dark:text-white font-english">
                                        {{ getRowSubtotal(pe).toFixed(2) }}
                                    </td>
                                </tr>
                                <tr v-if="pe.sizes && pe.sizes.length > 0" class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/20 dark:bg-slate-900/5">
                                    <td colspan="3"></td>
                                    <td colspan="6" class="px-4 py-2 text-start">
                                        <div class="flex items-center gap-1.5 flex-wrap text-xs text-slate-500 dark:text-slate-400">
                                            <span class="font-bold text-slate-655 dark:text-slate-300">সাইজ বিবরণ (Sizes):</span>
                                            <span 
                                                v-for="sz in pe.sizes" 
                                                :key="sz.size_id"
                                                class="px-2.5 py-0.5 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold"
                                            >
                                                {{ sz.name }}: <span class="text-blue-650 dark:text-blue-400 font-black font-english">{{ sz.quantity }}</span>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/10 font-bold text-slate-800 dark:text-white">
                                <td colspan="7" class="px-4 py-3 text-right">{{ t('total') }} =</td>
                                <td class="px-4 py-3 text-center font-black font-english">
                                    {{ form.purchases.reduce((sum, p) => sum + (parseInt(p.count) || 0), 0) }}
                                </td>
                                <td class="px-4 py-3 text-right pr-6 font-black font-english">{{ totalPayable.toFixed(2) }}</td>
                            </tr>
                            <tr class="font-bold text-slate-700 dark:text-slate-300">
                                <td colspan="8" class="px-4 py-3 text-right text-slate-500 dark:text-slate-400">{{ t('payment') }} =</td>
                                <td class="px-4 py-3 text-right pr-6 font-black text-green-600 dark:text-green-400 font-english">
                                    {{ toFixed(form.payment_amount) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Size Chart Modal -->
        <div class="fixed inset-0 z-50 flex items-start justify-center pt-8 p-4 bg-slate-900/60 backdrop-blur-sm overflow-x-hidden overflow-y-auto outline-none" v-if="showSizeChartModal">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="showSizeChartModal = false"></div>
            <div class="relative w-full max-w-4xl mx-auto z-50 p-4">
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <div class="flex items-center gap-3">
                            <h5 class="text-lg font-bold text-slate-850 dark:text-white font-nikosh">
                                সাইজ চার্ট (Size Chart)
                            </h5>
                            <button type="button" @click="addSort" class="bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-1 rounded text-xs transition cursor-pointer">সর্ট যোগ করুন</button>
                            <button type="button" @click="deleteSort" class="bg-red-500 hover:bg-red-600 text-white px-2.5 py-1 rounded text-xs transition cursor-pointer">সর্ট মুছুন</button>
                        </div>
                        <button type="button" class="p-1 bg-transparent border-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-3xl leading-none font-semibold outline-none focus:outline-none cursor-pointer" @click="showSizeChartModal = false">×</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="relative p-6 flex-auto font-nikosh">
                        <div class="table-responsive max-h-[60vh] overflow-y-auto">
                            <table class="table table-bordered text-center align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>সাইজ</th>
                                        <th>পরিমাণ</th>
                                        <th v-for="n in activeSortCount" :key="n">{{ n }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(sz, szIdx) in modalSizesList" :key="szIdx">
                                        <td class="font-bold font-english">{{ sz.name }}</td>
                                        <td>
                                            <input 
                                                v-model.number="sz.quantity"
                                                type="number" 
                                                readonly
                                                class="form-control text-center bg-slate-50 dark:bg-slate-900 border-slate-200 text-slate-700 font-bold font-english" 
                                            />
                                        </td>
                                        <td v-for="(sVal, sIdx) in activeSortCount" :key="sIdx">
                                            <input 
                                                v-model="sz.sorts[sIdx]"
                                                type="text" 
                                                @input="handleIntegerInputArray($event, sz.sorts, sIdx); calculateSizeQuantities()"
                                                class="form-control text-center border-slate-350 dark:border-slate-700 dark:bg-slate-900 text-slate-850 dark:text-white font-english"
                                            />
                                        </td>
                                    </tr>
                                    <tr v-if="modalSizesList.length === 0">
                                        <td :colspan="2 + activeSortCount" class="text-center p-4 text-muted">এই ক্যাটাগরির কোনো সাইজ পাওয়া যায়নি!</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal Total footer -->
                        <div class="flex justify-end gap-2 items-center mt-4 text-sm font-black text-slate-850 dark:text-white">
                            <span>মোট পরিমাণ (Size Chart Total):</span>
                            <span class="px-4 py-1.5 rounded border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 font-black font-english">
                                {{ totalSizesSum }}
                            </span>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end p-5 border-t border-solid border-slate-200 dark:border-slate-700 rounded-b gap-3">
                        <button type="button" @click="showSizeChartModal = false" class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-4 py-2 rounded text-sm transition">বন্ধ করুন</button>
                        <button type="button" @click="saveSizeChart" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded text-sm transition">সেভ করুন (Save)</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-nikosh {
    font-family: 'Nikosh', 'SolaimanLipi', sans-serif;
}
.table-responsive {
    overflow-x: auto;
}
/* Hide HTML5 number input spinner arrows */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
.font-english,
input[type="number"],
input[type="date"],
.form-control {
    font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
}
</style>
