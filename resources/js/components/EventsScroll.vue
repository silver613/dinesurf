<template>
    <div>
      <div class="restaurant-comp" v-if="loaded  && models.length > 0">
        <div class="border-b mb-2 mt-7">
          <h3 class="text-sm font-medium">
            <span class="text-sm font-thin text-gray-900 capitalize">
              {{ title }}
            </span>
  
            <Link
              v-if="seeAll"
              class="float-right rounded py-1 btn-text text-white searchBtnBg"
              :href="fullUrl"
            >
              See all <i class="fas fa-angle-right"></i
            ></Link>
          </h3>
        </div>
  
        <template v-if="models.length > 0">
          <vue-horizontal responsive>
            <section v-for="item in models" :key="item.title">
             <div class=" md:-mr-3 mr-1  md:mb-0  mb-4">
                      <event-card    :key="item.id" :event="item" :type="'diner'" :isHome="true"/>
                </div>
            </section>
          </vue-horizontal>
        </template>
  
        <div class="w-full" v-else>No {{ title }} Available</div>
      </div>
      <div
        v-if="!loaded"
        class="restaurant-comp flex justify-center items-center h-full my-20"
      >
        <div v-if="loading" class="flex justify-center items-center">
          <span class="mr-2 capitalize">Loading {{ title }}</span>
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
  
        <div v-if="!loading" class="flex flex-col justify-center items-center">
          <svg
            class="inline-block text-gray-500"
            xmlns="http://www.w3.org/2000/svg"
            width="65"
            height="51"
            viewBox="0 0 65 51"
          >
            <path
              class="fill-current"
              d="M56 40h2c.552285 0 1 .447715 1 1s-.447715 1-1 1h-2v2c0 .552285-.447715 1-1 1s-1-.447715-1-1v-2h-2c-.552285 0-1-.447715-1-1s.447715-1 1-1h2v-2c0-.552285.447715-1 1-1s1 .447715 1 1v2zm-5.364125-8H38v8h7.049375c.350333-3.528515 2.534789-6.517471 5.5865-8zm-5.5865 10H6c-3.313708 0-6-2.686292-6-6V6c0-3.313708 2.686292-6 6-6h44c3.313708 0 6 2.686292 6 6v25.049375C61.053323 31.5511 65 35.814652 65 41c0 5.522847-4.477153 10-10 10-5.185348 0-9.4489-3.946677-9.950625-9zM20 30h16v-8H20v8zm0 2v8h16v-8H20zm34-2v-8H38v8h16zM2 30h16v-8H2v8zm0 2v4c0 2.209139 1.790861 4 4 4h12v-8H2zm18-12h16v-8H20v8zm34 0v-8H38v8h16zM2 20h16v-8H2v8zm52-10V6c0-2.209139-1.790861-4-4-4H6C3.790861 2 2 3.790861 2 6v4h52zm1 39c4.418278 0 8-3.581722 8-8s-3.581722-8-8-8-8 3.581722-8 8 3.581722 8 8 8z"
            ></path>
          </svg>
          <h3 class="text-sm font-normal mt-3 text-red-500 mb-3">
            Failed to load {{ title }}!
          </h3>
  
          <button class="jet-btn active:bg-gray-900 text-xs" @click="loadData()">
            Reload
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import VueHorizontal from "vue-horizontal";
  import EventCard from '../Pages/Vendor/Events/EventCard.vue'
  export default {
    components: {
      VueHorizontal,
      EventCard
    },
    props: {
      title: {
        type: String,
      },
      seeAll: {
        type: String,
        default: null,
      },
      url: {
        type: String,
        required: true,
      },
    },
    data() {
      return {
        myOptions: {
          autoplay: true,
          autoplaySpeed: 5000,
          navButtons: false,
  
          responsive: [
            {
              breakpoint: 600,
              settings: {
                navButtons: false,
                dots: true,
                slidesToShow: 1,
              },
            },
  
            {
              breakpoint: 900,
              settings: {
                navButtons: true,
                dots: true,
                slidesToShow: 3,
              },
            },
          ],
        },
        params: { state_id: null },
        loading: true,
        loaded: false,
        models: [],
      };
    },
    computed: {
      filters() {
        return new URLSearchParams(this.params).toString();
      },
      fullDataUrl() {
        return this.url + "&" + this.filters;
      },
      fullUrl() {
        return this.seeAll
      },
    },
    methods: {
    
    geolocate() {
  
  
        const state_id = localStorage.getItem('state_id');
        if (state_id) {
          var vm = this;
          vm.params.state_id = state_id;
          
          this.loadData();
        } else {
          this.loadData();
        }
      },


      loadData() {
        this.loaded = false;
        this.loading = true;
  
        axios
          .get(route('data.events'))
          .then((result) => {

            const filt  =  result.data.models?.filter((item) => item.event_type != 'past');
            this.models = filt;
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
      this.geolocate();
    },
  };
  </script>
  