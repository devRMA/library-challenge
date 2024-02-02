<script setup>
import { reactive, defineProps } from "vue";
import InputError from "@/Components/InputError.vue";
import axios from "axios";

const props = defineProps({
    user: Object,
});

const visible = defineModel("visible");
const emit = defineEmits(["success"]);

const form = reactive({
    nome: "",
    cpf: "",
    errors: {},
});

const submit = () => {
    axios
        .put(route("users.update", props.user.id), form)
        .then(() => {
            emit("success");
            form.nome = "";
            form.cpf = "";
            form.errors = {};
        })
        .catch((error) => {
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                if (errors.nome) {
                    form.errors.nome = errors.nome[0];
                }
                if (errors.cpf) {
                    form.errors.cpf = errors.cpf[0];
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
        header="Editar Usuário"
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
                        placeholder="John Doe"
                        v-model="form.nome"
                        required
                        autofocus
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.nome" />
                <div>
                    <label
                        for="cpf"
                        class="block mb-2 text-sm font-medium text-gray-900"
                    >
                        Novo CPF
                    </label>
                    <input
                        type="text"
                        name="cpf"
                        id="cpf"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="11122233344"
                        v-model="form.cpf"
                        maxlength="11"
                        required
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.cpf" />
                <button
                    type="submit"
                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                >
                    Cadastrar
                </button>
            </form>
        </div>
    </Dialog>
</template>
