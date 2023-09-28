<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                    v-if="reservationData"
                >
                    <h3
                        class="
                            text-lg
                            font-medium
                            text-gray-900
                            mb-10
                            capitalize
                        "
                    >
                        {{ vendor.company_name }} Reservation
                    </h3>
                    <single-reservation
                        :reservation="reservation"
                    ></single-reservation>
                </div>
            </div>
        </div>



        
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";

export default {
    props: ["reservation", "vendor"],
    data() {
        return {
            reservationData: this.reservation,
        };
    },
    components: {
        AppLayout,
    },
    methods: {},
    mounted() {
        if (this.vendor) {
            document.title = "Reservation With " + this.vendor.company_name;
        }
    },
};
</script>
