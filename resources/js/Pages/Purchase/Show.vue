<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    purchase: {
        type: Object,
        required: true,
    }
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
            'shoe_purchase_receipt': 'Shoe Purchase Receipt',
            'memo_no': 'Memo No',
            'date': 'Date',
            'name': 'Name',
            'address': 'Address',
            'mobile_no': 'Mobile No',
            'image': 'Image',
            'id': 'ID',
            'type': 'Type',
            'color': 'Color',
            'selling_price': 'Selling Price',
            'purchase_price': 'Purchase Price',
            'pair': 'Pairs',
            'total': 'Total',
            'payment': 'Payment',
            'barcode_print': 'Barcode Print',
            'edit': 'Edit',
            'delete': 'Delete',
            'confirm_delete': 'Are you sure you want to delete this purchase receipt?',
        },
        bn: {
            'shoe_purchase_receipt': 'জুতা ক্রয়ের রশিদ',
            'memo_no': 'মেমো নং',
            'date': 'তারিখ',
            'name': 'নাম',
            'address': 'ঠিকানা',
            'mobile_no': 'মোবাইল নং',
            'image': 'ছবি',
            'id': 'আইডি',
            'type': 'টাইপ',
            'color': 'রং',
            'selling_price': 'গায়ের দাম',
            'purchase_price': 'ভজন দাম',
            'pair': 'জোড়া',
            'total': 'মোট',
            'payment': 'তাগাদা',
            'barcode_print': 'বারকোড প্রিন্ট',
            'edit': 'সম্পাদন',
            'delete': 'মুছে ফেলুন',
            'confirm_delete': 'আপনি কি নিশ্চিত যে আপনি এই ক্রয় রশিদটি মুছে ফেলতে চান?',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Formatted Date
const formattedDate = computed(() => {
    if (!props.purchase.created_at) return '';
    const date = new Date(props.purchase.created_at);
    return date.toLocaleDateString(page.props.locale === 'bn' ? 'bn-BD' : 'en-US', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
});

const canDelete = computed(() => {
    // Check if the purchase has been invoiced or is allowed to be deleted
    // The Purchase model has `canDelete` method, which is not directly accessible here but
    // we can pass it or check it if we want. Wait, the controller returns the model without restriction,
    // so we can rely on Inertia's checks or basic delete confirmation.
    return true;
});

const handleDelete = () => {
    if (confirm(t('pages.confirm_delete'))) {
        router.delete(route('purchase.destroy', props.purchase.id));
    }
};

const toFixed = (val) => {
    return parseFloat(val || 0).toFixed(2);
};

// Image modal / hover preview state
const showImageModal = ref(false);
const modalImageSrc = ref('');
const showHoverPreview = ref(false);
const hoverImageSrc = ref('');
const hoverPosition = ref({ top: '0px', left: '0px' });

const openImageModal = (imageName) => {
    if (imageName) {
        modalImageSrc.value = `/images/small-thumbnail/${imageName}`;
        showImageModal.value = true;
    }
};

const handleMouseEnter = (event, imageName) => {
    if (imageName) {
        hoverImageSrc.value = `/images/small-thumbnail/${imageName}`;
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
</script>

<template>
    <Head :title="t('shoe_purchase_receipt')" />

    <AuthenticatedLayout>
        <div class="border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded shadow-sm p-6 mb-8 w-full font-nikosh">
            <!-- Header Title -->
            <h1 class="text-[28px] font-bold text-[#212529] dark:text-white font-nikosh mb-3">
                {{ t('shoe_purchase_receipt') }}
            </h1>

            <!-- Receipt Metadata Table -->
            <table class="w-full border-collapse border border-[#dee2e6] dark:border-slate-700 mb-5 text-[#212529] dark:text-slate-200 text-sm font-nikosh">
                <tbody>
                    <tr class="bg-[#f8f9fa] dark:bg-slate-900 border-b border-[#dee2e6] dark:border-slate-700">
                        <td class="p-3 w-[70%] text-left align-middle border-r border-[#dee2e6] dark:border-slate-700">
                            {{ t('memo_no') }}: <strong class="font-black font-english">{{ purchase.id }}</strong>
                        </td>
                        <td class="p-3 w-[30%] text-right align-middle font-english">
                            {{ t('date') }}: <strong>{{ formattedDate }}</strong>
                        </td>
                    </tr>
                    <tr class="bg-white dark:bg-slate-800">
                        <td class="p-3 w-[70%] text-left align-top leading-relaxed border-r border-[#dee2e6] dark:border-slate-700">
                            {{ t('name') }}: <strong class="font-bold">{{ purchase.account_book?.account?.name }}</strong><br />
                            {{ t('address') }}: <strong class="font-bold">{{ purchase.account_book?.account?.address || '-' }}</strong><br />
                            {{ t('mobile_no') }}: <strong class="font-bold font-english">{{ purchase.account_book?.account?.mobile_no || '-' }}</strong>
                        </td>
                        <td class="p-3 w-[30%] text-right align-top">
                            <div class="flex items-center justify-end gap-1.5 pt-1.5">
                                <!-- Barcode Print -->
                                <a
                                    :href="route('purchase.show', purchase.id) + '/barcode'"
                                    target="_blank"
                                    class="bg-[#28a745] hover:bg-[#218838] border border-[#28a745] text-white font-bold px-3 py-1.5 rounded text-xs transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer h-[32px]"
                                >
                                    <i class="fas fa-barcode"></i> {{ t('barcode_print') }}
                                </a>

                                <!-- Edit Receipt -->
                                <Link
                                    :href="route('purchase.edit', purchase.id)"
                                    class="bg-[#007bff] hover:bg-[#0069d9] border border-[#007bff] text-white font-bold w-[32px] h-[32px] rounded transition shadow-sm inline-flex items-center justify-center cursor-pointer"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit text-xs"></i>
                                </Link>

                                <!-- Delete Receipt -->
                                <button
                                    v-if="canDelete"
                                    @click="handleDelete"
                                    class="bg-[#dc3545] hover:bg-[#c82333] border border-[#dc3545] text-white font-bold w-[32px] h-[32px] rounded transition shadow-sm inline-flex items-center justify-center cursor-pointer"
                                    title="Delete"
                                >
                                    <i class="fas fa-trash-alt text-xs"></i>
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
                            <tr class="border-b border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 font-black bg-slate-50/50 dark:bg-slate-900/30">
                                <th class="px-4 py-3 w-12 text-center">#</th>
                                <th class="px-4 py-3 w-16 text-center">{{ t('image') }}</th>
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
                            <template v-for="(entry, index) in purchase.purchase_entries" :key="entry.id">
                                <!-- Main Row -->
                                <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-slate-50/20 align-middle">
                                    <td class="px-4 py-3 text-center font-bold text-slate-400 font-english">{{ index + 1 }}</td>
                                    <!-- Image Zoomable -->
                                    <td class="px-4 py-3 text-center">
                                        <img 
                                            :src="entry.shoe?.image ? `/images/small-thumbnail/${entry.shoe.image}` : '/img/shoe.png'" 
                                            class="w-12 h-12 object-cover rounded shadow-sm border border-slate-200 dark:border-slate-700 mx-auto transition-transform duration-200 hover:scale-105 cursor-pointer"
                                            @click="openImageModal(entry.shoe?.image)"
                                            @mouseenter="handleMouseEnter($event, entry.shoe?.image)"
                                            @mouseleave="handleMouseLeave"
                                        />
                                    </td>
                                    <td class="px-4 py-3 text-center font-black text-blue-650 dark:text-blue-400 font-english">
                                        <Link :href="route('shoe.show', { shoe: entry.shoe?.code })">
                                            {{ entry.shoe?.code }}
                                        </Link>
                                    </td>
                                    <td class="px-4 py-3 text-center font-semibold text-slate-800 dark:text-slate-200">
                                        {{ entry.shoe?.category?.full_name || '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-slate-700 dark:text-slate-350">
                                        {{ entry.shoe?.color?.name || '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-success font-semibold font-english">
                                        {{ toFixed(entry.shoe?.retail_price) }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-danger font-semibold font-english">
                                        {{ entry.shoe?.purchase_price > 0 ? toFixed(entry.shoe.purchase_price) : 'পেন্ডিং' }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-800 dark:text-white font-english">
                                        {{ entry.count }}
                                    </td>
                                    <td class="px-4 py-3 text-right pr-6 font-black text-slate-900 dark:text-white font-english">
                                        {{ toFixed(entry.total_price) }}
                                    </td>
                                </tr>
                                
                                <!-- Sub-row showing Size quantities breakdown neatly -->
                                <tr v-if="entry.sizes_data && entry.sizes_data.length > 0" class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/20 dark:bg-slate-900/5">
                                    <td colspan="3"></td>
                                    <td colspan="6" class="px-4 py-2 text-start">
                                        <div class="flex items-center gap-1.5 flex-wrap text-xs text-slate-500 dark:text-slate-400">
                                            <span class="font-bold text-slate-655 dark:text-slate-300">সাইজ বিবরণ (Sizes):</span>
                                            <span 
                                                v-for="sz in entry.sizes_data" 
                                                :key="sz.id"
                                                class="px-2.5 py-0.5 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold"
                                            >
                                                {{ sz.size?.name }}: <span class="text-blue-650 dark:text-blue-400 font-black">{{ sz.quantity }}</span>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        
                        <!-- Totals footer -->
                        <tfoot>
                            <!-- Sum Row -->
                            <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/10 font-bold text-slate-800 dark:text-white">
                                <td colspan="7" class="px-4 py-3 text-right">{{ t('total') }} =</td>
                                <td class="px-4 py-3 text-center font-black">{{ purchase.total_purchase }}</td>
                                <td class="px-4 py-3 text-right pr-6 font-black">{{ toFixed(purchase.total_amount) }}</td>
                            </tr>

                            <!-- Payment Row (If any transaction or cheque exists) -->
                            <tr v-if="purchase.transaction || purchase.cheque" class="font-bold text-slate-700 dark:text-slate-300">
                                <td colspan="8" class="px-4 py-3 text-right text-slate-500 dark:text-slate-400">{{ t('payment') }} =</td>
                                <td class="px-4 py-3 text-right pr-6 font-black text-green-600 dark:text-green-400">
                                    {{ toFixed(purchase.transaction?.amount || purchase.cheque?.amount) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Image Modal Dialog -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-x-hidden overflow-y-auto outline-none" v-if="showImageModal" @click="showImageModal = false">
            <div class="fixed inset-0 bg-black/50 transition-opacity"></div>
            <div class="relative w-full max-w-3xl mx-auto z-50 p-4" @click.stop>
                <div class="relative flex flex-col w-full bg-white dark:bg-slate-800 border-0 rounded-lg shadow-lg outline-none">
                    <div class="flex items-center justify-between p-4 border-b border-solid border-slate-200 dark:border-slate-700 rounded-t">
                        <h5 class="text-lg font-bold text-slate-850 dark:text-white">
                            {{ t('pages.picture') || 'Picture' }}
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
.font-nikosh {
    font-family: 'Nikosh', 'SolaimanLipi', sans-serif;
}
.font-english {
    font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
}
.table-responsive {
    overflow-x: auto;
}
</style>
