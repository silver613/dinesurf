<template>
  <div class="flex flex-col   ">
    
    <div class="flex flex-wrap mt-5 vendor-gallery">
      <template v-if="images.length > 0">
        <div
          v-for="(item, index) in images"
          :key="index"
          class="md:mr-4 flex border  flex-col mb-5"
        >
          
          <div class="h-40 md:w-[220px]   w-80   rounded pb-2">
            
            <img
              @click="() => showImg(index)"
              :src="item.image_url"
              class="imageCustom cursor-pointer"
            />
          </div>
          <!-- <button title="Select To Delete" class="btn mx-2">
            <input
              class="form-control"
              id="day2"
              type="checkbox"
              @change="toggleImageDelete(item.id)"
            />
          </button> -->
          <div class="px-5 py-3">
            <p>
              {{ deleteImgMsgs[item.id] }}
            </p>
            <button @click="deleteImage(item.id)" title="Delete" class="w-full flex  p-2 border  text-center items-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 20 20"
              aria-labelledby="delete"
              role="presentation"
              class="fill-current text-80"
            >
              <path
                fill-rule="nonzero"
                d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"
              ></path>
            </svg>
          </button>
          </div>
          
        </div>
      </template>
      <div v-else class="mx-3 flex mb-5">No images found</div>
      <vue-easy-lightbox
        :visible="visible"
        :imgs="imageUrls"
        :index="index"
        @hide="handleHide"
      ></vue-easy-lightbox>
    </div>

    <div class="flex">
      <upload-images :vendor="vendor" :type="'galleryPhoto'"></upload-images>
    </div>
  </div>
</template>

<script>
import VueEasyLightbox from "vue-easy-lightbox";
import UploadImages from "./UploadMultipleImages.vue";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling

export default {
  components: {
    UploadImages,
    VueEasyLightbox,
  },
  data() {
    return {
      visible: false,
      images: [],
      images_to_delete: [],
      index: 0,
      deleteImgMsgs: [],
    };
  },
  computed: {
    imageUrls() {
      var arr = [];
      this.images.forEach((el) => {
        arr.push(el.image_url);
      });
      return arr;
    },
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
    toggleImageDelete(id) {
      this.images_to_delete = this.addOrRemove(this.images_to_delete, id);
    },
    getImages() {
      axios(route("vendors.get-images"))
        .then((result) => {
          console.log("result:", result)
          this.images = result.data.data;
          return;
        })
        .catch((err) => {
          this.handleApiError(err);
        });
      return;
    },
    deleteImage(id) {
      if (!this.images_to_delete.includes(id)) {
        this.images_to_delete = this.addOrRemove(this.images_to_delete, id);
      }

      var r = confirm("Are you sure you want to delete this image(s)?");

      if (!r) {
        return;
      }

      this.images_to_delete.forEach((el) => {
        this.deleteImgMsgs[el] = "Deleting...";
      });

      axios
        .post(route("vendors.delete-images"), { ids: this.images_to_delete })
        .then((result) => {
          this.images_to_delete.forEach((el) => {
            this.deleteImgMsgs[el] = "Deleted...will be removed in a sec.";
          });
          this.getImages();
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
    tippy(".nav-link-undone", {
      content: "Coming Soon",
    });


    console.log("imgaes:", this.images)

  },
};
</script>

<style scoped>
input[type="checkbox"] {
  border: solid black 2px !important;
}
</style>