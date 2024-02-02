<script setup>
import axios from "axios";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import CreateUserModal from "@/Components/CreateUserModal.vue";
import UpdateUserModal from "@/Components/UpdateUserModal.vue";
import CreateBookModal from "@/Components/CreateBookModal.vue";
import UpdateBookModal from "@/Components/UpdateBookModal.vue";

const usersLoading = ref(true);
const users = ref([]);
const booksLoading = ref(true);
const books = ref([]);
const rentsLoading = ref(true);
const rents = ref([]);
const createUserVisible = ref(false);
const updateUserVisible = ref(false);
const userToUpdate = ref(null);
const createBookVisible = ref(false);
const updateBookVisible = ref(false);
const bookToUpdate = ref(null);
const createRentVisible = ref(false);

const getUsers = () => {
    createUserVisible.value = false;
    updateUserVisible.value = false;
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

const updateUser = (user) => {
    userToUpdate.value = user;
    updateUserVisible.value = true;
};

const deleteUser = (user) => {
    axios
        .delete(route("users.destroy", user.id))
        .then(() => {
            getUsers();
        })
        .catch((error) => {
            console.error(error);
        });
};

const getBooks = () => {
    createBookVisible.value = false;
    updateBookVisible.value = false;
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

const updateBook = (book) => {
    bookToUpdate.value = book;
    updateBookVisible.value = true;
};

const deleteBook = (user) => {
    axios
        .delete(route("books.destroy", user.id))
        .then(() => {
            getBooks();
        })
        .catch((error) => {
            console.error(error);
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
            @update="updateUser"
            @delete="deleteUser"
        />
        <Card
            title="Livros"
            :data="books"
            :loading="booksLoading"
            @new="createBookVisible = true"
            @update="updateBook"
            @delete="deleteBook"
        />
        <Card
            title="Aluguéis"
            :data="rents"
            :loading="rentsLoading"
            @new="createRentVisible = true"
        />

        <CreateUserModal
            v-model:visible="createUserVisible"
            @success="getUsers"
        />
        <UpdateUserModal
            v-if="updateUserVisible"
            v-model:visible="updateUserVisible"
            :user="userToUpdate"
            @success="getUsers"
        />

        <CreateBookModal
            v-model:visible="createBookVisible"
            @success="getBooks"
        />
        <UpdateBookModal
            v-if="updateBookVisible"
            v-model:visible="updateBookVisible"
            :book="bookToUpdate"
            @success="getBooks"
        />
    </AppLayout>
</template>
