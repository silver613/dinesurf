<template>
    <jet-dropdown align="right" width="48" class="mr-3">
        <template #trigger>
            <span class="inline-flex rounded-md">
                <button
                    type="button"
                    class="
                        inline-flex
                        items-center
                        px-3 py-2
                        border border-gray-500
                        text-sm
                        leading-4
                        rounded-md
                        text-gray-900
                        bg-white
                        hover:text-gray-700
                        focus:outline-none
                        transition
                        capitalize
                        form-control
                    "
                    :class="py ? py : null"
                >
                    <span>Actions </span>

                    <svg
                        class="ml-2 -mr-0.5 h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </span>
        </template>

        <template #content>
            <jet-dropdown-link :as="'a'">
                Email Users
            </jet-dropdown-link>
             <jet-dropdown-link :as="'a'">
                Text Users
            </jet-dropdown-link>
            <div class="border-t border-gray-100"></div>
        </template>
    </jet-dropdown>
</template>

<script>
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
export default {
    data() {
        return {
            action:null
        }
    },
    components: {
        JetDropdown,
        JetDropdownLink,
    },
    props: {
        actions: {
            type: Array,
            default: [],
        },
        py: {
            type: String,
        },
    },
};
</script>
