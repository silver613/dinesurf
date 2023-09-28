<template>
    <vendor-layout>
        <template #header>
            <header-text   :title="'Create Menu'" />
        </template>
        <div class="md:py-12 px-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center">
                    <create-menu
                        class="b-g-white"
                        :vendor="vendor"
                        :is_admin="true"
                    ></create-menu>
                </div>
            </div>
        </div>
    </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import CreateMenu from "@/components/vendor/VendorCreateMenu.vue";
export default {
    props: ["vendor"],
    components: {
        VendorLayout,
        CreateMenu,
    },
};
</script>
