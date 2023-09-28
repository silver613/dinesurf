<template>
    <div>
        <div class="bg-img" :style="bannerStyle">
            <div
                :class="{ 'dark-overlay': showBanner }"
                class="flex w-full justify-center py-16"
            >
                <div class="absolute">
                    <MakeReservation
                        :vendor="vendor"
                        :extraTitle="true"
                    ></MakeReservation>
                </div>
            </div>
        </div>
        <div class="bg-img" :style="bannerStyle">
            <div
                :class="{ 'dark-overlay': showBanner }"
                class="flex w-full justify-center py-16"
            ></div>
        </div>
        <div class="bg-img" :style="bannerStyle">
            <div
                :class="{ 'dark-overlay': showBanner }"
                class="flex w-full justify-center py-16"
            ></div>
        </div>
    </div>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import MakeReservation from "@/components/vendor/MakeReservation.vue";

export default {
    props: ["vendor"],
    components: {
        AppLayout,
        MakeReservation,
    },
    computed: {
        bannerStyle() {
            return this.vendor?.banner
                ? `background-image: url(${this.vendor.banner_url})`
                : "";
        },
        showBanner() {
            return this.vendor?.banner;
        },
    },
    mounted() {
        if (this.vendor) {
            document.title = this.vendor.company_name + " - Make a reservation";
        }
    },
};
</script>
