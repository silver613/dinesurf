<template>
  <div class="min-h-full flex flex-wrap w-full">
    <template v-if="images.length > 0">
     
      <img
        v-for="(item, index) in images"
        :key="index"
        :src="item"
        @click="() => showImg(index)"
        alt=""
        class="w-[45%] md:w-auto h-auto md:h-52 mx-2 my-2 img-thumbnail cursor-pointer"
      />

      <!-- </div> -->
      <!-- </Photoswipe> -->
    </template>
    <div v-else class="w-full flex justify-center text-center">
      No images found
    </div>

    <vue-easy-lightbox
      :visible="visible"
      :imgs="images"
      :index="index"
      @hide="handleHide"
    ></vue-easy-lightbox>
  </div>
</template>

<script>
import VueEasyLightbox from "vue-easy-lightbox";

export default {
  props: ["vendor"],
  components: { VueEasyLightbox },
  data() {
    return {
      images: [],
      index: 0,
      visible: false,
    };
  },
  methods: {
    showImg(index) {
      this.index = index;
      this.visible = true;
    },
    handleHide() {
      this.visible = false;
    },
    onClick(i) {
      this.index = i;
    },
    getImages() {
      axios(route("restaurant-images", { id: this.vendor.id }))
        .then((result) => {
          console.log("images", result.data.data.image_links);
          this.images = result.data.data.image_links;
          return;
        })
        .catch((err) => {
          this.handleApiError(err);
        });
      return;
    },
  },
  mounted() {
    this.getImages();
    if (this.vendor) {
      document.title = this.vendor.company_name + " - Gallery";
    }
  },
};
</script>
