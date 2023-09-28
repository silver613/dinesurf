<template>
  <div
    class="
      flex flex-col
      vendor-index-overlay
      restaurants-info-banner
      justify-end
      w-full
    "
  >
    <!-- <div>
            <img
                v-if="vendor"
                :src="vendorData.profile_photo_url"
                class="mr-3 h-16 md:h-24 rounded-full"
            />
        </div> -->
    <div class="flex justify-between buttonContainer md:mt-4 pb-1 ">
      <div>
        <share-button :id="vendorData.id" class="my-1"></share-button>
      </div>
      <button class="flex justify-between bg-green text-black">
        <likeRestaurants :model="vendorData"> </likeRestaurants>
      </button>
    </div>

    <h3 class="flex align-middle items-center">
      <h1 class="mr-1  md:text-lg  text-sm">{{ vendorData.company_name }}
      </h1>
      <img
        v-if="vendorData.verified"
        src="https://img.icons8.com/color/48/000000/verified-badge.png"
        class="h-5"
      />

    </h3>
    <div class="flex flex-col md:flex-row">
      <div class="mr-6">
        <ratings class="text-sm" :stars="vendorData.average_rating"></ratings>
      </div>

      <div class="mr-5 flex">
        <img
          src="/images/Icon material-rate-review.svg"
          class="mr-1"
          width="15"
          height="5"
        />
        <span> {{ vendorData.number_of_reviews }} Reviews </span>
      </div>

      <div class="mr-5 flex" v-if="vendorData.average_menu_price > 0">
        <img
          src="/images/Icon awesome-money-bill.svg"
          class="mr-1"
          width="15"
          height="5"
        />
        <span>
          <!-- ₦ 100 -->
          <span v-if="vendorData.average_menu_price > 0">
            ~   {{ numberWithCommas(vendorData.average_menu_price, "NGN") }} </span
          >
        </span>
      </div>

      <!-- <div
        v-if="vendorData.cuisines.length > 0"
        class="
          flex
          mr-5
          max-h-[21px] max-w-[350px]
          md:max-w-[500px]
          overflow-clip
        "
      >
        <img
          src="/images/Icon metro-spoon-fork-white.svg"
          class="mr-1"
          width="15"
          height="5"
        />

        <ul class="cuisineres">
          <template v-for="(item, index) in vendorData.cuisines" :key="index">
            <li v-if="index < 4">• {{ item.name }}</li>
          </template>
        </ul>
      </div> -->

      <div class="flex" v-if="vendorData.party_size">
        <i class="fa fa-user">&nbsp;</i>
        <span>Maximum Party Size: {{ vendorData.party_size }} people</span>
      </div>
    </div>

    <card-modal
      :showing="exampleModalShowing"
      @close="exampleModalShowing = false"
    >
      <h3 class="text-lg font-medium text-gray-900 capitalize">
        {{ vendorData.company_name }} Tour Video
      </h3>
      <iframe
        class="youtube_video"
        :src="vendorData.youtube_link"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
      ></iframe>
    </card-modal>
  </div>
</template>

<script>
import likeRestaurants from "@/components/likeRestaurants.vue";
export default {
  components: { likeRestaurants },
  props: ["vendor"],
  data() {
    return {
      vendorData: this.vendor,
    };
  },
  methods: {
    getRestaurant() {
      fetch(route("restaurants-data.index", { id: this.vendor.id }))
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          if (result.success) {
            // toastr.success(result.message);
            this.vendorData = result.data;
          } else {
            toastr.error("An Error Occured!");
          }
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },
    toggleLike() {
      if (!this.$page.props.user) {
        alert("You have to login to add a restaurant to your hit list");
        return;
      }
      this.vendorData.liked = !this.vendorData.liked;
      fetch(route("toggle-like", { vendor_id: this.vendor.id }))
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          if (result.success) {
            this.getRestaurant();
          } else {
            toastr.error("An Error Occured!");
          }
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },
  },
  mounted() {
    // console.log("function mounted:", this.vendorData);
  },
};
</script>
