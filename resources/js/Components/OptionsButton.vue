<script setup>
import { ref } from "vue";

const menu = ref();
const emit = defineEmits(["update", "delete"]);
const menuItens = ref([
    {
        label: "Ações",
        items: [
            {
                label: "Editar",
                command() {
                    emit('update');
                },
            },
            {
                label: "Deletar",
                command() {
                    emit('delete');
                },
            },
        ],
    },
]);

const toggle = (event) => {
    menu.value.toggle(event);
};
</script>

<template>
    <button
        type="button"
        class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
        @click="toggle"
        aria-haspopup="true"
        aria-controls="overlay_menu"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 16 16"
            fill="currentColor"
            class="w-3.5 h-3.5"
        >
            <path
                d="M8 2a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM8 6.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM9.5 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"
            />
        </svg>
    </button>
    <Menu ref="menu" id="overlay_menu" :model="menuItens" :popup="true">
        <template #item="{ item, props }">
            <button
                v-bind="props.action"
                class="text-sm w-full font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
            >
                {{ item.label }}
            </button>
        </template>
    </Menu>
</template>
