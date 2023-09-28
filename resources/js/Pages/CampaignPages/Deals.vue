<template >
  <app-layout
    :page="$page.props.page"
    :query="query"
    :city_id="city_id"
    :state_id="state_id"
    @setCityId="setCityId"
    @setStateId="setStateId"
    class="deals-page"
  >
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dining Reservations Made Easy
      </h2>
    </template>

    <!-- intro -->
    <div
      class="flex md:flex-row flex-col w-full md:h-full pt-5 p-10 px-4 md:p-0"
    >
      <div class="w-full flex flex-row justify-center item-center md:pl-20">
        <div class="item-center justify-center flex flex-col">
          <span class="deal-h text-dinesurf-green-500"
            >Looking for Restaurants with discounts?
          </span>
          <span class="deal-p"
            >Get 10% off your bill when you make reservations at any restaurant
            of your choice, using <b>DINESURF</b> From Friday 8th of July -
            Sunday, July 28th.
          </span>
          <div class="mt-5">
            <button
              class="
                px-4
                md:px-8
                py-2
                md:py-4
                text-sm
                md:text-lg
                font-semibold
                text-white
                bg-dinesurf-red-500
                hover:bg-dinesurf-red-600
                items-center
                md:w-auto
                w-[9rem]
                text-center
                rounded-sm
                jump
              "
              @click="scrollToElement('restaurants_with_deals')"
            >
              Discover Now
            </button>
          </div>
        </div>
      </div>
      <div
        class="
          w-full
          md:pl-40 md:h-94
          flex flex-row
          justify-center
          text-center
          md:pr-20
          mt-7
          md:mt-auto
        "
      >
        <img
          src="/images/sideBanner.png"
          class="sideBannerContainer h-14 md:h-auto"
        />
      </div>
    </div>

    <!-- Restaurants with deals -->
    <div class="py-1 px-4 md:px-0 md:pl-20 mb-10" id="restaurants_with_deals">
      <div class="mb-10" id="restaurants">
        <span class="deal-h text-dinesurf-green-500">
          Restaurants with deals</span
        >
        <p>
          Browse a plethora of restaurants under DINESURF that are currently
          offering deals.
        </p>
      </div>
      <LoadingCard
        :loading="loading"
        :title="title"
        :loaded="loaded"
        @loadData="loadData()"
      >
        <div
          v-if="models.data.length > 0"
          class="
            flex flex-col
            restaurant-comp
            bg-white
            overflow-hidden
            sm:rounded-lg
          "
        >
          <div class="flex justify-center md:justify-start flex-wrap">
            <div class="" v-for="(item, index) in models.data" :key="index">
              <single-restaurant
                :restaurant="item"
              ></single-restaurant>
            </div>
          </div>
          <pagination
            v-if="models.data.length > 0 && models.links"
            class="mt-6"
            :links="models.links"
          />
        </div>
        <div
          v-else
          class="flex flex-col restaurant-comp bg-white overflow-hidden"
        >
          <div class="flex flex-col justify-center mb-10">
            <h5 class="text-center text-lg font-medium text-gray-900">
              No {{ title }} available
            </h5>
          </div>
        </div>
      </LoadingCard>
    </div>

    <!-- Why use dinesurf -->
    <div class="flex flex-col mt-5 md:p-20 md:pt-10 p-5">
      <div class="flex flex-row justify-between">
        <span class="deal-h">Why use Dinesurf?</span>
      </div>

      <div class="flex flex-row justify-between flex-wrap mt-5">
        <div
          class="
            bg-slate-100
            p-5
            pb-10
            md:pb-5
            mt-5
            flex flex-col
            justify-between
            md:w-96
            w-full
            h-auto
            md:h-64
            jump
            cursor-pointer
          "
          v-for="(item, index) in why_use_dinesurf"
          :key="index"
        >
          <div>
            <img :src="item.icon" />
          </div>

          <div class="h-1/2">
            <h3 class="font-normal deal-min-h">{{ item.title }}</h3>
            <p>{{ item.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- testimonials -->
    <div class="flex flex-col md:flex-row jsutify-start h-full searchBtnBg">
      <div class="containerTesti p-0 bg-yellow justify-start flex-row flex">
        <img src="/images/hero_1.PNG" />
      </div>

      <div
        class="
          bg-green
          flex flex-col
          md:justify-center md:h-96
          h-80
          text-center
          w-full
        "
      >
        <span
          class="text-white font-bold text-center text-lg deal-h md:mt-0 mt-5"
          >Testimonials</span
        >

        <div class="">
          <Slides :sliderItems="testimonials" />
        </div>
      </div>

      <div></div>
    </div>

    <!-- Join Mailing list -->
    <div class="newsletterContainer md:p-20 p-5 mt-10 mb-10">
      <div
        class="
          bg-dinesurf-green-500
          md:p-20
          p-5
          text-center
          flex flex-col
          justify-center
          items-center
        "
      >
        <div class="flex flex-col text-white">
          <span class="font-normal newsletter-h"
            >Subscribe To Get Newsletter</span
          >
          <span
            >Get discounts, updates and reminders sent directly to your mail.
            <br />
            Be a part of the Dinesurf Community
          </span>
        </div>

        <div class="w-full mt-5 flex md:w-1/2">
          <join-mailing-list></join-mailing-list>
        </div>
      </div>
    </div>

    <!--footer  -->
    <div>
      <Footer />
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import RestaurantsComp from "@/components/Restaurants.vue";
import Footer from "@/components/Footer.vue";
import JetTestimonyIcon from "@/Jetstream/TestimonyIcon.vue";
import Slides from "@/components/Slides.vue";
import JoinMailingList from "@/components/JoinMailingList.vue";

export default {
  props: {
    title: String,
  },
  components: {
    Slides,
    AppLayout,
    Welcome,
    RestaurantsComp,
    JetTestimonyIcon,
    Footer,
    JoinMailingList,
  },
  data() {
    return {
      testimonials: [
        {
          date: "Wednesday, 18th of August 2021  by  22:23 PM",
          title: "Bolaji, @thesocialmediaoga",
          description:
            "I made my reservation on Dinesurf it was pretty last minute and it was approved in minutes, I didn't have to call and it was free",
        },
        {
          date: "Wednesday, 18th of August 2021  by  22:23 PM",
          title: "The Adetoun, Food Blogger",
          description:
            "Before I visit a really popular high-end restaurant, I check reviews of people that have gone there, so I can be better prepared, I then made my reservations all using Dinesurf, and I would say that changed the game for me",
        },
        {
          date: "Wednesday, 18th of August 2021  by  22:23 PM",
          title: "Delphinato",
          description:
            "On Valentine’s day I arrived at the restaurant with my bobo, and we didn’t like the menu there so we quickly checked Dinesurf and found another restaurant, we made a reservation immediately and when we arrived there the ambience and everything else was lovely",
        },
      ],
      why_use_dinesurf: [
        {
          icon: "/images/why_use/search.svg",
          title: "Search and discover",
          description:
            "Search and discover restaurants using filter like price , rating cuisine and location",
        },
        {
          icon: "/images/why_use/save.svg",
          title: "Save restaurants",
          description: "Save restaurants for later visit or reservation.",
        },
        {
          icon: "/images/why_use/history.svg",
          title: "History of reservations",
          description:
            " See a personalized list of restaurants you have made reservations with.",
        },
        {
          icon: "/images/why_use/personal.svg",
          title: "Personalized service",
          description:
            " Additional info in your reservations allows restaurants serve your unique preferences.",
        },
        {
          icon: "/images/why_use/better.svg",
          title: "Better knowledge",
          description:
            "Detailed description of restaurants from prices to dress code and reviews.",
        },
        {
          icon: "/images/why_use/reservation.svg",
          title: "Make reservations easily",
          description:
            "Info on your preferred time and date without the hassles of phone calls or emails.",
        },
      ],
      address: localStorage.getItem("address")
        ? localStorage.getItem("address")
        : "unknown",
      state_id: this.$page.props.search.state_id,
      city_id: this.$page.props.search.city_id,
      query: this.$page.props.search.query,
      models: {
        data: [],
        links: [],
        total: 0,
      },
      loading: true,
      loaded: false,
    };
  },
  methods: {
    scrollToElement(id) {
      var element = document.getElementById(id);
      element.scrollIntoView({
        behavior: "smooth",
        block: "start",
        inline: "nearest",
      });
    },
    getLocationManually() {
      console.log("", this.state_id, this.city_id);

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
    loadData() {
      this.loaded = false;
      this.loading = true;
      this.models = {
        data: [],
        links: [],
        total: 0,
      };

      axios
        .get(route("data.restaurants", { deals: 1 }))
        .then((result) => {
          this.models = result.data.models;
          this.loading = false;
          this.loaded = true;
          // var vm = this;
          // setTimeout(
          //   () => {
          //     vm.showFilters = true;
          //   },
          //   1000,
          //   vm
          // );
        })
        .catch((err) => {
          this.loaded = false;
          this.loading = false;
        });
    },
  },
  mounted() {
    this.loadData();
  },
};
</script>