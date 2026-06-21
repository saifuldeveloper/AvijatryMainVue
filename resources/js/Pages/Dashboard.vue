<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const currentLocale = computed(() => page.props.locale || 'en');

const t = computed(() => {
    const sharedMain = page.props.translations?.main || {};
    return {
        erp_software: sharedMain.erp_software || 'ERP Software',
        modernity_in_personality: sharedMain.modernity_in_personality || 'Modernity in Personality'
    };
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <h1 class="text-[40px] font-bold text-slate-800 dark:text-slate-100 tracking-tight mt-2 mb-4 leading-tight">
            {{ t.erp_software }}
            <small class="text-[24px] font-normal text-slate-500 dark:text-slate-400 ml-2">
                {{ t.modernity_in_personality }}
            </small>
        </h1>
    </AuthenticatedLayout>
</template>
