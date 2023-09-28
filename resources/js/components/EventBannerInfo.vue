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


    <h3 class="flex align-middle items-center">
      <div class="mr-1  text-2xl"> {{ this.event.name }} 
         by
          {{ vendorData.company_name }}
      </div>
      <img
        v-if="vendorData.verified"
        src="https://img.icons8.com/color/48/000000/verified-badge.png"
        class="h-5"
      />

    </h3>
    <div class="flex flex-col ">
      <div class="mr-6 mb-2">
        <ratings class="text-sm" :stars="vendorData.average_rating"></ratings>
        ({{ vendorData.number_of_reviews }})
      </div>


   

      <div
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
  props: ["vendor", "event"],
  data() {
    return {
      vendorData: this.vendor,
    };
  },
 
};
</script>
