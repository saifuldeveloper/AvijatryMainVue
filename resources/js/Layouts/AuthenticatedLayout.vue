<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';

// Dropdown states
const activeDropdown = ref(null);
const isMobileMenuOpen = ref(false);

const toggleDropdown = (name) => {
    if (activeDropdown.value === name) {
        activeDropdown.value = null;
    } else {
        activeDropdown.value = name;
    }
};

const closeAllDropdowns = () => {
    activeDropdown.value = null;
};

// Handle click outside to close dropdowns
const handleDocumentClick = (e) => {
    if (!e.target.closest('.nav-dropdown-container')) {
        closeAllDropdowns();
    }
};

onMounted(() => {
    document.addEventListener('click', handleDocumentClick);
});

onUnmounted(() => {
    document.removeEventListener('click', handleDocumentClick);
});

// Page properties
const page = usePage();
const user = computed(() => page.props.auth?.user || { name: 'User', email: '' });
const stats = computed(() => page.props.stats || { commitments_count: 0, trash_count: 0 });
const currentLocale = computed(() => page.props.locale || 'en');
const currentTheme = computed(() => page.props.theme || 'light');

// Safe route helper
const safeRoute = (name, params = null) => {
    try {
        if (route().has(name)) {
            return route(name, params);
        }
    } catch (e) {}
    return '#';
};

// Theme toggle
const toggleTheme = () => {
    router.post(safeRoute('theme.toggle'), {}, {
        preserveState: false,
        onSuccess: () => {
            const root = window.document.documentElement;
            if (currentTheme.value === 'dark') {
                root.classList.add('dark');
            } else {
                root.classList.remove('dark');
            }
        }
    });
};

// Language change
const changeLanguage = (lang) => {
    router.get(safeRoute('change.language'), { lang }, {
        preserveState: false
    });
};

const t = computed(() => {
    const sharedMenu = page.props.translations?.menu || {};
    return {
        brand: page.props.appName || 'Laravel',
        purchase: sharedMenu.purchase || 'Purchase',
        sale: sharedMenu.sale || 'Sale',
        inventory: sharedMenu.inventory || 'Inventory',
        inventory_check: sharedMenu.inventory_check || 'Inventory Check',
        returns: sharedMenu.returns || 'Returns',
        waste: sharedMenu.waste || 'Waste',
        books: sharedMenu.books || 'Books',
        reports: sharedMenu.reports || 'Reports',
        others: sharedMenu.others || 'Others',
        profile: sharedMenu.profile || 'Profile',
        change_password: sharedMenu.change_password || 'Change Password',
        logout: sharedMenu.logout || 'Log Out',
    };
});

const userProfileImage = computed(() => {
    if (user.value && user.value.image) {
        if (user.value.image.startsWith('http://') || user.value.image.startsWith('https://')) {
            return user.value.image;
        }
        return user.value.image.startsWith('/') ? user.value.image : '/' + user.value.image;
    }
    return '/images/profile/no_image.png';
});

const handleProfileImageError = (e) => {
    e.target.src = 'https://api.dicebear.com/7.x/initials/svg?seed=' + encodeURIComponent(user.value.name || 'User');
};
</script>

