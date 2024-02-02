<script setup>
import ListSkeleton from "@/Components/ListSkeleton.vue";
import OptionsButton from "@/Components/OptionsButton.vue";
import { defineProps } from "vue";

const props = defineProps({
    title: String,
    data: Array,
    loading: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div
        class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 h-max"
    >
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-xl font-bold leading-none text-gray-900">
                {{ title }}
            </h5>
            <button
                type="button"
                class="px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center gap-1"
                @click="$emit('new')"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 16 16"
                    fill="currentColor"
                    class="w-3.5 h-3.5"
                >
                    <path
                        d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z"
                    />
                </svg>
            </button>
        </div>
        <div class="flow-root">
            <ListSkeleton v-if="loading" />
            <ul role="list" class="divide-y divide-gray-200" v-else>
                <li class="py-3 sm:py-4" v-for="item in data" :key="item.id">
                    <div class="flex items-center">
                        <div class="flex-1 min-w-0 ms-4">
                            <p
                                class="text-sm font-medium text-gray-900 truncate"
                            >
                                {{ item.title }}
                            </p>
                            <p
                                class="text-sm text-gray-500 truncate"
                                v-if="item.subtitle"
                            >
                                {{ item.subtitle }}
                            </p>
                        </div>
                        <div
                            class="inline-flex items-center text-base font-semibold text-gray-900"
                        >
                            <OptionsButton @update="$emit('update', item)" @delete="$emit('delete', item)" />
                        </div>
                    </div>
                </li>
            </ul>
            <p v-if="data.length === 0" class="text-gray-500">
                Não há itens cadastrados.
            </p>
        </div>
    </div>
</template>
