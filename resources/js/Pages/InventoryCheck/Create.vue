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

const handleBack = () => {
    window.history.back();
};
</script>

<template>
    <Head :title="t('menu.inventory_check') || 'Inventory Check'" />

    <AuthenticatedLayout>
        <div class="container-fluid mt-4">
            <div class="card card-primary card-outline p-3">
                <h1 class="font-weight-bold text-dark mb-4 fs-3">
                    {{ t('pages.want to start an inventory check') }}?
                </h1>
                <form @submit.prevent="handleSubmit">
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold me-3 mr-3" :disabled="form.processing">
                            {{ t('pages.yes') }}
                        </button>
                        <button type="button" @click="handleBack" class="btn btn-outline-primary px-4 py-2 fw-bold">
                            {{ t('pages.no') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.fw-bold {
    font-weight: 700 !important;
}
.fs-3 {
    font-size: 1.75rem !important;
}
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
.card-50-width {
    width: 50% !important;
}
@media (max-width: 768px) {
    .card-50-width {
        width: 100% !important;
    }
}
.card-primary.card-outline {
    border-top: 3px solid #007bff;
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
.btn-outline-primary {
    color: #007bff;
    border-color: #007bff;
}
.btn-outline-primary:hover {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.gap-3 {
    gap: 1rem !important;
}

/* Dark Mode overrides */
.dark .card {
    background-color: #1e1e2e !important;
    border-color: #2d2d3f !important;
    color: #f8f9fa !important;
}
.dark .text-dark {
    color: #ffffff !important;
}
</style>
