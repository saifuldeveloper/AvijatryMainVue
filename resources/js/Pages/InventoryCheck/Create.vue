<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

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
            'want to start an inventory check': 'Want to start an inventory check',
            'yes': 'Yes',
            'no': 'No',
        },
        bn: {
            'want to start an inventory check': 'আপনি কি নতুন ইনভেন্টরি চেক শুরু করতে চান',
            'yes': 'হ্যাঁ',
            'no': 'না',
        }
    };
    const locale = page.props.locale || 'en';
    return fallbacks[locale]?.[cleanKey] || key;
};

// Form state
const today = computed(() => {
    const d = new Date();
    return d.toISOString().split('T')[0];
});

const form = useForm({
    start_date: today.value
});

const handleSubmit = () => {
    form.post(route('inventory-check.store'));
};
</script>

<template>
    <Head :title="t('menu.inventory_check') || 'Inventory Check'" />

    <AuthenticatedLayout>
        <div class="container-fluid mt-5 d-flex justify-content-center">
            <div class="card card-primary card-outline shadow-sm text-center p-4" style="max-width: 500px; width: 100%;">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="fas fa-clipboard-check text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="font-weight-bold mb-4 text-dark fs-4">
                        {{ t('pages.want to start an inventory check') }}?
                    </h2>
                    <form @submit.prevent="handleSubmit">
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold me-3" :disabled="form.processing">
                                {{ t('pages.yes') }}
                            </button>
                            <Link :href="route('shoe.index')" class="btn btn-outline-secondary px-4 py-2 fw-bold">
                                {{ t('pages.no') }}
                            </Link>
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
</style>
