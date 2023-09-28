<template>
    <vendor-layout>
        <template #header>
           <header-text   :title="'Create Reservation'" />
        </template>
        <div class="md:py-12 px-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center">
                    <make-reservation
                        class="b-g-white"
                        :vendor="vendor"
                        :is_admin="true"
                    ></make-reservation>
                </div>
            </div>
        </div>
    </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import MakeReservation from "@/components/vendor/VendorMakeReservation.vue";
export default {
    props: ["vendor"],
    components: {
        VendorLayout,
        MakeReservation,
    },
};
</script>
