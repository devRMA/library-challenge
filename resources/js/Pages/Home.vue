<script setup>
import axios from "axios";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import CreateUserModal from "@/Components/CreateUserModal.vue";

const usersLoading = ref(true);
const users = ref([]);
const booksLoading = ref(true);
const books = ref([]);
const rentsLoading = ref(true);
const rents = ref([]);
const createUserVisible = ref(false);
const createBookVisible = ref(false);
const createRentVisible = ref(false);

const getUsers = () => {
    console.log("chamando get users");
    createUserVisible.value = false;
    usersLoading.value = true;
    users.value = [];
    console.log("variaveis limpas");
    console.log("createUserVisible", createUserVisible.value);
    console.log("usersLoading", usersLoading.value);
    console.log("users", users.value);

    axios
        .get(route("users.index"))
        .then((response) => {
            console.log("api respondeu");
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
            console.log("terminando o loading");
            usersLoading.value = false;
        });
};

const getBooks = () => {
    createBookVisible.value = false;
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
    createRentVisible.value = false;
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
        <Card
            title="Usuários"
            :data="users"
            :loading="usersLoading"
            @new="createUserVisible = true"
        />
        <Card
            title="Livros"
            :data="books"
            :loading="booksLoading"
            @new="createBookVisible = true"
        />
        <Card
            title="Alugueis"
            :data="rents"
            :loading="rentsLoading"
            @new="createRentVisible = true"
        />

        <CreateUserModal
            v-model:visible="createUserVisible"
            @success="getUsers"
        />
    </AppLayout>
</template>
