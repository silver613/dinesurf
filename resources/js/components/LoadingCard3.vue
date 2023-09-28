<template>
  <div class="relative">
    <slot v-if="loaded" />
    <div v-if="!loaded" class="flex justify-center items-center h-full">
      <div v-if="loading" class="flex justify-center items-center">
        <span class="mr-2 capitalize" id="loadingResponse"
          >Loading {{ title }}</span
        >
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

      <div v-else class="flex flex-col justify-center items-center">
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
        <h3
          class="text-sm font-normal mt-3 text-red-500 mb-3"
          id="failedLoadResponse"
        >
          Failed to load {{ title }}!
        </h3>

        <button
          class="jet-btn btn-black active:bg-gray-900 text-xs"
          @click="loadData()"
          id="reloadBtn"
        >
          Reload
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: this.initLoading,
      loaded: this.initLoaded,
    };
  },
  props: {
    title: {
      type: String,
      required: true,
    },
    url: {
      type: String,
      required: true,
    },
    trigger: {
      type: Number,
      default: 0,
    },
    initLoading: {
      type: Boolean,
      default: true,
    },
    initLoaded: {
      type: Boolean,
      default: false,
    },
    load: {
      type: Boolean,
      default: true,
    },
    paginated: {
      type: Boolean,
      default: false,
    },
    httpMethod: {
      type: String,
      default: "get",
    },
    requestData: Object,
  },
  methods: {
    loadData() {
      this.loading = true;
      this.loaded = false;

      if (this.httpMethod == "get") {
        axios
          .get(this.url)
          .then((result) => {
            if (this.paginated) {
              this.$emit("setData", result.data);
            } else {
              this.$emit("setData", result.data.data);
            }
            this.loaded = true;
            this.loading = false;
          })
          .catch((err) => {
            console.log(err);
            this.loaded = false;
            this.loading = false;
          });
      } else {
        axios
          .post(this.url, this.requestData)
          .then((result) => {
            this.$emit("setData", result.data);
            this.loaded = true;
            this.loading = false;
          })
          .catch((err) => {
            console.log(err);
            this.loaded = false;
            this.loading = false;
          });
      }
    },
  },
  watch: {
    trigger(newVal, oldVal) {
      this.loadData();
    },
  },
  mounted() {
    if (this.load) {
      this.loadData();
    }
  },
};
</script>
