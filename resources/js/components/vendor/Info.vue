<template>
    <div class="main_info justify-between">
        <div class="info-section shadow-xl lg:w-5/6 p-5">
            <div class="info">
                <span class="text-base font-bold">Information</span>
                <div
                    class="py-4"
                    v-if="this.vendor.description?.length > 200 && !isTextShow"
                >
                    <p v-html="textDescription"></p>
                    <span
                        class="text-xs text-center cursor-pointer underline text-black"
                        @click="isTextShow = !isTextShow"
                        >See more</span
                    >
                </div>

                <div
                    class="py-4"
                    v-else-if="
                        this.vendor.description?.length > 200 && isTextShow
                    "
                >
                    <p v-html="this.vendor.description"></p>
                    <span
                        class="text-xs text-center cursor-pointer underline text-black"
                        @click="isTextShow = !isTextShow"
                        >See less</span
                    >
                </div>

                <div class="py-4 flex" v-else>
                    <p v-html="textDescription"></p>
                </div>
            </div>

            <div
                class="contact-info flex flex-col md:flex-row md:justify-between md:mt-18"
            >
                <div
                    class="contact-info flex md:hidden justify-between md:mt-0"
                >
                    <div>
                        <div class="flex">
                            <div>
                                <img src="/images/Icon ionic-md-time.svg" />
                            </div>
                            <div class="ml-2">
                                <h6 class="text-medium font-bold">
                                    Hours of operation
                                </h6>

                                <open-hour :vendor="this.vendor" />
                            </div>
                        </div>

                        <div class="flex mt-1">
                            <div>
                                <img src="/images/Icon feather-phone.svg" />
                            </div>
                            <div class="ml-2" v-if="vendor.company_phone">
                                <h6 class="text-medium font-bold">
                                    Phone number
                                </h6>
                                <a
                                    :href="'tel:' + vendor.company_phone"
                                    class="text-xs mt-0"
                                    >{{ vendor.company_phone }}</a
                                >
                            </div>
                        </div>

                        <div class="flex mt-3">
                            <div>
                                <img
                                    src="/images/Icon material-location-on.svg"
                                />
                            </div>
                            <div class="ml-2">
                                <h6 class="text-medium font-bold">Location</h6>
                                <span class="text-xs mt-0"
                                    >{{ vendor.company_address }}
                                    <span v-if="vendor.city"
                                        >, {{ vendor.city.name }}</span
                                    >
                                    <span v-if="vendor.state"
                                        >, {{ vendor.state.name }}</span
                                    >
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex" v-if="vendor.features.length > 0">
                        <div>
                            <img src="/images/dinner-table.svg" />
                        </div>
                        <div class="ml-2">
                            <h6 class="text-medium font-bold">Dining Style</h6>
                            <div
                                v-for="(item, index) in vendor.features"
                                :key="index"
                            >
                                <span class="text-xs mt-0"
                                    >• {{ item.name }}</span
                                >
                            </div>
                            <!-- <span class="text-xs mt-0">Fine Dining</span> -->
                        </div>
                    </div>

                    <div class="flex mt-1" v-if="vendor.cuisines.length > 0">
                        <div>
                            <img src="/images/Icon metro-spoon-fork.svg" />
                        </div>
                        <div class="ml-2">
                            <h6 class="text-medium font-bold">Cuisine</h6>

                            <div v-if="vendor.cuisines.length <= 3">
                                <div
                                    v-for="(item, index) in vendor.cuisines"
                                    :key="index"
                                >
                                    <span class="text-xs mt-0"
                                        >• {{ item.name }}</span
                                    >
                                </div>
                            </div>

                            <div
                                v-else-if="
                                    vendor.cuisines.length > 3 && !isCuisineShow
                                "
                            >
                                <div
                                    v-for="(
                                        item, index
                                    ) in vendor.cuisines.slice(0, 3)"
                                    :key="index"
                                >
                                    <span class="text-xs mt-0"
                                        >• {{ item.name }}</span
                                    >
                                </div>

                                <span
                                    class="text-xs text-center cursor-pointer underline text-black"
                                    @click="isCuisineShow = !isCuisineShow"
                                    >see more...</span
                                >
                            </div>

                            <div
                                v-else-if="
                                    vendor.cuisines.length > 3 && isCuisineShow
                                "
                            >
                                <div
                                    v-for="(item, index) in vendor.cuisines"
                                    :key="index"
                                >
                                    <span class="text-xs mt-0"
                                        >• {{ item.name }}</span
                                    >
                                </div>

                                <span
                                    class="text-xs text-center cursor-pointer underline text-black"
                                    @click="isCuisineShow = !isCuisineShow"
                                    >see less...</span
                                >
                            </div>
                        </div>
                    </div>

                    <div class="flex mt-1" v-if="vendor.menu_highlights">
                        <p>{{ vendor.menu_highlights }}</p>
                    </div>
                    <!-- <div class="flex mt-1" >
            <div class="ml-2">
              <h6 class="text-small font-bold">Menu Link</h6>
            </div>
          </div> -->

                    <div class="flex mt-1">
                        <div>
                            <img src="/images/Icon feather-external-link.svg" />
                        </div>
                        <div class="ml-2">
                            <h6 class="text-medium font-bold">
                                External Links
                            </h6>
                        </div>
                    </div>
                    <div
                        class="flex mt-5"
                        v-if="
                            vendor.facebook_link ||
                            vendor.instagram_link ||
                            vendor.twitter_link ||
                            vendor.whatsapp_number ||
                            vendor.menu_link
                        "
                    >
                        <div class="ml-2">
                            <div class="mb-1" v-show="vendor.menu_link">
                                <a
                                    class="text-dinesurf-red-500 cursor-pointer underline"
                                    :href="vendor.menu_link"
                                    target="_blank"
                                    >View Menu</a
                                >
                            </div>

                            <h6 class="text-small font-bold mt-2">
                                Social Links:
                            </h6>
                            <div class="flex social-icons">
                                <a
                                    v-if="vendor.facebook_link"
                                    :href="vendor.facebook_link"
                                    class="jump facebook-icon text-base px-5 py-3 rounded-full mr-5 cursor-pointer"
                                    target="_blank"
                                >
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a
                                    v-if="vendor.instagram_link"
                                    :href="vendor.instagram_link"
                                    class="jump instagram-icon text-base px-4 py-3 rounded-full mr-5 cursor-pointer"
                                    target="_blank"
                                >
                                    <!-- <img src="/images/instagram.png" /> -->
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a
                                    v-if="vendor.twitter_link"
                                    :href="vendor.twitter_link"
                                    class="jump twitter-icon text-base px-4 py-3 rounded-full mr-5 cursor-pointer"
                                    target="_blank"
                                >
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a
                                    v-if="vendor.whatsapp_number"
                                    :href="
                                        'https://api.whatsapp.com/send?phone=' +
                                        vendor.whatsapp_number +
                                        '&amp;text=Hello%20My%20name%20is%20(name)%20i%20found%20your%20restaurant%20on' +
                                        '%20dinesurf.com,%20I%20want%20to%20make%20a%20reservation'
                                    "
                                    class="jump whatsapp-icon text-base px-4 py-3 rounded-full mr-5 cursor-pointer"
                                    target="_blank"
                                >
                                    <i class="fab fa-whatsapp"></i>
                                </a>

                                <button
                                    class="jump instagram-icon text-base px-4 py-3 rounded-full mr-5 cursor-pointer"
                                    style="width: 46px; height: 48px"
                                    v-if="vendor.youtube_link"
                                    @click="showVideoModal = !showVideoModal"
                                >
                                    <i
                                        class="fa fa-video"
                                        style="font-size: 12px"
                                    ></i>
                                </button>
                            </div>

                            <div class="md:hidden mt-10">
                                <Link
                                    class="jet-btn"
                                    :href="
                                        route('restaurants.index', {
                                            id: vendor.id,
                                        }) + '?tab=reservation'
                                    "
                                    >Reserve with us Now</Link
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="contact-info hidden md:flex justify-between md:mt-0 mt-10"
                >
                    <div>
                        <div class="flex">
                            <div>
                                <img src="/images/Icon ionic-md-time.svg" />
                            </div>
                            <div class="ml-2">
                                <h6 class="text-medium font-bold mb-3">
                                    Hours of operation
                                </h6>
                                <open-hour :vendor="this.vendor" />
                            </div>
                        </div>

                        <div class="flex mt-1">
                            <div>
                                <img src="/images/Icon feather-phone.svg" />
                            </div>
                            <div class="ml-2" v-if="vendor.company_phone">
                                <h6 class="text-medium font-bold">
                                    Phone number
                                </h6>
                                <a
                                    :href="'tel:' + vendor.company_phone"
                                    class="text-xs mt-0"
                                    >{{ vendor.company_phone }}
                                </a>
                            </div>
                        </div>

                        <div class="flex mt-3">
                            <div>
                                <img
                                    src="/images/Icon material-location-on.svg"
                                />
                            </div>
                            <div class="ml-2">
                                <h6 class="text-medium font-bold">Location</h6>
                                <span class="text-xs mt-0"
                                    >{{ vendor.company_address }}
                                    <span v-if="vendor.city"
                                        >, {{ vendor.city.name }}</span
                                    >
                                    <span v-if="vendor.state"
                                        >, {{ vendor.state.name }}</span
                                    >
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="md:hidden md:mt-0 mt-20"
                    v-if="this.formatedEvents.length > 0"
                >
                    <events :events="this.events" />
                </div>
            </div>
            <div id="map" v-if="vendor.company_address" class="h-80 mt-4 mb-20"></div>
        </div>

        <!-- make reservation -->
        <div
            class="reservation-section shadow-xl lg:w-5/6 p-5 text-center justify-center content-center item-center md:block hidden"
        >
            <div>
                <make-reservation :vendor="vendor"></make-reservation>
            </div>
        </div>
        <jet-modal
            :show="showVideoModal"
            @close="showVideoModal = false"
            :key="vendor.id"
        >
            <div class="p-6 w-full">
                <h3 class="text-lg mb-2 font-medium text-gray-900 capitalize">
                    {{ vendor.company_name }} Tour Video
                </h3>
                <div class="flex flex-col justify-center items-center w-full">
                    <iframe
                        class="youtube_video"
                        :src="vendor.youtube_link"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </jet-modal>
    </div>
