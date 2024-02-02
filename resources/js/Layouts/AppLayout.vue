<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-between">
        <!-- Header -->
        <header class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex gap-10 items-center h-16">
                    <!-- Logo -->
                    <ApplicationLogo class="block h-10 w-auto fill-current" />

                    <!-- Application name -->
                    <p
                        class="font-bold text-2xl bg-gradient-to-tr from-[#032B4D] via-[#004076] to-[#2B95D6] inline-block text-transparent bg-clip-text"
                    >
                        Library Challenge
                    </p>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto p-4 md:py-8">
                <hr class="my-6 border-gray-200 sm:mx-auto lg:my-8" />
                <span class="block text-sm text-gray-500 sm:text-center">
                    Feito com ❤️ por
                    <a
                        href="https://devrma.com"
                        target="_blank"
                        class="hover:underline"
                    >
                        Rafael Alves
                    </a>
                </span>
            </div>
        </footer>
    </div>
</template>
