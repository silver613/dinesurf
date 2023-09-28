<template>
  <div>
    <JetDropdown align="left-and-right" width="72" :closeOnClickContent="false">
      <template #trigger>
        <button class="flex justify-between pl-2">
          <span v-if="city">{{ city }}</span>
          <span v-if="state"> <span v-if="city"> ,</span> {{ state }}</span>
          <span v-if="!city && !state"
            >Set Location 
            
            <!-- <i class="fa-solid fa-location-dot"></i
          > -->
        </span>
        <span class="ml-1">
          <svg width="12" height="16" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7 9.75C8.24264 9.75 9.25 8.74264 9.25 7.5C9.25 6.25736 8.24264 5.25 7 5.25C5.75736 5.25 4.75 6.25736 4.75 7.5C4.75 8.74264 5.75736 9.75 7 9.75Z" stroke="#9DC64E" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M7 1.5C5.4087 1.5 3.88258 2.13214 2.75736 3.25736C1.63214 4.38258 1 5.9087 1 7.5C1 8.919 1.3015 9.8475 2.125 10.875L7 16.5L11.875 10.875C12.6985 9.8475 13 8.919 13 7.5C13 5.9087 12.3679 4.38258 11.2426 3.25736C10.1174 2.13214 8.5913 1.5 7 1.5V1.5Z" stroke="#9DC64E" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
        </span>
          <ChevronDownIcon class="-mr-1 ml-2 h-5 w-5" aria-hidden="true" />
        </button>
      </template>

      <template #content>
        <JetDropdownLink :as="'div'" class="py-1">
          <form @submit.prevent="mainSearch()" method="get">
            <div class="containerForm p-3">
              <div class="mb-5 pt-0 mt-0">
                <h4 class="font-bold">Change Location</h4>
              </div>

              <!-- state -->
              <div class="col-span-6 sm:col-span-4 selectInputContainer mb-5">
                <!-- <jet-label for="state" value="State" /> -->
                <select
                  v-model="form.state_id"
                  name="state_id"
                  id="state"
                  class="mt-1 block w-full form-control"
                  @change="getCities(form.state_id)"
                >
                  <option :value="null">Select state</option>
                  <option
                    v-for="(item, index) in states"
                    :key="index"
                    :value="item.id"
                  >
                    {{ item.name }}
                  </option>
                </select>
                <jet-input-error :message="form.errors.state_id" class="mt-2" />
                <button type="button" class="text-xs" @click="clearState()">
                  Clear
                </button>
              </div>

              <!-- city -->
              <!-- <div
            class="col-span-6 sm:col-span-4 selectInputContainer mb-5"
            v-if="cities.length > 0"
          >
            <jet-label for="city" value="city" />
            <select
              v-model="form.city_id"
              name="city_id"
              id="city"
              class="mt-1 block w-full form-control"
              @change="setCity(form.city_id)"
            >
              <option :value="null">Select city(optional)</option>
              <option
                v-for="(item, index) in cities"
                :key="index"
                :value="item.id"
              >
                {{ item.name }}
              </option>
            </select>
            <jet-input-error :message="form.errors.city_id" class="mt-2" /> -->
              <!-- <button type="button" class="text-xs" @click="form.state_id = null">
              Clear
            </button>
          </div> -->
              <!-- 
          <div class="selectInputContainer mb-5">
            <input
              type="search"
              name="query"
              v-model="form.query"
              class="block w-full h-9 border rounded-sm border-solid"
              placeholder="Search Restaurants(optional)"
            />
          </div> -->
            </div>

            <button
              type="submit"
              class="
                inline-flex
                justify-center
                w-full
                mb-3
                px-4
                py-2
                text-sm
                font-medium
                text-white
                border
                searchBtnBg
                items-center
              "
            >
              Find Restaurants <i class="ml-3 fa-solid fa-magnifying-glass"></i>
            </button>
          </form>
        </JetDropdownLink>
      </template>
    </JetDropdown>

    <jet-modal :show="locationShow" @close="locationShow = !locationShow">
      <div class="h-auto md:h-48">
        <h2
          class="
            font-semibold
            text-xl text-gray-800 text-center
            leading-tight
            mt-3
            mb-3
          "
        >
          Location Preference
        </h2>
        <div class="flex-column items-center md:px-20 px-7 pb-5">
          <p>Select The location for restaurants you want to see.</p>
          <select
            v-model="form.state_id"
            name="state_id"
            id="state"
            class="mt-3 block w-full form-control"
            @change="saveState(form.state_id)"
          >
            <option selected :value="null">Select state</option>
            <option
              v-for="(item, index) in states"
              :key="index"
              :value="item.id"
            >
              {{ item.name }}
            </option>
          </select>
        </div>
      </div>
    </jet-modal>
  </div>
</template>


<script>
import { Menu } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/solid";
import JetLocationMarkerLogo from "@/Jetstream/LocationMarkerLogo.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetInputError from "@/Jetstream/InputError.vue";

