<template>
    <div class="w-full">
 


      <div  v-if="imgs.length > 0" class="mb-6 w-full grid grid-cols-3 gap-3 ">
        <div
            class=" h-20 md:w-[120px]     w-80  shadow-sm"   :key="index" v-for="(item, index) in imgs">

          <img
                  :src="item"
                  @click="() => showImg(index)"
                  alt="Event Image"
                  class="block  max-h-20 imageCustom  img-thumbnail cursor-pointer"
                />
        </div>
      <!---->
    </div>

  <div v-else class="w-full h-full  flex items-center justify-center mt-10">
        No photos found
      </div>
     
  
      <vue-easy-lightbox
        :visible="visible"
        :imgs="imgs"
        :index="index"
        @hide="handleHide"
      ></vue-easy-lightbox>
    </div>
  </template>
  
  <script>
  import VueEasyLightbox  from "vue-easy-lightbox";
  
  export default {
    props: ["images", "vendor"],
    components: { VueEasyLightbox },
    data() {
      return {
        imgs: this.images.map((item) => item.image_url),
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
 
    },
    mounted() {
 

    // console.log("images:", this.images)
    },
  };
  </script>
  