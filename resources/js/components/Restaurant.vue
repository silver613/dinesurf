<template v-if="restaurantData">
    <div
        class="single-restaurant-card cursor-pointer flex flex-col items-left flex-shrink-0 mb-6 pb-6 space-x-4 mr-3 rounded-lg justify-start w-64 shadow-md jump h-72"
    >
        <div class="position-relative" style="position: relative">
            <div
                class="flex justify-between items-center position-absolute w-full pl-3 pr-5 h-7 mt-5"
                style="position: absolute"
                v-if="restaurantData.latest_deal"
            >
                <div class="mt-2" v-if="restaurantData.latest_deal">
                    <div class="deal">
                        {{ restaurantData.latest_deal.title }}
                    </div>
                </div>
                <div class="flex items-center justify-end flex-grow">
                    <share-button
                        :id="restaurantData.id"
                        class="mt-3"
                    ></share-button>
                    <like :model="restaurantData"></like>
                </div>
            </div>

            <div
                class="flex justify-between items-center position-absolute w-full pl-3 pr-5 h-7 mt-5"
                v-if="!restaurantData.latest_deal"
                style="position: absolute"
            >
                <share-button
                    :id="restaurantData.id"
                    class="mt-3"
                ></share-button>
                <like :model="restaurantData"></like>
            </div>

            <Link
                :href="
                    route('restaurants.index', [
                        {
                            id: restaurantData.id,
                        },
                        {
                            searched: searched,
                            searched_id: searched_id,
                            source: $page.props.tracking.source,
                            campaign_id: $page.props.tracking.campaign_id,
                        },
                    ])
                "
                class="flex flex-col items-start restarant-comp-card"
            >
              <div
                    class="restaurant-card-img bg-grey rounded-t-md"
                    :style="
                        'background-image: url(' +
                        restaurantData.banner_url +
                        ')'
                    "
                ></div>

                <div
                    class="pr-4 py-2 pb-3 w-11/12 flex flex-col overflow-auto m-0 pl-5"
                >
                    <div class="flex items-start">
                        <h4
                            class="text-medium text-blogtext-dark font-bold clamp-text max-w-90"
                        >
                            {{
                                restaurantData.company_name.length > 18
                                    ? restaurantData.company_name.substring(
                                          0,
                                          18
                                      ) + ".."
                                    : restaurantData.company_name
                            }}
                        </h4>
                        <img
                            v-if="restaurantData.verified"
                            src="https://img.icons8.com/color/48/000000/verified-badge.png"
                            class="h-5 mt-0 ml-1"
                        />
                    </div>

                    <div class="mt-1 whitespace-normal h-5 clamp-text max-w-90">
                        <i class="fa fa-location-dot mr-2"></i>
                        {{ restaurantData.company_address }}
                    </div>
                    <div
                        class="mt-1 whitespace-normal h-5 clamp-text max-w-90 text-xs"
                    >

                    <open-hour  :vendor="restaurantData"  :noDropDown="true" />
                 </div>
                    <div
                        class="flex mt-1 justify-between w-full capitalize clamp-text mx-w-90 whitespace-normal"
                        v-if="restaurantData.cuisines"
                    >
                     
                        <div
                            class="flex flex-wrap h-4"
                            v-if="restaurantData.cuisines.length > 0"
                        >
                            <span
                                v-for="(item, index) in restaurantData.cuisines"
                                :key="index"
                                class="flex items-center"
                            >
                                <span style="font-size: 4px" v-if="index != 0"
                                    >&#9679;</span
                                >
                                <span class="mx-1 text-xs">{{
                                    item.name
                                }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </Link>

            <div class="flex w-11/12 pl-5">
                <a
                    :href="
                        route('restaurants.index', [
                            { id: restaurantData.id },
                        ]) + '?tab=info'
                    "
                    class="btn-width btn-width-expand btn inline-flex justify-between items-center px-1 py-1 bg-red-600 border border-transparent rounded-md text-xs text-white tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition mx-1 font-bold"
                    >Reserve
                </a>

                <!-- ratings -->

                <a
                    :href="
                        route('restaurants.index', [
                            { id: restaurantData.id },
                        ]) + '?tab=info'
                    "
                    class="btn inline-flex justify-between items-center btn-width border border-black rounded-md font-bold text-xs text-black tracking-widest transition mx-1"
                >
                    <span class="fa fa-star text-yellow-500"></span>
                    <span class="text-black">{{
                        restaurantData.average_rating
                    }}</span>
                </a>

                <a
                    :href="
                        route('restaurants.index', [
                            { id: restaurantData.id },
                        ]) + '?tab=info'
                    "
                    class="btn inline-flex justify-between items-center px-1 border border-black rounded-md font-bold text-xs text-black tracking-widest transition mx-1"
                    >{{ restaurantData.average_menu_price_nairas }}
                </a>

                <button
                    class="btn inline-flex justify-between items-center py-1 px-3 border border-black rounded-md font-bold text-black tracking-widest mx-1 transition jump"
                    v-if="restaurantData.youtube_link"
                    @click="showVideoModal = !showVideoModal"
                >
                    <i class="fa fa-video" style="font-size: 9px"></i>
                </button>
            </div>

            <jet-modal
                :show="showVideoModal"
                @close="showVideoModal = false"
                :key="restaurantData.id"
            >
                <div class="p-6 w-full">
                    <h3
                        class="text-lg mb-2 font-medium text-gray-900 capitalize"
                    >
                        {{ restaurantData.company_name }} Tour Video
                    </h3>
                    <div
                        class="flex flex-col justify-center items-center w-full"
                    >
                        <iframe
                            class="youtube_video"
                            :src="restaurantData.youtube_link"
                            title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            </jet-modal>
        </div>
    </div>
</template>
<script>
import moment from "moment";
import OpenHour from "@/components/vendor/OpenHour.vue";
export default {
    props: ["restaurant", "searched", "searched_id"],
    components: {
        OpenHour
    },
    data() {
        return {
            restaurantData: this.restaurant,
            showVideoModal: false,
        };
    },

    methods: {
      
    },
    mounted() {
      

    },
};
</script>
