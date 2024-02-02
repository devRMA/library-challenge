<script setup>
import { reactive, defineProps } from "vue";
import InputError from "@/Components/InputError.vue";
import axios from "axios";

const props = defineProps({
    book: Object,
});

const visible = defineModel("visible");
const emit = defineEmits(["success"]);

const form = reactive({
    nome: "",
    errors: {},
});

const submit = () => {
    axios
        .put(route("books.update", props.book.id), form)
        .then(() => {
            emit("success");
            form.nome = "";
            form.errors = {};
        })
        .catch((error) => {
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                if (errors.nome) {
                    form.errors.nome = errors.nome[0];
                }
            }
        });
};
</script>

<template>
    <Dialog
        v-model:visible="visible"
        v-if="visible"
        modal
        :draggable="false"
        position="center"
        header="Editar Livro"
        :pt="{
            mask: {
                style: 'backdrop-filter: blur(2px); background-color: rgba(0, 0, 0, 0.5);',
            },
        }"
        :style="{ width: '25rem' }"
    >
        <div class="p-4 md:p-5">
            <form class="space-y-4" @submit.prevent="submit">
                <div>
                    <label
                        for="nome"
                        class="block mb-2 text-sm font-medium text-gray-900"
                    >
                        Novo Nome
                    </label>
                    <input
                        type="text"
                        name="nome"
                        id="nome"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="Livro de PHP"
                        v-model="form.nome"
                        required
                        autofocus
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.nome" />
                <button
                    type="submit"
                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                >
                    Atualizar
                </button>
            </form>
        </div>
    </Dialog>
</template>
