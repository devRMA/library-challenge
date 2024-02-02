<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";

const usersLoading = ref(true);
const users = ref([]);
const booksLoading = ref(true);
const books = ref([]);
const rentsLoading = ref(true);
const rents = ref([]);

const getUsers = () => {
    usersLoading.value = true;
    users.value = [];
    axios
        .get(route("users.index"))
        .then((response) => {
            users.value = response.data.map((user) => ({
                id: user.id,
                title: user.nome,
                subtitle: user.cpf,
            }));
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            usersLoading.value = false;
        });
};

const getBooks = () => {
    booksLoading.value = true;
    books.value = [];
    axios
        .get(route("books.index"))
        .then((response) => {
            books.value = response.data.map((book) => ({
                id: book.id,
                title: book.nome,
                subtitle: null,
            }));
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            booksLoading.value = false;
        });
};

const getRents = () => {
    rentsLoading.value = true;
    rents.value = [];
    axios
        .get(route("book-users.index"))
        .then((response) => {
            rents.value = response.data.map((rent) => ({
                id: rent.id,
                title: rent.user.nome,
                subtitle: rent.book.nome,
            }));
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            rentsLoading.value = false;
        });
};

onMounted(() => {
    getUsers();
    getBooks();
    getRents();
});
</script>

<template>
    <Head title="Home" />

    <AppLayout class="flex flex-row gap-10">
        <Card title="Usuários" :data="users" :loading="usersLoading" />
        <Card title="Livros" :data="books" :loading="booksLoading" />
        <Card title="Alugueis" :data="rents" :loading="rentsLoading" />
    </AppLayout>
</template>
