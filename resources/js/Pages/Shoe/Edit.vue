<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';

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
    const fallbacks = {
        en: {
            'shoe_edit': 'Edit Shoe Details',
            'type': 'Type',
            'color': 'Color',
            'selling_price': 'Selling Price',
            'purchase_price': 'Purchase Price',
            'picture': 'Picture',
            'save': 'Save',
            'close': 'Cancel',
            'all': 'All',
        },
        bn: {
            'shoe_edit': 'জুতার তথ্য সম্পাদন',
            'type': 'ধরণ',
            'color': 'রঙ',
            'selling_price': 'বিক্রয়মূল্য',
            'purchase_price': 'ক্রয়মূল্য',
            'picture': 'ছবি',
            'save': 'সংরক্ষণ',
            'close': 'বাতিল করুন',
            'all': 'সব',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

const form = useForm({
    id: props.shoe.id,
    code: props.shoe.id,
    category_id: props.shoe.category_id || '',
    color_id: props.shoe.color_id || '',
    retail_price: props.shoe.retail_price,
    purchase_price: props.shoe.purchase_price,
    image: null
});

const handleSubmit = () => {
    form.post(route('shoe.update', { shoe: form.code }), {
        forceFormData: true,
    });
};

const onImageChange = (e) => {
    form.image = e.target.files[0];
};
</script>

<template>
    <Head :title="t('pages.shoe_edit')" />

    <AuthenticatedLayout>
        <div class="container-fluid mt-4" style="max-width: 600px;">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header bg-white border-bottom p-3">
                    <h4 class="card-title font-weight-bold text-primary mb-0">
                        <i class="fas fa-edit me-2"></i> {{ t('pages.shoe_edit') }} (#{{ shoe.id }})
                    </h4>
                </div>
                <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ t('pages.type') }}</label>
                                <select v-model="form.category_id" class="form-select select-field" required>
                                    <option value="">{{ t('pages.all') }}</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                        {{ cat.parent ? cat.parent.name + '-' + cat.name : cat.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ t('pages.color') }}</label>
                                <select v-model="form.color_id" class="form-select select-field" required>
                                    <option value="">{{ t('pages.all') }}</option>
                                    <option v-for="col in colors" :key="col.id" :value="col.id">
                                        {{ col.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ t('pages.selling_price') }}</label>
                                <input type="number" step="0.01" v-model="form.retail_price" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ t('pages.purchase_price') }}</label>
                                <input type="number" step="0.01" v-model="form.purchase_price" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ t('pages.picture') }}</label>
                                <input type="file" @change="onImageChange" class="form-control">
                                <div v-if="form.progress" class="progress mt-2" style="height: 10px;">
                                    <div class="progress-bar" role="progressbar" :style="{ width: form.progress.percentage + '%' }"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 border-top d-flex justify-content-end">
                        <Link :href="route('shoe.index')" class="btn btn-outline-secondary me-2">{{ t('pages.close') }}</Link>
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">
                            <i class="fas fa-save me-1"></i> {{ t('pages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
