<script setup lang="ts">
    const links = [
        {
            label: 'Clients',
            to: '/clients',
        },
        {
            label: 'Books',
            to: '/books',
        },
    ];
    const isMenuOpen = ref(false);
</script>

<template>
    <header>
        <nav class="bg-gray-50 px-4 py-2.5 lg:px-6 dark:bg-gray-800">
            <UContainer>
                <div class="mx-auto grid max-w-screen-xl grid-cols-2 justify-center lg:grid-cols-3">
                    <AppLogo />
                    <div class="hidden w-full items-center justify-center lg:flex">
                        <ul class="w-font-medium flex gap-x-4">
                            <li
                                v-for="link in links"
                                :key="link.label"
                            >
                                <ULink
                                    :to="link.to"
                                    active-class="font-bold"
                                    inactive-class="text-gray-700 hover:text-black dark:text-gray-300 dark:hover:text-white"
                                >
                                    {{ link.label }}
                                </ULink>
                            </li>
                        </ul>
                    </div>
                    <div class="flex w-full items-center justify-end gap-4">
                        <ThemeToggle />
                        <UButton label="Cadastro" />
                        <UButton
                            @click="isMenuOpen = true"
                            color="gray"
                            variant="ghost"
                            aria-label="Menu"
                            class="lg:hidden"
                        >
                            <UIcon
                                name="i-heroicons-bars-3"
                                class="h-6 w-6"
                            />
                        </UButton>
                    </div>
                    <USlideover v-model="isMenuOpen">
                        <div class="flex flex-1 flex-col p-4">
                            <ul class="flex flex-col gap-4">
                                <li
                                    v-for="link in links"
                                    :key="link.label"
                                >
                                    <ULink
                                        :to="link.to"
                                        class="dark:hover block rounded border border-gray-200 p-4 transition-all dark:border-gray-700"
                                        active-class="bg-primary-600 dark:bg-primary-600 text-white border-transparent"
                                        inactive-class="hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-300"
                                    >
                                        {{ link.label }}
                                    </ULink>
                                </li>
                            </ul>
                        </div>
                    </USlideover>
                </div>
            </UContainer>
        </nav>
    </header>
</template>
