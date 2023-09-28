<template>
  <app-layout
    :page="$page.props.page"
    :city_id="city_id"
    :state_id="state_id"
    :query="query"
  >
    <div
      v-if="show"
      class="md:hidden w-screen fixed h-screen z-1024"
      @click="load()"
    ></div>
    <div class="w-full search-banner-bg bg-gray-100">
      <div class="dark-overlay h-120 pt-8 pb-0">
        <!-- search  -->
        <global-SearchNew
          :type="'homeBanner'"
          :search="true"
          :date="date"
          :time="time"
          :partySize="party_size"
          :query="query"
          :cuisine="cuisine"
          @loadData="setForm()"
        />
      </div>
    </div>

    <!-- Mobile Filter -->
    <div class="flex justify-center md:hidden pt-1">
      <JetDropdown align="center" width="64">
        <template #trigger>
          <button
            type="button"
            class="
              flex
              items-center
              justify-center
              border border-black
              px-[20px]
              md:px-2
              py-[3px]
              md:py-1
              bg-white
              text-xs
              md:text-sm
              font-medium
              text-black
              rounded-lg
              mt-4
            "
          >
            <i class="fa fa-filter mr-1 md:mr-2 font-bold"></i>
            <span class="font-bold">FILTER BY </span>
          </button>
        </template>

        <template #content>
          <JetDropdownLink :as="'div'" class="py-1">
            <SearchFilter
              :key="'searchFilter1'"
              :rating="rating"
              :price="price"
              :category="category"
              :cuisine="cuisine"
              @submitSearch="callSubmitSearch"
            ></SearchFilter>
          </JetDropdownLink>
        </template>
      </JetDropdown>
    </div>

    <div class="w-auto bg-white pLine md:pt-10 pb-10 h-auto flex">
      <div class="hidden md:flex mr-5">
        <div class="w-max">
          <h5>
            <i class="fa fa-filter mr-2 mt-1 font-bold"></i>
            <span class="font-bold">FILTER BY </span>
          </h5>
          <search-filter
            :key="'searchFilter2'"
            class="w-full"
            v-if="showFilters"
            :rating="rating"
            :price="price"
            :category="category"
            :cuisine="cuisine"
            @submitSearch="callSubmitSearch"
          ></search-filter>
        </div>
      </div>
      <div class="flex-grow">
        <div class="flex flex-row flex-wrap justify-between">
          <!-- btn list -->
          <div class="btn-list flex flex-wrap">
            <div class="m-1">
              <button type="button" class="search-pill searchBtnBg">
                <span class="text-white font-bold">All </span>
              </button>
            </div>

            <div class="m-1" v-if="form.query">
              <button type="button" class="search-pill">
                <span class="text-black font-bold">{{ form.query }} </span>
              </button>
            </div>
            <div class="m-1" v-if="form.party_size">
              <button type="button" class="search-pill">
                <span class="text-black font-bold"
                  >For {{ form.party_size }}
                </span>
              </button>
            </div>

            <div class="m-1" v-if="form.date">
              <button type="button" class="search-pill">
                <span class="text-black font-bold">{{ formatedDate }} </span>
              </button>
            </div>

            <div class="m-1" v-if="form.time">
              <button type="button" class="search-pill">
                <span class="text-black font-bold">{{ formatedTime }} </span>
              </button>
            </div>

            <div class="m-1" v-if="form.cuisine && getCuisine">
              <button type="button" class="search-pill">
                <span class="text-black font-bold">{{ getCuisine.name }} </span>
              </button>
            </div>

            <div class="m-1" v-if="stateName">
              <button type="button" class="search-pill">
                <span class="text-black font-bold">{{ stateName }} </span>
              </button>
            </div>

            <div class="m-1" v-if="cityName">
              <button type="button" class="search-pill">
                <span class="text-black font-bold">{{ cityName }} </span>
              </button>
            </div>

            <div class="m-1" v-if="form.price">
              <button type="button" class="search-pill">
                <span
                  class="text-black font-bold capitalize"
                  v-if="getPriceRange"
                >
                  Price: {{ numberWithCommas(getPriceRange.from, "NGN") }} -
                  {{ numberWithCommas(getPriceRange.to, "NGN") }}</span
                >
              </button>
            </div>

            <div class="m-1" v-if="form.category">
              <button type="button" class="search-pill">
                <span class="text-black font-bold capitalize">
                  {{
                    form.category !== "nearme"
                      ? form.category + " Restaurants"
                      : "Restaurants " + form.category
                  }}
                </span>
              </button>
            </div>

            <div class="m-1" v-if="form.rating">
              <button type="button" class="search-pill">
                <span class="text-black font-bold"
                  >{{ form.rating }} star
                </span>
              </button>
            </div>
          </div>
        </div>

        <div class="py-1 search-results">
          <div class="max-w-7xl mx-auto md:mt-5">
            <LoadingCard
              :loading="loading"
              :title="'Search Results'"
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
                  md:w-full
                  overflow-x-hideen
                "
              >
                <h3
                  class="
                    text-lg
                    font-medium
                    text-gray-900
                    mb-10
                    capitalize
                    hidden
                    md:block
                  "
                  v-if="models.data.length > 0"
                >
                  Results
                </h3>

                <div
                  class="
                    flex flex-col
                    md:flex-row
                    mt-1
                    justify-center
                    md:justify-start
                    w-[100%]
                    flex-wrap
                  "
                  v-if="models.data.length > 0"
                >
                  <div v-for="(item, index) in models.data" :key="index">
                    <SingleRestaurant
                      :restaurant="item"
                      :searched="true"
                      :searched_id="item.id"
                    ></SingleRestaurant>
                  </div>
                </div>
                <div v-if="models.data.length > 0">
                  <pagination
                    v-show="models.data.length > 8"
                    class="mt-6"
                    :links="models.links"
                  />
                </div>

                <div class="flex justify-center mb-10" v-else>
                  <h5 class="text-center text-lg font-medium text-gray-900">
                    We are sorry, we were not able to find a match
                    <span v-if="query">
                      for
                      <span class="font-bold text-xl text-red-500 capitalize">{{
                        query
                      }}</span>
                    </span>
                    with the specified filters
                    <br />
                    <i>Try Another Search ?</i>
                  </h5>
                  <div class="text-center mt-2 w-full">
                    <jet-button
                      style="font-size: 10px"
                      @click="openLocationDropdown()"
                    >
                      Reset Location?
                    </jet-button>
                  </div>
                </div>
              </div>
              <div
                v-else
                class="
                  flex flex-col
                  restaurant-comp
                  md:w-full
                  w-[28%]
                  bg-white
                  overflow-hidden
                "
              >
                <div class="flex flex-col justify-center mt-2 mb-10">
                  <h5 class="text-center text-lg font-medium text-gray-900">
                    We are sorry, we were not able to find a match
                    <span v-if="query">
                      for
                      <span class="font-bold text-xl text-red-500 capitalize">{{
                        query
                      }}</span>
                    </span>
                    with the specified filters
                  </h5>

                  <h5 class="text-center text-lg font-medium text-gray-900">
                    <i class="mt-10">Try Another Search ?</i>
                  </h5>
                  <div class="text-center mt-1 w-full">
                    <jet-button
                      style="font-size: 10px"
                      @click="openLocationDropdown()"
                    >
                      Reset Location?
                    </jet-button>
                  </div>
                </div>
                <h3
                  v-show="history.length > 0"
                  class="text-lg font-medium text-gray-900 mb-10 capitalize"
                >
                  Recent Searches
                </h3>
                <div
                  class="flex justify-center md:justify-start flex-wrap"
                  v-if="history.length > 0"
                >
                  <div v-for="(item, index) in history" :key="index">
                    <SingleRestaurant
                      :restaurant="item.vendor"
                    ></SingleRestaurant>
                  </div>
                </div>
                <!-- <div class="flex" v-else>No Recent Searches Available</div> -->
              </div>

              <!-- Suggestions -->

              <div
                v-show="models.data.length < 5"
                class="md:w-[80%] w-[28%] pl-0 pr-5"
              >
                <h3>Suggestions</h3>
                <restaurants-comp
                  :title="'Featured Restaurants'"
                  :seeAll="
                    route('restaurants', {
                      category: 'featured',
                    })
                  "
                  :url="
                    route('data.restaurants', {
                      category: 'featured',
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
              </div>
            </LoadingCard>
          </div>
        </div>
      </div>
    </div>

    <!--footer  -->

    <Footer />
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from "moment";
import Footer from "@/components/Footer.vue";
import SearchFilter from "@/components/SearchFilter.vue";
import { ChevronDownIcon } from "@heroicons/vue/solid";
import JetButton from "@/Jetstream/Button.vue";
import RestaurantsComp from "@/components/Restaurants.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";

export default {
  props: [
    "lng",
    "lat",
    "category",
    "resource_name",
    "query",
    "party_size",
    "cuisine",
    "date",
    "time",
    "state_id",
    "city_id",
    "price",
    "rating",
  ],
  components: {
    AppLayout,
    Footer,
    ChevronDownIcon,
    SearchFilter,
    JetButton,
    RestaurantsComp,
    JetDropdown,
    JetDropdownLink,
  },

  data() {
    return {
      models: {
        data: [],
        links: [],
        total: 0,
      },
      history: [],
      loading: true,
      loaded: false,
      cuisines: [],
      stateName: null,
      cityName: null,
      show: false,
      showFilters: false,
      form: {
        cuisine: null,
        query: null,
        party_size: null,
        date: null,
        time: null,
        state_id: null,
        city_id: null,
        price: null,
        rating: null,
        category: null,
        lat: null,
        lng: null,
        type: "restaurant",
      },
    };
  },
  computed: {
    formatedTime() {
      if (this.time) {
        return moment(this.form.time, "HH:mm:ss").format("hh:mm A");
      }
      return;
    },
    formatedDate() {
      if (this.date) {
        return moment(this.form.date).format("dddd, MMM Do YYYY");
      }
      return;
    },

    getPriceRange() {
      var range = null;
      this.priceRange.forEach((el) => {
        if (el.id == this.price) {
          range = el;
        }
      });

      return range;
    },
    getCuisine() {
      var cuisine = null;
      this.cuisines?.forEach((el) => {
        if (el.id == this.cuisine) {
          cuisine = el;
        }
      });
      return cuisine;
    },
  },

  methods: {
    getCuisines() {
      axios(route("cuisines"))
        .then((result) => {
          this.cuisines = result.data.data.cuisines;
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },

    callSubmitSearch(data) {
      return this.submitSearch(data.category, data.value);
    },

    submitSearch(category, value) {
      if (category == "price") {
        this.form.price = value;
      }

      if (category == "cuisine") {
        this.form.cuisine = value;
      }

      if (category == "rating") {
        this.form.rating = value;
      }

      if (category == "category") {
        this.form.category = value;
        if (value === "nearme") {
          const lat = localStorage.getItem("lat")
            ? localStorage.getItem("lat")
            : null;
          const lng = localStorage.getItem("lng")
            ? localStorage.getItem("lng")
            : null;
          this.form.lat = lat;
          this.form.lng = lng;
        }
      }

      this.$inertia.get(
        route("search-page", {
          type: this.form.type,
        }),
        {
          ...this.form,
        },
        {
          replace: false,
          preserveState: true,
          preserveScroll: true,
          onFinish: (visit) => {
            this.setForm();
          },
        }
      );

      return;
    },

    getStates() {
      axios(
        route("states", {
          country_id: 128,
        })
      )
        .then((result) => {
          const fResult = result.data.data;
          const singleResult = fResult.filter((item) => {
            return item.id == this.state_id;
          });

          this.stateName = singleResult[0].name;
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },

    getCities() {
      axios(
        route("cities", {
          state_id: this.state_id,
        })
      )
        .then((result) => {
          const fResult = result.data.data;
          const singleResult = fResult.filter((item) => {
            return item.id == this.city_id;
          });

          this.cityName = singleResult[0].name;

          const location = `${this.cityName}, ${this.stateName}`;
          localStorage.setItem("address", location);
        })
        .catch((err) => {
          this.handleApiError(err);
        });
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
        .get(route("data.search", { ...this.form }))
        .then((result) => {
          this.models = result.data.models;
          this.history = result.data.history;
          this.loading = false;
          this.loaded = true;
          var vm = this;
          setTimeout(
            () => {
              vm.showFilters = true;
            },
            1000,
            vm
          );
        })
        .catch((err) => {
          this.loaded = false;
          this.loading = false;
        });
    },
    setForm() {
      const urlSearchParams = new URLSearchParams(window.location.search);
      this.form = Object.fromEntries(urlSearchParams.entries());
      this.form.type = "restaurant";
      this.loadData();
    },
  },
  mounted() {
    this.getCuisines();

    if (this.form.state_id) {
      this.getStates();
      this.getCities();
    }

    this.setForm();
  },
};
</script>
