<template>
  <div class="flex justify-center">
    <div
      class="py-6 px-3"
      :class="
        type == 'homeBanner'
          ? 'md:px-0 w-full  md:w-2/3'
          : 'md:w-2/2  xs:w-[24rem]'
      "
    >
      <div class="text-center w-full">
        <h3
          :class="type == 'homeBanner' ? 'text-white' : ''"
          class="text-center font-Nunito letter-spacing"
          style="line-spancing: 23px"
        >
          {{ search ? "Search results" : "Discover Unforgettable Dining in Africa!" }}
        </h3>
      </div>
      <form @submit.prevent="mainSearch()">
        <div
          class="container flex mt-2"
          :class="type == 'homeBanner' ? 'justify-center' : ''"
        >
          <div
            class="
              flex
              justify-around
              searchContainer
              flex-wrap
              md:flex-nowrap
              w-auto
            "
          >
            <!-- <div class="flex-basis mx-2">
              <label
                for="party_size"
                class="text-xs mb-1 text-white font-bold"
                :class="type == 'homeBanner' ? 'text-white' : 'text-black'"
                >Date</label
              >
              <input
                v-model="params.date"
                :class="
                  type == 'homeBanner'
                    ? 'bg-transparent text-white border-solid border-white datePickerStyling select-w-1'
                    : 'select-w-1'
                "
                type="date"
                name="date"
                id="date"
                class="block text-xs w-32 h-9 border rounded-sm border-solid"
              />
            </div>
            <div class="flex-basis mx-2">
              <label
                for="party_size"
                class="text-xs mb-1 text-white font-bold"
                :class="type == 'homeBanner' ? 'text-white' : 'text-black'"
                >Time</label
              >
              <input
                :class="
                  type == 'homeBanner'
                    ? 'bg-transparent text-white border-solid border-white select-w-1 '
                    : 'select-w-1'
                "
                name="time"
                v-model="params.time"
                type="time"
                id="time"
                class="block py-0 w-32 h-9 border rounded-sm border-solid"
              />
            </div>

            <div class="flex-basis mx-2">
              <label
                for="party_size"
                class="text-xs mb-1 text-white font-bold"
                :class="type == 'homeBanner' ? 'text-white' : 'text-black'"
                >Party Size</label
              >
              <input
                :class="
                  type == 'homeBanner'
                    ? 'bg-transparent text-white border-solid border-white select-w-1'
                    : 'select-w-1'
                "
                v-model="params.party_size"
                min="1"
                name="party_size"
                type="number"
                id="party_size"
                class="block w-32 h-9 border rounded-sm border-solid"
              />
            </div>
            <div class="flex-basis mx-2 dropCuisinContainer">
              <label
                for="party_size"
                class="text-xs mb-1 text-white font-bold"
                :class="type == 'homeBanner' ? 'text-white' : 'text-black'"
                >Cuisine</label
              >
              <select
                name="cuisine"
                id="Cuisine"
                class="block w-full h-9 border rounded-sm border-solid w-32"
                :class="type == 'homeBanner' ? 'select-w-1' : 'select-w-1'"
                v-model="params.cuisine"
              >
                <option :value="null"></option>
                <option
                  v-for="(item, index) in cuisines"
                  :key="index"
                  :value="item.id"
                >
                  {{ item.name }}
                </option>
              </select>
            </div> -->
          </div>
        </div>

        <div class="w-full mt-5">
          <div class="searchInputContainer w-full">
            <input
              type="search"
              name="query"
              v-model="params.query"
              id="query"
              placeholder="Search Restaurant"
              class="
                bg-white
                h-9
                px-5
                pr-10
                w-full
                focus:outline-none
                searchInput
                text-base
              "
            />
            <button
              type="submit"
              class="
                search-btn-2
                absolute
                top-0
                right-0
                p-2.5
                text-sm
                font-medium
                text-white
                searchBtnBg
                rounded-r-lg
                border
                focus:ring-4 focus:outline-none
              "
            >
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                ></path>
              </svg>
            </button>

            <div class="iconSearchContainer-01">
              <JetSearchIcon class="text-slate-300 iconSearch-01" />
            </div>
          </div>

          <!-- <div class="flex justify-center mt-5">
            <button
              type="submit"
              class="
                flex
                justify-center
                text-center
                w-full
                px-4
                py-2
                searchBtnBg
                text-sm
                font-medium
                text-gray-700
                rounded
              "
            >
              <span class="text-white">
                {{ search ? "Show result" : "Search" }}
              </span>
            </button>
          </div> -->
        </div>
      </form>
    </div>
  </div>
</template>


<script>
import JetSearchIcon from "@/Jetstream/SearchIcon.vue";
import { throttle } from "lodash";
export default {
  props: [
    "type",
    "search",
    "date",
    "time",
    "partySize",
    "query",
    "cuisine",
    "state_id",
    "city_id",
  ],

  components: {
    JetSearchIcon,
  },
  data() {
    return {
      cuisines: [],
      states: [],
      params: {
        query: this.query,
        cuisine: this.cuisine,
        party_size: this.partySize,
        date: this.date,
        time: this.time,
        state_id: this.state_id,
        city_id: this.city_id,
      },
    };
  },
  watch: {
    state_id: {
      handler: throttle(function () {
        this.params.state_id = this.state_id;
      }, 150),
      deep: true,
    },
    city_id: {
      handler: throttle(function () {
        this.params.city_id = this.city_id;
      }, 150),
      deep: true,
    },
  },

  methods: {
    getCuisines() {
      axios(route("cuisines"))
        .then((result) => {
          this.cuisines = result.data.data.cuisines;
          // console.log(" this.cuisines", this.cuisines);
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },

    getStates(country_id) {
      axios(
        route("states", {
          country_id: country_id,
        })
      )
        .then((result) => {
          this.states = result.data.data;
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },
    mainSearch() {
      this.$inertia.get(
        route("search-page", { type: "restaurant" }),
        {
          ...this.params,
        },
        {
          replace: true,
          preserveState: true,
          preserveScroll: true,
          onFinish: (visit) => {
            this.$emit("loadData");
          },
        }
      );
    },
  },
  mounted() {
    this.getCuisines();
    this.getStates(128);
  },
};
</script>