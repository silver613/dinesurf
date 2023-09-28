<template>
    <app-layout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 leading-tight capitalize"
            >
                {{ vendor.type }}
            </h2>
        </template>
        <div
            class="restaurant-card-img restaurant-card-img2"
            :style="'background-image: url(' + vendor.banner_url + ')'"
        >
            <banner-info :vendor="vendor"></banner-info>
        </div>

        <div class="flex flex-col mx-auto sm:px-6 lg:px-8 bg-white">
            <div class="bg-white overflow-hidden sm:rounded-lg restaurant-body">
                <div
                    id="vendorTabs"
                    class="flex justify-flex-start md:overflow-y-hidden overflow-y-scroll vendor-tab md:vendorTabs text-center"
                >
                    <div
                        class="w-24 text-sm"
                        :class="tab == 'info' ? 'active' : ''"
                        @click="toggleTab('info')"
                    >
                        Overview
                    </div>

                    <div
                        class="w-24 text-sm md:hidden"
                        :class="!tab || tab == 'reservation' ? 'active' : ''"
                        @click="toggleTab('reservation')"
                    >
                        Reserve
                    </div>

                    <div
                        class="w-24 text-sm"
                        :class="tab == 'gallery' ? 'active' : ''"
                        @click="toggleTab('gallery')"
                    >
                        Photos
                    </div>

                    <div
                        class="w-24 text-sm"
                        :class="tab == 'reviews' ? 'active' : ''"
                        @click="toggleTab('reviews')"
                    >
                        Reviews
                    </div>

                    <div
                        v-if="this.menus?.length > 0"
                        class="w-24 text-sm"
                        :class="tab == 'menus' ? 'active' : ''"
                        @click="toggleTab('menus')"
                    >
                        Menus
                    </div>

                    <div
                        class="w-24 text-sm hidden md:block"
                        :class="tab == 'events' ? 'active' : ''"
                        @click="toggleTab('events')"
                    >
                        Events
                    </div>
                </div>

                <div class="pt-0 md:pb-48">
                    <div
                        class="vendor-tab-info w-full"
                        v-if="!tab || tab == 'info'"
                    >
                        <info
                            :vendor="vendor"
                            :events="events"
                            :days="days"
                        ></info>
                    </div>

                    <div v-if="tab == 'gallery'" class="w-full">
                        <images :vendor="vendor"></images>
                    </div>

                    <div v-if="tab == 'menus'" class="w-full md:h-[200px]">
                        <Menu :vendor="vendor" :menus="this.menus"></Menu>
                    </div>

                    <div v-if="tab == 'events'" class="w-full md:px-0 px-5">
                        <events :events="events" :vendor="vendor" />
                    </div>

                    <div
                        v-if="tab == 'reservation'"
                        class="w-full flex text-center justify-center content-center item-center"
                    >
                        <make-reservation
                            :vendor="vendor"
                            :paystackkey="paystackkey"
                        ></make-reservation>
                    </div>

                    <div v-if="tab == 'reviews'" class="w-full p-6">
                        <h3>Reviews</h3>
                        <div class="flex w-full justify-center">
                            <div class="w-full md:w-1/2 flex justify-center">
                                <reviews :vendor="vendor"></reviews>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import MakeReservation from "@/components/vendor/MakeReservation.vue";
import Images from "@/components/vendor/UserGallery.vue";
import Reviews from "@/components/vendor/Reviews.vue";
import Info from "@/components/vendor/Info.vue";
import BannerInfo from "@/components/vendor/BannerInfo.vue";
import Menu from "@/components/vendor/Menu.vue";
import Events from "@/components/vendor/Events.vue";

export default {
    props: [
        "resource_name",
        "vendor",
        "step",
        "menus",
        "events",
        "days",
        "paystackkey",
    ],
    components: {
        AppLayout,
        Welcome,
        MakeReservation,
        Images,
        Reviews,
        Info,
        BannerInfo,
        Menu,
        Events,
    },
    data() {
        return {
            exampleModalShowing: false,
            tab: "info",
        };
    },
    methods: {
        toggleTab(tab) {
            //   var url = window.location.pathname + "?tab=" + tab;
            this.$inertia.get(
                route("restaurants.index", { id: this.vendor.id }),
                { tab: tab },
                {
                    preserveState: true,
                    preserveScroll: true,
                    onFinish: (visit) => {
                        this.tab = tab;
                    },
                }
            );
        },
      load(){
            const urlSearchParams = new URLSearchParams(window.location.search);
            this.params = Object.fromEntries(urlSearchParams.entries());
            this.tab = this.params.tab ?  this.params.tab : "info";

       }
  },
    mounted() {
        if (this.step) {
            this.tab = this.step;
        }
        // if (this.vendor) {
        //     document.title = this.vendor.company_name + " - Make a reservation";
        // }
    },
};
</script>
