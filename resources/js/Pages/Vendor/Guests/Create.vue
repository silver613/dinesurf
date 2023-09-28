<template>
    <vendor-layout>
        <template #header>
            <header-text   :title="'Create Guest'" />
        </template>
        <div class="md:py-12 px-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center">
                    <create-guest
                        class="b-g-white"
                        :vendor="vendor"
                        :is_admin="true"
                    ></create-guest>
                </div>
            </div>
        </div>
    </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import CreateGuest from "@/components/vendor/VendorCreateGuest.vue";
export default {
    props: ["vendor"],
    components: {
        VendorLayout,
        CreateGuest,
    },
};
</script>
