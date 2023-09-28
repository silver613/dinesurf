<template>
  <div class="favoriteContainer justify-between w-full border-b-2 p-5 pb-3">
    <div class="flex basis-8">
      <div
        :style="'background-image: url(' + vendorData.banner_url + ')'"
        class="bg-fav"
      >
        <!-- <img src="/images/product4.jpg" alt="" width="60" class="img bg-grey-500 img-responsive"> -->
      </div>
      <div class="flex flex-col pl-2">
        <span class="font-bold text-xs">{{ vendorData?.company_name }}</span>
        <button class="p-0 m-0 text-left justify-flex-start">
          <i class="fa fa-bookmark text-xs cursor-pointer text-red-500 mr-2">
          </i>
          <span class="text-red-500 text-xs">
            <span v-if="vendorData.liked">Restaurant Saved</span>
            <span v-else>Save this restaurant</span>
          </span>
        </button>
        <span class="text-xs font-bold">Restaurant</span>
        <ratings class="text-sm" :stars="vendorData?.average_rating"></ratings>
        <span class="text-xs font-bold">{{ vendorData?.company_address }}</span>
      </div>
    </div>

    <div class="btnContainerfavorite mt-5">
      <a
        :href="route('restaurants.index', [{ id: vendorData.id }])"
        class="bg-red-500 text-sm font-bold p-3 text-white rounded"
      >
        Reserve Now
      </a>
    </div>
  </div>
</template>

<script>
export default {
  props: ["vendor"],
  data() {
    return {
      vendorData: this.vendor,
    };
  },

  mounted() {
    console.log("vendor records:", this.vendorData.company_name);
  },
};
</script>