<template>
    <app-layout>
        <template #header>
            <h2
                class="
                    font-semibold
                    text-xl text-gray-800
                    leading-tight
                    capitalize
                "
            >
                Reservation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="
                        bg-white
                        overflow-hidden
                        shadow-xl
                        py-12
                        px-4
                        sm:rounded-lg
                    "
                >
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ vendor.company_name }}
                    </h3>
                    <div class="flex w-full justify-center">
                        <div class="w-full md:w-1/3 flex justify-center">
                            <edit-reservation
                                :reservation="reservation"
                                :vendor="vendor"
                            ></edit-reservation>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <Footer /> -->
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import EditReservation from "@/components/vendor/EditReservation.vue";
import Footer from "@/components/Footer.vue"

export default {
    props: ["reservation", "vendor"],
    components: {
        AppLayout,
        EditReservation,
        Footer
    },
    mounted() {
        if (this.vendor) {
            document.title =
                "Edit Reservation With " + this.vendor.company_name;
        }
    },
};
</script>