<template>
    <div :class="{'dark': currentTheme === 'dark'}">
        <div class="min-h-screen bg-[#f8f9fa] font-sans transition-colors duration-300 dark:bg-slate-900 flex flex-col">
            <!-- Top Horizontal Navbar (Bootstrap-like fluid navbar) -->
            <nav class="sticky top-0 z-50 border-b border-slate-200/80 bg-[#f8f9fa] dark:border-slate-800 dark:bg-slate-900">
                <div class="w-full px-4 sm:px-6 lg:px-8">
                    <div class="flex h-14 items-center justify-between">
                        <!-- Left side: Brand Logo and Title -->
                        <div class="flex items-center gap-6">
                            <Link :href="safeRoute('dashboard')" class="flex items-center gap-2 shrink-0">
                                <span class="text-[20px] font-semibold tracking-tight text-slate-900 dark:text-white">
                                    {{ t.brand }}
                                </span>
                            </Link>

                            <!-- Horizontal Menus (Desktop only) -->
                            <div class="hidden xl:flex items-center space-x-1">
                                <!-- Purchases -->
                                <Link
                                    :href="safeRoute('purchase.create')"
                                    class="px-1.5 py-2 text-[16px] font-normal text-slate-700 hover:text-black dark:text-slate-400 dark:hover:text-white transition-colors duration-150"
                                >
                                    {{ t.purchase }}
                                </Link>

                                <!-- Sale -->
                                <Link
                                    :href="safeRoute('invoice.create')"
                                    class="px-1.5 py-2 text-[16px] font-normal text-slate-700 hover:text-black dark:text-slate-400 dark:hover:text-white transition-colors duration-150"
                                >
                                    {{ t.sale }}
                                </Link>

                                <!-- Inventory -->
                                <Link
                                    :href="safeRoute('shoe.index')"
                                    class="px-1.5 py-2 text-[16px] font-normal text-slate-700 hover:text-black dark:text-slate-400 dark:hover:text-white transition-colors duration-150"
                                >
                                    {{ t.inventory }}
                                </Link>

                                <!-- Inventory Check -->
                                <Link
                                    :href="safeRoute('inventory-check.index')"
                                    class="px-1.5 py-2 text-[16px] font-normal text-slate-700 hover:text-black dark:text-slate-400 dark:hover:text-white transition-colors duration-150"
                                >
                                    {{ t.inventory_check }}
                                </Link>

                                <!-- Returns Dropdown -->
                                <div class="relative nav-dropdown-container">
                                    <button
                                        @click="toggleDropdown('returns')"
                                        class="flex items-center gap-0.5 px-1.5 py-2 text-[16px] font-normal text-slate-700 hover:text-black dark:text-slate-400 dark:hover:text-white transition-colors duration-150"
                                    >
                                        <span>{{ t.returns }}</span>
                                        <svg class="h-1.5 w-2 ml-1 fill-current opacity-70 self-center" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 0l5 6 5-6z" />
                                        </svg>
                                    </button>
                                    <div v-show="activeDropdown === 'returns'" class="absolute left-0 mt-0.5 w-48 rounded border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-800 dark:bg-slate-900 transition-all">
                                        <Link :href="safeRoute('return.factory')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                            {{ page.props.translations?.menu?.supplier_returns || 'মহাজন ফেরত' }}
                                        </Link>
                                        <Link :href="safeRoute('return.retail-store.create')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                            {{ page.props.translations?.menu?.retailer_returns || 'পার্টি ফেরত' }}
                                        </Link>
                                        <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                        <Link :href="safeRoute('return.pending')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                            {{ page.props.translations?.menu?.pending_return || 'পেন্ডিং ফেরত' }}
                                        </Link>
                                    </div>
                                </div>

                                <!-- Waste (Jolap) Dropdown -->
                                <div class="relative nav-dropdown-container">
                                    <button
                                        @click="toggleDropdown('waste')"
                                        class="flex items-center gap-0.5 px-1.5 py-2 text-[16px] font-normal text-slate-700 hover:text-black dark:text-slate-300 dark:hover:text-white transition-colors duration-150"
                                    >
                                        <span>{{ t.waste }}</span>
                                        <svg class="h-1.5 w-2 ml-1 fill-current opacity-70 self-center" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 0l5 6 5-6z" />
                                        </svg>
                                    </button>
                                    <div v-show="activeDropdown === 'waste'" class="absolute left-0 mt-0.5 w-48 rounded border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-800 dark:bg-slate-900 transition-all">
                                        <Link :href="safeRoute('waste.shoes-page')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                            {{ page.props.translations?.menu?.shoe_waste || 'জুতা জোলাপ' }}
                                        </Link>
                                        <Link :href="safeRoute('waste.gifts-page')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                            {{ page.props.translations?.menu?.gift_waste || 'গিফট জোলাপ' }}
                                        </Link>
                                        <Link :href="safeRoute('waste.party.gift')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                            {{ page.props.translations?.menu?.party_gift || 'পার্টি উপহার' }}
                                        </Link>
                                    </div>
                                </div>

                                <!-- Ledger Books Dropdown -->
                                <div class="relative nav-dropdown-container">
                                    <button
                                        @click="toggleDropdown('books')"
                                        class="flex items-center gap-0.5 px-1.5 py-2 text-[16px] font-normal text-slate-700 hover:text-black dark:text-slate-300 dark:hover:text-white transition-colors duration-150"
                                    >
                                        <span>{{ t.books }}</span>
                                        <svg class="h-1.5 w-2 ml-1 fill-current opacity-70 self-center" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 0l5 6 5-6z" />
                                        </svg>
                                    </button>
                                    <div v-show="activeDropdown === 'books'" class="absolute left-0 mt-0.5 w-60 rounded border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-800 dark:bg-slate-900 transition-all">
                                        <Link :href="safeRoute('transaction.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.sales_books || 'Sales Books' }}
                                        </Link>
                                        <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                        <Link :href="safeRoute('factory.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.supplier_books || 'Supplier Books' }}
                                        </Link>
                                        <Link :href="safeRoute('retail-store.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.retailer_books || 'Retailer Books' }}
                                        </Link>
                                        <Link :href="safeRoute('bank-account.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.bank_accounts_books || 'Bank Account Books' }}
                                        </Link>
                                        <Link :href="safeRoute('cheque.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.check_books || 'Cheque Books' }}
                                        </Link>
                                        <Link :href="safeRoute('gift-supplier.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.gift_supplier_books || 'Gift Supplier Books' }}
                                        </Link>
                                        <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                        <Link :href="safeRoute('employee.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.staff_books || 'Staff Books' }}
                                        </Link>
                                        <Link :href="safeRoute('loan.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.loan_books || 'Loan Books' }}
                                        </Link>
                                        <Link :href="safeRoute('expense.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.other_expenses_books || 'Other Expenses Books' }}
                                        </Link>
                                    </div>
                                </div>

                                <!-- Reports Dropdown -->
                                <div class="relative nav-dropdown-container">
                                    <button
                                        @click="toggleDropdown('reports')"
                                        class="flex items-center gap-0.5 px-1.5 py-2 text-[16px] font-normal text-slate-700 hover:text-black dark:text-slate-300 dark:hover:text-white transition-colors duration-150"
                                    >
                                        <span>{{ t.reports }}</span>
                                        <svg class="h-1.5 w-2 ml-1 fill-current opacity-70 self-center" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 0l5 6 5-6z" />
                                        </svg>
                                    </button>
                                    <div v-show="activeDropdown === 'reports'" class="absolute left-0 mt-0.5 w-60 rounded border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-800 dark:bg-slate-900 transition-all max-h-[400px] overflow-y-auto">
                                        <Link :href="safeRoute('report.transaction-page')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.transactions || 'Transactions' }}
                                        </Link>
                                        <Link :href="safeRoute('report.cash')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.cash_exchange || 'Cash Exchange' }}
                                        </Link>
                                        <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                        <Link :href="safeRoute('retailer.report.view')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Retailer Report'] || 'Retailer Report' }}
                                        </Link>
                                        <Link :href="safeRoute('onetime.retailer.report')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['One Time Retialer Report'] || 'One Time Retailer Report' }}
                                        </Link>
                                        <Link :href="safeRoute('retailer.payment.report')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Payment after Eid'] || 'Payment after Eid' }}
                                        </Link>
                                        <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                        <Link :href="safeRoute('report.transaction.daily.view')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Daily Transaction'] || 'Daily Transaction' }}
                                        </Link>
                                        <Link :href="safeRoute('report.transaction.monthly.view')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Monthly Report'] || 'Monthly Report' }}
                                        </Link>
                                        <Link :href="safeRoute('report.transaction.three.view')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Average calculation'] || 'Average calculation' }}
                                        </Link>
                                        <Link :href="safeRoute('report.transaction.four.view')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Fiscal year Report'] || 'Fiscal year Report' }}
                                        </Link>
                                        <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                        <Link :href="safeRoute('factory.report.view')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Factory Report'] || 'Factory Report' }}
                                        </Link>
                                        <Link :href="safeRoute('factory.commission.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Factory Commission'] || 'Factory Commission' }}
                                        </Link>
                                        <Link :href="safeRoute('gift.factory.report.view')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Gift Factory Report'] || 'Gift Factory Report' }}
                                        </Link>
                                        <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                        <Link :href="safeRoute('current.capital.report')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Capital'] || 'Capital' }}
                                        </Link>
                                        <Link :href="safeRoute('profit.loss.report')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Profit/Loss'] || 'Profit/Loss' }}
                                        </Link>
                                    </div>
                                </div>

                                <!-- Others Dropdown -->
                                <div class="relative nav-dropdown-container">
                                    <button
                                        @click="toggleDropdown('others')"
                                        class="flex items-center gap-0.5 px-1.5 py-2 text-[16px] font-normal text-slate-700 hover:text-black dark:text-slate-300 dark:hover:text-white transition-colors duration-150"
                                    >
                                        <span>{{ t.others }}</span>
                                        <svg class="h-1.5 w-2 ml-1 fill-current opacity-70 self-center" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 0l5 6 5-6z" />
                                        </svg>
                                    </button>
                                    <div v-show="activeDropdown === 'others'" class="absolute left-0 mt-0.5 w-60 rounded border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-800 dark:bg-slate-900 transition-all max-h-[400px] overflow-y-auto">
                                        <Link :href="safeRoute('category.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.shoe_categories || 'Shoe Categories' }}
                                        </Link>
                                        <Link :href="safeRoute('color.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.shoe_colors || 'Shoe Colors' }}
                                        </Link>
                                        <Link :href="safeRoute('size.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.shoe_sizes || 'Shoe Sizes' }}
                                        </Link>
                                        <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                        <Link :href="safeRoute('gift.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.gift_inventory || 'Gift Inventory' }}
                                        </Link>
                                        <Link :href="safeRoute('commitment.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.commitments || 'Commitments' }}
                                        </Link>
                                        <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                        <Link :href="safeRoute('shoe.barcode')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.barcode_printing || 'Barcode Printing' }}
                                        </Link>
                                        <Link :href="safeRoute('notes.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.menu?.note || 'Note' }}
                                        </Link>
                                        <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                        <Link :href="safeRoute('asset-book.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['asset'] || 'Asset' }}
                                        </Link>
                                        <Link :href="safeRoute('liabilities.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['liabilities'] || 'Liabilities' }}
                                        </Link>
                                        <Link :href="safeRoute('zakat.page')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                            {{ page.props.translations?.pages?.['Zakat'] || 'Zakat' }}
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right side: Notifications, Settings, Profile -->
                        <div class="flex items-center gap-1.5 sm:gap-2.5">
                            <!-- Alert Badge 1: Commitments -->
                            <Link
                                :href="safeRoute('commitment.today')"
                                class="relative rounded-full p-2 text-slate-400 hover:text-slate-750 dark:text-slate-400 dark:hover:text-white"
                                :title="page.props.translations?.menu?.commitments || 'কমিটমেন্ট'"
                            >
                                <svg class="h-[21px] w-[21px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span
                                    class="absolute top-1 right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-600 px-1 text-[9px] font-bold text-white"
                                >
                                    {{ stats.commitments_count }}
                                </span>
                            </Link>

                            <!-- Alert Badge 2: Trash -->
                            <Link
                                :href="safeRoute('trash.list')"
                                class="relative rounded-full p-2 text-slate-400 hover:text-slate-755 dark:text-slate-400 dark:hover:text-white"
                                :title="page.props.translations?.menu?.delete || 'ট্র্যাশ'"
                            >
                                <svg class="h-[21px] w-[21px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span
                                    class="absolute top-1 right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-600 px-1 text-[9px] font-bold text-white"
                                >
                                    {{ stats.trash_count }}
                                </span>
                            </Link>

                            <!-- History Log -->
                            <Link
                                :href="safeRoute('activity.log')"
                                class="rounded-full p-2 text-slate-400 hover:text-slate-755 dark:text-slate-400 dark:hover:text-white"
                                :title="page.props.translations?.menu?.activity_log || 'অ্যাক্টিভিটি লগ'"
                            >
                                <svg class="h-[21px] w-[21px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </Link>

                            <!-- Language Switcher -->
                            <div class="relative nav-dropdown-container ml-1">
                                <button
                                    @click="toggleDropdown('language')"
                                    class="flex items-center gap-1.5 border border-slate-300 rounded-[7px] bg-white dark:bg-slate-800 p-1 px-2.5 text-[13px] font-normal text-[#495057] hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-150 dark:border-slate-700 dark:text-slate-300"
                                    title="Language"
                                >
                                    <span>{{ currentLocale === 'en' ? 'English' : 'বাংলা' }}</span>
                                    <svg class="h-1.5 w-2 ml-0.5 fill-current opacity-70 self-center" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 0l5 6 5-6z" />
                                    </svg>
                                </button>
                                <div
                                    v-show="activeDropdown === 'language'"
                                    class="absolute right-0 mt-1 w-32 rounded-lg border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-800 dark:bg-slate-900 transition-all z-[60]"
                                >
                                    <button
                                        @click="changeLanguage('en'); closeAllDropdowns();"
                                        class="flex items-center justify-between w-full px-4 py-2 text-[14px] text-left text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-150"
                                    >
                                        <span :class="{'font-semibold text-sky-600 dark:text-sky-400': currentLocale === 'en'}">English</span>
                                        <svg v-if="currentLocale === 'en'" class="h-3.5 w-3.5 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="changeLanguage('bn'); closeAllDropdowns();"
                                        class="flex items-center justify-between w-full px-4 py-2 text-[14px] text-left text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-150"
                                    >
                                        <span :class="{'font-semibold text-sky-600 dark:text-sky-400': currentLocale === 'bn'}">বাংলা</span>
                                        <svg v-if="currentLocale === 'bn'" class="h-3.5 w-3.5 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Dark/Light Theme Toggle -->
                            <button
                                @click="toggleTheme"
                                class="border border-slate-300 rounded-[7px] p-1 px-1.5 text-[#495057] hover:bg-slate-50 transition-colors duration-150 dark:border-slate-700 dark:text-slate-300 flex items-center justify-center"
                                title="Toggle Theme"
                            >
                                <svg class="h-[21px] w-[21px]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                            </button>

                            <!-- Settings Dropdown -->
                            <div class="relative nav-dropdown-container">
                                <button
                                    @click="toggleDropdown('settings')"
                                    class="flex items-center gap-1.5 px-1.5 py-2 text-[16px] font-normal text-slate-700 hover:text-black dark:text-slate-300 dark:hover:text-white transition-colors duration-150"
                                    title="Settings"
                                >
                                    <svg class="h-[21px] w-[21px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <svg class="h-1.5 w-2 ml-0.5 fill-current opacity-70 self-center" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 0l5 6 5-6z" />
                                    </svg>
                                </button>
                                <div v-show="activeDropdown === 'settings'" class="absolute right-0 mt-1 w-56 rounded border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-800 dark:bg-slate-900 transition-all">
                                    <Link :href="safeRoute('setting.users')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                        {{ page.props.translations?.menu?.users || 'Users' }}
                                    </Link>
                                    <Link :href="safeRoute('setting.roles')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                        {{ page.props.translations?.menu?.users_types || 'User Types' }}
                                    </Link>
                                    <Link :href="safeRoute('setting.sms.retailer')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                        {{ page.props.translations?.menu?.message_settings || 'Message Settings' }}
                                    </Link>
                                    <Link :href="safeRoute('setting.sms.custom')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                        {{ page.props.translations?.menu?.custom_messages || 'Custom Messages' }}
                                    </Link>
                                    <Link :href="safeRoute('setting.sms.config')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                        {{ page.props.translations?.menu?.sms_settings || 'SMS Settings' }}
                                    </Link>
                                    <Link :href="safeRoute('size.chart.setting')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                        {{ page.props.translations?.menu?.size_chart_settings || 'Size Chart Settings' }}
                                    </Link>
                                    <Link :href="safeRoute('closing-period.index')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                        {{ page.props.translations?.pages?.['Closing_Period'] || 'Closing Period' }}
                                    </Link>
                                </div>
                            </div>

                            <!-- User Profile Dropdown -->
                            <div class="relative nav-dropdown-container">
                                <button
                                    @click="toggleDropdown('profile')"
                                    class="flex items-center gap-1 group cursor-pointer"
                                >
                                    <div class="profile-pic border border-slate-200 transition group-hover:border-slate-300">
                                        <img :src="userProfileImage" alt="Profile" @error="handleProfileImageError" />
                                    </div>
                                    <svg class="h-1.5 w-2 ml-1 fill-current text-slate-500 self-center" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 0l5 6 5-6z" />
                                    </svg>
                                </button>
                                <div v-show="activeDropdown === 'profile'" class="absolute right-0 mt-1.5 w-48 rounded border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-800 dark:bg-slate-900 transition-all">
                                    <Link :href="safeRoute('user.profile')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                        {{ page.props.translations?.menu?.profile || 'Profile' }}
                                    </Link>
                                    <Link :href="safeRoute('change.password')" class="block px-4 py-1.5 text-[16px] font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">
                                        {{ page.props.translations?.menu?.change_password || 'Change Password' }}
                                    </Link>
                                    <div class="h-px bg-slate-150 dark:bg-slate-800 my-1"></div>
                                    <Link :href="safeRoute('app.logout')" class="block w-full text-left px-4 py-1.5 text-[16px] font-normal text-rose-650 dark:text-rose-455 hover:bg-slate-100">
                                        {{ page.props.translations?.menu?.logout || 'Logout' }}
                                    </Link>
                                </div>
                            </div>

                            <!-- Mobile Menu Hamburger -->
                            <button
                                @click="isMobileMenuOpen = !isMobileMenuOpen"
                                class="rounded-full p-2 text-slate-500 hover:bg-slate-100 xl:hidden"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Links -->
                <div v-show="isMobileMenuOpen" class="xl:hidden border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 space-y-1 shadow-inner">
                    <Link :href="safeRoute('purchase.create')" class="block rounded px-3 py-1.5 text-sm font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">ক্রয়</Link>
                    <Link :href="safeRoute('invoice.create')" class="block rounded px-3 py-1.5 text-sm font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">বিক্রয়</Link>
                    <Link :href="safeRoute('shoe.index')" class="block rounded px-3 py-1.5 text-sm font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">ইনভেন্টরি</Link>
                    <Link :href="safeRoute('inventory-check.index')" class="block rounded px-3 py-1.5 text-sm font-normal text-slate-700 dark:text-slate-300 hover:bg-slate-100">ইনভেন্টরি চেক</Link>
                </div>
            </nav>

            <!-- Main Content Area (Fluid / Full width to match 1st image) -->
            <main class="flex-1 p-6 w-full">
                <div class="max-w-[90%] mx-auto w-full">
                    <!-- Alert Area -->
                    <div class="space-y-4 mb-6">
                        <div v-if="$page.props.flash && $page.props.flash.success" class="rounded border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800 dark:border-emerald-800/40 dark:bg-emerald-950/20 dark:text-emerald-400">
                            {{ $page.props.flash.success }}
                        </div>
                        <div v-if="$page.props.flash && $page.props.flash.error" class="rounded border border-rose-200 bg-rose-50 p-4 text-sm font-medium text-rose-800 dark:border-rose-800/40 dark:bg-rose-950/20 dark:text-rose-400">
                            {{ $page.props.flash.error }}
                        </div>
                    </div>

                    <slot />
                </div>
            </main>

            <!-- Footer (Bootstrap footer fluid style) -->
            <footer class="border-t border-slate-200 bg-white py-4 px-6 text-center text-xs font-semibold text-slate-400 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-500 transition-colors duration-300">
                &copy; 2026 Avijatry ERP. All rights reserved.
            </footer>
        </div>
    </div>
</template>

<style>
/* Font overrides to match the exact font in the screenshot */
html, body, select, input, textarea, button, a, span, div, p, h1, h2, h3, h4, h5, h6, table, th, td {
    font-family: 'Nunito', 'Noto Sans Bengali', 'SolaimanLipi', 'Nikosh', sans-serif !important;
}

/* Profile picture style matching the original project */
.profile-pic {
    display: inline-block;
    vertical-align: middle;
    width: 38px;
    height: 38px;
    overflow: hidden;
    border-radius: 50%;
}
.profile-pic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