</template>

<script>
import MakeReservation from "@/components/vendor/MakeReservation.vue";
import Events from "@/components/vendor/Events.vue";
import OpenHour from "@/components/vendor/OpenHour.vue";

export default {
    props: ["vendor", "events", "days"],
    data() {
        return {
            showVideoModal: false,
            formatedEvents: this.events?.filter(
                (item) => item.event_type != "past"
            ),
            isCuisineShow: false,
            isTextShow: false,
            customDays: [],
            textDescription:
                this.vendor.description?.length > 200
                    ? this.vendor.description.slice(0, 200) + "..."
                    : this.vendor.description,
        };
    },
    components: {
        MakeReservation,
        Events,
        OpenHour,
    },

    methods: {
        initMap() {
            if (this.vendor.company_address) {
                // Get the text address from a variable or input field
                var address = this.vendor.company_address;

                // Create a geocoder object
                var geocoder = new google.maps.Geocoder();

                // Convert the text address to coordinates
                geocoder.geocode(
                    { address: address },
                    function (results, status) {
                        if (status === "OK") {
                            // Get the latitude and longitude from the geocoder response
                            var lat = results[0].geometry.location.lat();
                            var lng = results[0].geometry.location.lng();

                            // Create a new map instance
                            var map = new google.maps.Map(
                                document.getElementById("map"),
                                {
                                    center: { lat: lat, lng: lng },
                                    zoom: 15,
                                }
                            );

                            // Add a marker to the map
                            var marker = new google.maps.Marker({
                                position: { lat: lat, lng: lng },
                                map: map,
                                title: address,
                            });
                        } else {
                            // Handle geocoding errors
                            console.log(
                                "Geocode was not successful for the following reason: " +
                                    status
                            );
                        }
                    }
                );
            }
        },
    },
    mounted() {
     
        this.initMap();
    },
};
</script>
