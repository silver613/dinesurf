<template>
  <div>
    <div class="pt-2 pr-0">
      <div class="mb-6 relative">
        <div data-v-1c591609="" class=" imageContainer">
          <img
            :src="value"
            class="imageN"
            draggable="false"
            :id="'image-output' + type"
          />
        </div>
        <div
          class="
            absolute
            -bottom-5
            right-0
            bg-white
            w-10
            h-10
            flex
            items-center
            justify-center
            p-1
            rounded-full
          "
        >
          <i
            class="fa fa-edit text-lg cursor-pointer"
            @click="edit = !edit"
          ></i>
        </div>
        <!---->
      </div>
      <!---->
      <div class="form-file" v-show="edit">
        <form @submit.prevent="submit()">
          <div
            class="
              flex
              items-center
              w-full
              border border-dashed border-dinesurf-green-400
              bg-gray-100
              justify-between
              p-3
            "
          >
            <div class="flex flex-col">
              <span class="text-dinesurf-green-400">
                Upload Additional Doc
              </span>
              <span class="text-xs">Max 2MB,PNG,JPEG</span>
              <span v-if="this.value" class="ml-1 capitalize">
                <span class="mr-2 text-green-600" v-html="responseText"></span>
              </span>
              <span class="text-xs" v-else> No file uploaded </span>
            </div>
            <div>
              <button
                type="button"
                class="
                  bg-dinesurf-green-400
                  w-32
                  h-10
                  text-white
                  jump
                  cursor-pointer
                "
              >
                <span class="absolute top-2 left-5">Choose a file</span>
                <input
                  dusk="image"
                  type="file"
                  :id="'file-image' + type"
                  name="image"
                  accept="image/*"
                  class="
                    form-file-input
                    select-none
                    opacity-0
                    h-10
                    w-full
                    block
                    object-cover
                    cursor-pointer
                  "
                  @change="displayFile"
                />
              </button>
            </div>
          </div>
          <div class="flex flex-col pt-5">
            <button
              type="submit"
              class="
                w-32
                inline-flex
                items-center
                px-4
                py-2
                bg-dinesurf-green-400
                border border-transparent
                rounded-md
                font-semibold
                text-xs text-white
                uppercase
                tracking-widest
                hover:bg-gray-700
                active:bg-gray-900
                focus:outline-none
                focus:border-gray-900
                focus:ring
                focus:ring-gray-300
                disabled:opacity-25
                transition
              "
              :disabled="
                responseText == 'Saving...' || responseText == 'Saved.'
              "
            >
              Save
            </button>
            <span class="mt-3 block" v-html="responseText"> </span>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["vendor", "type"],
  data() {
    return {
      responseText: "",
      file: null,
      value: null,
      edit: false,
    };
  },

  methods: {
    displayFile() {
      var files = document.getElementById("file-image" + this.type).files;
      var output = document.getElementById("image-output" + this.type);
      this.file = files[0];
      output.src = URL.createObjectURL(files[0]);
      output.onload = function () {
        URL.revokeObjectURL(output.src); // free memory
      };
      
    },
    submit() {
      if (this.file) {
        this.responseText = "Saving...";
        var formData = new FormData();
        formData.append("image", this.file);
        formData.append("type", this.type);

        axios
          .post(route("vendors.upload-image"), formData)
          .then((result) => {
            this.responseText = "Saved.";
            if (this.type == "galleryPhoto") {
              this.gallery();
              return;
            }
            const urlSearchParams = new URLSearchParams(window.location.search);
            var params = Object.fromEntries(urlSearchParams.entries());
            params.fromImageUpload = true;
            let vm = this;
            setTimeout(
              function () {
                vm.$inertia.visit(route("vendors.profile.show", params), {
                  preserveScroll: true,
                  preserveState: true,
                  onFinish: (visit) => {
                    vm.responseText = null;
                  },
                });
              },
              500,
              vm
            );
          })
          .catch((err) => {
            console.log(err);
            if (err.response) {
              if (err.response.status == 422) {
                this.responseText =
                  '<span class="text-red-700 font-bold block">Error Uploading File...File might be too large..max file size is 2mb</span>';
                toastr.error("one of the files is too large");
                toastr.error("max file size 2mb");
              }
              return;
            }
            this.responseText =
              '<span class="text-red-700 font-bold block">Error Uploading File...File might be too large..max file size is 2mb</span>';
            this.handleApiError(err);
          });
      }
    },
    setValue() {
      if (this.type == "profilePhoto") {
        this.value = this.vendor.profile_photo_url;
      }

      if (this.type == "bannerPhoto") {
        this.value = this.vendor.banner_url;
      }
    },
    checkBannerFileSize() {
      var reader = new FileReader();

      //Read the contents of Image File.
      reader.readAsDataURL(this.file);
      reader.onload = function (e) {
        //Initiate the JavaScript Image object.
        var image = new Image();

        //Set the Base64 string return from FileReader as source.
        image.src = e.target.result;

        //Validate the File Height and Width.
        image.onload = function () {
          var height = this.height;
          var width = this.width;
          if (height > 2660 || width > 100) {
            alert("Height and Width must not exceed 100px.");
            return false;
          }
          alert("Uploaded image has valid Height and Width.");
          return true;
        };
      };
    },
    gallery() {
      this.$parent.getImages();
    },
  },
  mounted() {
    this.setValue();
  },
};
</script>
<style>
.imageN {
  object-fit: cover;
  width: 100% !important;
  height: 100% !important;
}
.imageContainer {
  width: 100%;
  height: 200px;
  background-color: gray;
}
</style>
