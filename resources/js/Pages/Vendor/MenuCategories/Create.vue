<template>
    <vendor-layout>
        <template #header>
            <header-text :title="'Create Menu Category'"  />
        </template>
        <div class="md:py-12 px-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center">
                    <create-menu-category
                        class="b-g-white"
                        :vendor="vendor"
                        :menus="menus"
                        :is_admin="true"
                    ></create-menu-category>
                </div>
            </div>
        </div>
    </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import CreateMenuCategory from "@/components/vendor/VendorCreateMenuCategory.vue";
export default {
    props: ["vendor", "menus"],
    components: {
        VendorLayout,
        CreateMenuCategory,
    },

    mounted(){
        console.log("The menu create:", this.menus)
    }
};
</script>
