<template>
  <app-layout>
    <template #header>
      <h class="font-semibold text-xl text-gray-800 leading-tight capitalize">
        {{ resource_name }}
      </h>
    </template>

    <div class="py-6 min-h-[70vh] w-full">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-10 md:pt-24" v-if="!loaded">
        <div v-if="loading" class="flex justify-center items-center">
          <span class="mr-2 capitalize">Loading {{ resource_name }}</span>
          <svg
            class="text-gray-300"
            viewBox="0 0 120 30"
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            style="width: 50px"
          >
            <circle cx="15" cy="15" r="15">
              <animate
                attributeName="r"
                from="15"
                to="15"
                begin="0s"
                dur="0.8s"
                values="15;9;15"
                calcMode="linear"
                repeatCount="indefinite"
              ></animate>
              <animate
                attributeName="fill-opacity"
                from="1"
                to="1"
                begin="0s"
                dur="0.8s"
                values="1;.5;1"
                calcMode="linear"
                repeatCount="indefinite"
              ></animate>
            </circle>
            <circle cx="60" cy="15" r="9" fill-opacity="0.3">
              <animate
                attributeName="r"
                from="9"
                to="9"
                begin="0s"
                dur="0.8s"
                values="9;15;9"
                calcMode="linear"
                repeatCount="indefinite"
              ></animate>
              <animate
                attributeName="fill-opacity"
                from="0.5"
                to="0.5"
                begin="0s"
                dur="0.8s"
                values=".5;1;.5"
                calcMode="linear"
                repeatCount="indefinite"
              ></animate>
            </circle>
            <circle cx="105" cy="15" r="15">
              <animate
                attributeName="r"
                from="15"
                to="15"
                begin="0s"
                dur="0.8s"
                values="15;9;15"
                calcMode="linear"
                repeatCount="indefinite"
              ></animate>
              <animate
                attributeName="fill-opacity"
                from="1"
                to="1"
                begin="0s"
                dur="0.8s"
                values="1;.5;1"
                calcMode="linear"
                repeatCount="indefinite"
              ></animate>
            </circle>
          </svg>
        </div>
      </div>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-3 md:pt-6" v-if="loaded">
        <div
          class="
            flex flex-col
            restaurant-comp
            bg-white
            overflow-hidden
            shadow-xl
            py-3
            px-4
            sm:rounded-lg
          "
        >
          <div class="mb-5">
            <h1 class="font-semibold text-lg capitalize">{{ resource_name }}</h1>
          </div>
          <div class="flex flex-col md:flex-row flex-wrap justify-start items-center search-results">
            <template v-if="models.data.length > 0">
              <div
                class="w-auto md:w-64 m-1 jus"
                v-for="(item, index) in models.data"
                :key="index"
              >
                <single-restaurant :restaurant="item"></single-restaurant>
              </div>
            </template>
            <div class="w-full" v-else>No {{ resource_name }} found</div>
          </div>
          <pagination class="mt-6" :links="models.links" />
        </div>
      </div>
    </div>
    <Footer />
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import Footer from "@/components/Footer.vue";

export default {
  props: ["resource_name", "vendors"],
  components: {
    AppLayout,
    Welcome,
    Footer,
  },
  data() {
    return {
      params: {},
      loading: true,
      loaded: false,
      models: {
        data: [],
        links: [],
        total: 0,
      },
    };
  },
  methods: {
    loadData() {
      const urlSearchParams = new URLSearchParams(window.location.search);
      this.params = Object.fromEntries(urlSearchParams.entries());

      this.params.path = "/restaurants";

      this.loaded = false;
      this.loading = true;
      this.models = {
        data: [],
        links: [],
        total: 0,
      };

      axios
        .get(route("data.restaurants", { ...this.params }))
        .then((result) => {
          this.models = result.data.models;
          this.loading = false;
          this.loaded = true;
        })
        .catch((err) => {
          this.loaded = false;
          this.loading = false;
        });
    },
  },
  mounted() {
    this.loadData();
    // document.title = this.resource_name + " Restaurants - Dinesurf";
  },
};
</script>
