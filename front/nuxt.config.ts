export default defineNuxtConfig({
    devtools: { enabled: true },
    modules: ['@nuxt/ui', '@nuxtjs/eslint-module', '@nuxt/image', '@nuxtjs/seo'],
    site: {
        url: 'https://library.devrma.com/',
        name: 'Library Challenge',
        description: 'A library simulation, where renting books to customers is simulated.',
        defaultLocale: 'en',
    },
});
