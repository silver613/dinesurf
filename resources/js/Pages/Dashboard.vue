<template >
  <app-layout
    :page="$page.props.page"
    :query="query"
    :city_id="city_id"
    :state_id="state_id"
    @setCityId="setCityId"
    @setStateId="setStateId"
  >
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dining Reservations Made Easy<span>.</span>
      </h2>
    </template>

    <div class="bg-white">
      <div class="w-full home-banner-bg bg-gray-100">
        <div class="dark-overlay h-120 pt-8 pb-0">
          <!-- search  -->
          <global-SearchNew
            :type="'homeBanner'"
            :query="query"
            :city_id="city_id"
            :state_id="state_id"
          />
        </div>
      </div>

      <div class="w-auto pLine">
        <!-- <div class="border-b hidden md:block">
          <div class="border-b-2 border-red-500 w-auto flex mt-2 justify-between">
            <span class="text-xs font-bold mb-3"
              >Current location is <span class="capitalize">{{ address }}</span>
            </span>

            <button
              class="
                flex
                justify-between
                mb-3
                text-xs
                font-medium
                text-gray-700
              "
            >
              <JetLocationMarkerLogo
                width="15"
                height="10"
                color="#D82B2B"
                class="mt-1"
              />
              <span
                style="color: #d82b2b"
                class="text-[#D82B2B] text-xs cursor-pointer"
                @click="openLocationDropdown()"
              >
                If not change location or search by state</span
              >
            </button>
          </div>
        </div> -->

        <!-- <div class="mt-5 hidden md:block">
          <span class="mt-10 text-lg font-black"
            >Dinner Reservations Made Easy</span
          >
        </div> -->

         <restaurants-comp
          :title="'Featured Restaurants'"
          :seeAll="
            route('restaurants', {
              category: 'featured',
            })
          "
          :url="  
          route('data.restaurants',{
              category: 'featured',
              take: 15,
            })
          "
        ></restaurants-comp>

        <events-scroll     :seeAll=" route('all-events') "   :title="'Events on dinesurf'" /> 

         <restaurants-comp
          :title="'New To Dinesurf'"
          :seeAll="
            route('restaurants', {
              category: 'new',
            })
          "
          :url="
            route('data.restaurants', {
              category: 'new',
              take: 15,
            })
          "
        ></restaurants-comp>


        <restaurants-comp
          :title="'Restaurants near you'"
          :seeAll="
            route('restaurants', {
              category: 'near',
            })
          "
          :url="
            route('data.restaurants', {
              category: 'near',
              take: 15,
            })
          "
        ></restaurants-comp>

        <restaurants-comp
          :title="'Popular Restaurants'"
          :seeAll="
            route('restaurants', {
              category: 'popular',
            })
          "
          :url="
            route('data.restaurants', {
              category: 'popular',
              take: 15,
            })
          "
        ></restaurants-comp>

        <restaurants-comp
          :title="'Lagos Restaurants'"
          :seeAll="
            route('restaurants', {
              city: 'lagos',
            })
          "
          :url="
            route('data.restaurants', {
              city: 'lagos',
              take: 15,
            })
          "
        ></restaurants-comp>

        <restaurants-comp
          :title="'Abuja Restaurants'"
          :seeAll="
            route('restaurants', {
              city: 'abuja',
            })
          "
          :url="
            route('data.restaurants', {
              city: 'abuja',
              take: 15,
            })
          "
        ></restaurants-comp>

        <restaurants-comp
          :title="'Port Harcourt Restaurants'"
          :seeAll="
            route('restaurants', {
              city: 'port harcourt',
            })
          "
          :url="
            route('data.restaurants', {
              city: 'port harcourt',
              take: 15,
            })
          "
        ></restaurants-comp>


        <restaurants-comp
          :title="'Italian food in Lagos '"
          :seeAll="
            route('restaurants', {
              cuisine: 'italian',
              city: 'lagos'
            })
          "
          :url="
            route('data.restaurants', {
              cuisine: 'italian',
              city: 'lagos',
              take: 15,
            })
          "
        ></restaurants-comp>


        <restaurants-comp
          :title="'Italian food in Abuja '"
          :seeAll="
            route('restaurants', {
              cuisine: 'italian',
              city: 'abuja'
            })
          "
          :url="
            route('data.restaurants', {
              cuisine: 'italian',
              city: 'abuja',
              take: 15,
            })
          "
        ></restaurants-comp>

        <restaurants-comp
          :title="'Italian food in PH '"
          :seeAll="
            route('restaurants', {
              cuisine: 'italian',
              city: 'port harcourt'
            })
          "
          :url="
            route('data.restaurants', {
              cuisine: 'italian',
              city: 'port harcourt',
              take: 15,
            })
          "
        ></restaurants-comp>






        <restaurants-comp
          :title="'Mexican food in Lagos '"
          :seeAll="
            route('restaurants', {
              cuisine: 'mexican',
              city: 'lagos'
            })
          "
          :url="
            route('data.restaurants', {
              cuisine: 'mexican',
              city: 'lagos',
              take: 15,
            })
          "
        ></restaurants-comp>


        <restaurants-comp
          :title="'Mexican food in Abuja '"
          :seeAll="
            route('restaurants', {
              cuisine: 'mexican',
              city: 'abuja'
            })
          "
          :url="
            route('data.restaurants', {
              cuisine: 'mexican',
              city: 'abuja',
              take: 15,
            })
          "
        ></restaurants-comp>

        <restaurants-comp
          :title="'Mexican food in PH'"
          :seeAll="
            route('restaurants', {
              cuisine: 'mexican',
              city: 'port harcourt'
            })
          "
          :url="
            route('data.restaurants', {
              cuisine: 'mexican',
              city: 'port harcourt',
              take: 15,
            })
          "
        ></restaurants-comp>




        <restaurants-comp
          :title="'Chinese food in Lagos '"
          :seeAll="
            route('restaurants', {
              cuisine: 'chinese',
              city: 'lagos'
            })
          "
          :url="
            route('data.restaurants', {
              cuisine: 'chinese',
              city: 'lagos',
              take: 15,
            })
          "
        ></restaurants-comp>


        <restaurants-comp
          :title="'Chinese food in Abuja '"
          :seeAll="
            route('restaurants', {
              cuisine: 'chinese',
              city: 'abuja'
            })
          "
          :url="
            route('data.restaurants', {
              cuisine: 'chinese',
              city: 'abuja',
              take: 15,
            })
          "
        ></restaurants-comp>

        <restaurants-comp
          :title="'Chinese food in PH '"
          :seeAll="
            route('restaurants', {
              cuisine: 'chinese',
              city: 'port harcourt'
            })
          "
          :url="
            route('data.restaurants', {
              cuisine: 'chinese',
              city: 'port harcourt',
              take: 15,
            })
          "
        ></restaurants-comp>





       
       

        <div class="w-auto bg-gray-100 py-20 mb-10">
          <div class="text-center justify-center item-center content-center">
            <h3>Restaurateurs Join Us</h3>
            <p class="justifyContentonxs mt-2">
              Join over 100,000 restauranteurs using technology to run their
              restaurant better.
            </p>
            <div class="mt-10">
              <a
                href="https://dinesurf.com/for-restaurants/"
                target="_blank"
                class="
                  text-center
                  mt-10
                  px-6
                  py-2
                  searchBtnBg
                  text-sm
                  font-medium
                  rounded
                "
              >
                <span class="text-white"> Learn more </span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!--footer  -->
      <Footer />
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import RestaurantsComp from "@/components/Restaurants.vue";
import JetLocationMarkerLogo from "@/Jetstream/LocationMarkerLogo.vue";
import Footer from "@/components/Footer.vue";
import EventsScroll  from "@/components/EventsScroll.vue"
export default {
  components: {
    AppLayout,
    Welcome,
    RestaurantsComp,
    JetLocationMarkerLogo,
    Footer,
    EventsScroll
  },
  data() {
    return {
      address: localStorage.getItem("state_name")
        ? localStorage.getItem("state_name")
        : "unknown",
      state_id: localStorage.getItem("state_id"),
      city_id: this.$page.props.search.city_id,
      query: this.$page.props.search.query,
    };
  },
  methods: {
    getLocationManually() {
      // console.log("manually getting location...", this.state_id, this.city_id);

      if (this.city_id) {
        axios
          .get(
            route("data.city", {
              id: this.city_id,
            })
          )
          .then((result) => {
            this.address = result.data.name;
          })
          .catch((err) => {
            console.log(err);
          });
      }

      if (this.state_id) {
        axios
          .get(
            route("data.state", {
              id: this.state_id,
            })
          )
          .then((result) => {
            if (this.address == "unknown") {
              this.address = result.data.name;
            } else {
              this.address += ", " + result.data.name;
            }
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },
    setStateId(data) {
      console.log("dashboard state id:", data);
      this.state_id = data;
    },
    setCityId(data) {
      console.log("dashboard city id:", data);
      this.city_id = data;
    },
  },
  mounted() {
    document.title = "Dining Reservations Made Easy - Dinesurf";
    document.querySelector("link[rel=icon]").href = "/images/favicon.jpg";
    this.address = localStorage.getItem("address")
      ? localStorage.getItem("address")
      : "unknown";

    if (this.address == "unknown") {
      this.getLocationManually();
    }
  },
  // created() {
  //   this.address = localStorage.getItem("address")
  //     ? localStorage.getItem("address")
  //     : "unknown";
  //   if (this.address == "unknown") {
  //     this.getLocationManually();
  //   }
  // },
};
</script>