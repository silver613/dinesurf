<template>
  <!-- <vendor-layout> -->
    <!-- <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Vendor Gallery
      </h2>
    </template> -->

    <div class="pt-5 md:py-5  bg-white  shadow-settings-page">
      <div class="  px-6 lg:px-8">
        <div class="bg-white overflow-hidden md:max-w-md border border-red p-4 border-l-4 border-l-dinesurf-green-400 sm:rounded-lg mb-6">
          <ul>
            <li class="text-dinesurf-green-400   text-sm">File format: JPEG, GIF, or PNG.</li>
            <li class="text-gray-400 text-xs">Max File Size: 2mb.</li>
            <li>Avoid text, logos, and busy backgrounds.</li>
          </ul>
        </div>

         <div class="flex flex-col  mb-6  space-y-5  ">
          <div
            class="mb-6 lg:mb-0 w-full md:max-w-md mr-5 bg-white overflow-hidden border p-4 sm:rounded-lg"
          >
            <h2 class="font-bold text-lg text-gray-800 leading-tight mb-3">
              Profile Logo
            </h2>
            <div class="mb-3">
                <p class="text-xs">Logo must be at least 750px x 750px. (a Square)</p> 
            </div>
            <upload-image
              :vendor="vendor"
              :type="'profilePhoto'"
            ></upload-image>
          </div>
          <div
            class="w-full md:max-w-md  bg-white overflow-hidden  border p-4 sm:rounded-lg"
          >
            <h2 class="font-bold text-lg text-gray-800 leading-tight mb-3">
              Banner Image
            </h2>
            
            <div class="mb-3">
               <p class="text-xs">Header image must be at least 2660x1140px.(a Rectangle)</p>
            </div>
            <upload-image :vendor="vendor" :type="'bannerPhoto'"></upload-image>
          </div>
        </div>
        <div class="bg-white overflow-hidden   p-4 border sm:rounded-lg mb-6">
          <h2 class="font-bold text-lg text-gray-800 leading-tight mb-3">
            Gallery Images
          </h2>
          <p>images must be at least 750x750px. (a Square)</p>
          <gallery-comp></gallery-comp>
        </div>
      </div>
    </div>

</template>

<script>
// import VendorLayout from "@/Layouts/VendorLayout.vue";
import GalleryComp from "@/components/vendor/Gallery.vue";
 
// console.log("GalleryJdksds:", this.vendor);
export default {
  props: ["vendor"],
  components: {
    GalleryComp,
  },


};
</script>
