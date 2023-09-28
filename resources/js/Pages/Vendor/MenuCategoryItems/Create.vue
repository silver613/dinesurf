<template>
    <vendor-layout>
        <template #header>
            <header-text :title="'Create Item'"  />
           
        </template>
        <div class="md:py-12 px-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center">
                    <create-menu-category-item
                        class="b-g-white"
                        :vendor="vendor"
                        :categories="categories"
                        :is_admin="true"
                    ></create-menu-category-item>
                </div>
            </div>
        </div>
    </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import CreateMenuCategoryItem from "@/components/vendor/VendorCreateMenuCategoryItem.vue";
export default {
    props: ["vendor", "categories"],
    components: {
        VendorLayout,
        CreateMenuCategoryItem,
    },

    mounted(){
        console.log("The menu create:", this.menus)
    }
};
</script>
