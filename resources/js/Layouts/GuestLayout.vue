<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-slate-50 px-4 py-12 transition-colors duration-300 dark:bg-slate-950 sm:px-6 lg:px-8"
    >
        <!-- Background decorative glowing orbs -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div
                class="absolute -top-[40%] -left-[20%] h-[80%] w-[80%] rounded-full bg-gradient-to-tr from-indigo-500/10 to-purple-500/10 blur-3xl dark:from-indigo-500/5 dark:to-purple-500/5"
            ></div>
            <div
                class="absolute -bottom-[40%] -right-[20%] h-[80%] w-[80%] rounded-full bg-gradient-to-br from-cyan-500/10 to-emerald-500/10 blur-3xl dark:from-cyan-500/5 dark:to-emerald-500/5"
            ></div>
        </div>

        <div class="relative w-full max-w-md">
            <!-- Glassmorphic card container -->
            <div
                class="overflow-hidden rounded-2xl border border-white/20 bg-white/70 p-8 shadow-2xl backdrop-blur-xl transition-all duration-300 dark:border-slate-800/40 dark:bg-slate-900/70"
            >
                <!-- Brand logo / header -->
                <div class="mb-8 flex flex-col items-center justify-center text-center">
                    <Link href="/" class="group flex flex-col items-center gap-3">
                        <!-- Custom styled premium logo -->
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-cyan-500 shadow-lg transition-all duration-300 group-hover:scale-105 group-hover:shadow-indigo-500/20"
                        >
                            <svg
                                class="h-9 w-9 text-white animate-pulse"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                ></path>
                            </svg>
                        </div>
                        <div class="mt-2">
                            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                                অভিযাত্রী <span class="bg-gradient-to-r from-indigo-500 to-cyan-500 bg-clip-text text-transparent">ERP</span>
                            </h1>
                            <p class="mt-1 text-sm font-medium text-slate-500 dark:text-slate-400">
                                Avijatry Shoe Factory & Retailer Management
                            </p>
                        </div>
                    </Link>
                </div>

                <!-- Card content -->
                <slot />
            </div>
        </div>
    </div>
</template>