export default {
  props: ["latQuery", "lngQuery", "city_id", "state_id", "query"],
  components: {
    Menu,
    ChevronDownIcon,
    JetLocationMarkerLogo,
    JetDropdownLink,
    JetDropdown,
    JetInputError,
  },
  data() {
    return {
      page: this.$page.props.page,
      locationShow: false,
      city: null,
      cityLat: null,
      cityLng: null,
      lng: null,
      lat: null,
      address: localStorage.getItem("address")
        ? localStorage.getItem("address")
        : "unknown",
      show: false,
      states: [],
      cities: [],
      query: this.query,
      form: this.$inertia.form({
        _method: "POST",
        state_id: this.state_id,
        // city_id: this.city_id,
        // query: this.query,
        country_id: 128,
      }),
    };
  },
  computed: {
    // city() {
    //   var name = null;
    //   if (this.form.city_id) {
    //     this.cities.forEach((el) => {
    //       if (el.id == this.form.city_id) {
    //         name = el.name;
    //       }
    //     });
    //   }
    //   return name;
    // },
    state() {
      var name = null;
      if (this.form.state_id) {
        this.states.forEach((el) => {
          if (el.id == this.form.state_id) {
            name = el.name;
          }
        });
      }

      localStorage.setItem("state_name", name);
      return name;
    },
  },
  methods: {
    handleSubmit() {
      vm.locationChanged();
    },

    show() {
      console.log("Address: ", this.address);
      console.log("Lat: ", this.lat);
      console.log("Lng: ", this.lng);
    },

    initialize() {
      var input = document.getElementById("searchTextField");
      var autocomplete = new google.maps.places.Autocomplete(input);
      this.complete = autocomplete;
      var vm = this;

      google.maps.event.addListener(autocomplete, "place_changed", function () {
        var place = autocomplete.getPlace();

        vm.address = place.name;
        document.getElementById("address").value = place.name;

        vm.lat = place.geometry.location.lat();
        document.getElementById("lat").value = place.geometry.location.lat();

        vm.lng = place.geometry.location.lng();
        document.getElementById("lng").value = place.geometry.location.lng();

        localStorage.setItem("address", place.name);
        localStorage.setItem("lat", place.geometry.location.lat());
        localStorage.setItem("lng", place.geometry.location.lng());
      });
    },
    geolocate() {
      var vm = this;
      navigator.geolocation.getCurrentPosition(
        (position) => {
          vm.codeLatLng(position.coords.latitude, position.coords.longitude);
        },
        function (error) {
          if (error.code == error.PERMISSION_DENIED) vm.locationShow = true;
        }
      );
    },
    locationChanged() {
      if (this.lat && this.lng) {
        this.show = false;

        localStorage.setItem(
          "address",
          document.getElementById("searchTextField").value
        );
        localStorage.setItem("lat", this.lat);
        localStorage.setItem("lng", this.lng);
      }
    },

    codeLatLng(lat, lng) {
      var latlng = new google.maps.LatLng(lat, lng);
      var vm = this;

      vm.lat = lat;
      vm.lng = lng;

      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ latLng: latlng }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[1]) {
            vm.address = results[1].formatted_address;
            // document.getElementById("searchTextField").value =
            //   results[1].formatted_address;
          } else {
            if (results[0]) {
              vm.address = results[0].formatted_address;
              // document.getElementById("searchTextField").value =
              //   results[0].formatted_address;
            }
          }
          vm.address = document.getElementById("searchTextField").value;
          vm.cityLat = lat;
          vm.cityLng = lng;
          vm.lat = lat;
          vm.lng = lng;

          localStorage.setItem("address", vm.address);
          localStorage.setItem("lat", lat);
          localStorage.setItem("lng", lng);
        }
      });
    },

    getStates(country_id) {
      axios
        .get(
          route("states", {
            country_id: country_id,
            with_vendors: true,
          })
        )
        .then((result) => {
          this.states = result.data.data;
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },

    getCities(state_id) {
      this.$emit("setStateId", state_id);
      this.saveState(state_id);
      axios(
        route("cities", {
          state_id: state_id,
        })
      )
        .then((result) => {
          this.cities = result.data.data;
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },

    getLocations() {
      if (this.form.country_id) {
        this.getStates(this.form.country_id);
      }
      var vm = this;

      const state = localStorage.getItem("state_id");
      console.log("state fromn local: ", state);

      if (!state && vm.page == "dashboard") {
        vm.locationShow = true;
      } else {
        vm.locationShow = false;
      }
    },
    mainSearch() {
      if (this.form.state_id == null) return;
      this.$inertia.get(
        route("search-page", { type: "restaurant" }),
        {
          query: this.query,
          ...this.form,
        },
        {
          replace: true,
          preserveScroll: true,
        }
      );
    },
    clearState() {
      this.form.state_id = null;
      // this.form.city_id = null;
      this.cities = [];
    },

    saveState(state) {
      if (state);
      this.form.state_id = state;
      localStorage.setItem("state_id", state);
      this.getLocations();
      toastr.success("Location Updated");
    },
  },
  mounted() {
    this.getLocations();
  },
};
</script>
